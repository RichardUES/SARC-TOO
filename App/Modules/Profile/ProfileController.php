<?php

namespace App\Modules\Profile;

use App\Core\Controller;
use App\Models\Cliente;

use DateTime;

class ProfileController extends Controller
{

  private ProfileService $profileService;

  public function __construct() {

    $this->profileService = new ProfileService();

  }

  public function index()
  {
    session_start();
    $this->view("profile/profile");
  }

  public function personal_info()
  {
    session_start();
    $this->view("profile/personal_info");
  }

  public function create_ticket()
  {
    session_start();
    $this->view("profile/create_ticket");
  }

  public function ticket_history()
  {
    session_start();
    $this->view("profile/ticket_history");
  }

  public function notifications()
  {
    session_start();
    $this->view("profile/notifications");
  }

  public function settings()
  {
    session_start();
    $this->view("profile/settings");
  }

  public function update_profile(): void {

    // Los datos del usuario los obtengo de la sesión
    if( session_status() !== PHP_SESSION_ACTIVE ){
      session_start();
    }

    if( !$this->isPost() ) {
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

    if( $primerNombre === '' 
        || $primerApellido === '' 
        || $fechaNac === '' 
        || $dui === '' 
        || $telefono === '' ) 
    {
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
    $client->setFechaNac(new DateTime($fechaNac) );
    $client->setDui($dui);
    $client->setTelefono($telefono);

    $cliente = $this->profileService->createProfileClient($client);

    if ( isset($cliente) ) {
      $_SESSION["cliente"] = $cliente;

      $this->redirect("/profile/personal_info");
      return;
      
    }

  }


}