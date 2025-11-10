<?php

namespace App\Modules\Tickets;

use App\Config\Database;
use App\Models\enums\TicketStatus;
use App\Models\enums\TypeAssign;
use App\Models\Ticket;
use App\Models\Usuario;
use App\Modules\Auth\Repositories\UsuarioRepository;
use PDO;
use DateTime;

class TicketsService
{

  private TicketsRepository $ticketsRepository;
  private UsuarioRepository $usuarioRepository;
  private PDO $db;

  public function __construct()
  {
    $this->ticketsRepository = new TicketsRepository();
    $this->usuarioRepository = new UsuarioRepository();
    $this->db = Database::getIntance()->getConnection();
  }

  public function createTicket(Ticket $ticket): bool
  {
    try {
      // 🔒 INICIAMOS UNA TRANSACCIÓN PARA TODA LA OPERACIÓN
      $this->db->beginTransaction();

      // 1- creamos el ticket
      $createdTicket = $this->ticketsRepository->createTicket($ticket);

      if (!$createdTicket) {
        $this->db->rollBack();
        error_log("TicketsService::createTicket - Error al crear el ticket");
        return false;
      }

      //echo "✅ Ticket creado con ID: {$createdTicket->codigo}<br>";

      // 2- Buscamos el agente disponible
      $agenteDisponible = $this->usuarioRepository->obtenerAgenteDisponible();

      // 3- asignamos el ticket creado, si hay un agente disponible
      if (isset($agenteDisponible)) {

        //echo "✅ Agente disponible encontrado: {$agenteDisponible["USU_USERNAME"]} (ID: {$agenteDisponible["USU_CODIGO"]})<br>";

        // Creamos el array que pasaremos para asignar el ticket
        $ticketAssignData = [
          "ticketID" => $createdTicket->codigo,
          "agentID" => $agenteDisponible["USU_CODIGO"],
          "typeAssign" => TypeAssign::AUTOMATIC->value,
          "message" => "Asignación automática del sistema al agente: {$agenteDisponible["USU_USERNAME"]}",
          "AssignmentCompleted" => "S"
        ];

        $isAsigandoExito = $this->ticketsRepository->asignarTicket($ticketAssignData);

        if (!$isAsigandoExito) {
          $this->db->rollBack();
          error_log("TicketsService::createTicket - Error al asignar ticket {$createdTicket->codigo} al agente {$agenteDisponible["USU_CODIGO"]}");
          //echo "❌ Error: No se pudo asignar el ticket al agente disponible.<br>";
          return false;
        }

        //echo "✅ Ticket asignado correctamente al agente<br>";

        // Actualizamos el estado del ticket a "asignado" y agregamos la fecha de asignacion
        $updatedEstadoTicket = $this->ticketsRepository->updateEstadoTicket(
          $createdTicket->codigo,
          TicketStatus::ASSIGNED->value
        ); // --------------
        $updateFechaAsignacion = $this->ticketsRepository->updateTicketFechaAsignacion(
          $createdTicket->codigo,
          new DateTime()
        );

        if (!$updateFechaAsignacion) {
          $this->db->rollBack();
          error_log("TicketsService::createTicket - Error al actualizar la fecha de asignación del ticket");
          echo "❌ Error al actualizar la fecha de asignación del ticket<br>";
          return false;
        }

        if (!$updatedEstadoTicket) {
          $this->db->rollBack();
          error_log("TicketsService::createTicket - Error al actualizar el ticket después de asignar");
          echo "❌ Error al actualizar el estado del ticket<br>";
          return false;
        }

        echo "✅ Estado del ticket actualizado a ASIGNADO<br>";

        // Actualizamos la disponibilidad del agente a ocupado con "S"
        $updatedAgent = $this->updateDisponibilidadAgente($agenteDisponible["USU_CODIGO"], "S");
        if ($updatedAgent) {
          echo "✅ Agente actualizado a ocupado<br>";
        } else {
          $this->db->rollBack();
          error_log("TicketsService::createTicket - Error al actualizar disponibilidad del agente");
          echo "❌ Error al actualizar disponibilidad del agente<br>";
          return false;
        }

        // 🔓 TODO SALIÓ BIEN - HACEMOS COMMIT
        $this->db->commit();
        echo "🎉 TRANSACCIÓN COMPLETADA EXITOSAMENTE<br>";

        return true;
      } else {
        // No hay agente disponible
        //echo "⚠️ No hay agentes disponibles - encolando ticket<br>";

        // Encolamos el ticket hasta que se encuentre un agente disponible
        // Solamente actualizamos el estado del tkt a "pendiente"
        $updatedEstadoTicket = $this->ticketsRepository->updateEstadoTicket(
          $createdTicket->codigo,
          TicketStatus::PENDING->value
        );

        if (!$updatedEstadoTicket) {
          $this->db->rollBack();
          error_log("TicketsService::createTicket - Error al encolar el ticket");
          //echo "❌ Error al encolar el ticket<br>";
          return false;
        }

        // 🔓 COMMIT - ticket creado y encolado
        $this->db->commit();
        //echo "✅ Ticket encolado correctamente<br>";

        return true;
      }
    } catch (\Exception $e) {
      // 🔙 ROLLBACK en caso de cualquier error
      if ($this->db->inTransaction()) {
        $this->db->rollBack();
      }

      error_log("TicketsService::createTicket - Exception: " . $e->getMessage());
      //echo "❌ Error en TicketsService::createTicket - " . $e->getMessage() . "<br>";
      return false;
    }
  }

  public function obtenerColaTickets(): ?array
  {
    return $this->ticketsRepository->colaDeTickets();
  }

  public function getTicketsByClient(int $clientID): array
  {
    return $this->ticketsRepository->getTicketsByClient($clientID);
  }

  public function getAllTickets(): array
  {
    return $this->ticketsRepository->getAllTickets();
  }

  /**
   * Obtiene los tickets asignados a un agente específico
   * 
   * @param int $agenteID ID del agente
   * @return array Lista de tickets del agente
   */
  public function getTicketsByAgente(int $agenteID): array
  {
    return $this->ticketsRepository->getTicketsByAgente($agenteID);
  }

  /**
   * Completa un ticket (cambia estado y libera agente)
   * Después de completar, intenta asignar automáticamente el siguiente ticket de la cola
   * 
   * @param int $ticketID ID del ticket
   * @param int $agenteID ID del agente
   * @return bool True si la operación fue exitosa
   */
  public function completarTicket(int $ticketID, int $agenteID): bool
  {
    $resultado = $this->ticketsRepository->completarTicket($ticketID, $agenteID);
    
    if ($resultado) {
      // Después de liberar al agente, intentar asignar siguiente ticket de la cola
      $this->asignarSiguienteTicketDeCola($agenteID);
    }
    
    return $resultado;
  }

  /**
   * Escala un ticket a un área específica (libera al agente)
   * Después de escalar, intenta asignar automáticamente el siguiente ticket de la cola
   * 
   * @param int $ticketID ID del ticket
   * @param int $agenteID ID del agente
   * @param int $areaID ID del área
   * @param string $motivo Motivo del escalamiento
   * @return bool True si la operación fue exitosa
   */
  public function escalarTicket(int $ticketID, int $agenteID, int $areaID, string $motivo): bool
  {
    $resultado = $this->ticketsRepository->escalarTicket($ticketID, $agenteID, $areaID, $motivo);
    
    if ($resultado) {
      // Después de liberar al agente, intentar asignar siguiente ticket de la cola
      $this->asignarSiguienteTicketDeCola($agenteID);
    }
    
    return $resultado;
  }

  // ==================== ================ ====================
  // ==================== MÉTODOS PRIVADOS ====================
  // ==================== ================ ====================

  /**
   * Asigna automáticamente el siguiente ticket de la cola a un agente recién liberado
   * 
   * @param int $agenteID ID del agente que acaba de quedar libre
   * @return bool True si se asignó un ticket, false si no hay tickets en cola o error
   */
  private function asignarSiguienteTicketDeCola(int $agenteID): bool
  {
    try {
      $this->db->beginTransaction();

      // 1. Obtener el siguiente ticket pendiente de la cola (FIFO)
      $ticketsPendientes = $this->ticketsRepository->colaDeTickets();

      if (empty($ticketsPendientes)) {
        $this->db->rollBack();
        error_log("TicketsService::asignarSiguienteTicketDeCola - No hay tickets pendientes en la cola");
        return false;
      }

      // Tomar el primer ticket (más antiguo)
      $siguienteTicket = $ticketsPendientes[0];
      $ticketID = $siguienteTicket['CODIGO'];

      error_log("TicketsService::asignarSiguienteTicketDeCola - Asignando ticket {$ticketID} al agente {$agenteID}");

      // 2. Obtener información del agente para el mensaje de asignación
      $agentInfo = $this->usuarioRepository->findById($agenteID);
      if (!$agentInfo) {
        $this->db->rollBack();
        error_log("TicketsService::asignarSiguienteTicketDeCola - No se encontró información del agente {$agenteID}");
        return false;
      }

      // 3. Crear la asignación automática
      $ticketAssignData = [
        "ticketID" => $ticketID,
        "agentID" => $agenteID,
        "typeAssign" => TypeAssign::AUTOMATIC->value,
        "message" => "Asignación automática tras liberación del agente: {$agentInfo->username}",
        "AssignmentCompleted" => "N"
      ];

      $isAsignado = $this->ticketsRepository->asignarTicket($ticketAssignData);
      if (!$isAsignado) {
        $this->db->rollBack();
        error_log("TicketsService::asignarSiguienteTicketDeCola - Error al asignar ticket {$ticketID} al agente {$agenteID}");
        return false;
      }

      // 4. Actualizar el estado del ticket a ASIGNADO
      $updatedEstadoTicket = $this->ticketsRepository->updateEstadoTicket(
        $ticketID,
        TicketStatus::ASSIGNED->value
      );

      if (!$updatedEstadoTicket) {
        $this->db->rollBack();
        error_log("TicketsService::asignarSiguienteTicketDeCola - Error al actualizar estado del ticket {$ticketID}");
        return false;
      }

      // 5. Actualizar la fecha de asignación del ticket
      $updateFechaAsignacion = $this->ticketsRepository->updateTicketFechaAsignacion(
        $ticketID,
        new DateTime()
      );

      if (!$updateFechaAsignacion) {
        $this->db->rollBack();
        error_log("TicketsService::asignarSiguienteTicketDeCola - Error al actualizar fecha de asignación del ticket {$ticketID}");
        return false;
      }

      // 6. Marcar al agente como ocupado nuevamente
      $updatedAgent = $this->updateDisponibilidadAgente($agenteID, "S");
      if (!$updatedAgent) {
        $this->db->rollBack();
        error_log("TicketsService::asignarSiguienteTicketDeCola - Error al ocupar nuevamente al agente {$agenteID}");
        return false;
      }

      $this->db->commit();
      error_log("TicketsService::asignarSiguienteTicketDeCola - ✅ Ticket {$ticketID} asignado exitosamente al agente {$agenteID}");
      return true;

    } catch (\Exception $e) {
      if ($this->db->inTransaction()) {
        $this->db->rollBack();
      }
      error_log("TicketsService::asignarSiguienteTicketDeCola - Exception: " . $e->getMessage());
      return false;
    }
  }

  private function updateDisponibilidadAgente(int $agentID, string $ocupado): bool
  {
    $userAgent = new Usuario(); // Hay que pasarle todo el objeto
    $userAgent->setCodigo($agentID);
    $userAgent->setOcupado($ocupado);
    return $this->usuarioRepository->updateDisponibilidadAgente($userAgent);
  }
}
