<?php

namespace App\Modules\Auth;

use App\Core\Controller;
use App\Models\enums\UserRol;
use App\Modules\Dashboard\administracion\AgenciaService;

class AuthController extends Controller
{

  private AuthService $authService;
  private AgenciaService $agenciaService;

  public function __construct() {
    $this->agenciaService = new AgenciaService();
  }

  public function index()
  {

    $this->view("auth/login");
  }

  public function login(): void
  {
    $this->view("auth/login");
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

    if (
      isset($_POST)
      &&  $_POST['userOrEmail']
      &&  $_POST['txtPassword']
    ) {

      // INICIAMOS LA SESION, PORQUE USARE VARIABLES DE SESION
      session_start();

      // obtenemos el valor que nos enviaron
      $username_or_email = $_POST['userOrEmail'];
      $password = $_POST['txtPassword'];

      if ($username_or_email == "rpineda") {

        $_SESSION["autorizado"] = "Ricardo Pineda";
        $this->redirect("/dashboard");
      } else {

        $this->view("auth/login", [
          // "Error" => $_SESSION["error_login"] = "Soy un error :("
          "Error" => "Soy un error :("
        ]);
      }

      // header("Location: " . "/auth/login");

      // identificar al usuario
      // Consulta a la base de datos
      //$usuario = $this->$userRepository->singin($pasword, $username_or_email);

    }
  } // FIN DE METODO signin()

  public function register()
  {
    $this->view("auth/registro", [
      "agencias" => $this->agenciaService->listAgencias()
    ]);
  }

  /**
   * Metodo controlador para la creacion de un nuevo usuario (Cliente)
   */
  public function userRegister()
  {
    // Sólo procesar si es POST
    if (! $this->isPost()) {
      $this->view("auth/registro", ["Error" => "Método no permitido"]);
      return;
    }

    // Extraer y normalizar campos
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $txtPassword = $_POST['txtPassword'] ?? '';
    $txtPassword2 = $_POST['txtPassword2'] ?? '';
    $agenciaId = $_POST['agencia'] ?? 0;
    // Agregamos los campos por defecto
    // 1. El Rol, por defecto "CLIENTE" ID: 4
    $rolID = UserRol::CLIENT;
    

    // Validaciones básicas
    if ($username === '' || $email === '' || $txtPassword === '' || $txtPassword2 === '') {
      $this->view("auth/registro", ["Error" => "Todos los campos son obligatorios"]);
      return;
    }

    if ($txtPassword !== $txtPassword2) {
      $this->view("auth/registro", ["Error" => "Las claves no coinciden"]);
      return;
    }

    // Aquí iría la lógica de guardado (verificar usuario existente, hashear contraseña, persistir en BD)
    // Por ahora devolvemos una vista de éxito o redirección según convenga.
    // Ejemplo mínimo: hashear y mostrar mensaje (no persistimos todavía)
    $passwordHash = password_hash($txtPassword, PASSWORD_BCRYPT);

    // Puedes reemplazar esto por la llamada a tu repositorio para crear el usuario.
    $this->view("auth/registro", [
      "Success" => "Cuenta creada (simulada)",
      "username" => $username,
      "email" => $email,
      "passwordHash" => $passwordHash,
    ]);
    return;
  }// Fin metodo createAccount()

} // Fin de clase
