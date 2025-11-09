<?php

namespace App\Modules\Profile;

use App\Core\Controller;
use App\Models\Cliente;
use App\Models\Usuario;
use App\Models\enums\RolType;
use App\Modules\Tickets\TicketsService;
use App\Modules\Dashboard\administracion\AgenciaService;
use DateTime;
use Exception;

class ProfileController extends Controller
{

  private ProfileService $profileService;
  private TicketsService $ticketsService;
  private AgenciaService $agenciaService;

  public function __construct()
  {

    $this->profileService = new ProfileService();
    $this->ticketsService = new TicketsService();
    $this->agenciaService = new AgenciaService();
  }

  /**
   * Inicializa el perfil del cliente en la sesión.
   * 
   * Verifica que el usuario esté autenticado y carga su perfil de cliente si existe.
   * Si no tiene perfil aún (usuario recién registrado), simplemente no lo carga.
   */
  private function profileInit(): void
  {
    // Iniciar sesión si no está activa
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }

    try {
      // Solo intentar cargar el perfil si el usuario está autenticado
      if (isset($_SESSION['autorizado'])) {
        $cliente = $this->profileService->getProfileByUserID($_SESSION['autorizado']->codigo);
        
        if ($cliente) {
          $_SESSION["cliente"] = $cliente;
        }
      }
    } catch (\Throwable $th) {
      error_log("ProfileController::profileInit - Error al obtener el perfil del usuario: " . $th->getMessage());
    }
  }

  public function index()
  {
    $this->profileInit();

    try {
      // Solo obtener tickets si el cliente existe en sesión
      $results = [];
      if (isset($_SESSION["cliente"])) {
        $clienteID = $_SESSION["cliente"]->codigo;
        error_log("ProfileController::index - Buscando tickets para cliente ID: {$clienteID}");
        
        $results = $this->ticketsService->getTicketsByClient($clienteID);
        
        error_log("ProfileController::index - Tickets encontrados: " . count($results));
        
        if (!is_array($results)) {
          error_log("ProfileController::index - WARNING: getTicketsByClient no devolvió un array");
          $results = [];
        }
      } else {
        error_log("ProfileController::index - Cliente no está en sesión");
      }
      
      $this->view("profile/profile", ["tickets" => $results]);
      
    } catch (Exception $e) {
      error_log("ProfileController::index - Error: " . $e->getMessage());
      $this->view("profile/profile", ["tickets" => []]);
    }
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

    try {
      // Solo obtener tickets si el cliente existe en sesión
      $results = [];
      if (isset($_SESSION["cliente"])) {
        $clienteID = $_SESSION["cliente"]->codigo;
        error_log("ProfileController::ticket_history - Buscando tickets para cliente ID: {$clienteID}");
        
        $results = $this->ticketsService->getTicketsByClient($clienteID);
        
        error_log("ProfileController::ticket_history - Tickets encontrados: " . count($results));
        
        if (!is_array($results)) {
          error_log("ProfileController::ticket_history - WARNING: getTicketsByClient no devolvió un array");
          $results = [];
        }
      } else {
        error_log("ProfileController::ticket_history - Cliente no está en sesión");
      }
      
      $this->view("profile/ticket_history", ["tickets" => $results]);
      
    } catch (Exception $th) {
      error_log("ProfileController::ticket_history - Error: " . $th->getMessage());
      $this->view("profile/ticket_history", ["tickets" => []]);
    }
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

  /**
   * Crear cliente el agente desde el dashboard
   * Método para crear un nuevo cliente completo (Usuario + Cliente)
   * Utiliza el stored procedure SP_REGISTRO_CLIENTE
   */
  public function crearCliente()
  {
    // Iniciar sesión si no está activa
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }

    // Validar que sea una petición POST
    if (!$this->isPost()) {
      $_SESSION['Error'] = 'Método no permitido';
      $this->redirect("/dashboard/registro_clientes");
      return;
    }

    // Validar que el usuario esté autenticado y tenga permisos
    if (!isset($_SESSION["autorizado"]) ||
        !in_array($_SESSION["autorizado"]->rolID, [
          RolType::ADMIN->value,
          RolType::SUPERVISOR->value,
          RolType::AGENT->value
        ])) {
      $_SESSION['Error'] = 'No tienes permisos para realizar esta acción';
      $this->redirect("/dashboard/registro_clientes");
      return;
    }

    try {
      // Obtener y validar datos del formulario
      $username = trim($_POST['username'] ?? '');
      $email = trim($_POST['email'] ?? '');
      $password = $_POST['txtPassword'] ?? '';
      $password2 = $_POST['txtPassword2'] ?? '';
      $agenciaID = (int)($_POST['agencia'] ?? 0);
      $fechaNacimiento = $_POST['fecha_nacimiento'] ?? '';
      $primerNombre = trim($_POST['primer_nombre'] ?? '');
      $segundoNombre = trim($_POST['segundo_nombre'] ?? '');
      $primerApellido = trim($_POST['primer_apellido'] ?? '');
      $segundoApellido = trim($_POST['segundo_apellido'] ?? '');
      $telefono = trim($_POST['telefono'] ?? '');
      $dui = trim($_POST['dui'] ?? '');

      // Validaciones básicas
      $errores = [];

      if (empty($username)) $errores[] = 'El nombre de usuario es obligatorio';
      if (empty($email)) $errores[] = 'El email es obligatorio';
      if (empty($password)) $errores[] = 'La contraseña es obligatoria';
      if (empty($password2)) $errores[] = 'La confirmación de contraseña es obligatoria';
      if (empty($primerNombre)) $errores[] = 'El primer nombre es obligatorio';
      if (empty($primerApellido)) $errores[] = 'El primer apellido es obligatorio';
      if (empty($fechaNacimiento)) $errores[] = 'La fecha de nacimiento es obligatoria';
      if (empty($telefono)) $errores[] = 'El teléfono es obligatorio';
      if (empty($dui)) $errores[] = 'El DUI es obligatorio';
      if ($agenciaID <= 0) $errores[] = 'Debes seleccionar una agencia';

      // Validar formato de email
      if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'El formato del email no es válido';
      }

      // Validar que las contraseñas coincidan
      if (!empty($password) && !empty($password2) && $password !== $password2) {
        $errores[] = 'Las contraseñas no coinciden';
      }

      // Validar longitud de contraseña
      if (!empty($password) && strlen($password) < 6) {
        $errores[] = 'La contraseña debe tener al menos 6 caracteres';
      }

      // Validar formato de fecha
      if (!empty($fechaNacimiento)) {
        $fecha = DateTime::createFromFormat('Y-m-d', $fechaNacimiento);
        if (!$fecha || $fecha->format('Y-m-d') !== $fechaNacimiento) {
          $errores[] = 'El formato de fecha no es válido';
        } else {
          // Validar que no sea fecha futura
          $hoy = new DateTime();
          if ($fecha > $hoy) {
            $errores[] = 'La fecha de nacimiento no puede ser futura';
          }
        }
      }

      // Validar formato de teléfono (El Salvador: 8 dígitos)
      if (!empty($telefono) && !preg_match('/^\d{4}-?\d{4}$/', $telefono)) {
        $errores[] = 'El formato del teléfono no es válido (ej: 7123-4567)';
      }

      // Validar formato de DUI (El Salvador: 8 dígitos + 1 dígito verificador)
      if (!empty($dui) && !preg_match('/^\d{8}-?\d$/', $dui)) {
        $errores[] = 'El formato del DUI no es válido (ej: 12345678-9)';
      }

      // Si hay errores, regresar al formulario
      if (!empty($errores)) {
        $_SESSION['Error'] = implode('<br>', $errores);
        $this->redirect("/dashboard/registro_clientes");
        return;
      }

      // Verificar que el username no exista
      if ($this->profileService->usernameExists($username)) {
        $_SESSION['Error'] = 'El nombre de usuario ya está en uso';
        $this->redirect("/dashboard/registro_clientes");
        return;
      }

      // Verificar que el email no exista
      if ($this->profileService->emailExists($email)) {
        $_SESSION['Error'] = 'El email ya está registrado';
        $this->redirect("/dashboard/registro_clientes");
        return;
      }

      // Hashear la contraseña
      $passwordHash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 6]);

      // Normalizar formato de teléfono y DUI
      $telefonoLimpio = preg_replace('/[^0-9]/', '', $telefono);
      $duiLimpio = preg_replace('/[^0-9]/', '', $dui);

      // Llamar al stored procedure para crear el cliente completo
      $resultado = $this->profileService->registrarClienteCompleto(
        RolType::CLIENT->value, // U_ROL_ID
        $username,              // U_USERNAME
        $email,                 // U_EMAIL
        $passwordHash,          // U_CLAVE
        $fechaNacimiento,       // C_FECHA_NAC
        $primerNombre,          // C_PRIMER_NOMBRE
        $segundoNombre,         // C_SEGUNDO_NOMBRE
        $primerApellido,        // C_PRIMER_APELLIDO
        $segundoApellido,       // C_SEGUNDO_APELLIDO
        $telefonoLimpio,        // C_TELEFONO
        $duiLimpio              // C_DUI
      );

      if ($resultado) {
        $_SESSION['Success'] = 'Cliente registrado exitosamente. Se ha creado la cuenta de usuario y el perfil del cliente.';
        error_log("ProfileController::crearCliente - Cliente registrado exitosamente: {$username}");
      } else {
        $_SESSION['Error'] = 'Error al registrar el cliente. Por favor, intente de nuevo.';
        error_log("ProfileController::crearCliente - Error al registrar cliente: {$username}");
      }

    } catch (Exception $e) {
      $_SESSION['Error'] = 'Ocurrió un error inesperado: ' . $e->getMessage();
      error_log("ProfileController::crearCliente - Exception: " . $e->getMessage());
    }

    // Regresar al formulario
    $this->redirect("/dashboard/registro_clientes");
    return;
  }
}
