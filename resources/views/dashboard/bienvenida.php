<?php

use App\Models\enums\RolType;

require_once "layouts/head.php";
require_once "layouts/header.php";

// Validación de acceso: Todos los roles del dashboard excepto clientes
if (
  !isset($_SESSION["autorizado"]) ||
  !in_array($_SESSION["autorizado"]->rolID, [
    RolType::ADMIN->value,
    RolType::SUPERVISOR->value,
    RolType::AGENT->value
  ])
) {
  header("Location: http://luzelfaro.com/errors/unauthorized");
  exit;
}

require_once "layouts/sidebar.php";

// Obtener información del usuario actual
$usuario = $_SESSION["autorizado"];
$rolName = match($usuario->rolID) {
  RolType::ADMIN->value => 'Administrador',
  RolType::SUPERVISOR->value => 'Supervisor',
  RolType::AGENT->value => 'Agente de Soporte',
  default => 'Usuario'
};

// Obtener fecha y hora actual
$fechaActual = new DateTime();
$horaActual = $fechaActual->format('H:i');
$fechaFormateada = $fechaActual->format('d \d\e F \d\e Y');

// Mensaje de saludo basado en la hora
$saludo = match(true) {
  $fechaActual->format('H') < 12 => 'Buenos días',
  $fechaActual->format('H') < 18 => 'Buenas tardes',
  default => 'Buenas noches'
};

?>

<!-- Dashboard de Bienvenida -->
<div class="container-fluid py-4 main">
  <div class="row">
    <div class="col-12">

      <!-- Header de Bienvenida -->
      <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-info text-white py-4">
          <div class="row align-items-center">
            <div class="col-md-8">
              <h2 class="mb-1 fw-bold">
                <i class="bi bi-house-heart me-2"></i><?= $saludo ?>, <?= htmlspecialchars($usuario->username) ?>
              </h2>
              <p class="mb-0 opacity-75 fs-5">
                Bienvenido al Sistema SARC - <?= $rolName ?>
              </p>
            </div>
            <div class="col-md-4 text-md-end">
              <div class="d-flex flex-column align-items-md-end">
                <div class="badge bg-light text-info px-3 py-2 fs-6 mb-2">
                  <i class="bi bi-clock me-2"></i><?= $horaActual ?>
                </div>
                <small class="text-light opacity-75"><?= $fechaFormateada ?></small>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Cards de Información según Rol -->
      <div class="row g-4 mb-4">

        <?php if ($usuario->rolID === RolType::ADMIN->value): ?>
        <!-- Cards específicas para Administrador -->
        <div class="col-lg-3 col-md-6">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
              <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                <i class="bi bi-people-fill fs-3 text-info"></i>
              </div>
              <h5 class="card-title">Gestión de Usuarios</h5>
              <p class="card-text text-muted small">Administrar usuarios del sistema</p>
              <a href="/dashboard/gestion_usuarios" class="btn btn-outline-info btn-sm">
                <i class="bi bi-arrow-right me-1"></i>Acceder
              </a>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-md-6">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
              <div class="rounded-circle bg-secondary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                <i class="bi bi-building fs-3 text-secondary"></i>
              </div>
              <h5 class="card-title">Gestión de Agencias</h5>
              <p class="card-text text-muted small">Configurar agencias y sucursales</p>
              <a href="/dashboard/gestion_agencias" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-right me-1"></i>Acceder
              </a>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-md-6">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
              <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                <i class="bi bi-diagram-3 fs-3 text-success"></i>
              </div>
              <h5 class="card-title">Gestión de Áreas</h5>
              <p class="card-text text-muted small">Administrar áreas de trabajo</p>
              <a href="/dashboard/gestion_areas" class="btn btn-outline-success btn-sm">
                <i class="bi bi-arrow-right me-1"></i>Acceder
              </a>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-md-6">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
              <div class="rounded-circle bg-info bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                <i class="bi bi-graph-up-arrow fs-3 text-info"></i>
              </div>
              <h5 class="card-title">Reportes Avanzados</h5>
              <p class="card-text text-muted small">Generar reportes del sistema</p>
              <a href="/dashboard/reporteria" class="btn btn-outline-info btn-sm">
                <i class="bi bi-arrow-right me-1"></i>Acceder
              </a>
            </div>
          </div>
        </div>
        <?php endif; ?>

        <?php if (in_array($usuario->rolID, [RolType::ADMIN->value, RolType::SUPERVISOR->value])): ?>
        <!-- Cards para Admin y Supervisor -->
        <div class="col-lg-3 col-md-6">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
              <div class="rounded-circle bg-warning bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                <i class="bi bi-stack fs-3 text-warning"></i>
              </div>
              <h5 class="card-title">Bitácora de Tickets</h5>
              <p class="card-text text-muted small">Revisar cola y historial de tickets</p>
              <a href="/dashboard/cola_tickets" class="btn btn-outline-warning btn-sm">
                <i class="bi bi-arrow-right me-1"></i>Acceder
              </a>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-md-6">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
              <div class="rounded-circle bg-danger bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                <i class="bi bi-file-earmark-bar-graph fs-3 text-danger"></i>
              </div>
              <h5 class="card-title">Generar Reportes</h5>
              <p class="card-text text-muted small">Crear reportes y estadísticas</p>
              <a href="/dashboard/reporteria" class="btn btn-outline-danger btn-sm">
                <i class="bi bi-arrow-right me-1"></i>Acceder
              </a>
            </div>
          </div>
        </div>
        <?php endif; ?>

        <!-- Cards comunes para todos los roles -->
        <div class="col-lg-3 col-md-6">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
              <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                <i class="bi bi-list-ul fs-3 text-info"></i>
              </div>
              <h5 class="card-title">Consultar Tickets</h5>
              <p class="card-text text-muted small">Ver y gestionar todos los tickets</p>
              <a href="/dashboard/tickets" class="btn btn-outline-info btn-sm">
                <i class="bi bi-arrow-right me-1"></i>Acceder
              </a>
            </div>
          </div>
        </div>

        <?php if ($usuario->rolID === RolType::AGENT->value): ?>
        <!-- Card específica para Agentes -->
        <div class="col-lg-3 col-md-6">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
              <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                <i class="bi bi-ticket fs-3 text-success"></i>
              </div>
              <h5 class="card-title">Mis Tickets</h5>
              <p class="card-text text-muted small">Tickets asignados a mí</p>
              <a href="/dashboard/mis_tickets/<?= $usuario->id ?>" class="btn btn-outline-success btn-sm">
                <i class="bi bi-arrow-right me-1"></i>Acceder
              </a>
            </div>
          </div>
        </div>
        <?php endif; ?>

        <!-- Card común: Registro de Clientes -->
        <?php if (in_array($usuario->rolID, [RolType::ADMIN->value, RolType::SUPERVISOR->value, RolType::AGENT->value])): ?>
        <div class="col-lg-3 col-md-6">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body text-center">
              <div class="rounded-circle bg-secondary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                <i class="bi bi-person-plus fs-3 text-secondary"></i>
              </div>
              <h5 class="card-title">Registro de Clientes</h5>
              <p class="card-text text-muted small">Registrar nuevos clientes</p>
              <a href="/dashboard/registro_usuarios" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-right me-1"></i>Acceder
              </a>
            </div>
          </div>
        </div>
        <?php endif; ?>

      </div>

      <!-- Información del Sistema -->
      <div class="row">
        <div class="col-12">
          <div class="card shadow-sm border-0">
            <div class="card-header bg-light">
              <h5 class="mb-0 text-secondary fw-semibold">
                <i class="bi bi-info-circle me-2"></i>Información del Sistema
              </h5>
            </div>
            <div class="card-body">
              <div class="row g-4">

                <!-- Estado del Sistema -->
                <div class="col-md-4">
                  <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-success d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                      <i class="bi bi-check-circle-fill text-white"></i>
                    </div>
                    <div>
                      <h6 class="mb-0">Estado del Sistema</h6>
                      <small class="text-muted">Operativo</small>
                    </div>
                  </div>
                </div>

                <!-- Última Actualización -->
                <div class="col-md-4">
                  <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-info d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                      <i class="bi bi-arrow-clockwise text-white"></i>
                    </div>
                    <div>
                      <h6 class="mb-0">Última Actualización</h6>
                      <small class="text-muted">Noviembre 2025</small>
                    </div>
                  </div>
                </div>

                <!-- Soporte -->
                <div class="col-md-4">
                  <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                      <i class="bi bi-headset text-white"></i>
                    </div>
                    <div>
                      <h6 class="mb-0">Soporte Técnico</h6>
                      <small class="text-muted">Disponible 24/7</small>
                    </div>
                  </div>
                </div>

              </div>

              <!-- Mensaje de rol específico -->
              <div class="mt-4 p-3 bg-light rounded">
                <?php if ($usuario->rolID === RolType::ADMIN->value): ?>
                <p class="mb-0 text-secondary">
                  <i class="bi bi-shield-check me-2 text-primary"></i>
                  Como <strong>Administrador</strong>, tienes acceso completo al sistema. Puedes gestionar usuarios, configuraciones y generar reportes avanzados.
                </p>
                <?php elseif ($usuario->rolID === RolType::SUPERVISOR->value): ?>
                <p class="mb-0 text-secondary">
                  <i class="bi bi-eye me-2 text-warning"></i>
                  Como <strong>Supervisor</strong>, puedes monitorear tickets, generar reportes y supervisar el trabajo del equipo de soporte.
                </p>
                <?php elseif ($usuario->rolID === RolType::AGENT->value): ?>
                <p class="mb-0 text-secondary">
                  <i class="bi bi-headset me-2 text-success"></i>
                  Como <strong>Agente de Soporte</strong>, tu prioridad es atender y resolver los tickets asignados. ¡Cada problema resuelto hace la diferencia!
                </p>
                <?php endif; ?>
              </div>

            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<?php require_once "layouts/footer.php" ?>