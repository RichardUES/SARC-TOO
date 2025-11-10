<?php

use App\Models\enums\RolType;

require_once "layouts/head.php";

// Validación de acceso: Solo agentes
if (
  !isset($_SESSION["autorizado"]) ||
  $_SESSION["autorizado"]->rolID !== RolType::AGENT->value
) {
  header("Location: http://luzelfaro.com/errors/unauthorized");
  exit;
}

require_once "layouts/header.php";

?>

<article class="container main">
  <div class="row">
    <div class="col-12">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-secondary mt-4">
          <i class="bi bi-ticket-detailed-fill"></i> Mis Tickets Asignados
        </h2>
        <div class="badge bg-info fs-6">
          Total: <?= count($tickets) ?> ticket(s)
        </div>
      </div>

      <!-- Mensajes de estado -->
      <?php if (isset($_SESSION["success"])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="bi bi-check-circle-fill me-2"></i>
          <?= $_SESSION["success"] ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION["success"]); ?>
      <?php endif; ?>

      <?php if (isset($_SESSION["Error"])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="bi bi-exclamation-triangle-fill me-2"></i>
          <?= $_SESSION["Error"] ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION["Error"]); ?>
      <?php endif; ?>

      <!-- Lista de tickets -->
      <?php if (!empty($tickets)): ?>
        <div class="row">
          <?php foreach ($tickets as $ticket): ?>
            <div class="col-lg-6 mb-4">
              <div class="card shadow-sm border-start border-4 border-primary">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                  <h6 class="card-title mb-0">
                    <span class="badge bg-info me-2">TKT<?= str_pad($ticket['TKT_CODIGO'], 5, '0', STR_PAD_LEFT) ?></span>
                    <span class="badge bg-<?= $ticket['TKT_PRIORIDAD'] === 'ALTA' ? 'danger' : ($ticket['TKT_PRIORIDAD'] === 'MEDIA' ? 'warning' : 'success') ?>">
                      <?= $ticket['TKT_PRIORIDAD'] ?>
                    </span>
                  </h6>
                  <small class="text-muted">
                    <i class="bi bi-calendar"></i>
                    <?= date('d/m/Y H:i', strtotime($ticket['TKT_FECHA_CREACION'])) ?>
                  </small>
                </div>

                <div class="card-body">
                  <!-- Asunto -->
                  <h6 class="text-secundary mb-3">
                    <i class="bi bi-chat-square-text me-2"></i>
                    <?= htmlspecialchars($ticket['TKT_ASUNTO']) ?>
                  </h6>

                  <!-- Información del cliente -->
                  <div class="row mb-3">
                    <div class="col-sm-6">
                      <small class="text-muted d-block">Cliente:</small>
                      <strong>
                        <?= htmlspecialchars(trim($ticket['CLI_PRIMER_NOM'] . ' ' . $ticket['CLI_SEGUNDO_NOM'] . ' ' . $ticket['CLI_PRIMER_APE'] . ' ' . $ticket['CLI_SEGUNDO_APE'])) ?>
                      </strong>
                    </div>
                    <div class="col-sm-6">
                      <small class="text-muted d-block">DUI:</small>
                      <strong><?= htmlspecialchars($ticket['CLI_DUI']) ?></strong>
                    </div>
                  </div>

                  <!-- Contacto del cliente -->
                  <div class="row mb-3">
                    <div class="col-sm-6">
                      <small class="text-muted d-block">Email:</small>
                      <a href="mailto:<?= htmlspecialchars($ticket['CLIENTE_EMAIL']) ?>" class="text-decoration-none text-info">
                        <i class="bi bi-envelope me-1"></i>
                        <?= htmlspecialchars($ticket['CLIENTE_EMAIL']) ?>
                      </a>
                    </div>
                    <div class="col-sm-6">
                      <small class="text-muted d-block">Teléfono:</small>
                      <a href="tel:<?= htmlspecialchars($ticket['CLI_TELEFONO']) ?>" class="text-decoration-none text-info">
                        <i class="bi bi-telephone me-1"></i>
                        <?= htmlspecialchars($ticket['CLI_TELEFONO']) ?>
                      </a>
                    </div>
                  </div>

                  <!-- Descripción -->
                  <div class="mb-3">
                    <small class="text-muted d-block">Descripción:</small>
                    <p class="text-wrap" style="font-size: 0.95rem;">
                      <?= htmlspecialchars($ticket['TKT_DESCRIPCION']) ?>
                    </p>
                  </div>

                  <!-- Información adicional -->
                  <div class="row mb-3">
                    <div class="col-sm-6">
                      <small class="text-muted d-block">Agencia:</small>
                      <span class="badge bg-secondary"><?= htmlspecialchars($ticket['AGENCIA_NOMBRE']) ?></span>
                    </div>
                    <div class="col-sm-6">
                      <small class="text-muted d-block">Estado:</small>
                      <span class="badge bg-<?= $ticket['ESTADO_NOMBRE'] !== 'COMPLETADO' ? 'warning' : 'success' ?>">
                        <?= htmlspecialchars($ticket['ESTADO_NOMBRE']) ?>
                      </span>
                    </div>
                  </div>

                  <!-- Información de asignación -->
                  <div class="row mb-4">
                    <div class="col-sm-6">
                      <small class="text-muted d-block">Asignado el:</small>
                      <small><?= date('d/m/Y H:i', strtotime($ticket['ASIG_FECHA'])) ?></small>
                    </div>
                    <div class="col-sm-6">
                      <small class="text-muted d-block">Tipo asignación:</small>
                      <small class="badge bg-info text-dark"><?= htmlspecialchars($ticket['ASIG_TIPO']) ?></small>
                    </div>
                  </div>

                  <!-- Botones de acción -->
                  <div class="d-flex gap-2">
                    <!-- Botón Completar -->
                    <button type="button" class="btn btn-success btn-sm flex-fill" 
                            onclick="completarTicket(<?= $ticket['TKT_CODIGO'] ?>)">
                      <i class="bi bi-check-circle me-1"></i>
                      Completar
                    </button>

                    <!-- Botón Escalar -->
                    <button type="button" class="btn btn-warning btn-sm flex-fill" 
                            data-bs-toggle="modal" 
                            data-bs-target="#modalEscalar" 
                            onclick="prepararEscalamiento(<?= $ticket['TKT_CODIGO'] ?>, '<?= htmlspecialchars($ticket['TKT_ASUNTO']) ?>')">
                      <i class="bi bi-arrow-up-circle me-1"></i>
                      Escalar
                    </button>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

      <?php else: ?>
        <!-- Sin tickets asignados -->
        <div class="text-center py-5">
          <div class="mb-4">
            <i class="bi bi-inbox display-1 text-muted"></i>
          </div>
          <h4 class="text-muted">No tienes tickets asignados</h4>
          <p class="text-muted">Actualmente no hay tickets pendientes de atención.</p>
          <div class="badge bg-success fs-6 mt-2">
            <i class="bi bi-check-circle me-1"></i>
            Estado: Disponible
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</article>

<!-- Modal para Escalamiento -->
<div class="modal fade" id="modalEscalar" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title text-white">
          <i class="bi bi-arrow-up-circle me-2"></i>
          Escalar Ticket
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <form action="/tickets/escalar" method="POST" id="formEscalar">
        <div class="modal-body">
          <input type="hidden" id="ticket_id_escalar" name="ticket_id" value="">
          
          <div class="mb-3">
            <label class="form-label">Ticket a escalar:</label>
            <div class="alert alert-light">
              <strong id="ticket_asunto_escalar"></strong>
            </div>
          </div>

          <div class="mb-3">
            <label for="area_id" class="form-label">Área de escalamiento: <span class="text-danger">*</span></label>
            <select class="form-select" id="area_id" name="area_id" required>
              <option value="">Seleccione un área...</option>
              <?php if (!empty($areas)): ?>
                <?php foreach ($areas as $area): ?>
                  <option value="<?= $area->codigo ?>">
                    <?= htmlspecialchars($area->nombre) ?>
                  </option>
                <?php endforeach; ?>
              <?php endif; ?>
            </select>
          </div>

          <div class="mb-3">
            <label for="motivo" class="form-label">Motivo del escalamiento: <span class="text-danger">*</span></label>
            <textarea class="form-control" id="motivo" name="motivo" rows="4" 
                      placeholder="Describa el motivo por el cual está escalando este ticket..." required></textarea>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-warning">
            <i class="bi bi-arrow-up-circle me-1"></i>
            Escalar Ticket
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Scripts -->
<script>
function completarTicket(ticketId) {
  if (confirm('¿Está seguro que desea marcar este ticket como completado?')) {
    // Crear formulario dinámico para enviar por POST
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/tickets/completar';
    
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'ticket_id';
    input.value = ticketId;
    
    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
  }
}

function prepararEscalamiento(ticketId, asunto) {
  document.getElementById('ticket_id_escalar').value = ticketId;
  document.getElementById('ticket_asunto_escalar').textContent = asunto;
  
  // Limpiar formulario
  document.getElementById('area_id').value = '';
  document.getElementById('motivo').value = '';
}

// Validación del formulario de escalamiento
document.getElementById('formEscalar').addEventListener('submit', function(e) {
  const areaId = document.getElementById('area_id').value;
  const motivo = document.getElementById('motivo').value.trim();
  
  if (!areaId || !motivo) {
    e.preventDefault();
    alert('Por favor complete todos los campos obligatorios.');
    return false;
  }
  
  if (motivo.length < 10) {
    e.preventDefault();
    alert('El motivo debe tener al menos 10 caracteres.');
    return false;
  }
});
</script>

<?php require_once "layouts/sidebar.php"; ?>

<?php require_once "layouts/footer.php" ?>