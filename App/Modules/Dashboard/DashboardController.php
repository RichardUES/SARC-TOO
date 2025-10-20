<?php

namespace App\Modules\Dashboard;

use App\Core\Controller;
use App\Models\Agencia;
use App\Modules\Dashboard\administracion\AgenciaService;

class DashboardController extends Controller
{
  private AgenciaService $agenciaService;

  private $admin_path = "dashboard/administracion";

  public function __construct() {
    $this->agenciaService = new AgenciaService;
  }

  // ROUTER DEL PANEL ADMINISTRATIVO

  public function index()
  {
    $this->view('dashboard/main');
  }

  // MENU PRINCIPAL
  public function registro_usuarios()
  {
    $this->view("dashboard/registro_usuarios");
  }

  public function mis_tickets()
  {
    $this->view("dashboard/mis_tickets");
  }

  public function tickets()
  {
    $this->view("dashboard/consultar_tickets");
  }

  public function cola_tickets()
  {
    $this->view("dashboard/cola_tickets");
  }

  public function reporteria()
  {
    $this->view("dashboard/generar_reportes");
  }

  // ROUTER DE ADMINISTRACIÓN
  public function gestion_usuarios()
  {
    $this->view($this->admin_path . "/gestion_usuarios");
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

    $isCreate = $this->agenciaService->createAgency($agencia);

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
}
