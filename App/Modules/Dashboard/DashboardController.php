<?php

namespace App\Modules\Dashboard;

use App\Core\Controller;
use App\Core\GenericCrud;
use App\Models\Agencia;
use App\Models\Area;
use App\Modules\Dashboard\administracion\AgenciaService;
use App\Modules\Dashboard\administracion\AreaService;
use App\Modules\Tickets\TicketsService;

class DashboardController extends Controller
{
  private AgenciaService $agenciaService;
  private AreaService $areaService;

  private TicketsService $ticketsService;

  private $admin_path = "dashboard/administracion";

  public function __construct()
  {
    $this->agenciaService = new AgenciaService();
    $this->areaService = new AreaService();
    $this->ticketsService = new TicketsService();
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
    $this->view("dashboard/consultar_tickets");
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
}
