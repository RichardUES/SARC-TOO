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

/**
 * Repositorio para la gestión de tickets en la base de datos.
 * 
 * Maneja todas las operaciones CRUD y consultas relacionadas con tickets,
 * incluyendo la asignación de tickets a agentes y gestión de estados.
 * 
 * @package App\Modules\Tickets
 */
class TicketsRepository
{

  private PDO $db;

  /**
   * Constructor del repositorio.
   * 
   * Inicializa la conexión a la base de datos mediante el patrón Singleton.
   */
  public function __construct()
  {
    $this->db = Database::getIntance()->getConnection();
  }

  /**
   * Crea un nuevo ticket o actualiza uno existente en la base de datos.
   * 
   * Si el ticket tiene un código asignado (ID > 0), realiza un UPDATE con todos
   * los campos del ticket. Si no tiene código, realiza un INSERT con los campos
   * básicos y asigna el ID generado al objeto ticket.
   * 
   * IMPORTANTE: Este método NO maneja transacciones internamente. La transacción
   * debe ser manejada por la capa superior (servicio).
   * 
   * @param Ticket $ticket Objeto ticket a crear o actualizar
   * @return Ticket|null Retorna el ticket con su código asignado si la operación
   *                     fue exitosa, o null si ocurrió un error
   * 
   * @throws PDOException Captura y registra cualquier error de base de datos
   */
  public function createTicket(Ticket $ticket): ?Ticket
  {

    try {

      if (
        isset($ticket->codigo)
        && $ticket->codigo > 0
      ) {

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

  /**
   * Actualiza el estado de un ticket específico.
   * 
   * Modifica únicamente el campo TKT_ESTADO_ID del ticket identificado por su código.
   * Este método es útil para transiciones de estado (ej: Pendiente -> Asignado -> Cerrado).
   * 
   * @param int $ticketID Código único del ticket a actualizar
   * @param int $estadoID ID del nuevo estado del ticket (ver enum TicketStatus)
   * @return bool true si la actualización fue exitosa, false en caso contrario
   * 
   * @see TicketStatus Para los valores válidos de estado
   */
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

  /**
   * Actualiza la fecha de asignación de un ticket.
   * 
   * Establece la fecha y hora en que un ticket fue asignado a un agente.
   * Útil cuando se asigna un ticket a un usuario con rol de agente.
   * 
   * @param int $ticketID Código único del ticket
   * @param DateTime $fechaAsignacion Fecha y hora de la asignación
   * @return bool true si la actualización fue exitosa, false en caso contrario
   */
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

  /**
   * Actualiza la fecha de cierre de un ticket.
   * 
   * Establece la fecha y hora en que un ticket fue cerrado o resuelto.
   * Se utiliza típicamente cuando un agente finaliza la atención del ticket.
   * 
   * @param int $ticketID Código único del ticket
   * @param DateTime $fechaCierre Fecha y hora del cierre del ticket
   * @return bool true si la actualización fue exitosa, false en caso contrario
   */
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

  /**
   * Obtiene todos los tickets almacenados en la base de datos.
   * 
   * Recupera la lista completa de tickets y los mapea a objetos Ticket.
   * Este método retorna todos los registros sin filtros ni paginación.
   * 
   * @return array Array de objetos Ticket. Retorna un array vacío si no hay tickets
   *               o si ocurre un error en la consulta
   */
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

  /**
   * Asigna un ticket a un agente específico.
   * 
   * Crea un registro en la tabla ASIGNACION_TICKET vinculando un ticket con un agente.
   * Valida que todos los campos requeridos estén presentes antes de realizar la inserción.
   * 
   * IMPORTANTE: Este método NO maneja transacciones. Debe ser llamado dentro de una
   * transacción manejada por la capa de servicio para garantizar consistencia.
   * 
   * @param array $asignacion Array asociativo
   * 
   * @return bool true si la asignación fue exitosa, false si falta algún campo requerido
   *              o si ocurre un error en la base de datos
   * 
   * @see TypeAssign Para los tipos válidos de asignación
   */
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
   * Obtiene la lista de todos los tickets pendientes para la cola de atención.
   * 
   * Consulta todos los tickets que están en estado PENDING (pendiente) y los retorna
   * con información combinada de cliente, agencia, área y estado. Esta información
   * se utiliza para construir la bitácora o cola de tickets pendientes de asignación.
   * 
   * @return array|null Array asociativo 
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


  /**
   * Obtiene los tickets en proceso para un cliente específico.
   * * Recupera todos los tickets asociados a un cliente que no estén en estado
   * * COMPLETED, incluyendo detalles del agente asignado y tiempos transcurridos.
   * @param int $clientID ID del cliente cuyos tickets se desean obtener
   * @return array Array asociativo
   */
  public function getTicketsByClient(int $clientID): array
  {
    try {

      $query = "SELECT
                  -- Datos del ticket
                  T.TKT_CODIGO AS CODIGO_TICKET,
                  T.TKT_ASUNTO AS ASUNTO,
                  T.TKT_DESCRIPCION AS DESCRIPCION,
                  T.TKT_PRIORIDAD AS PRIORIDAD,
                  T.TKT_ORIGEN AS ORIGEN,
                  T.TKT_FECHA_CREACION AS FECHA_CREACION,
                  T.TKT_FECHA_ASIGNACION AS FECHA_ASIGNACION,
                  T.TKT_FECHA_CIERRE AS FECHA_CIERRE,

                  -- Estado actual
                  ET.EST_CODIGO AS CODIGO_ESTADO,
                  ET.EST_NOMBRE AS ESTADO_ACTUAL,

                  -- Datos de la agencia
                  AG.AGE_NOMBRE AS AGENCIA,
                  AG.AGE_DIRECCION AS DIRECCION_AGENCIA,
                  AG.AGE_TELEFONO AS TELEFONO_AGENCIA,

                  -- Área escalada (si aplica)
                  AR.AREA_NOMBRE AS AREA_ESCALADA,

                  -- Agente asignado (si existe)
                  U.USU_USERNAME AS AGENTE_ASIGNADO,
                  U.USU_EMAIL AS EMAIL_AGENTE,
                  AT.ASIG_FECHA AS FECHA_ASIGNACION_AGENTE,
                  AT.ASIG_TIPO AS TIPO_ASIGNACION,

                  -- Tiempo transcurrido
                  TIMESTAMPDIFF(DAY, T.TKT_FECHA_CREACION, NOW()) AS DIAS_DESDE_CREACION,
                  TIMESTAMPDIFF(HOUR, T.TKT_FECHA_CREACION, NOW()) AS HORAS_DESDE_CREACION

              FROM TICKETS T
                  INNER JOIN ESTADO_TICKET ET
                      ON T.TKT_ESTADO_ID = ET.EST_CODIGO
                  INNER JOIN AGENCIAS AG
                      ON T.TKT_AGENCIA_ID = AG.AGE_CODIGO
                  INNER JOIN CLIENTES C
                      ON T.TKT_CLIENTE_ID = C.CLI_CODIGO
                  LEFT JOIN AREAS AR
                      ON T.TKT_AREA_ID = AR.AREA_CODIGO
                  LEFT JOIN ASIGNACION_TICKET AT
                      ON T.TKT_CODIGO = AT.ASIG_TKT_ID
                  LEFT JOIN USUARIOS U
                      ON AT.ASIG_USUARIO_ID = U.USU_CODIGO

              WHERE C.CLI_CODIGO = :CLIENTE_ID  

              ORDER BY T.TKT_PRIORIDAD DESC, T.TKT_FECHA_CREACION ASC";

      $ps = $this->db->prepare($query);
      $ps->bindValue(':CLIENTE_ID', $clientID);
      $ps->execute();

      $tickets = $ps->fetchAll(PDO::FETCH_ASSOC);

      return $tickets;

    } catch (PDOException $ex) {
      error_log("TicketsRepository::getTicketInProcessClient - Error: " . $ex->getMessage());
      return [];
    }
  }


  /**
   * Mapea un array asociativo de datos a un objeto Ticket.
   * 
   * Convierte los datos crudos obtenidos de la base de datos en un objeto de dominio Ticket,
   * estableciendo todos sus atributos incluyendo las conversiones necesarias de fechas.
   * Este método es privado y se utiliza internamente para transformar resultados de consultas.
   * 
   * @param array $data Array asociativo 
   * 
   * @return Ticket Objeto Ticket completamente inicializado con los datos proporcionados
   */
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
