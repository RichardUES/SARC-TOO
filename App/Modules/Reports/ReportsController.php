<?php

namespace App\Modules\Reports;

use App\Core\Controller;
use App\Models\enums\RolType;
use App\Modules\Auth\UsuarioService;
use App\Modules\Dashboard\administracion\AreaService;
use TCPDF;

class ReportsController extends Controller
{

  private ReportsService $reportsService;
  private UsuarioService $usuarioService;
  private AreaService $areaService;

  public function __construct()
  {
    $this->reportsService = new ReportsService();
    $this->usuarioService = new UsuarioService();
    $this->areaService = new AreaService();
  }

  /**
   * Página principal de reportes
   */
  public function index()
  {
    // Iniciar sesión si no está activa
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }

    // Validar acceso: Solo admin y supervisor
    if (
      !isset($_SESSION["autorizado"]) ||
      !in_array($_SESSION["autorizado"]->rolID, [
        RolType::ADMIN->value,
        RolType::SUPERVISOR->value
      ])
    ) {
      header("Location: http://luzelfaro.com/errors/unauthorized");
      exit;
    }

    try {
      // Obtener datos para los selects
      $agentes = $this->usuarioService->getAgentesList();
      $areas = $this->areaService->findAll();

      $this->view("reports/index", [
        "agentes" => $agentes,
        "areas" => $areas
      ]);
    } catch (\Exception $e) {
      error_log("ReportsController::index - Error: " . $e->getMessage());
      $_SESSION['Error'] = 'Error al cargar la página de reportes';
      $this->view("reports/index", [
        "agentes" => [],
        "areas" => []
      ]);
    }
  }

  /**
   * Reporte de tickets resueltos por rango de fechas
   */
  public function tickets_resueltos_rango()
  {
    $this->validateAccess();

    if (!$this->isPost()) {
      $_SESSION['Error'] = 'Método no permitido';
      $this->redirect("/reports");
      return;
    }

    try {
      $fechaInicio = $_POST['fecha_inicio'] ?? '';
      $fechaFin = $_POST['fecha_fin'] ?? '';

      if (empty($fechaInicio) || empty($fechaFin)) {
        $_SESSION['Error'] = 'Las fechas son obligatorias';
        $this->redirect("/reports");
        return;
      }

      // Obtener datos procesados del reporte
      $datosReporte = $this->reportsService->procesarTicketsResueltos($fechaInicio, $fechaFin);

      // Generar PDF
      $this->generateTicketsResueltosRangoPDF($datosReporte, $fechaInicio, $fechaFin);
    } catch (\Exception $e) {
      error_log("ReportsController::tickets_resueltos_rango - Error: " . $e->getMessage());
      $_SESSION['Error'] = 'Error al generar el reporte: ' . $e->getMessage();
      $this->redirect("/reports");
    }
  }

  /**
   * Reporte de performance por agente
   */
  public function tickets_por_agente()
  {
    $this->validateAccess();

    if (!$this->isPost()) {
      $_SESSION['Error'] = 'Método no permitido';
      $this->redirect("/reports");
      return;
    }

    try {
      $fechaInicio = $_POST['fecha_inicio'] ?? '';
      $fechaFin = $_POST['fecha_fin'] ?? '';

      if (empty($fechaInicio) || empty($fechaFin)) {
        $_SESSION['Error'] = 'Las fechas son obligatorias';
        $this->redirect("/reports");
        return;
      }

      // Obtener datos procesados del reporte de performance de agentes
      $datosReporte = $this->reportsService->procesarPerformanceAgentes($fechaInicio, $fechaFin);

      // Generar PDF
      $this->generateTicketsPorAgentePDF($datosReporte, $fechaInicio, $fechaFin);
    } catch (\Exception $e) {
      error_log("ReportsController::tickets_por_agente - Error: " . $e->getMessage());
      $_SESSION['Error'] = 'Error al generar el reporte: ' . $e->getMessage();
      $this->redirect("/reports");
    }
  }

  /**
   * Reporte de casos escalados por área
   */
  public function casos_escalados()
  {
    $this->validateAccess();

    if (!$this->isPost()) {
      $_SESSION['Error'] = 'Método no permitido';
      $this->redirect("/reports");
      return;
    }

    try {
      $fechaInicio = $_POST['fecha_inicio'] ?? '';
      $fechaFin = $_POST['fecha_fin'] ?? '';

      if (empty($fechaInicio) || empty($fechaFin)) {
        $_SESSION['Error'] = 'Las fechas son obligatorias';
        $this->redirect("/reports");
        return;
      }

      // Obtener datos procesados del reporte de casos escalados
      $datosReporte = $this->reportsService->procesarCasosEscalados($fechaInicio, $fechaFin);

      // Generar PDF
      $this->generateCasosEscaladosPDF($datosReporte, $fechaInicio, $fechaFin);
    } catch (\Exception $e) {
      error_log("ReportsController::casos_escalados - Error: " . $e->getMessage());
      $_SESSION['Error'] = 'Error al generar el reporte: ' . $e->getMessage();
      $this->redirect("/reports");
    }
  }

  /**
   * Reporte general del sistema
   */
  public function reporte_general()
  {
    $this->validateAccess();

    if (!$this->isPost()) {
      $_SESSION['Error'] = 'Método no permitido';
      $this->redirect("/reports");
      return;
    }

    try {
      $fechaInicio = $_POST['fecha_inicio'] ?? '';
      $fechaFin = $_POST['fecha_fin'] ?? '';

      if (empty($fechaInicio) || empty($fechaFin)) {
        $_SESSION['Error'] = 'Las fechas son obligatorias';
        $this->redirect("/reports");
        return;
      }

      // Obtener datos procesados del reporte general
      $datosReporte = $this->reportsService->procesarReporteGeneral($fechaInicio, $fechaFin);

      // Generar PDF
      $this->generateReporteGeneralPDF($datosReporte, $fechaInicio, $fechaFin);
    } catch (\Exception $e) {
      error_log("ReportsController::reporte_general - Error: " . $e->getMessage());
      $_SESSION['Error'] = 'Error al generar el reporte: ' . $e->getMessage();
      $this->redirect("/reports");
    }
  }

  /**
   * Validar acceso a reportes
   */
  private function validateAccess()
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }

    if (
      !isset($_SESSION["autorizado"]) ||
      !in_array($_SESSION["autorizado"]->rolID, [
        RolType::ADMIN->value,
        RolType::SUPERVISOR->value
      ])
    ) {
      header("Location: http://luzelfaro.com/errors/unauthorized");
      exit;
    }
  }

  /**
   * Configuración base de TCPDF
   */
  private function createPDF($titulo = 'Reporte SARC')
  {
    $pdf = new TCPDF();

    // Información del documento
    $pdf->SetCreator('SARC - Luz el Faro');
    $pdf->SetAuthor('Sistema SARC');
    $pdf->SetTitle($titulo);
    $pdf->SetSubject('Reporte del Sistema');
    $pdf->SetKeywords('SARC, Reporte, Tickets, Luz el Faro');

    // Configuración de página
    $pdf->setPrintHeader(true);
    $pdf->setPrintFooter(true);

    // Header personalizado
    $pdf->SetHeaderData('', 0, 'Luz el Faro - SARC', $titulo . "\nFecha de generación: " . date('d/m/Y H:i:s'));

    // Fuentes
    $pdf->setHeaderFont(['helvetica', '', 10]);
    $pdf->setFooterFont(['helvetica', '', 8]);

    // Márgenes
    $pdf->SetMargins(15, 27, 15);
    $pdf->SetHeaderMargin(5);
    $pdf->SetFooterMargin(10);

    // Auto page breaks
    $pdf->SetAutoPageBreak(TRUE, 25);

    return $pdf;
  }

  /**
   * Generar PDF de tickets resueltos por rango
   */
  private function generateTicketsResueltosRangoPDF($datosReporte, $fechaInicio, $fechaFin)
  {
    $pdf = $this->createPDF('Reporte de Tickets Resueltos');
    $pdf->AddPage();

    // Título del reporte
    $html = '<h1 style="text-align: center; color: #2c5aa0;">Reporte de Tickets Resueltos</h1>';
    $html .= '<h3 style="text-align: center; color: #666;">Período: ' . date('d/m/Y', strtotime($fechaInicio)) . ' - ' . date('d/m/Y', strtotime($fechaFin)) . '</h3>';
    $html .= '<hr>';

    // Estadísticas generales
    $estadisticas = $datosReporte['estadisticas'];
    $html .= '<h2 style="color: #2c5aa0;">Resumen Ejecutivo</h2>';
    $html .= '<table border="1" style="width: 100%; border-collapse: collapse;">';
    $html .= '<tr style="background-color: #f8f9fa;">';
    $html .= '<th style="padding: 8px;">Total Tickets Resueltos</th>';
    $html .= '<th style="padding: 8px;">Tiempo Promedio Resolución (horas)</th>';
    $html .= '<th style="padding: 8px;">Tiempo Mínimo</th>';
    $html .= '<th style="padding: 8px;">Tiempo Máximo</th>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<td style="padding: 8px; text-align: center;">' . $datosReporte['total_tickets'] . '</td>';
    $html .= '<td style="padding: 8px; text-align: center;">' . $estadisticas['tiempo_promedio_horas'] . '</td>';
    $html .= '<td style="padding: 8px; text-align: center;">' . $estadisticas['tiempo_minimo_horas'] . '</td>';
    $html .= '<td style="padding: 8px; text-align: center;">' . $estadisticas['tiempo_maximo_horas'] . '</td>';
    $html .= '</tr>';
    $html .= '</table><br>';

    // Distribución por prioridad
    if (!empty($estadisticas['distribucion_prioridad'])) {
      $html .= '<h3 style="color: #2c5aa0;">Distribución por Prioridad</h3>';
      $html .= '<table border="1" style="width: 50%; border-collapse: collapse;">';
      $html .= '<tr style="background-color: #f8f9fa;"><th style="padding: 8px;">Prioridad</th><th style="padding: 8px;">Cantidad</th></tr>';
      foreach ($estadisticas['distribucion_prioridad'] as $prioridad => $cantidad) {
        $html .= '<tr><td style="padding: 8px;">' . htmlspecialchars($prioridad) . '</td><td style="padding: 8px; text-align: center;">' . $cantidad . '</td></tr>';
      }
      $html .= '</table><br>';
    }

    // Lista de tickets
    $tickets = $datosReporte['tickets'];
    if (!empty($tickets)) {
      $html .= '<h2 style="color: #2c5aa0;">Detalle de Tickets</h2>';
      $html .= '<table border="1" style="width: 100%; border-collapse: collapse; font-size: 9px;">';
      $html .= '<tr style="background-color: #2c5aa0; color: white;">';
      $html .= '<th style="padding: 4px;">Código</th>';
      $html .= '<th style="padding: 4px;">Cliente</th>';
      $html .= '<th style="padding: 4px;">Asunto</th>';
      $html .= '<th style="padding: 4px;">Agente</th>';
      $html .= '<th style="padding: 4px;">Prioridad</th>';
      $html .= '<th style="padding: 4px;">Horas Resolución</th>';
      $html .= '</tr>';

      foreach ($tickets as $ticket) {
        $html .= '<tr>';
        $html .= '<td style="padding: 4px;">TKT' . str_pad($ticket['TKT_CODIGO'], 5, '0', STR_PAD_LEFT) . '</td>';
        $html .= '<td style="padding: 4px;">' . htmlspecialchars($ticket['CLIENTE_NOMBRE_COMPLETO'] ?? 'N/A') . '</td>';
        $html .= '<td style="padding: 4px;">' . htmlspecialchars($ticket['TKT_ASUNTO'] ?? 'N/A') . '</td>';
        $html .= '<td style="padding: 4px;">' . htmlspecialchars($ticket['AGENTE_USUARIO'] ?? 'N/A') . '</td>';
        $html .= '<td style="padding: 4px;">' . htmlspecialchars($ticket['TKT_PRIORIDAD'] ?? 'N/A') . '</td>';
        $html .= '<td style="padding: 4px; text-align: center;">' . ($ticket['HORAS_RESOLUCION'] ?? 'N/A') . '</td>';
        $html .= '</tr>';
      }
      $html .= '</table>';
    } else {
      $html .= '<p style="text-align: center; color: #666; font-style: italic;">No se encontraron tickets resueltos en el período seleccionado.</p>';
    }

    $pdf->writeHTML($html, true, false, true, false, '');

    // Salida del PDF
    $filename = 'tickets_resueltos_' . $fechaInicio . '_' . $fechaFin . '.pdf';
    $pdf->Output($filename, 'D'); // D = forzar descarga
  }

  /**
   * Generar PDF de performance por agentes
   */
  private function generateTicketsPorAgentePDF($datosReporte, $fechaInicio, $fechaFin)
  {
    $pdf = $this->createPDF('Reporte de Performance por Agentes');
    $pdf->AddPage();

    // Título del reporte
    $html = '<h1 style="text-align: center; color: #2c5aa0;">Reporte de Performance por Agentes</h1>';
    $html .= '<h3 style="text-align: center; color: #666;">Período: ' . date('d/m/Y', strtotime($fechaInicio)) . ' - ' . date('d/m/Y', strtotime($fechaFin)) . '</h3>';
    $html .= '<hr>';

    // Estadísticas globales
    $estadisticasGlobales = $datosReporte['estadisticas_globales'];
    $html .= '<h2 style="color: #2c5aa0;">Resumen Global</h2>';
    $html .= '<table border="1" style="width: 100%; border-collapse: collapse;">';
    $html .= '<tr style="background-color: #f8f9fa;">';
    $html .= '<th style="padding: 8px;">Total Agentes Activos</th>';
    $html .= '<th style="padding: 8px;">Promedio Tickets Resueltos</th>';
    $html .= '<th style="padding: 8px;">Promedio Eficiencia (%)</th>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<td style="padding: 8px; text-align: center;">' . $estadisticasGlobales['total_agentes_activos'] . '</td>';
    $html .= '<td style="padding: 8px; text-align: center;">' . $estadisticasGlobales['promedio_tickets_resueltos'] . '</td>';
    $html .= '<td style="padding: 8px; text-align: center;">' . $estadisticasGlobales['promedio_eficiencia'] . '%</td>';
    $html .= '</tr>';
    $html .= '</table><br>';

    // Top performers
    if (isset($estadisticasGlobales['agente_mas_productivo']) && $estadisticasGlobales['agente_mas_productivo']) {
      $html .= '<h3 style="color: #2c5aa0;">Agente Más Productivo</h3>';
      $agenteProd = $estadisticasGlobales['agente_mas_productivo'];
      $html .= '<p><strong>' . htmlspecialchars($agenteProd['AGENTE_USUARIO']) . '</strong> - ' . $agenteProd['TICKETS_RESUELTOS'] . ' tickets resueltos</p>';
    }

    if (isset($estadisticasGlobales['agente_mas_eficiente']) && $estadisticasGlobales['agente_mas_eficiente']) {
      $html .= '<h3 style="color: #2c5aa0;">Agente Más Eficiente</h3>';
      $agenteEfic = $estadisticasGlobales['agente_mas_eficiente'];
      $html .= '<p><strong>' . htmlspecialchars($agenteEfic['AGENTE_USUARIO']) . '</strong> - ' . $agenteEfic['PORCENTAJE_EFICIENCIA'] . '% de eficiencia</p>';
    }

    // Lista detallada de agentes
    $agentes = $datosReporte['agentes'];
    if (!empty($agentes)) {
      $html .= '<h2 style="color: #2c5aa0;">Detalle por Agente</h2>';
      $html .= '<table border="1" style="width: 100%; border-collapse: collapse; font-size: 8px;">';
      $html .= '<tr style="background-color: #2c5aa0; color: white;">';
      $html .= '<th style="padding: 4px;">Usuario</th>';
      $html .= '<th style="padding: 4px;">Agencia</th>';
      $html .= '<th style="padding: 4px;">Asignados</th>';
      $html .= '<th style="padding: 4px;">Resueltos</th>';
      $html .= '<th style="padding: 4px;">Pendientes</th>';
      $html .= '<th style="padding: 4px;">Escalados</th>';
      $html .= '<th style="padding: 4px;">Eficiencia %</th>';
      $html .= '<th style="padding: 4px;">Tiempo Prom. (h)</th>';
      $html .= '</tr>';

      foreach ($agentes as $agente) {
        $html .= '<tr>';
        $html .= '<td style="padding: 4px;">' . htmlspecialchars($agente['AGENTE_USUARIO'] ?? 'N/A') . '</td>';
        $html .= '<td style="padding: 4px;">' . htmlspecialchars($agente['AGENCIA_NOMBRE'] ?? 'N/A') . '</td>';
        $html .= '<td style="padding: 4px; text-align: center;">' . ($agente['TICKETS_ASIGNADOS'] ?? 0) . '</td>';
        $html .= '<td style="padding: 4px; text-align: center;">' . ($agente['TICKETS_RESUELTOS'] ?? 0) . '</td>';
        $html .= '<td style="padding: 4px; text-align: center;">' . ($agente['TICKETS_PENDIENTES'] ?? 0) . '</td>';
        $html .= '<td style="padding: 4px; text-align: center;">' . ($agente['TICKETS_ESCALADOS'] ?? 0) . '</td>';
        $html .= '<td style="padding: 4px; text-align: center;">' . ($agente['PORCENTAJE_EFICIENCIA'] ?? 0) . '%</td>';
        $html .= '<td style="padding: 4px; text-align: center;">' . ($agente['TIEMPO_PROMEDIO_RESOLUCION_HORAS'] ?? 'N/A') . '</td>';
        $html .= '</tr>';
      }
      $html .= '</table>';
    } else {
      $html .= '<p style="text-align: center; color: #666; font-style: italic;">No se encontraron datos de agentes en el período seleccionado.</p>';
    }

    $pdf->writeHTML($html, true, false, true, false, '');

    // Salida del PDF
    $filename = 'performance_agentes_' . $fechaInicio . '_' . $fechaFin . '.pdf';
    $pdf->Output($filename, 'D');
  }

  /**
   * Generar PDF de casos escalados
   */
  private function generateCasosEscaladosPDF($datosReporte, $fechaInicio, $fechaFin)
  {
    $pdf = $this->createPDF('Reporte de Casos Escalados');
    $pdf->AddPage();

    // Título del reporte
    $html = '<h1 style="text-align: center; color: #2c5aa0;">Reporte de Casos Escalados</h1>';
    $html .= '<h3 style="text-align: center; color: #666;">Período: ' . date('d/m/Y', strtotime($fechaInicio)) . ' - ' . date('d/m/Y', strtotime($fechaFin)) . '</h3>';
    $html .= '<hr>';

    // Estadísticas generales
    $estadisticas = $datosReporte['estadisticas'];
    $html .= '<h2 style="color: #2c5aa0;">Resumen Ejecutivo</h2>';
    $html .= '<table border="1" style="width: 100%; border-collapse: collapse;">';
    $html .= '<tr style="background-color: #f8f9fa;">';
    $html .= '<th style="padding: 8px;">Total Casos Escalados</th>';
    $html .= '<th style="padding: 8px;">Promedio Horas Antes Escalamiento</th>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<td style="padding: 8px; text-align: center;">' . $datosReporte['total_casos_escalados'] . '</td>';
    $html .= '<td style="padding: 8px; text-align: center;">' . $estadisticas['promedio_horas_antes_escalamiento'] . '</td>';
    $html .= '</tr>';
    $html .= '</table><br>';

    // Distribución por área
    if (!empty($estadisticas['distribucion_por_area'])) {
      $html .= '<h3 style="color: #2c5aa0;">Distribución por Área</h3>';
      $html .= '<table border="1" style="width: 60%; border-collapse: collapse;">';
      $html .= '<tr style="background-color: #f8f9fa;"><th style="padding: 8px;">Área</th><th style="padding: 8px;">Cantidad</th></tr>';
      foreach ($estadisticas['distribucion_por_area'] as $area => $cantidad) {
        $html .= '<tr><td style="padding: 8px;">' . htmlspecialchars($area) . '</td><td style="padding: 8px; text-align: center;">' . $cantidad . '</td></tr>';
      }
      $html .= '</table><br>';
    }

    // Distribución por prioridad
    if (!empty($estadisticas['distribucion_por_prioridad'])) {
      $html .= '<h3 style="color: #2c5aa0;">Distribución por Prioridad</h3>';
      $html .= '<table border="1" style="width: 50%; border-collapse: collapse;">';
      $html .= '<tr style="background-color: #f8f9fa;"><th style="padding: 8px;">Prioridad</th><th style="padding: 8px;">Cantidad</th></tr>';
      foreach ($estadisticas['distribucion_por_prioridad'] as $prioridad => $cantidad) {
        $html .= '<tr><td style="padding: 8px;">' . htmlspecialchars($prioridad) . '</td><td style="padding: 8px; text-align: center;">' . $cantidad . '</td></tr>';
      }
      $html .= '</table><br>';
    }

    // Lista detallada de casos
    $casos = $datosReporte['casos'];
    if (!empty($casos)) {
      $html .= '<h2 style="color: #2c5aa0;">Detalle de Casos Escalados</h2>';
      $html .= '<table border="1" style="width: 100%; border-collapse: collapse; font-size: 8px;">';
      $html .= '<tr style="background-color: #2c5aa0; color: white;">';
      $html .= '<th style="padding: 4px;">Código</th>';
      $html .= '<th style="padding: 4px;">Cliente</th>';
      $html .= '<th style="padding: 4px;">Asunto</th>';
      $html .= '<th style="padding: 4px;">Área</th>';
      $html .= '<th style="padding: 4px;">Prioridad</th>';
      $html .= '<th style="padding: 4px;">Agente Escalador</th>';
      $html .= '<th style="padding: 4px;">Días desde Escalamiento</th>';
      $html .= '</tr>';

      foreach ($casos as $caso) {
        $html .= '<tr>';
        $html .= '<td style="padding: 4px;">TKT' . str_pad($caso['TKT_CODIGO'], 5, '0', STR_PAD_LEFT) . '</td>';
        $html .= '<td style="padding: 4px;">' . htmlspecialchars($caso['CLIENTE_NOMBRE_COMPLETO'] ?? 'N/A') . '</td>';
        $html .= '<td style="padding: 4px;">' . htmlspecialchars($caso['TKT_ASUNTO'] ?? 'N/A') . '</td>';
        $html .= '<td style="padding: 4px;">' . htmlspecialchars($caso['AREA_ESCALADO'] ?? 'N/A') . '</td>';
        $html .= '<td style="padding: 4px;">' . htmlspecialchars($caso['TKT_PRIORIDAD'] ?? 'N/A') . '</td>';
        $html .= '<td style="padding: 4px;">' . htmlspecialchars($caso['AGENTE_ESCALADOR'] ?? 'Sistema') . '</td>';
        $html .= '<td style="padding: 4px; text-align: center;">' . ($caso['DIAS_DESDE_ESCALAMIENTO'] ?? 'N/A') . '</td>';
        $html .= '</tr>';
      }
      $html .= '</table>';
    } else {
      $html .= '<p style="text-align: center; color: #666; font-style: italic;">No se encontraron casos escalados en el período seleccionado.</p>';
    }

    $pdf->writeHTML($html, true, false, true, false, '');

    // Salida del PDF
    $filename = 'casos_escalados_' . $fechaInicio . '_' . $fechaFin . '.pdf';
    $pdf->Output($filename, 'D');
  }

  /**
   * Generar PDF del reporte general ejecutivo
   */
  private function generateReporteGeneralPDF($datosReporte, $fechaInicio, $fechaFin)
  {
    $pdf = $this->createPDF('Reporte General del Sistema');
    $pdf->AddPage();

    // Título del reporte
    $html = '<h1 style="text-align: center; color: #2c5aa0;">Reporte General del Sistema SARC</h1>';
    $html .= '<h3 style="text-align: center; color: #666;">Período: ' . date('d/m/Y', strtotime($fechaInicio)) . ' - ' . date('d/m/Y', strtotime($fechaFin)) . '</h3>';
    $html .= '<hr>';

    // Estadísticas generales
    $estadisticas = $datosReporte['estadisticas_generales'];
    $metricas = $datosReporte['metricas_ejecutivas'];

    $html .= '<h2 style="color: #2c5aa0;">Panel de Control Ejecutivo</h2>';
    $html .= '<table border="1" style="width: 100%; border-collapse: collapse;">';
    $html .= '<tr style="background-color: #f8f9fa;">';
    $html .= '<th style="padding: 8px;">Métricas</th>';
    $html .= '<th style="padding: 8px;">Valores</th>';
    $html .= '<th style="padding: 8px;">Métricas</th>';
    $html .= '<th style="padding: 8px;">Valores</th>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<td style="padding: 8px;"><strong>Total Tickets</strong></td>';
    $html .= '<td style="padding: 8px; text-align: center;">' . $estadisticas['TOTAL_TICKETS'] . '</td>';
    $html .= '<td style="padding: 8px;"><strong>% Resolución</strong></td>';
    $html .= '<td style="padding: 8px; text-align: center;">' . $metricas['porcentaje_resolucion'] . '%</td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<td style="padding: 8px;"><strong>Tickets Resueltos</strong></td>';
    $html .= '<td style="padding: 8px; text-align: center;">' . $estadisticas['TICKETS_RESUELTOS'] . '</td>';
    $html .= '<td style="padding: 8px;"><strong>% Escalamiento</strong></td>';
    $html .= '<td style="padding: 8px; text-align: center;">' . $metricas['porcentaje_escalamiento'] . '%</td>';
    $html .= '</tr>';
    $html .= '<tr>';
    $html .= '<td style="padding: 8px;"><strong>Tickets Escalados</strong></td>';
    $html .= '<td style="padding: 8px; text-align: center;">' . $estadisticas['TICKETS_ESCALADOS'] . '</td>';
    $html .= '<td style="padding: 8px;"><strong>Tiempo Prom. Resolución</strong></td>';
    $html .= '<td style="padding: 8px; text-align: center;">' . $metricas['tiempo_promedio_resolucion_dias'] . ' días</td>';
    $html .= '</tr>';
    $html .= '</table><br>';

    // Distribución por estados
    $html .= '<h3 style="color: #2c5aa0;">Distribución por Estados</h3>';
    $html .= '<table border="1" style="width: 70%; border-collapse: collapse;">';
    $html .= '<tr style="background-color: #2c5aa0; color: white;">';
    $html .= '<th style="padding: 6px;">Estado</th>';
    $html .= '<th style="padding: 6px;">Cantidad</th>';
    $html .= '</tr>';
    $html .= '<tr><td style="padding: 6px;">Recibidos</td><td style="padding: 6px; text-align: center;">' . $estadisticas['TICKETS_RECIBIDOS'] . '</td></tr>';
    $html .= '<tr><td style="padding: 6px;">Asignados</td><td style="padding: 6px; text-align: center;">' . $estadisticas['TICKETS_ASIGNADOS'] . '</td></tr>';
    $html .= '<tr><td style="padding: 6px;">En Proceso</td><td style="padding: 6px; text-align: center;">' . $estadisticas['TICKETS_EN_PROCESO'] . '</td></tr>';
    $html .= '<tr><td style="padding: 6px;">Pendientes</td><td style="padding: 6px; text-align: center;">' . $estadisticas['TICKETS_PENDIENTES'] . '</td></tr>';
    $html .= '<tr><td style="padding: 6px;">Escalados</td><td style="padding: 6px; text-align: center;">' . $estadisticas['TICKETS_ESCALADOS'] . '</td></tr>';
    $html .= '<tr style="background-color: #d4edda;"><td style="padding: 6px;"><strong>Resueltos</strong></td><td style="padding: 6px; text-align: center;"><strong>' . $estadisticas['TICKETS_RESUELTOS'] . '</strong></td></tr>';
    $html .= '</table><br>';

    // Performance por agencias
    $agencias = $datosReporte['estadisticas_agencias'];
    if (!empty($agencias)) {
      $html .= '<h3 style="color: #2c5aa0;">Performance por Agencias</h3>';
      $html .= '<table border="1" style="width: 100%; border-collapse: collapse; font-size: 9px;">';
      $html .= '<tr style="background-color: #2c5aa0; color: white;">';
      $html .= '<th style="padding: 5px;">Agencia</th>';
      $html .= '<th style="padding: 5px;">Total Tickets</th>';
      $html .= '<th style="padding: 5px;">Resueltos</th>';
      $html .= '<th style="padding: 5px;">% Resolución</th>';
      $html .= '</tr>';

      foreach ($agencias as $agencia) {
        $html .= '<tr>';
        $html .= '<td style="padding: 5px;">' . htmlspecialchars($agencia['AGE_NOMBRE']) . '</td>';
        $html .= '<td style="padding: 5px; text-align: center;">' . $agencia['TOTAL_TICKETS'] . '</td>';
        $html .= '<td style="padding: 5px; text-align: center;">' . $agencia['TICKETS_RESUELTOS'] . '</td>';
        $html .= '<td style="padding: 5px; text-align: center;">' . $agencia['PORCENTAJE_RESOLUCION'] . '%</td>';
        $html .= '</tr>';
      }
      $html .= '</table><br>';
    }

    // Análisis de tendencias
    $analisis = $datosReporte['analisis_tendencias'];
    $html .= '<h3 style="color: #2c5aa0;">Análisis de Tendencias</h3>';
    $html .= '<table border="1" style="width: 80%; border-collapse: collapse;">';
    $html .= '<tr style="background-color: #f8f9fa;">';
    $html .= '<th style="padding: 8px;">Aspecto</th>';
    $html .= '<th style="padding: 8px;">Resultado</th>';
    $html .= '</tr>';
    $html .= '<tr><td style="padding: 8px;">Canal Predominante</td><td style="padding: 8px; text-align: center;">' . $analisis['canal_predominante'] . '</td></tr>';
    $html .= '<tr><td style="padding: 8px;">Prioridad Predominante</td><td style="padding: 8px; text-align: center;">' . $analisis['prioridad_predominante'] . '</td></tr>';
    $html .= '<tr><td style="padding: 8px;">Agencia Más Activa</td><td style="padding: 8px; text-align: center;">' . $analisis['agencia_mas_activa'] . '</td></tr>';
    $html .= '<tr><td style="padding: 8px;">Área Más Escalada</td><td style="padding: 8px; text-align: center;">' . $analisis['area_mas_escalada'] . '</td></tr>';
    $html .= '</table>';

    $pdf->writeHTML($html, true, false, true, false, '');

    // Salida del PDF
    $filename = 'reporte_general_' . $fechaInicio . '_' . $fechaFin . '.pdf';
    $pdf->Output($filename, 'D');
  }
}
