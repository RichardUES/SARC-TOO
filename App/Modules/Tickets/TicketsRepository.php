<?php

namespace App\Modules\Tickets;

use App\Config\Database;
use App\Models\enums\Priority;
use App\Models\enums\TicketStatus;
use App\Models\EstadoTicket;
use App\Models\Ticket;
use PDO;
use PDOException;
use DateTime;

class TicketsRepository
{

  private PDO $db;

  public function __construct()
  {
    $this->db = Database::getIntance()->getConnection();
  }

  public function createTicket(Ticket $ticket): ?Ticket
  {

    try {

      if (isset($ticket->codigo) 
          && $ticket->codigo > 0 ) {

        // Formatear fechas o asignar NULL
        $fechaAsignacion = $ticket->fecha_asignacion instanceof DateTime
          ? $ticket->fecha_asignacion->format('Y-m-d H:i:s')
          : null;

        $fechaCierre = $ticket->fecha_cierre instanceof DateTime
          ? $ticket->fecha_cierre->format('Y-m-d H:i:s')
          : null;

        $query = "UPDATE TICKETS SET 
                TKT_CLIENTE_ID = :CLIENTE_ID,
                TKT_AGENCIA_ID = :AGENCIA_ID,
                TKT_AREA_ID = :AREA_ID,
                TKT_ESTADO_ID = :ESTADO_ID,
                TKT_ASUNTO = :ASUNTO,
                TKT_DESCRIPCION = :DESCRIPCION,
                TKT_FECHA_ASIGNACION = :FECHA_ASIGNACION,
                TKT_FECHA_CIERRE = :FECHA_CIERRE,
                TKT_PRIORIDAD = :PRIORIDAD,
                TKT_ORIGEN = :ORIGEN,
                TKT_ESTADO_LOGICO = :ESTADO_LOGICO
              WHERE TKT_CODIGO = :CODIGO";

        $ps = $this->db->prepare($query);
        $ps->bindParam(":CODIGO", $ticket->codigo);
        $ps->bindParam(":CLIENTE_ID", $ticket->clienteID);
        $ps->bindParam(":AGENCIA_ID", $ticket->agenciaID);
        $ps->bindParam(":AREA_ID", $ticket->areaID);
        $ps->bindParam(":ESTADO_ID", $ticket->estadoTicket);
        $ps->bindParam(":ASUNTO", $ticket->asunto);
        $ps->bindParam(":DESCRIPCION", $ticket->descripcion);
        $ps->bindParam(":FECHA_ASIGNACION", $fechaAsignacion);
        $ps->bindParam(":FECHA_CIERRE", $fechaCierre);
        $ps->bindParam(":PRIORIDAD", $ticket->prioridad);
        $ps->bindParam(":ORIGEN", $ticket->origen);
        $ps->bindParam(":ESTADO_LOGICO", $ticket->estadoTicket);
        $ps->execute();

        return $ticket;
      }

      // INSERT
      $query = "INSERT INTO 
          TICKETS(TKT_CLIENTE_ID,
                  TKT_AGENCIA_ID,
                  TKT_ESTADO_ID,
                  TKT_ASUNTO,
                  TKT_DESCRIPCION,
                  TKT_PRIORIDAD,
                  TKT_ORIGEN) 
                VALUES(:CLIENTE_ID,
                      :AGENCIA_ID,
                      :ESTADO_ID,
                      :ASUNTO,
                      :DESCRIPCION,
                      :PRIORIDAD,
                      :ORIGEN)";

      $clienteID = $ticket->clienteID;
      $agenciaID = $ticket->agenciaID;
      $estadoTicket = $ticket->estadoTicket;
      $asunto = $ticket->asunto;
      $descripcion = $ticket->descripcion;
      $prioridad = $ticket->prioridad;
      $origen = $ticket->origen;

      $ps = $this->db->prepare($query);
      $ps->bindParam(":CLIENTE_ID", $clienteID);
      $ps->bindParam(":AGENCIA_ID", $agenciaID);
      $ps->bindParam(":ESTADO_ID", $estadoTicket);
      $ps->bindParam(":ASUNTO", $asunto);
      $ps->bindParam(":DESCRIPCION", $descripcion);
      $ps->bindParam(":PRIORIDAD", $prioridad);
      $ps->bindParam(":ORIGEN", $origen);
      $ps->execute();

      $lastId = $this->db->lastInsertId();
      $ticket->setCodigo($lastId);

      return $ticket;

    } catch (PDOException $ex) {
      error_log("Error al crear/actualizar ticket: " . $ex->getMessage());
      return null;
    }
  }

  public function updateEstadoTicket(int $ticketID, int $estadoID): bool
  {
    try {
      $query = "UPDATE TICKETS SET 
                TKT_ESTADO_ID = :ESTADO_ID
              WHERE TKT_CODIGO = :CODIGO";

      $ps = $this->db->prepare($query);
      $ps->bindParam(":CODIGO", $ticketID);
      $ps->bindParam(":ESTADO_ID", $estadoID);
      $isUpdated = $ps->execute();

      return $isUpdated;
    } catch (PDOException $ex) {
      echo "Error al actualizar estado del ticket: " . $ex->getMessage() . "<br>";
      return false;
    }
  } 

  public function updateTicketFechaAsignacion(int $ticketID, DateTime $fechaAsignacion): bool
  {
    try {
      $formattedDate = $fechaAsignacion->format('Y-m-d H:i:s');

      $query = "UPDATE TICKETS SET 
                TKT_FECHA_ASIGNACION = :FECHA_ASIGNACION
              WHERE TKT_CODIGO = :CODIGO";

      $ps = $this->db->prepare($query);
      $ps->bindParam(":CODIGO", $ticketID);
      $ps->bindParam(":FECHA_ASIGNACION", $formattedDate);
      $isUpdated = $ps->execute();

      return $isUpdated;
    } catch (PDOException $ex) {
      echo "Error al actualizar fecha de asignación del ticket: " . $ex->getMessage() . "\n";
      return false;
    }
  }

  public function updateTicketFechaCierre(int $ticketID, DateTime $fechaCierre): bool
  {
    try {
      $formattedDate = $fechaCierre->format('Y-m-d H:i:s');

      $query = "UPDATE TICKETS SET 
                TKT_FECHA_CIERRE = :FECHA_CIERRE
              WHERE TKT_CODIGO = :CODIGO";

      $ps = $this->db->prepare($query);
      $ps->bindParam(":CODIGO", $ticketID);
      $ps->bindParam(":FECHA_CIERRE", $formattedDate);
      $isUpdated = $ps->execute();

      return $isUpdated;
    } catch (PDOException $ex) {
      echo "Error al actualizar fecha de cierre del ticket: " . $ex->getMessage() . "\n";
      return false;
    }
  }

  public function getTickets(): array
  {

    try {
      $query = "SELECT * FROM TICKETS";
      $ps = $this->db->prepare($query);
      $ps->execute();
      $results = $ps->fetchAll(PDO::FETCH_ASSOC);

      $tickets = [];
      foreach ($results as $row) {
        $ticket = $this->mapToTicket($row);
        array_push($tickets, $ticket);
      }

      return $tickets;
    } catch (PDOException $ex) {
      echo "Error al obtener tickets: " . $ex->getMessage();
      return [];
    }
  }


  public function asignarTicket(array $asignacion): bool
  {
    // Validar datos mínimos esperados
    $required = ['ticketID', 'agentID', 'typeAssign', 'message', 'AssignmentCompleted'];
    foreach ($required as $key) {
      if (!array_key_exists($key, $asignacion)) {
        error_log("TicketsRepository::asignarTicket - missing key: $key");
        return false;
      }
    }

    // Normalizar valores
    $ticketID = (int) $asignacion['ticketID'];
    $agentID = (int) $asignacion['agentID'];
    $typeAssign = trim((string) $asignacion['typeAssign']);
    $message = trim((string) $asignacion['message']);
    $finalizada = $asignacion['AssignmentCompleted'];

    try {
      $query = "INSERT INTO ASIGNACION_TICKET(
          ASIG_TKT_ID,
          ASIG_USUARIO_ID,
          ASIG_TIPO,
          ASIG_OBSERVACION,
          ASIG_FINALIZADA)
        VALUES(:TKT_ID, :USUARIO_ID, :TIPO, :OBSERVACION, :FINALIZADA)";

      $ps = $this->db->prepare($query);
      $ps->bindParam(':TKT_ID', $ticketID, PDO::PARAM_INT);
      $ps->bindParam(':USUARIO_ID', $agentID, PDO::PARAM_INT);
      $ps->bindParam(':TIPO', $typeAssign, PDO::PARAM_STR);
      $ps->bindParam(':OBSERVACION', $message, PDO::PARAM_STR);
      $ps->bindParam(':FINALIZADA', $finalizada, PDO::PARAM_STR);
      
      $isInserted = $ps->execute();

      if (!$isInserted) {
        error_log('TicketsRepository::asignarTicket - Execute returned false');
        error_log('PDO Error Info: ' . print_r($ps->errorInfo(), true));
      }

      return $isInserted;

    } catch (PDOException $ex) {
      error_log('TicketsRepository::asignarTicket error: ' . $ex->getMessage());
      
      return false;
    }
  }

  /**
   * Obtenemos la lista de todos los tiuckets pendientes, para armar la bitacora
   * @return array|null
   */
  public function colaDeTickets(): ?array
  {

    try {

      $estado_ticket = TicketStatus::PENDING->value; // estado 4

      $query = "SELECT
                T.TKT_CODIGO AS CODIGO,
                CONCAT(C.CLI_PRIMER_NOM, ' ', C.CLI_PRIMER_APE) AS NOMBRE,
                AG.AGE_NOMBRE AS AGENCIA,
                AR.AREA_NOMBRE AS AREA,
                ET.EST_NOMBRE AS ESTADO,
                T.TKT_ASUNTO AS ASUNTO,
                T.TKT_DESCRIPCION AS DESCRIPCION,
                T.TKT_FECHA_CREACION AS FECHA_CREACION,
                T.TKT_FECHA_ASIGNACION AS FECHA_ASIGNACION,
                T.TKT_FECHA_CIERRE AS FECHA_CIERRE,
                T.TKT_PRIORIDAD AS PRIORIDAD,
                T.TKT_ORIGEN AS ORIGEN
                FROM TICKETS T
              INNER JOIN CLIENTES C
                  ON T.TKT_CLIENTE_ID = C.CLI_CODIGO
              INNER JOIN AGENCIA AG
                  ON T.TKT_AGENCIA_ID = AG.AGE_CODIGO
              LEFT JOIN AREA AR
                ON T.TKT_AREA_ID = AR.AREA_CODIGO
              INNER JOIN ESTADO_TICKET ET
                ON T.TKT_ESTADO_ID = ET.EST_CODIGO
              WHERE T.TKT_ESTADO_ID = $estado_ticket";

      $ps = $this->db->prepare($query);
      $ps->execute();
      $tickets = $ps->fetchAll(PDO::FETCH_ASSOC);

      return $tickets;
    } catch (\Throwable $th) {
      echo "Error al obtener cola de tickets: " . $th->getMessage();
      return null;
    }
  }

  private function mapToTicket(array $data): Ticket
  {
    $ticket = new Ticket();
    $ticket->setCodigo($data['TKT_CODIGO']);
    $ticket->setClienteID($data['TKT_CLIENTE_ID']);
    $ticket->setAgenciaID($data['TKT_AGENCIA_ID']);
    $areaID = $data['TKT_AREA_ID'] ?? null;
    if (isset($areaID)) {
      $ticket->setAreaID($areaID);
    }
    $ticket->setEstadoTicket($data['TKT_ESTADO_ID']);
    $ticket->setAsunto($data['TKT_ASUNTO']);
    $ticket->setDescripcion($data['TKT_DESCRIPCION']);

    $ticket->setFechaCreacion(new DateTime($data['TKT_FECHA_CREACION']));

    if ($data['TKT_FECHA_ASIGNACION'] != null) {
      $asignacion = new DateTime($data['TKT_FECHA_ASIGNACION']);
      $ticket->setFechaAsignacion($asignacion);
    }

    if ($data['TKT_FECHA_CIERRE'] != null) {
      $cierre = new DateTime($data['TKT_FECHA_CIERRE']);
      $ticket->setFechaCierre($cierre);
    }

    $ticket->setPrioridad($data['TKT_PRIORIDAD']);
    $ticket->setOrigen($data['TKT_ORIGEN']);
    $ticket->setStatus($data['TKT_ESTADO_LOGICO']);

    return $ticket;
  }
}
