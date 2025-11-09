<?php

namespace App\Modules\Dashboard;

use App\Core\Controller;
use App\Core\GenericCrud;
use App\Models\Agencia;
use App\Models\Area;
use App\Models\Usuario;
use App\Modules\Auth\AuthService;
use App\Modules\Auth\UsuarioService;
use App\Modules\Dashboard\administracion\AgenciaService;
use App\Modules\Dashboard\administracion\AreaService;
use App\Modules\Tickets\TicketsService;
use Error;
use Exception;

class DashboardController extends Controller
{
  private AgenciaService $agenciaService;
  private AreaService $areaService;
  private TicketsService $ticketsService;
  private UsuarioService $userService;
  private AuthService $authService;

  private $admin_path = "dashboard/administracion";

  public function __construct()
  {
    $this->agenciaService = new AgenciaService();
    $this->areaService = new AreaService();
    $this->ticketsService = new TicketsService();
    $this->userService = new UsuarioService();
    $this->authService = new AuthService();
  }

  /* ===================================================== */
  /* ========== ROUTER DEL DASHBOARD ============ */
  /* ===================================================== */

  public function index()
  {
    $this->view('dashboard/main');
  }

  // MENU PRINCIPAL
  public function registro_usuarios()
  {
    $this->view("dashboard/registro_usuarios");
  }

  public function mis_tickets($id)
  {
    //var_dump($id);
    $this->view("dashboard/mis_tickets");
  }

  public function tickets()
  {

    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }

    try {
      $tickets = $this->ticketsService->getAllTickets();

      if ( isset($tickets) ) {
        $_SESSION['tickets'] = $tickets;
      }

      $this->view("dashboard/consultar_tickets");

    } catch (Exception $th) {
      $_SESSION['Error'] = 'Ocurrió un error inesperado al cargar los tickets. Por favor, intente de nuevo más tarde.';
      error_log("DashboardController::tickets - Error al obtener tickets: " . $th->getMessage());
      $this->view("dashboard/consultar_tickets");
    }
  }

  public function cola_tickets()
  {
    $tickets = $this->ticketsService->obtenerColaTickets();
    $this->view("dashboard/cola_tickets", $tickets);
  }

  public function reporteria()
  {
    $this->view("dashboard/generar_reportes");
  }

  // ROUTER DE ADMINISTRACIÓN
  public function gestion_usuarios()
  {

    try {
      if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
      }

      $data = $this->getAgenciasAndRoles();
      if ($data) {
        $_SESSION['agencias'] = $data['agencias'];
        $_SESSION['roles'] = $data['roles'];
      }

      $this->view($this->admin_path . "/gestion_usuarios");
      return;
    } catch (Exception $ex) {
      error_log("DashboardController::gestion_usuarios - Error: " . $ex->getMessage());
      $_SESSION['Error'] = 'Ocurrió un error inesperado al cargar la gestión de usuarios. Por favor, intente de nuevo más tarde.';
      $data = $this->getAgenciasAndRoles();
      if ($data) {
        $_SESSION['agencias'] = $data['agencias'];
        $_SESSION['roles'] = $data['roles'];
      }
      $this->view($this->admin_path . "/gestion_usuarios");
    }
  }

  public function gestion_agencias()
  {
    $this->view($this->admin_path . "/gestion_agencias");
  }

  public function gestion_areas()
  {
    $this->view($this->admin_path . "/gestion_areas");
  }

  /* ===================================================== */
  /* ========== FUNCIONALIDADES DEL DASHBOARD ============ */
  /* ===================================================== */

  public function crear_agencia()
  {

    if (!$this->isPost()) {
      $this->view(
        $this->admin_path . "/gestion_agencias",
        ["Error" => "Método no permitido"]
      );
      return;
    }

    // Extraer valor de campos
    $nombre = $_POST["nombre"] ?? '';
    $direccion = $_POST["direccion"] ?? '';
    $telefono = $_POST["telefono"];

    // validacion básica
    if (
      $nombre === ''
      || $direccion === ''
    ) {
      $this->view($this->admin_path . "/gestion_agencias", ["Error" => "Nombre y dirección son obligatorios"]);
      return;
    }

    // Procedemos a crear la Agencia
    $agencia = new Agencia();

    $agencia->setNombre($nombre);
    $agencia->setDireccion($direccion);
    $agencia->setTelefono($telefono);

    $isCreate = $this->agenciaService->save($agencia);

    if ($isCreate) {
      $this->view(
        $this->admin_path . "/gestion_agencias",
        ["Success" => "Agencia creada satisfactoriamente!"]
      );
    } else {
      $this->view(
        $this->admin_path . "/gestion_agencias",
        ["success" => "Hubo problema al crear la agencia, consulte con el administrador de IT"]
      );
    }
  }

  public function crear_area()
  {

    if (!$this->isPost()) {
      $this->view(
        $this->admin_path . "/gestion_areas",
        ["Error" => "Método no permitido"]
      );
      return;
    }

    // Extraer valor de campos
    $nombre = $_POST["nombre"] ?? '';
    $descripcion = $_POST["descripcion"] ?? '';

    // validacion básica
    if (
      $nombre === ''
      || $descripcion === ''
    ) {
      $this->view($this->admin_path . "/gestion_areas", ["Error" => "Nombre y descripción son obligatorios"]);
      return;
    }

    // Procedemos a crear la Agencia
    $area = new Area();

    $area->setNombre($nombre);
    $area->setDescripcion($descripcion);

    $isCreate = $this->areaService->save($area);

    if ($isCreate) {
      $_SESSION['Success'] = "Área creada satisfactoriamente!";
      $this->view($this->admin_path . "/gestion_areas");
    } else {
      $_SESSION['Error'] = "Hubo problema al crear el área, consulte con el administrador de IT";
      $this->view($this->admin_path . "/gestion_areas");
    }
  }

  public function crear_usuario()
  {

    try {
      if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
      }

      if (!$this->isPost()) {
        $_SESSION['Error'] = "Método no permitido";
        $data = $this->getAgenciasAndRoles();
        if ($data) {
          $_SESSION['agencias'] = $data['agencias'];
          $_SESSION['roles'] = $data['roles'];
        }
        $this->view($this->admin_path . "/gestion_usuarios");
        return;
      }

      // Extraer valor de campos
      $username = $_POST["username"] ?? '';
      $email = $_POST["email"] ?? '';
      $password = $_POST["password"] ?? '';
      $role_id = $_POST["role_id"] ?? '';
      $agency_id = $_POST["agency_id"] ?? '';

      // validacion básica
      if (
        $username === ''
        || $email === ''
        || $password === ''
        || $role_id === ''
        || $agency_id === ''
      ) {
        $_SESSION['Error'] = "Todos los campos son obligatorios";
        $data = $this->getAgenciasAndRoles();
        if ($data) {
          $_SESSION['agencias'] = $data['agencias'];
          $_SESSION['roles'] = $data['roles'];
        }
        $this->view($this->admin_path . "/gestion_usuarios");
        return;
      }

      // Procedemos a crear el Usuario
      $user = new Usuario();
      $user->setUsername($username);
      $user->setEmail($email);
      $user->setClave(password_hash($password, PASSWORD_BCRYPT, ['cost' => 6]));
      $user->setRolID((int)$role_id);
      $user->setAgenciaID((int)$agency_id);

      $isCreated = $this->userService->save($user);

      if (isset($isCreated)) {

        $_SESSION['Success'] = "Usuario creado satisfactoriamente!";
        $data = $this->getAgenciasAndRoles();
        if ($data) {
          $_SESSION['agencias'] = $data['agencias'];
          $_SESSION['roles'] = $data['roles'];
        }
        $this->view($this->admin_path . "/gestion_usuarios");
        return;
      }
      // Fallo al crear el usuario
      $_SESSION['Error'] = 'Hubo un error al crear el usuario. Por favor, intente de nuevo.';
      $data = $this->getAgenciasAndRoles();
      if ($data) {
        $_SESSION['agencias'] = $data['agencias'];
        $_SESSION['roles'] = $data['roles'];
      }
      $this->view($this->admin_path . "/gestion_usuarios");
    } catch (Exception $ex) {
      error_log("Error al crear usuario: " . $ex->getMessage());
      $_SESSION['Error'] = 'Ocurrió un error inesperado al crear el usuario. Por favor, intente de nuevo más tarde.';
      $data = $this->getAgenciasAndRoles();
      if ($data) {
        $_SESSION['agencias'] = $data['agencias'];
        $_SESSION['roles'] = $data['roles'];
      }
      $this->view($this->admin_path . "/gestion_usuarios");
    }
  } // FIN DE MÉTODO crear_usuario()


  private function getAgenciasAndRoles()
  {
    try {
      $agencias = $this->agenciaService->findAll();
      $roles = $this->authService->obtenerRoles();

      return [
        'agencias' => $agencias,
        'roles' => $roles
      ];
    } catch (Error $ex) {
      error_log("DashboardController::getAgenciasAndRoles - Error: " . $ex->getMessage());
      return null;
    }
  }
} // FIN DE CLASE
