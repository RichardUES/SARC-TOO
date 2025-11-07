<?php

use App\Models\enums\TicketStatus;

if (!isset($_SESSION["autorizado"])) header("Location: /errors/401");

if (!isset($_SESSION["cliente"]))
  $_SESSION["sin_perfil"] = "No tienes un perfil creado. Por favor, crea tu perfil para poder tener historial.";

require_once "layouts/header.php";

?>

<!-- Menu lateral (sidebar) -->
<?php require_once "layouts/sidebar.php" ?>

<?php if (isset($_SESSION["sin_perfil"])): ?>
  <div class="alert alert-warning col-md-9 d-flex justify-content-center align-items-center flex-wrap ">
    <strong class="h4 w-100 text-center">
      <?= $_SESSION["sin_perfil"]; ?>
    </strong>
    <a href="/profile/personal_info" class="btn btn-outline-info">Crear perfil aquí</a>
  </div>
  <?php deleteSession('sin_perfil'); ?>
<?php else: ?>

  <div class="card shadow-sm col-md-9">
    <div class="card-body">
      <h3 class="card-title h5 mb-4">
        Historial de Tickets
        <!-- <span class="float-end">
                <select class="form-select form-select-sm d-inline-block w-auto" id="filterStatus">
                    <option value="all">Todos los estados</option>
                    <option value="OPEN">Abiertos</option>
                    <option value="IN_PROGRESS">En Progreso</option>
                    <option value="RESOLVED">Resueltos</option>
                    <option value="CLOSED">Cerrados</option>
                </select>
            </span> -->
      </h3>

      <!-- Lista de Tickets -->
      <div class="table-responsive">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>#TKT</th>
              <th>Asunto</th>
              <th>Estado</th>
              <th>Fecha inicio</th>
              <th>Fecha de cierre</th>
              <th>Agencia</th>
              <th>Tiempo de espera</th>
            </tr>
          </thead>
          <tbody id="ticketsTableBody">
            <?php foreach ($tickets as $ticket): ?>
              <tr>
                <td>TKT000<?= htmlspecialchars($ticket["CODIGO_TICKET"]) ?></td>
                <td><?= htmlspecialchars($ticket["ASUNTO"]) ?></td>
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
                <td><?= htmlspecialchars(date("d/m/Y", strtotime($ticket["FECHA_CREACION"]))) ?></td>
                <td>
                  <?php
                  if ($ticket["FECHA_CIERRE"] !== null) {
                    echo htmlspecialchars(date("d/m/Y", strtotime($ticket["FECHA_CIERRE"])));
                  } else {
                    echo "<em>No cerrado</em>";
                  }
                  ?>
                </td>
                <td><?= htmlspecialchars($ticket["AGENCIA"]) ?></td>
                <td>
                  <?= htmlspecialchars($ticket["DIAS_DESDE_CREACION"]) ?> días
                  <?= htmlspecialchars($ticket["HORAS_DESDE_CREACION"]) ?> horas
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <!-- Paginación -->
      <nav aria-label="Navegación de páginas" class="d-flex justify-content-between align-items-center">
        <p class="text-muted mb-0" id="totalRecords"></p>
        <ul class="pagination pagination-sm mb-0" id="pagination">
          <!-- Se llenará dinámicamente -->
        </ul>
      </nav>
    </div>
  </div>

  <!-- Modal para Ver Detalles -->
  <div class="modal fade" id="ticketDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Detalles del Ticket</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div id="ticketDetails">
            <!-- Se llenará dinámicamente -->
          </div>

          <hr>

          <h6>Comentarios</h6>
          <div id="commentsList" class="mb-3">
            <!-- Se llenará dinámicamente -->
          </div>

          <form id="commentForm" class="mt-3">
            <div class="form-floating mb-3">
              <textarea class="form-control" id="newComment" style="height: 100px" required></textarea>
              <label for="newComment">Agregar un comentario</label>
            </div>
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-chat-dots me-2"></i>Comentar
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

<?php endif; ?>

<?php require_once "layouts/footer.php" ?>