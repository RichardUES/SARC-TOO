<?php

use App\Models\enums\RolType;

require_once VIEWS_PATH . "/dashboard/layouts/head.php";
require_once VIEWS_PATH . "/dashboard/layouts/header.php";

// Validación de acceso: Solo admin y supervisor
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

require_once VIEWS_PATH . "/dashboard/layouts/sidebar.php";

?>

<!-- Generación de Reportes -->
<div class="container-fluid py-4 main">
  <div class="row">
    <div class="col-12">

      <!-- Header de la página -->
      <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-secondary text-white py-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h3 class="mb-0 fw-bold">
                <i class="bi bi-file-earmark-bar-graph me-2"></i>Generación de Reportes
              </h3>
              <p class="mb-0 mt-1 opacity-75">Sistema de reportería y análisis estadístico</p>
            </div>
            <div class="badge bg-primary bg-opacity-25 text-primary px-3 py-2">
              <i class="bi bi-person-badge me-2"></i>
              <?= htmlspecialchars($_SESSION["autorizado"]->username) ?>
            </div>
          </div>
        </div>
      </div>

      <!-- Alertas -->
      <?php if (isset($_SESSION['Error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="bi bi-exclamation-triangle-fill me-2"></i>
          <strong><?= $_SESSION['Error'] ?></strong>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['Error']); ?>
      <?php endif; ?>

      <?php if (isset($_SESSION['Success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="bi bi-check-circle-fill me-2"></i>
          <strong><?= $_SESSION['Success'] ?></strong>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['Success']); ?>
      <?php endif; ?>

      <!-- Grid de Reportes -->
      <div class="row g-4">

        <!-- Reporte 1: Tickets Resueltos por Fechas -->
        <div class="col-lg-6 col-md-12">
          <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-primary bg-opacity-10">
              <h5 class="mb-0 text-info fw-semibold">
                <i class="bi bi-graph-up me-2"></i>Tickets Resueltos por Fechas
              </h5>
            </div>
            <div class="card-body">
              <p class="text-muted mb-4">
                Genera un reporte detallado de todos los tickets resueltos en un período específico,
                incluyendo estadísticas y métricas de rendimiento.
              </p>

              <form method="POST" action="/reports/tickets_resueltos_rango">
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label for="fecha_inicio_1" class="form-label fw-semibold text-secondary">
                      <i class="bi bi-calendar-event me-1"></i>Fecha Inicio
                    </label>
                    <input
                      type="date"
                      id="fecha_inicio_1"
                      name="fecha_inicio"
                      required
                      class="form-control"
                      max="<?= date('Y-m-d') ?>">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="fecha_fin_1" class="form-label fw-semibold text-secondary">
                      <i class="bi bi-calendar-check me-1"></i>Fecha Fin
                    </label>
                    <input
                      type="date"
                      id="fecha_fin_1"
                      name="fecha_fin"
                      required
                      class="form-control"
                      max="<?= date('Y-m-d') ?>">
                  </div>
                </div>
                <button type="submit" class="btn btn-primary w-100">
                  <i class="bi bi-file-earmark-pdf me-2"></i>Generar Reporte PDF
                </button>
              </form>
            </div>
          </div>
        </div>

        <!-- Reporte 2: Performance por Agente -->
        <div class="col-lg-6 col-md-12">
          <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-success bg-opacity-10">
              <h5 class="mb-0 text-success fw-semibold">
                <i class="bi bi-people-fill me-2"></i>Performance por Agente
              </h5>
            </div>
            <div class="card-body">
              <p class="text-muted mb-4">
                Análisis de productividad y rendimiento de agentes específicos,
                mostrando tickets asignados, resueltos y tiempos de respuesta.
              </p>

              <form method="POST" action="/reports/tickets_por_agente">
                <div class="mb-3">
                  <label for="agente_id" class="form-label fw-semibold text-secondary">
                    <i class="bi bi-person-check me-1"></i>Seleccionar Agente
                  </label>
                  <select id="agente_id" name="agente_id" required class="form-select">
                    <option value="">Seleccione un agente</option>
                    <?php if (isset($data["agentes"]) && !empty($data["agentes"])): ?>
                      <?php foreach ($data["agentes"] as $agente): ?>
                        <option value="<?= $agente->codigo ?>">
                          <?= htmlspecialchars($agente->username) ?> - <?= htmlspecialchars($agente->email) ?>
                        </option>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </select>
                </div>

                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label for="fecha_inicio_2" class="form-label fw-semibold text-secondary">
                      <i class="bi bi-calendar-event me-1"></i>Fecha Inicio
                    </label>
                    <input
                      type="date"
                      id="fecha_inicio_2"
                      name="fecha_inicio"
                      required
                      class="form-control"
                      max="<?= date('Y-m-d') ?>">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="fecha_fin_2" class="form-label fw-semibold text-secondary">
                      <i class="bi bi-calendar-check me-1"></i>Fecha Fin
                    </label>
                    <input
                      type="date"
                      id="fecha_fin_2"
                      name="fecha_fin"
                      required
                      class="form-control"
                      max="<?= date('Y-m-d') ?>">
                  </div>
                </div>
                <button type="submit" class="btn btn-success w-100">
                  <i class="bi bi-file-earmark-pdf me-2"></i>Generar Reporte PDF
                </button>
              </form>
            </div>
          </div>
        </div>

        <!-- Reporte 3: Casos Escalados por Área -->
        <div class="col-lg-6 col-md-12">
          <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-warning bg-opacity-10">
              <h5 class="mb-0 text-warning fw-semibold">
                <i class="bi bi-arrow-up-circle me-2"></i>Casos Escalados por Área
              </h5>
            </div>
            <div class="card-body">
              <p class="text-muted mb-4">
                Reporte de tickets que han sido escalados a diferentes áreas,
                incluyendo tiempos de escalación y resolución por departamento.
              </p>

              <form method="POST" action="/reports/casos_escalados">
                <div class="mb-3">
                  <label for="area_id" class="form-label fw-semibold text-secondary">
                    <i class="bi bi-diagram-3 me-1"></i>Seleccionar Área
                  </label>
                  <select id="area_id" name="area_id" required class="form-select">
                    <option value="">Seleccione un área</option>
                    <?php if (isset($data["areas"]) && !empty($data["areas"])): ?>
                      <?php foreach ($data["areas"] as $area): ?>
                        <option value="<?= $area->codigo ?>">
                          <?= htmlspecialchars($area->nombre) ?>
                        </option>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </select>
                </div>

                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label for="fecha_inicio_3" class="form-label fw-semibold text-secondary">
                      <i class="bi bi-calendar-event me-1"></i>Fecha Inicio
                    </label>
                    <input
                      type="date"
                      id="fecha_inicio_3"
                      name="fecha_inicio"
                      required
                      class="form-control"
                      max="<?= date('Y-m-d') ?>">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="fecha_fin_3" class="form-label fw-semibold text-secondary">
                      <i class="bi bi-calendar-check me-1"></i>Fecha Fin
                    </label>
                    <input
                      type="date"
                      id="fecha_fin_3"
                      name="fecha_fin"
                      required
                      class="form-control"
                      max="<?= date('Y-m-d') ?>">
                  </div>
                </div>
                <button type="submit" class="btn btn-warning w-100">
                  <i class="bi bi-file-earmark-pdf me-2"></i>Generar Reporte PDF
                </button>
              </form>
            </div>
          </div>
        </div>

        <!-- Reporte 4: Reporte General del Sistema -->
        <div class="col-lg-6 col-md-12">
          <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-info bg-opacity-10">
              <h5 class="mb-0 text-info fw-semibold">
                <i class="bi bi-clipboard-data me-2"></i>Reporte General del Sistema
              </h5>
            </div>
            <div class="card-body">
              <p class="text-muted mb-4">
                Resumen completo del sistema incluyendo estadísticas generales,
                métricas de rendimiento y estado global de todos los módulos.
              </p>

              <form method="POST" action="/reports/reporte_general">
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label for="fecha_inicio_4" class="form-label fw-semibold text-secondary">
                      <i class="bi bi-calendar-event me-1"></i>Fecha Inicio
                    </label>
                    <input
                      type="date"
                      id="fecha_inicio_4"
                      name="fecha_inicio"
                      required
                      class="form-control"
                      max="<?= date('Y-m-d') ?>">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="fecha_fin_4" class="form-label fw-semibold text-secondary">
                      <i class="bi bi-calendar-check me-1"></i>Fecha Fin
                    </label>
                    <input
                      type="date"
                      id="fecha_fin_4"
                      name="fecha_fin"
                      required
                      class="form-control"
                      max="<?= date('Y-m-d') ?>">
                  </div>
                </div>
                <button type="submit" class="btn btn-info w-100">
                  <i class="bi bi-file-earmark-pdf me-2"></i>Generar Reporte PDF
                </button>
              </form>
            </div>
          </div>
        </div>

      </div>

      <!-- Información adicional -->
      <div class="row mt-5">
        <div class="col-12">
          <div class="card shadow-sm border-0">
            <div class="card-header bg-light">
              <h5 class="mb-0 text-secondary fw-semibold">
                <i class="bi bi-info-circle me-2"></i>Información sobre Reportes
              </h5>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-3 text-center">
                  <div class="mb-3">
                    <i class="bi bi-file-earmark-pdf text-danger" style="font-size: 2rem;"></i>
                  </div>
                  <h6>Formato PDF</h6>
                  <p class="text-muted small">Todos los reportes se generan en formato PDF para fácil distribución.</p>
                </div>
                <div class="col-md-3 text-center">
                  <div class="mb-3">
                    <i class="bi bi-download text-primary" style="font-size: 2rem;"></i>
                  </div>
                  <h6>Descarga Directa</h6>
                  <p class="text-muted small">Los reportes se descargan automáticamente al generarse.</p>
                </div>
                <div class="col-md-3 text-center">
                  <div class="mb-3">
                    <i class="bi bi-shield-check text-success" style="font-size: 2rem;"></i>
                  </div>
                  <h6>Acceso Controlado</h6>
                  <p class="text-muted small">Solo administradores y supervisores pueden generar reportes.</p>
                </div>
                <div class="col-md-3 text-center">
                  <div class="mb-3">
                    <i class="bi bi-clock-history text-warning" style="font-size: 2rem;"></i>
                  </div>
                  <h6>Datos Actualizados</h6>
                  <p class="text-muted small">La información se obtiene en tiempo real de la base de datos.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Validar que fecha fin no sea menor a fecha inicio
    const fechaInicios = document.querySelectorAll('input[name="fecha_inicio"]');
    const fechaFines = document.querySelectorAll('input[name="fecha_fin"]');

    fechaInicios.forEach((fechaInicio, index) => {
      fechaInicio.addEventListener('change', function() {
        if (fechaFines[index]) {
          fechaFines[index].min = this.value;
        }
      });
    });

    fechaFines.forEach((fechaFin, index) => {
      fechaFin.addEventListener('change', function() {
        if (fechaInicios[index]) {
          fechaInicios[index].max = this.value;
        }
      });
    });
  });
</script>

<?php require_once VIEWS_PATH . "/dashboard/layouts/footer.php" ?>