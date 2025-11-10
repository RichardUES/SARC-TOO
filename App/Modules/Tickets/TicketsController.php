<?php

namespace App\Modules\Tickets;

use App\Core\Controller;
use App\Models\enums\OrigenTicket;
use App\Models\enums\Priority;
use App\Models\enums\RolType;
use App\Models\enums\TicketStatus;
use App\Models\Ticket;

class TicketsController extends Controller {
  
  private TicketsService $ticketsService;

  public function __construct() {
    $this->ticketsService = new TicketsService();
  }

  public function create(): void 
  {

    try {
      
      session_start();

      if($this->isPost()) {
        // Recibimos los datos del formulario
        $clienteID = $_POST["cliente_id"] ?? null;
        $agenciaID = $_POST["agencia_id"] ?? null;
        // $areaID = $_POST["area"];
        $estadoTicket = TicketStatus::RECEIVED->value;
        $asunto = $_POST["asunto"] ?? null;
        $descripcion = $_POST["descripcion"] ?? null;
        $prioridad = Priority::MEDIUM->value;
        $origen = OrigenTicket::WEB->value;

        if ( !isset($clienteID) || !isset($agenciaID) || !isset($asunto) || !isset($descripcion) ) {
          $_SESSION["Error"] = "Por favor, complete todos los campos obligatorios.";
          $this->redirect("/profile/create_ticket");
          return;
        }

        // Creamos el objeto Ticket
        $ticket = new Ticket();
        $ticket->setClienteID($clienteID);
        $ticket->setAgenciaID($agenciaID);
        // $ticket->areaID = $areaID;
        $ticket->setEstadoTicket($estadoTicket); // Estado inicial
        $ticket->setAsunto($asunto);
        $ticket->setDescripcion($descripcion);
        $ticket->setPrioridad($prioridad);
        $ticket->setOrigen($origen); // se crea desde la web

        // Llamamos al servicio para crear el ticket
        $isCreated = $this->ticketsService->createTicket($ticket);

        if (!$isCreated) {
          $_SESSION["Error"] = "Hemos tenido dificultades para procesar su solicitud. Por favor, inténtelo de nuevo más tarde. (200)";
          $this->redirect("/profile/create_ticket");
          return;
        }

        $_SESSION["success"] = "El ticket se ha envíado a nustro equipo de soporte. ¡Gracias!";

        // Redirigimos o mostramos un mensaje de éxito
        $this->redirect("/profile/create_ticket");
        
      }
    } catch (\Throwable $th) {
      $_SESSION["Error"] = "Hemos tenido dificultades para procesar su solicitud. Por favor, inténtelo de nuevo más tarde. (500)";
      $this->redirect("/profile/create_ticket");
      
    }
  }

  /**
   * Completa un ticket (POST)
   */
  public function completar(): void
  {
    session_start();

    if (!$this->isPost()) {
      $_SESSION["Error"] = "Método no permitido";
      $this->redirect("/dashboard/mis_tickets");
      return;
    }

    try {
      $ticketID = (int)($_POST["ticket_id"] ?? 0);
      $agenteID = $_SESSION["autorizado"]->codigo ?? 0;

      if ($ticketID <= 0 || $agenteID <= 0) {
        $_SESSION["Error"] = "Datos inválidos";
        $this->redirect("/dashboard/mis_tickets");
        return;
      }

      $resultado = $this->ticketsService->completarTicket($ticketID, $agenteID);

      if ($resultado) {
        $_SESSION["success"] = "Ticket completado exitosamente";
      } else {
        $_SESSION["Error"] = "Error al completar el ticket";
      }

      $this->redirect("/dashboard/mis_tickets");

    } catch (\Exception $e) {
      error_log("TicketsController::completar - Error: " . $e->getMessage());
      $_SESSION["Error"] = "Error interno del servidor";
      $this->redirect("/dashboard/mis_tickets");
    }
  }

  /**
   * Escala un ticket (POST)
   */
  public function escalar(): void
  {
    session_start();

    if (!$this->isPost()) {
      $_SESSION["Error"] = "Método no permitido";
      $this->redirect("/dashboard/mis_tickets");
      return;
    }

    try {
      $ticketID = (int)($_POST["ticket_id"] ?? 0);
      $areaID = (int)($_POST["area_id"] ?? 0);
      $motivo = trim($_POST["motivo"] ?? "");
      $agenteID = $_SESSION["autorizado"]->codigo ?? 0;

      if ($ticketID <= 0 || $areaID <= 0 || empty($motivo) || $agenteID <= 0) {
        $_SESSION["Error"] = "Todos los campos son obligatorios";
        $this->redirect("/dashboard/mis_tickets");
        return;
      }

      $resultado = $this->ticketsService->escalarTicket($ticketID, $agenteID, $areaID, $motivo);

      if ($resultado) {
        $_SESSION["success"] = "Ticket escalado exitosamente";
      } else {
        $_SESSION["Error"] = "Error al escalar el ticket";
      }

      $this->redirect("/dashboard/mis_tickets");

    } catch (\Exception $e) {
      error_log("TicketsController::escalar - Error: " . $e->getMessage());
      $_SESSION["Error"] = "Error interno del servidor";
      $this->redirect("/dashboard/mis_tickets");
    }
  }
}