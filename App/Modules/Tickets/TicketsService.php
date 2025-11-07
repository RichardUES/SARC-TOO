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

  // ==================== ================ ====================
  // ==================== MÉTODOS PRIVADOS ====================
  // ==================== ================ ====================
  private function updateDisponibilidadAgente(int $agentID, string $ocupado): bool
  {
    $userAgent = new Usuario(); // Hay que pasarle todo el objeto
    $userAgent->setCodigo($agentID);
    $userAgent->setOcupado($ocupado);
    return $this->usuarioRepository->updateDisponibilidadAgente($userAgent);
  }
}
