<?php

namespace App\Modules\Profile;

use App\Core\Controller;
use App\Models\Cliente;
use App\Modules\Tickets\TicketsService;
use DateTime;

class ProfileController extends Controller
{

  private ProfileService $profileService;
  private TicketsService $ticketsService;

  public function __construct()
  {

    $this->profileService = new ProfileService();
    $this->ticketsService = new TicketsService();

  }

  private function profileInit(): void
  {
    session_start();

    try {

      $isCliente = $this->profileService->getProfileByUserID($_SESSION['autorizado']->codigo);

      if (isset($isCliente)) $_SESSION["cliente"] = $isCliente;

    } catch (\Throwable $th) {
      error_log("ProfileController::profileInit - Error al obtener el perfil del usuario: " . $th->getMessage());
    }

  }

  public function index()
  {
    $this->profileInit();
    $results = $this->ticketsService
            ->getTicketsByClient( $_SESSION["cliente"]->codigo );
    $this->view("profile/profile", ["tickets" => $results]);
  }

  public function personal_info()
  {
    $this->profileInit();
    $this->view("profile/personal_info");
  }

  public function create_ticket()
  {
    $this->profileInit();
    $this->view("profile/create_ticket");
  }

  public function ticket_history()
  {
    $this->profileInit();
    $results = $this->ticketsService
            ->getTicketsByClient( $_SESSION["cliente"]->codigo );
    $this->view("profile/ticket_history", ["tickets" => $results]);
  }

  public function notifications()
  {
    $this->profileInit();
    $this->view("profile/notifications");
  }

  public function settings()
  {
    $this->profileInit();
    $this->view("profile/settings");
  }

  /**
   * Crea un perfil nuevo con el usuario existente
   * @return void
   */
  public function update_profile(): void
  {

    // Los datos del usuario los obtengo de la sesión
    if (session_status() !== PHP_SESSION_ACTIVE) {
      session_start();
    }

    if (!$this->isPost()) {
      $_SESSION['Error'] = 'Método no permitido.';
      $this->redirect("/profile/personal_info");
      return;
    }

    $userId = $_SESSION['autorizado']->codigo;
    $primerNombre = $_POST['primer-nombre'] ?? '';
    $segundoNombre = $_POST['segundo-nombre'] ?? '';
    $primerApellido = $_POST['primer-apellido'] ?? '';
    $segundoApellido = $_POST['segundo-apellido'] ?? '';
    $fechaNac = $_POST['fechaNac'] ?? '';
    $dui = $_POST['dui'] ?? '';
    $telefono = $_POST['telefono'] ?? '';

    if (
      $primerNombre === ''
      || $primerApellido === ''
      || $fechaNac === ''
      || $dui === ''
      || $telefono === ''
    ) {
      $_SESSION['Error'] = 'Por favor, complete todos los campos obligatorios.';
      $this->redirect("/profile/personal_info");
      return;
    }

    $client = new Cliente();
    $client->setUsuarioID($userId);
    $client->setPrimerNombre($primerNombre);
    $client->setSegundoNombre($segundoNombre);
    $client->setPrimerApellido($primerApellido);
    $client->setSegundoApellido($segundoApellido);
    $client->setFechaNac(new DateTime($fechaNac));
    $client->setDui($dui);
    $client->setTelefono($telefono);

    $cliente = $this->profileService->createProfileClient($client);

    if (isset($cliente)) {
      $_SESSION["cliente"] = $cliente;

      $this->redirect("/profile/personal_info");
      return;

    }

    // Fallo al crear el perfil
    $_SESSION['Error'] = 'Hubo un error al actualizar el perfil. Por favor, intente de nuevo.';

  }

  public function update_password(): void
  {
    // Implementar la lógica para actualizar la contraseña del usuario
    if (session_status() !== PHP_SESSION_ACTIVE) {
      session_start();
    }

    if (!$this->isPost()) {
      $_SESSION['Error'] = 'Método no permitido.';
      $this->redirect("/profile/settings");
      return;
    }

    $userId = $_SESSION['autorizado']->codigo;
    $currentPassword = $_POST['passwordActual'] ?? '';
    $newPassword = $_POST['passwordNueva'] ?? '';
    $confirmPassword = $_POST['passwordConfirmar'] ?? '';
    
    if (
      $currentPassword === ''
      || $newPassword === ''
      || $confirmPassword === ''
    ) {
      $_SESSION['Error'] = 'Por favor, complete todos los campos obligatorios.';
      $this->redirect("/profile/settings");
      return;
    }

    // hay que validar que exista la contraseña actual
    $isValidCurrentPassword = $this->profileService->validateUserPassword($userId, $currentPassword);
    if (!$isValidCurrentPassword) {
      $_SESSION['Error'] = 'La contraseña actual es incorrecta.';
      $this->redirect("/profile/settings");
      return;
    }

    if ($newPassword !== $confirmPassword) {
      $_SESSION['Error'] = 'La nueva contraseña y la confirmación de la misma no coinciden.';
      $this->redirect("/profile/settings");
      return;
    }

    $passHash = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 6]);

    $isUpdated = $this->profileService->updateUserPassword($userId, $passHash);

    if ($isUpdated) {
      $_SESSION['success'] = 'Contraseña actualizada correctamente.';
      $this->redirect("/profile/settings");
      return;
    }
    
    $_SESSION['Error'] = 'Error al actualizar la contraseña. Por favor, intente de nuevo.';
    $this->redirect("/profile/settings");
    return;

  }


}