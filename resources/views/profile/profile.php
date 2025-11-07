<?php

use App\Models\enums\TicketStatus;

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
              <h3 class="mb-0" id="inProgressCount"> <?= count($tickets) ?> </h3>
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
              <h3 class="mb-0" id="resolvedCount"> <?= count(array_filter($tickets, fn($ticket) => $ticket["CODIGO_ESTADO"] === TicketStatus::COMPLETED->value)) ?> </h3>
            </div>
          </div>
        </div>
      </article>

    </div>

  </section>

  <!-- Contenido Principal -->
  <div class="row g-4">
    <!-- Tickets Recientes -->
    <div class="col-lg-9">
      <div class="card shadow-sm">
        <div class="card-header bg-transparent">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Tickets Recientes</h5>
            <!-- <div class="btn-group btn-group-sm">
              <button type="button" class="btn btn-outline-primary text-secondary active"
                data-filter="all">Todos</button>
              <button type="button" class="btn btn-outline-primary text-secondary" data-filter="open">Abiertos</button>
              <button type="button" class="btn btn-outline-primary text-secondary"
                data-filter="urgent">Urgentes</button>
            </div> -->
          </div>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th class="ps-3" style="width: 60px">#TKT</th>
                  <th>Asunto</th>
                  <th style="width: 120px">Estado</th>
                  <th style="width: 160px">Fecha Inicio</th>
                  <th class="pe-3" style="width: 100px">Acciones</th>
                </tr>
              </thead>
              <tbody id="recentTicketsTable">
                <?php foreach (array_slice($tickets, 0, 5) as $ticket): ?>
                <tr data-status="<?= strtolower(str_replace(' ', '_', $ticket["ESTADO_ACTUAL"])) ?>"
                  data-priority="<?= strtolower($ticket["PRIORIDAD"]) ?>">
                  <td class="ps-3">
                    TKT000<?= htmlspecialchars($ticket["CODIGO_TICKET"]) ?>
                  </td>
                  <td>
                    <?= htmlspecialchars($ticket["ASUNTO"]) ?>
                  </td>
                  <td>
                    <?php
                      $statusClass = match ($ticket['CODIGO_ESTADO']) {
                        TicketStatus::RECEIVED->value   => 'secondary',
                        TicketStatus::ASSIGNED->value   => 'primary text-secondary',
                        TicketStatus::IN_PROCESS->value => 'info',
                        TicketStatus::PENDING->value    => 'warning',
                        TicketStatus::SCALING->value    => 'danger',
                        TicketStatus::COMPLETED->value  => 'success',
                        default                          => 'secondary',
                      };
                    ?>
                    <span class="badge bg-<?= $statusClass ?>">
                      <?= htmlspecialchars($ticket["ESTADO_ACTUAL"]) ?>
                    </span>
                  </td>
                  <td>
                    <?= (new DateTime($ticket["FECHA_CREACION"]))->format("d/m/Y") ?>
                  </td>
                  <td class="pe-3">
                    <a href="/profile/ticket_history" class="btn btn-sm btn-outline-secondary">
                      <i class="bi bi-eye"></i> Ver
                    </a>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Panel Lateral -->
    <div class="col-lg-3">
      <!-- Acciones Rápidas -->
      <div class="card shadow-sm mb-4">
        <div class="card-header bg-transparent">
          <h5 class="card-title mb-0">Acciones Rápidas</h5>
        </div>
        <div class="card-body">
          <div class="d-grid gap-2">
            <a href="/profile/create_ticket" class="btn btn-primary text-secondary">
              <i class="bi bi-plus-circle-fill me-2"></i>Crear Ticket
            </a>
            <a href="/profile/personal_info" class="btn btn-outline-primary text-secondary">
              <i class="bi bi-person-check me-2"></i>Completar Perfil
            </a>
            <a href="/profile/notifications" class="btn btn-outline-primary text-secondary">
              <i class="bi bi-bell me-2"></i>Notificaciones
              <span class="badge bg-danger ms-1" id="notifQuickCount">1</span>
            </a>
          </div>
        </div>
      </div>

    </div>
  </div>

</section>


<?php require_once "layouts/footer.php" ?>