<?php

require_once "layouts/header.php";

?>


<!-- Menu lateral (sidebar) -->
<?php require_once "layouts/sidebar.php" ?>

<!-- Contenido Principal -->
<section class="col-md-9">
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
              <button type="button" class="btn btn-outline-primary text-secondary active"
                data-filter="all">Todos</button>
              <button type="button" class="btn btn-outline-primary text-secondary" data-filter="open">Abiertos</button>
              <button type="button" class="btn btn-outline-primary text-secondary"
                data-filter="urgent">Urgentes</button>
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
            <a href="/profile/personal_info" class="btn btn-outline-primary text-secondary">
              <i class="bi bi-person-check me-2"></i>Completar Perfil
            </a>
            <a href="/profile/notifications" class="btn btn-outline-primary text-secondary">
              <i class="bi bi-bell me-2"></i>Ver Notificaciones
              <span class="badge bg-danger ms-1" id="notifQuickCount">0</span>
            </a>
          </div>
        </div>
      </div>

    </div>
  </div>

</section>

</div>
</div>


<?php require_once "layouts/footer.php" ?>