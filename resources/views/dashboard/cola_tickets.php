<?php

use App\Models\enums\Priority;
use App\Models\enums\RolType;
use App\Models\enums\TicketStatus;

require_once VIEWS_PATH . "/dashboard/layouts/head.php";
require_once VIEWS_PATH . "/dashboard/layouts/header.php";

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

require_once VIEWS_PATH . "/dashboard/layouts/sidebar.php";

?>

<!-- Cola de Tickets -->
<div class="container-fluid py-4 main">
  <div class="row">
    <div class="col-12">

      <!-- Header de la página -->
      <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-secondary text-white py-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h3 class="mb-0 fw-bold">
                <i class="bi bi-list-ul me-2"></i>Cola de Tickets
              </h3>
              <p class="mb-0 mt-1 opacity-75">Bitácora y gestión de tickets pendientes</p>
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
                <option value="Recibido">Recibido</option>
                <option value="Asignado">Asignado</option>
                <option value="En Proceso">En Proceso</option>
                <option value="Pendiente">Pendiente</option>
                <option value="Escalado">Escalado</option>
                <option value="Completado">Completado</option>
              </select>
            </div>

            <!-- Filtro por prioridad -->
            <div class="col-md-3">
              <label for="filtroPrioridad" class="form-label fw-semibold text-secondary">
                <i class="bi bi-exclamation-triangle me-1"></i>Prioridad
              </label>
              <select id="filtroPrioridad" class="form-select">
                <option value="">Todas las prioridades</option>
                <option value="BAJA">Baja</option>
                <option value="MEDIA">Media</option>
                <option value="ALTA">Alta</option>
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
              <i class="bi bi-table me-2"></i>Bitácora de Tickets
            </h5>
            <span class="badge bg-info" id="contadorTickets">
              <i class="bi bi-ticket-detailed me-1"></i> 
              <?= isset($data) ? count($data) : 0 ?> tickets
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
                  <th>Área</th>
                  <th style="width: 120px">Estado</th>
                  <th style="width: 100px">Prioridad</th>
                  <th style="width: 100px">Origen</th>
                  <th style="width: 130px">Fecha Creación</th>
                  <th class="pe-3 text-center" style="width: 100px">Acciones</th>
                </tr>
              </thead>
              <tbody>

                <?php if (isset($data) && !empty($data)): ?>
                  <?php foreach ($data as $ticket): ?>
                    <tr>
                      <td class="ps-3">TKT<?= str_pad($ticket["CODIGO"], 5, '0', STR_PAD_LEFT) ?></td>
                      <td><?= htmlspecialchars($ticket["ASUNTO"], ENT_QUOTES, 'UTF-8') ?></td>
                      <td><?= htmlspecialchars($ticket["NOMBRE"], ENT_QUOTES, 'UTF-8') ?></td>
                      <td><?= htmlspecialchars($ticket["AGENCIA"], ENT_QUOTES, 'UTF-8') ?></td>
                      <td><?= htmlspecialchars($ticket["AREA"] ?? 'Sin asignar', ENT_QUOTES, 'UTF-8') ?></td>
                      <td>
                        <?php
                        // Mapear estados dinámicamente
                        $estadoClass = match(strtolower($ticket["ESTADO"])) {
                          'recibido' => 'bg-secondary',
                          'asignado' => 'bg-primary',
                          'en proceso' => 'bg-info',
                          'pendiente' => 'bg-warning',
                          'escalado' => 'bg-danger',
                          'completado' => 'bg-success',
                          default => 'bg-secondary'
                        };
                        ?>
                        <span class="badge <?= $estadoClass ?>">
                          <?= htmlspecialchars($ticket["ESTADO"], ENT_QUOTES, 'UTF-8') ?>
                        </span>
                      </td>
                      <td>
                        <?php
                        $prioridadClass = match(strtoupper($ticket["PRIORIDAD"])) {
                          'BAJA' => 'bg-light text-dark',
                          'MEDIA' => 'bg-info',
                          'ALTA' => 'bg-warning',
                          default => 'bg-secondary'
                        };
                        ?>
                        <span class="badge <?= $prioridadClass ?>">
                          <?= htmlspecialchars($ticket["PRIORIDAD"], ENT_QUOTES, 'UTF-8') ?>
                        </span>
                      </td>
                      <td><?= htmlspecialchars($ticket["ORIGEN"] ?? 'Web', ENT_QUOTES, 'UTF-8') ?></td>
                      <td><?= date("d/m/Y H:i", strtotime($ticket["FECHA_CREACION"])) ?></td>
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
                    <td colspan="10" class="text-center py-5 text-muted">
                      <div class="d-flex flex-column align-items-center">
                        <i class="bi bi-inbox fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted">No hay tickets disponibles</h5>
                        <p class="mb-0">La cola de tickets está vacía en este momento.</p>
                      </div>
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
              Mostrando <span id="ticketsInicio">1</span> - <span id="ticketsFin"><?= isset($data) ? count($data) : 0 ?></span>
              de <span id="ticketsTotal"><?= isset($data) ? count($data) : 0 ?></span> tickets
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
                <span class="badge bg-light text-dark fs-6" id="modalCodigoTicket">TKT00123</span>
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

            <!-- Fecha de creación -->
            <div class="mb-3">
              <label class="form-label fw-semibold text-secondary">
                <i class="bi bi-calendar-event me-1"></i>Fecha de Creación
              </label>
              <p class="mb-0" id="modalFechaCreacion">--/--/----</p>
            </div>

            <!-- Tiempo transcurrido -->
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
// Función para cargar datos del ticket al modal
function cargarDetallesTicket(ticket) {
    console.log('Datos del ticket:', ticket); // Debug para ver qué datos llegan
    
    // Función para obtener badge de estado
    function getEstadoBadge(estado) {
        const badges = {
            'recibido': '<span class="badge bg-secondary">Recibido</span>',
            'asignado': '<span class="badge bg-primary">Asignado</span>',
            'en proceso': '<span class="badge bg-info">En Proceso</span>', 
            'pendiente': '<span class="badge bg-warning">Pendiente</span>',
            'escalado': '<span class="badge bg-danger">Escalado</span>',
            'completado': '<span class="badge bg-success">Completado</span>'
        };
        return badges[estado.toLowerCase()] || '<span class="badge bg-secondary">Desconocido</span>';
    }
    
    // Función para obtener badge de prioridad  
    function getPrioridadBadge(prioridad) {
        const badges = {
            'BAJA': '<span class="badge bg-light text-dark">Baja</span>',
            'MEDIA': '<span class="badge bg-info">Media</span>',
            'ALTA': '<span class="badge bg-warning">Alta</span>'
        };
        return badges[prioridad.toUpperCase()] || '<span class="badge bg-secondary">--</span>';
    }
    
    // Función para calcular días transcurridos
    function calcularDiasTranscurridos(fechaCreacion) {
        const fecha = new Date(fechaCreacion);
        const hoy = new Date();
        const diferencia = Math.floor((hoy - fecha) / (1000 * 60 * 60 * 24));
        return diferencia;
    }
    
    try {
        // Llenar datos en el modal
        document.getElementById('modalCodigoTicket').textContent = `TKT${String(ticket.CODIGO).padStart(5, '0')}`;
        document.getElementById('modalEstadoTicket').innerHTML = getEstadoBadge(ticket.ESTADO);
        document.getElementById('modalCliente').textContent = ticket.NOMBRE || 'No disponible';
        document.getElementById('modalAgencia').textContent = ticket.AGENCIA || 'No disponible';
        document.getElementById('modalArea').textContent = ticket.AREA || 'Sin asignar';
        document.getElementById('modalPrioridad').innerHTML = getPrioridadBadge(ticket.PRIORIDAD || 'MEDIA');
        document.getElementById('modalOrigen').textContent = ticket.ORIGEN || 'Web';
        
        // Formatear fecha
        const fecha = new Date(ticket.FECHA_CREACION);
        document.getElementById('modalFechaCreacion').textContent = fecha.toLocaleDateString('es-ES', {
            day: '2-digit',
            month: '2-digit', 
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
        
        // Calcular días transcurridos
        const diasTranscurridos = calcularDiasTranscurridos(ticket.FECHA_CREACION);
        document.getElementById('modalTiempoTranscurrido').textContent = `${diasTranscurridos} días`;
        
        document.getElementById('modalAsunto').textContent = ticket.ASUNTO || 'Sin asunto';
        document.getElementById('modalDescripcion').textContent = ticket.DESCRIPCION || 'Sin descripción disponible';
        
        console.log('Modal cargado correctamente');
    } catch (error) {
        console.error('Error cargando el modal:', error);
    }
}

// Funcionalidad de filtros (opcional)
document.addEventListener('DOMContentLoaded', function() {
    const filtroEstado = document.getElementById('filtroEstado');
    const filtroPrioridad = document.getElementById('filtroPrioridad');
    const buscarTicket = document.getElementById('buscarTicket');
    const limpiarFiltros = document.getElementById('limpiarFiltros');
    
    // Función para filtrar tabla
    function filtrarTabla() {
        const filas = document.querySelectorAll('#tablaTickets tbody tr');
        const estadoFiltro = filtroEstado.value.toLowerCase();
        const prioridadFiltro = filtroPrioridad.value.toLowerCase();
        const busqueda = buscarTicket.value.toLowerCase();
        
        let ticketsVisibles = 0;
        
        filas.forEach(fila => {
            // Saltar fila de "no hay datos"
            if (fila.children.length === 1 && fila.children[0].colSpan > 1) {
                return;
            }
            
            const codigo = fila.children[0].textContent.toLowerCase();
            const asunto = fila.children[1].textContent.toLowerCase();
            const cliente = fila.children[2].textContent.toLowerCase();
            const estado = fila.children[5].textContent.toLowerCase();
            const prioridad = fila.children[6].textContent.toLowerCase();
            
            let mostrar = true;
            
            // Filtro por estado
            if (estadoFiltro && !estado.includes(estadoFiltro)) {
                mostrar = false;
            }
            
            // Filtro por prioridad
            if (prioridadFiltro && !prioridad.includes(prioridadFiltro)) {
                mostrar = false;
            }
            
            // Filtro por búsqueda
            if (busqueda && !codigo.includes(busqueda) && !asunto.includes(busqueda) && !cliente.includes(busqueda)) {
                mostrar = false;
            }
            
            fila.style.display = mostrar ? '' : 'none';
            if (mostrar) ticketsVisibles++;
        });
        
        // Actualizar contador
        document.getElementById('contadorTickets').innerHTML = 
            `<i class="bi bi-ticket-detailed me-1"></i> ${ticketsVisibles} tickets`;
    }
    
    // Event listeners para filtros
    if (filtroEstado) filtroEstado.addEventListener('change', filtrarTabla);
    if (filtroPrioridad) filtroPrioridad.addEventListener('change', filtrarTabla);
    if (buscarTicket) buscarTicket.addEventListener('input', filtrarTabla);
    
    // Limpiar filtros
    if (limpiarFiltros) {
        limpiarFiltros.addEventListener('click', function() {
            filtroEstado.value = '';
            filtroPrioridad.value = '';
            buscarTicket.value = '';
            filtrarTabla();
        });
    }
});
</script>

<?php require_once VIEWS_PATH . "/dashboard/layouts/footer.php" ?>