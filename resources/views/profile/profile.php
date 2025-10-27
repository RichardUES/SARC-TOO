<?php
/* ==================================================
   resources/views/profile/profile.php - Vista de Perfil
   ================================================== */

// Iniciar captura de contenido
ob_start();

use App\Models\enums\RolType;

if (
  !isset($_SESSION["autorizado"])
  && $_SESSION["autorizado"]->rolID !== RolType::CLIENT->value
) {
  header("Location: errors/unauthorized");
}
?>

<!-- Banner de bienvenida -->
<section class="bg-primary text-white py-5 mb-4">

  <article class="container">
    <div class="row align-items-center">
      <div class="col-md-8">
        <h1 class="display-4 text-secondary">Bienvenido/a, <?php echo htmlspecialchars($_SESSION["autorizado"]->username ?? 'Usuario'); ?></h1>
        <p class="lead mb-0 text-secondary">Panel de Control de Usuario</p>
      </div>
    </div>
  </article>

</section>

<div class="container">
  <div class="row">
    <!-- Menú Lateral -->
    <div class="col-md-3 mb-4">
      <div class="card shadow-sm">
        <div class="card-body p-0">
          <div class="list-group list-group-flush ">
            <a href="/profile" class="text-secondary list-group-item list-group-item-action <?php echo !isset($_GET['section']) ? 'active' : ''; ?>">
              <i class="bi bi-speedometer2 me-2"></i>Panel Principal
            </a>
            <a href="/profile/personal_info" class="list-group-item list-group-item-action <?php echo isset($_GET['section']) && $_GET['section'] === 'personal_info' ? 'active' : ''; ?>">
              <i class="bi bi-person me-2"></i>Información Personal
            </a>
            <a href="/profile/create_ticket" class="list-group-item list-group-item-action <?php echo isset($_GET['section']) && $_GET['section'] === 'create_ticket' ? 'active' : ''; ?>">
              <i class="bi bi-ticket-detailed me-2"></i>Crear Ticket
            </a>
            <a href="/profile/ticket_history" class="list-group-item list-group-item-action <?php echo isset($_GET['section']) && $_GET['section'] === 'ticket_history' ? 'active' : ''; ?>">
              <i class="bi bi-clock-history me-2"></i>Historial de Tickets
            </a>
            <a href="/profile/notifications" class="list-group-item list-group-item-action <?php echo isset($_GET['section']) && $_GET['section'] === 'notifications' ? 'active' : ''; ?>">
              <i class="bi bi-bell me-2"></i>Notificaciones
              <span class="badge bg-danger rounded-pill float-end" id="notificationsBadge">0</span>
            </a>
            <a href="/profile/settings" class="list-group-item list-group-item-action <?php echo isset($_GET['section']) && $_GET['section'] === 'settings' ? 'active' : ''; ?>">
              <i class="bi bi-gear me-2"></i>Configuración
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Contenido Principal -->
    <section class="col-md-9">
      <?php if (!isset($_GET['section'])): ?>
        <!-- Resumen de Estadísticas -->
        <section class="row g-4 mb-4">

          <div class="col-sm-6 col-xl-3">

            <article class="card shadow-sm">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div class="shrink-0">
                    <div class="bg-warning bg-opacity-10 text-warning p-3 rounded">
                      <i class="bi bi-hourglass-split fs-4"></i>
                    </div>
                  </div>
                  <div class="grow ms-3">
                    <p class="text-muted mb-1">En Proceso</p>
                    <h3 class="mb-0" id="inProgressCount">-</h3>
                  </div>
                </div>
              </div>
            </article>

          </div>
          <div class="col-sm-6 col-xl-3">

            <article class="card shadow-sm">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div class="shrink-0">
                    <div class="bg-success bg-opacity-10 text-success p-3 rounded">
                      <i class="bi bi-check-circle-fill fs-4"></i>
                    </div>
                  </div>
                  <div class="grow ms-3">
                    <p class="text-muted mb-1">Resueltos</p>
                    <h3 class="mb-0" id="resolvedCount">-</h3>
                  </div>
                </div>
              </div>
            </article>

          </div>

        </section>

        <!-- Contenido Principal -->
        <div class="row g-4">
          <!-- Tickets Recientes -->
          <div class="col-lg-8">
            <div class="card shadow-sm">
              <div class="card-header bg-transparent">
                <div class="d-flex justify-content-between align-items-center">
                  <h5 class="card-title mb-0">Tickets Recientes</h5>
                  <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-outline-primary text-secondary active" data-filter="all">Todos</button>
                    <button type="button" class="btn btn-outline-primary text-secondary" data-filter="open">Abiertos</button>
                    <button type="button" class="btn btn-outline-primary text-secondary" data-filter="urgent">Urgentes</button>
                  </div>
                </div>
              </div>
              <div class="card-body p-0">
                <div class="table-responsive">
                  <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                      <tr>
                        <th class="ps-3" style="width: 60px">#</th>
                        <th>Asunto</th>
                        <th style="width: 120px">Estado</th>
                        <th style="width: 160px">Actualización</th>
                        <th class="pe-3" style="width: 100px">Acciones</th>
                      </tr>
                    </thead>
                    <tbody id="recentTicketsTable">
                      <tr>
                        <td colspan="5" class="text-center py-5">
                          <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                          </div>
                          <p class="text-muted mt-2 mb-0">Cargando tickets recientes...</p>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <!-- Panel Lateral -->
          <div class="col-lg-4">
            <!-- Acciones Rápidas -->
            <div class="card shadow-sm mb-4">
              <div class="card-header bg-transparent">
                <h5 class="card-title mb-0">Acciones Rápidas</h5>
              </div>
              <div class="card-body">
                <div class="d-grid gap-2">
                  <button class="btn btn-primary text-secondary" data-bs-toggle="modal" data-bs-target="#quickTicketModal">
                    <i class="bi bi-plus-circle-fill me-2"></i>Crear Ticket Rápido
                  </button>
                  <a href="/profile/personal-info" class="btn btn-outline-primary text-secondary">
                    <i class="bi bi-person-check me-2"></i>Completar Perfil
                  </a>
                  <a href="/profile/notifications" class="btn btn-outline-primary text-secondary">
                    <i class="bi bi-bell me-2"></i>Ver Notificaciones
                    <span class="badge bg-danger ms-1" id="notifQuickCount">0</span>
                  </a>
                </div>
              </div>
            </div>

            <!-- Últimas Notificaciones -->
            <div class="card shadow-sm">
              <div class="card-header bg-transparent">
                <h5 class="card-title mb-0">Últimas Notificaciones</h5>
              </div>
              <div class="list-group list-group-flush" id="recentNotifications">
                <div class="list-group-item text-center py-4">
                  <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                  </div>
                  <p class="text-muted mt-2 mb-0">Cargando notificaciones...</p>
                </div>
              </div>
              <div class="card-footer bg-transparent text-center">
                <a href="/profile/notifications" class="btn btn-link text-decoration-none text-secondary">Ver todas las notificaciones</a>
              </div>
            </div>
          </div>
        </div>
      <?php else: ?>
        <?php
        // Convertir guiones a guiones bajos para los nombres de archivo
        $section = str_replace('-', '_', $_GET['section']);

        // Verificar que el archivo existe
        $viewPath = __DIR__ . '/' . $section . '.php';
        if (file_exists($viewPath)) {
          // La vista incluirá su propio contenido dentro del layout base
          require_once $viewPath;
          exit; // Importante: evitar que se procese el layout dos veces
        } else {
          require_once RESOURCES_PATH . '/views/errors/404.php';
          exit;
        }
        ?>
      <?php endif; ?>
    </section>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Cargar contadores del banner
    function loadDashboardCounters() {
      fetch('/api/profile/counters')
        .then(response => response.json())
        .then(data => {
          document.getElementById('activeTicketsCount').textContent = data.activeTickets;
          document.getElementById('unreadNotificationsCount').textContent = data.unreadNotifications;

          const badge = document.getElementById('notificationsBadge');
          badge.textContent = data.unreadNotifications;
          badge.style.display = data.unreadNotifications > 0 ? 'inline' : 'none';
        })
        .catch(console.error);
    }

    // Cargar tickets recientes
    function loadRecentTickets() {
      fetch('/api/tickets/recent')
        .then(response => response.json())
        .then(tickets => {
          const tbody = document.getElementById('recentTicketsTable');
          if (!tickets.length) {
            tbody.innerHTML = `
                        <tr>
                            <td colspan="4" class="text-center py-4">
                                <i class="bi bi-inbox text-muted fs-2"></i>
                                <p class="text-muted mt-2 mb-0">No hay tickets recientes</p>
                            </td>
                        </tr>
                    `;
            return;
          }

          tbody.innerHTML = tickets.map(ticket => `
                    <tr>
                        <td>#${ticket.id}</td>
                        <td>
                            <a href="/profile/ticket-history?id=${ticket.id}" class="text-decoration-none">
                                ${ticket.asunto}
                            </a>
                        </td>
                        <td>
                            <span class="badge bg-${getStatusColor(ticket.estado)}">
                                ${ticket.estado}
                            </span>
                        </td>
                        <td>${formatDate(ticket.ultima_actualizacion)}</td>
                    </tr>
                `).join('');
        })
        .catch(console.error);
    }

    function getStatusColor(status) {
      const colors = {
        'ABIERTO': 'info',
        'EN_PROGRESO': 'warning',
        'RESUELTO': 'success',
        'CERRADO': 'secondary'
      };
      return colors[status] || 'primary';
    }

    function formatDate(dateString) {
      const date = new Date(dateString);
      return new Intl.DateTimeFormat('es-SV', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      }).format(date);
    }

    // Inicializar
    loadDashboardCounters();
    if (document.getElementById('recentTicketsTable')) {
      loadRecentTickets();
    }

    // Actualizar contadores cada minuto
    setInterval(loadDashboardCounters, 60000);
  });
</script>


<?php
// Capturar el contenido y pasarlo al layout
$content = ob_get_clean();
require_once RESOURCES_PATH . '/views/layouts/base.php';
?>