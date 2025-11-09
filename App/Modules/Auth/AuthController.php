<?php

namespace App\Modules\Auth;

use App\Core\Controller;
use App\Core\GenericCrud;
use App\Models\enums\Status;
use App\Models\enums\RolType;
use App\Models\Usuario;
use App\Modules\Auth\Repositories\RolRepository;
use App\Modules\Dashboard\administracion\AgenciaService;

class AuthController extends Controller
{

  private AuthService $authService;
  private UsuarioService $userService;
  private GenericCrud $agenciaService;
  private RolRepository $rolRepository;

  public function __construct()
  {
    $this->agenciaService = new AgenciaService();
    $this->userService = new UsuarioService();
    $this->rolRepository = new RolRepository();
    $this->authService = new AuthService();
  }

  public function index()
  {

    $this->view("auth/login");
  }

  public function register()
  {
    $this->view("auth/registro", [
      "agencias" => $this->agenciaService->findAll()
    ]);
  }

  public function login(): void
  {
    // Iniciar sesión para poder leer mensajes de error
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
    
    $this->view("auth/login");
  }

  public function logout()
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
    
    unset($_SESSION['autorizado']);
    session_destroy();

    // header('Location: ' . Parameters::BASE_URL . '/auth/login/vista');
    $this->redirect("/auth/login");
    exit();
  }

  /**
   * metodo encriptador de contraseñas
   */
  public function encriptador(): void
  {

    $data = [];

    try {

      if ($this->isPost()) {
        $pass_user = $_POST["password"];
        $pass_crypt = password_hash($pass_user, PASSWORD_BCRYPT, ['cost' => 6]);
        $data = ["pass_encryp" => $pass_crypt];
      }
    } catch (\Exception $e) {
      echo $e->getMessage();
    }

    $this->view("auth/encriptar", $data);
  }

  public function signin(): void
  {
    // Iniciar sesión si no está activa
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }

    if (
      isset($_POST)
      &&  isset($_POST['userOrEmail'])
      &&  isset($_POST['txtPassword'])
    ) {
      // obtenemos el valor que nos enviaron
      $username_or_email = $_POST['userOrEmail'];
      $password = $_POST['txtPassword'];

      $usuarioLogeado = $this->authService->signin($username_or_email, $password);

      // Manejar respuestas del servicio de autenticación
      if ($usuarioLogeado === 'Sin Registros') {
        // El Usuario o email no existe
        
        $_SESSION["Error"] = "El usuario o email no existen";
        $this->redirect("/auth/login");
        return;
      }

      if ($usuarioLogeado === false) {
        $_SESSION["Error"] = 'La contraseña es incorrecta';
        $this->redirect("/auth/login");
        return;
      }

      if (is_object($usuarioLogeado)) {
        // Regenerar id de sesión por seguridad
        session_regenerate_id(true);

        // Guardar el objeto usuario en sesión para usarlo en vistas y autorizaciones
        $_SESSION["autorizado"] = $usuarioLogeado;

        // Obtengo el Rol según usuario
        $rol = $this->rolRepository->findById($usuarioLogeado->rolID);

        // Validación de autorización y redirección según rol
        switch ($rol->codigo) {
          case RolType::ADMIN->value:
          case RolType::SUPERVISOR->value:
          case RolType::AGENT->value:
            // Todos los roles del dashboard van a la página de bienvenida
            $this->redirect("/dashboard/bienvenida");
            return;

          case RolType::CLIENT->value:
            $this->redirect("/profile");
            return;

          default:
            // Rol desconocido
            $this->redirect('/auth/login');
            return;
        }
      }

      // Si llegamos aquí, no hubo respuesta válida
      echo 'No hubo respuestas';
    }
  }

  /**
   * Metodo controlador para la creacion de un nuevo usuario (Cliente)
   */
  public function createAccount()
  {
    // Sólo procesar si es POST
    if (! $this->isPost()) {
      $this->view("auth/registro", [
        "agencias" => $this->agenciaService->findAll(),
        "Error" => "Método no permitido"
      ]);
      return;
    }

    // Extraer y normalizar campos
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $txtPassword = $_POST['txtPassword'] ?? '';
    $txtPassword2 = $_POST['txtPassword2'] ?? '';
    $agenciaID = $_POST['agencia'] ?? 1;
    // Agregamos los campos por defecto
    // 1. El Rol, por defecto "CLIENTE" ID: 4
    $rolID = RolType::CLIENT->value;
    // 2. Por defecto siempre será ACTIVO
    $estado = Status::ACTIVE->value;

    // Validaciones básicas
    if ($username === '' || $email === '' || $txtPassword === '' || $txtPassword2 === '') {
      $this->view("auth/registro", [
        "agencias" => $this->agenciaService->findAll(),
        "Error" => "Todos los campos son obligatorios"
      ]);
      return;
    }

    // Validamos misma contraseña
    if ($txtPassword !== $txtPassword2) {
      $this->view("auth/registro", [
        "agencias" => $this->agenciaService->findAll(),
        "Error" => "Las claves no coinciden"
      ]);
      return;
    }

    // Aquí iría la lógica de guardado (verificar usuario existente, hashear contraseña, persistir en BD)
    // Encripto la clave
    $passwordHash = password_hash($txtPassword, PASSWORD_BCRYPT, ['cost' => 6]);

    // creo el objeto Usuario
    $usuario = new Usuario();
    $usuario->setRolID($rolID);
    $usuario->setAgenciaID($agenciaID);
    $usuario->setUsername($username);
    $usuario->setEmail($email);
    $usuario->setClave($passwordHash);

    $createdUser = $this->userService->save($usuario);

    if ($createdUser) {
      // Iniciar sesión y guardar el objeto Usuario en sesión
      if (session_status() !== PHP_SESSION_ACTIVE) session_start();
      session_regenerate_id(true);
      $_SESSION['autorizado'] = $createdUser;

      // Redirigir al dashboard
      $this->redirect('/profile');
      return;
    } else {
      $this->view("auth/registro", [
        "Error" => "Hubo un error al crear la cuenta, intentelo más tarde",
        "agencias" => $this->agenciaService->findAll(),
      ]);
      return;
    }
  } // Fin metodo createAccount()


} // Fin de clase
