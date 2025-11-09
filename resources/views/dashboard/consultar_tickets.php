<?php

use App\Models\enums\Priority;
use App\Models\enums\RolType;
use App\Models\enums\TicketStatus;

require_once "layouts/head.php";
require_once "layouts/header.php";

// Validación de acceso: Solo agentes, supervisores y administradores
if (
  !isset($_SESSION["autorizado"]) ||
  !in_array($_SESSION["autorizado"]->rolID, [
    RolType::AGENT->value,
    RolType::SUPERVISOR->value,
    RolType::ADMIN->value
  ])
) {
  header("Location: http://luzelfaro.com/errors/unauthorized");
  exit;
}

require_once "layouts/sidebar.php";

?>


<!-- Consulta de Todos los Tickets -->
<div class="container-fluid py-4 main">
  <div class="row">
    <div class="col-12">

      <!-- Header de la página -->
      <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-secondary text-white py-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h3 class="mb-0 fw-bold">
                <i class="bi bi-list-task me-2"></i>Consultar Todos los Tickets
              </h3>
              <p class="mb-0 mt-1 opacity-75">Gestión completa del sistema de tickets</p>
            </div>
            <div class="badge bg-primary bg-opacity-25 text-primary px-3 py-2">
              <i class="bi bi-person-badge me-2"></i>
              <?= htmlspecialchars($_SESSION["autorizado"]->username) ?>
            </div>
          </div>
        </div>
      </div>

      <!-- Filtros y controles -->
      <div class="card shadow-sm border-0 mb-4">
        <div class="card-body py-3">
          <div class="row g-3 align-items-end">

            <!-- Filtro por estado -->
            <div class="col-md-3">
              <label for="filtroEstado" class="form-label fw-semibold text-secondary">
                <i class="bi bi-funnel me-1"></i>Estado
              </label>
              <select id="filtroEstado" class="form-select">
                <option value="">Todos los estados</option>
                <option value="1">Recibido</option>
                <option value="2">Asignado</option>
                <option value="3">En Proceso</option>
                <option value="4">Pendiente</option>
                <option value="5">Escalado</option>
                <option value="6">Completado</option>
              </select>
            </div>

            <!-- Filtro por prioridad -->
            <div class="col-md-3">
              <label for="filtroPrioridad" class="form-label fw-semibold text-secondary">
                <i class="bi bi-exclamation-triangle me-1"></i>Prioridad
              </label>
              <select id="filtroPrioridad" class="form-select">
                <option value="">Todas las prioridades</option>
                <option value="1">Baja</option>
                <option value="2">Media</option>
                <option value="3">Alta</option>
              </select>
            </div>

            <!-- Búsqueda -->
            <div class="col-md-4">
              <label for="buscarTicket" class="form-label fw-semibold text-secondary">
                <i class="bi bi-search me-1"></i>Buscar
              </label>
              <input type="text" id="buscarTicket" class="form-control"
                placeholder="Código, asunto, cliente...">
            </div>

            <!-- Botón limpiar filtros -->
            <div class="col-md-2">
              <button type="button" id="limpiarFiltros" class="btn btn-outline-secondary w-100">
                <i class="bi bi-arrow-clockwise me-1"></i>Limpiar
              </button>
            </div>

          </div>
        </div>
      </div>

      <!-- Tabla de tickets -->
      <div class="card shadow-sm border-0">
        <div class="card-header bg-light">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-secondary fw-semibold">
              <i class="bi bi-table me-2"></i>Lista de Tickets
            </h5>
            <span class="badge bg-info" id="contadorTickets">
              <i class="bi bi-ticket-detailed me-1"></i> <?= count($_SESSION['tickets']) ?> tickets
            </span>
          </div>
        </div>

        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="tablaTickets">
              <thead class="table-light">
                <tr>
                  <th class="ps-3" style="width: 80px">#TKT</th>
                  <th>Asunto</th>
                  <th>Cliente</th>
                  <th>Agencia</th>
                  <th style="width: 120px">Estado</th>
                  <th style="width: 100px">Prioridad</th>
                  <th style="width: 130px">Fecha Creación</th>
                  <th style="width: 120px">Agente</th>
                  <th class="pe-3 text-center" style="width: 100px">Acciones</th>
                </tr>
              </thead>
              <tbody>

              
                <!-- Los datos se cargarán dinámicamente -->
                <?php  if ( isset($_SESSION['tickets']) && !empty($_SESSION['tickets']) ): ?>
                  <?php foreach ($_SESSION['tickets'] as $ticket): ?>

                    <tr>
                      <td class="ps-3">TKT000<?= htmlspecialchars($ticket["CODIGO_TICKET"]) ?></td>
                      <td><?= htmlspecialchars($ticket["ASUNTO"]) ?></td>
                      <td><?= htmlspecialchars($ticket["NOMBRE_CLIENTE"]) ?></td>
                      <td><?= htmlspecialchars($ticket["AGENCIA"]) ?></td>
                      <td>
                        <?php
                        $estadoBadge = match ($ticket["CODIGO_ESTADO"]) {
                          TicketStatus::RECEIVED->value => '<span class="badge bg-secondary">Recibido</span>',
                          TicketStatus::ASSIGNED->value => '<span class="badge bg-primary">Asignado</span>',
                          TicketStatus::IN_PROCESS->value => '<span class="badge bg-info">En Proceso</span>',
                          TicketStatus::PENDING->value => '<span class="badge bg-warning">Pendiente</span>',
                          TicketStatus::SCALING->value => '<span class="badge bg-danger">Escalado</span>',
                          TicketStatus::COMPLETED->value => '<span class="badge bg-success">Completado</span>',
                          default => '<span class="badge bg-secondary">Desconocido</span>',
                        };
                        echo $estadoBadge;
                        ?>
                      </td>
                      <td>
                        <?php
                        $prioridadBadge = match ($ticket["PRIORIDAD"]) {
                          Priority::LOW->value => '<span class="badge bg-light text-dark">Baja</span>',
                          Priority::MEDIUM->value => '<span class="badge bg-info">Media</span>',
                          Priority::HIGH->value => '<span class="badge bg-warning">Alta</span>',
                          default => '<span class="badge bg-secondary">--</span>',
                        };
                        echo $prioridadBadge;
                        ?>
                      </td>
                      <td><?= htmlspecialchars(date("d/m/Y H:i", strtotime($ticket["FECHA_CREACION"]))) ?></td>
                      <td><?= htmlspecialchars($ticket["AGENTE_ASIGNADO"] ?? 'Sin asignar') ?></td>
                      <td class="pe-3 text-center">
                        <button class="btn btn-sm btn-outline-info" 
                          data-bs-toggle="modal" 
                          data-bs-target="#modalDetallesTicket"
                          onclick="cargarDetallesTicket(<?= htmlspecialchars(json_encode($ticket), ENT_QUOTES, 'UTF-8') ?>)">
                          <i class="bi bi-eye me-1"></i> Ver
                        </button>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="9" class="text-center py-5 text-muted">
                      <div class="spinner-border spinner-border-sm me-2" role="status">
                        <span class="visually-hidden">Cargando...</span>
                      </div>
                      Cargando tickets...
                    </td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Paginación -->
        <div class="card-footer bg-light">
          <div class="d-flex justify-content-between align-items-center">
            <small class="text-muted">
              Mostrando <span id="ticketsInicio">1</span> - <span id="ticketsFin"> <?= count($_SESSION['tickets']) ?> </span>
              de <span id="ticketsTotal"><?= count($_SESSION['tickets']) ?></span> tickets
            </small>
            <nav aria-label="Paginación de tickets">
              <ul class="pagination pagination-sm mb-0" id="paginacion">
                <!-- Se genera dinámicamente -->
              </ul>
            </nav>
          </div>
        </div>

      </div>

    </div>
  </div>
</div>

<!-- Modal de Detalles del Ticket -->
<div class="modal fade" id="modalDetallesTicket" tabindex="-1" aria-labelledby="modalDetallesTicketLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">

      <!-- Header del modal -->
      <div class="modal-header bg-secondary text-white">
        <h5 class="modal-title fw-bold" id="modalDetallesTicketLabel">
          <i class="bi bi-ticket-detailed me-2"></i>Detalles del Ticket
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Body del modal -->
      <div class="modal-body">

        <!-- Información básica -->
        <div class="row g-4">

          <!-- Columna izquierda -->
          <div class="col-md-6">

            <!-- Código y estado -->
            <div class="mb-3">
              <label class="form-label fw-semibold text-secondary">
                <i class="bi bi-hash me-1"></i>Código del Ticket
              </label>
              <div class="d-flex align-items-center gap-2">
                <span class="badge bg-light text-dark fs-6" id="modalCodigoTicket">TKT000123</span>
                <span class="badge" id="modalEstadoTicket">Estado</span>
              </div>
            </div>

            <!-- Cliente -->
            <div class="mb-3">
              <label class="form-label fw-semibold text-secondary">
                <i class="bi bi-person-fill me-1"></i>Cliente
              </label>
              <p class="mb-0" id="modalCliente">Nombre del Cliente</p>
            </div>

            <!-- Agencia -->
            <div class="mb-3">
              <label class="form-label fw-semibold text-secondary">
                <i class="bi bi-building me-1"></i>Agencia
              </label>
              <p class="mb-0" id="modalAgencia">Nombre de la Agencia</p>
            </div>

            <!-- Área -->
            <div class="mb-3">
              <label class="form-label fw-semibold text-secondary">
                <i class="bi bi-diagram-3 me-1"></i>Área Asignada
              </label>
              <p class="mb-0" id="modalArea">Sin asignar</p>
            </div>

          </div>

          <!-- Columna derecha -->
          <div class="col-md-6">

            <!-- Prioridad y origen -->
            <div class="mb-3">
              <div class="row">
                <div class="col-6">
                  <label class="form-label fw-semibold text-secondary">
                    <i class="bi bi-exclamation-triangle me-1"></i>Prioridad
                  </label>
                  <p class="mb-0">
                    <span class="badge" id="modalPrioridad">Media</span>
                  </p>
                </div>
                <div class="col-6">
                  <label class="form-label fw-semibold text-secondary">
                    <i class="bi bi-arrow-right-circle me-1"></i>Origen
                  </label>
                  <p class="mb-0" id="modalOrigen">Web</p>
                </div>
              </div>
            </div>

            <!-- Agente asignado -->
            <div class="mb-3">
              <label class="form-label fw-semibold text-secondary">
                <i class="bi bi-person-badge me-1"></i>Agente Asignado
              </label>
              <p class="mb-0" id="modalAgente">Sin asignar</p>
            </div>

            <!-- Fechas -->
            <div class="mb-3">
              <label class="form-label fw-semibold text-secondary">
                <i class="bi bi-calendar-event me-1"></i>Fecha de Creación
              </label>
              <p class="mb-0" id="modalFechaCreacion">--/--/----</p>
            </div>

            <div class="mb-3">
              <label class="form-label fw-semibold text-secondary">
                <i class="bi bi-clock-history me-1"></i>Tiempo Transcurrido
              </label>
              <p class="mb-0" id="modalTiempoTranscurrido">-- días</p>
            </div>

          </div>

        </div>

        <!-- Asunto -->
        <div class="mb-4">
          <label class="form-label fw-semibold text-secondary">
            <i class="bi bi-chat-left-text me-1"></i>Asunto
          </label>
          <p class="mb-0 bg-light p-3 rounded" id="modalAsunto">Asunto del ticket</p>
        </div>

        <!-- Descripción -->
        <div class="mb-4">
          <label class="form-label fw-semibold text-secondary">
            <i class="bi bi-card-text me-1"></i>Descripción Detallada
          </label>
          <div class="bg-light p-3 rounded" id="modalDescripcion" style="max-height: 200px; overflow-y: auto;">
            Descripción completa del problema reportado...
          </div>
        </div>

      </div>

      <!-- Footer del modal -->
      <div class="modal-footer bg-light">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
          <i class="bi bi-x-circle me-1"></i>Cerrar
        </button>
        <button type="button" class="btn btn-primary" id="btnAccionTicket">
          <i class="bi bi-gear me-1"></i>Gestionar Ticket
        </button>
      </div>

    </div>
  </div>
</div>

<script>
// Función simple para cargar datos del ticket al modal
function cargarDetallesTicket(ticket) {
    console.log('Datos del ticket:', ticket); // Debug para ver qué datos llegan
    
    // Función para obtener badge de estado
    function getEstadoBadge(codigo) {
        const badges = {
            1: '<span class="badge bg-secondary">Recibido</span>',
            2: '<span class="badge bg-primary">Asignado</span>',
            3: '<span class="badge bg-info">En Proceso</span>', 
            4: '<span class="badge bg-warning">Pendiente</span>',
            5: '<span class="badge bg-danger">Escalado</span>',
            6: '<span class="badge bg-success">Completado</span>'
        };
        return badges[codigo] || '<span class="badge bg-secondary">Desconocido</span>';
    }
    
    // Función para obtener badge de prioridad  
    function getPrioridadBadge(prioridad) {
        const badges = {
            'BAJA': '<span class="badge bg-light text-dark">Baja</span>',
            'MEDIA': '<span class="badge bg-info">Media</span>',
            'ALTA': '<span class="badge bg-warning">Alta</span>'
        };
        return badges[prioridad] || '<span class="badge bg-secondary">--</span>';
    }
    
    try {
        // Llenar datos en el modal
        document.getElementById('modalCodigoTicket').textContent = `TKT${String(ticket.CODIGO_TICKET).padStart(5, '0')}`;
        document.getElementById('modalEstadoTicket').innerHTML = getEstadoBadge(ticket.CODIGO_ESTADO);
        document.getElementById('modalCliente').textContent = ticket.NOMBRE_CLIENTE || 'No disponible';
        document.getElementById('modalAgencia').textContent = ticket.AGENCIA || 'No disponible';
        document.getElementById('modalArea').textContent = ticket.AREA_ESCALADA || 'Sin asignar';
        document.getElementById('modalPrioridad').innerHTML = getPrioridadBadge(ticket.PRIORIDAD);
        document.getElementById('modalOrigen').textContent = ticket.ORIGEN || 'Web';
        document.getElementById('modalAgente').textContent = ticket.AGENTE_ASIGNADO || 'Sin asignar';
        
        // Formatear fecha
        const fecha = new Date(ticket.FECHA_CREACION);
        document.getElementById('modalFechaCreacion').textContent = fecha.toLocaleDateString('es-ES', {
            day: '2-digit',
            month: '2-digit', 
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
        
        document.getElementById('modalTiempoTranscurrido').textContent = `${ticket.DIAS_DESDE_CREACION || 0} días`;
        document.getElementById('modalAsunto').textContent = ticket.ASUNTO;
        document.getElementById('modalDescripcion').textContent = ticket.DESCRIPCION;
        
        console.log('Modal cargado correctamente');
    } catch (error) {
        console.error('Error cargando el modal:', error);
    }
}
</script>

<?php require_once "layouts/sidebar.php"; ?>
<?php require_once "layouts/footer.php" ?>