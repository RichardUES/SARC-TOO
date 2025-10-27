<?php if (!isset($_SESSION["autorizado"])) header("Location: /errors/401"); ?>

<div class="card shadow-sm">
    <div class="card-body">
        <h3 class="card-title h5 mb-4">
            Historial de Tickets
            <span class="float-end">
                <select class="form-select form-select-sm d-inline-block w-auto" id="filterStatus">
                    <option value="all">Todos los estados</option>
                    <option value="OPEN">Abiertos</option>
                    <option value="IN_PROGRESS">En Progreso</option>
                    <option value="RESOLVED">Resueltos</option>
                    <option value="CLOSED">Cerrados</option>
                </select>
            </span>
        </h3>

        <!-- Lista de Tickets -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Asunto</th>
                        <th>Área</th>
                        <th>Estado</th>
                        <th>Prioridad</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="ticketsTableBody">
                    <!-- Se llenará dinámicamente -->
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

<script>
let currentPage = 1;
const itemsPerPage = 10;
let currentStatus = 'all';

// Cargar tickets
function loadTickets(page = 1, status = 'all') {
    fetch(`/api/tickets/user?page=${page}&status=${status}`)
        .then(response => response.json())
        .then(data => {
            renderTickets(data.tickets);
            renderPagination(data.totalPages, page);
            document.getElementById('totalRecords').textContent = 
                `Mostrando ${data.tickets.length} de ${data.total} tickets`;
        })
        .catch(error => console.error('Error:', error));
}

// Renderizar tickets en la tabla
function renderTickets(tickets) {
    const tbody = document.getElementById('ticketsTableBody');
    tbody.innerHTML = '';
    
    tickets.forEach(ticket => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${ticket.id}</td>
            <td>${ticket.asunto}</td>
            <td>${ticket.area}</td>
            <td><span class="badge bg-${getStatusBadgeColor(ticket.estado)}">${ticket.estado}</span></td>
            <td><span class="badge bg-${getPriorityBadgeColor(ticket.prioridad)}">${ticket.prioridad}</span></td>
            <td>${new Date(ticket.fecha_creacion).toLocaleDateString()}</td>
            <td>
                <button class="btn btn-sm btn-outline-primary" onclick="showTicketDetails(${ticket.id})">
                    <i class="bi bi-eye"></i>
                </button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

// Obtener color de badge según estado
function getStatusBadgeColor(status) {
    const colors = {
        'OPEN': 'info',
        'IN_PROGRESS': 'warning',
        'RESOLVED': 'success',
        'CLOSED': 'secondary'
    };
    return colors[status] || 'primary';
}

// Obtener color de badge según prioridad
function getPriorityBadgeColor(priority) {
    const colors = {
        'LOW': 'success',
        'MEDIUM': 'info',
        'HIGH': 'warning',
        'URGENT': 'danger'
    };
    return colors[priority] || 'primary';
}

// Renderizar paginación
function renderPagination(totalPages, currentPage) {
    const pagination = document.getElementById('pagination');
    pagination.innerHTML = '';
    
    // Botón Anterior
    const prevBtn = document.createElement('li');
    prevBtn.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
    prevBtn.innerHTML = `
        <button class="page-link" onclick="loadTickets(${currentPage - 1}, '${currentStatus}')" 
                ${currentPage === 1 ? 'disabled' : ''}>
            <i class="bi bi-chevron-left"></i>
        </button>
    `;
    pagination.appendChild(prevBtn);
    
    // Páginas
    for (let i = 1; i <= totalPages; i++) {
        const pageItem = document.createElement('li');
        pageItem.className = `page-item ${currentPage === i ? 'active' : ''}`;
        pageItem.innerHTML = `
            <button class="page-link" onclick="loadTickets(${i}, '${currentStatus}')">${i}</button>
        `;
        pagination.appendChild(pageItem);
    }
    
    // Botón Siguiente
    const nextBtn = document.createElement('li');
    nextBtn.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
    nextBtn.innerHTML = `
        <button class="page-link" onclick="loadTickets(${currentPage + 1}, '${currentStatus}')"
                ${currentPage === totalPages ? 'disabled' : ''}>
            <i class="bi bi-chevron-right"></i>
        </button>
    `;
    pagination.appendChild(nextBtn);
}

// Mostrar detalles del ticket
function showTicketDetails(ticketId) {
    fetch(`/api/tickets/${ticketId}`)
        .then(response => response.json())
        .then(ticket => {
            document.getElementById('ticketDetails').innerHTML = `
                <dl class="row">
                    <dt class="col-sm-3">Asunto</dt>
                    <dd class="col-sm-9">${ticket.asunto}</dd>
                    
                    <dt class="col-sm-3">Descripción</dt>
                    <dd class="col-sm-9">${ticket.descripcion}</dd>
                    
                    <dt class="col-sm-3">Estado</dt>
                    <dd class="col-sm-9">
                        <span class="badge bg-${getStatusBadgeColor(ticket.estado)}">${ticket.estado}</span>
                    </dd>
                    
                    <dt class="col-sm-3">Prioridad</dt>
                    <dd class="col-sm-9">
                        <span class="badge bg-${getPriorityBadgeColor(ticket.prioridad)}">${ticket.prioridad}</span>
                    </dd>
                    
                    <dt class="col-sm-3">Área</dt>
                    <dd class="col-sm-9">${ticket.area}</dd>
                    
                    <dt class="col-sm-3">Fecha Creación</dt>
                    <dd class="col-sm-9">${new Date(ticket.fecha_creacion).toLocaleString()}</dd>
                </dl>
            `;
            
            loadComments(ticketId);
            const modal = new bootstrap.Modal(document.getElementById('ticketDetailModal'));
            modal.show();
        })
        .catch(error => console.error('Error:', error));
}

// Cargar comentarios
function loadComments(ticketId) {
    fetch(`/api/tickets/${ticketId}/comments`)
        .then(response => response.json())
        .then(comments => {
            const commentsList = document.getElementById('commentsList');
            commentsList.innerHTML = '';
            
            comments.forEach(comment => {
                const commentDiv = document.createElement('div');
                commentDiv.className = 'border-start border-4 ps-3 mb-3';
                commentDiv.innerHTML = `
                    <p class="mb-1">${comment.contenido}</p>
                    <small class="text-muted">
                        Por ${comment.usuario} - ${new Date(comment.fecha).toLocaleString()}
                    </small>
                `;
                commentsList.appendChild(commentDiv);
            });
        })
        .catch(error => console.error('Error:', error));
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    loadTickets(1, 'all');
    
    // Filtro de estado
    document.getElementById('filterStatus').addEventListener('change', function(e) {
        currentStatus = e.target.value;
        loadTickets(1, currentStatus);
    });
    
    // Formulario de comentarios
    document.getElementById('commentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const ticketId = "" /* obtener ID del ticket actual */;
        const comment = document.getElementById('newComment').value;
        
        fetch(`/api/tickets/${ticketId}/comments`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ content: comment })
        })
        .then(response => response.json())
        .then(() => {
            document.getElementById('newComment').value = '';
            loadComments(ticketId);
        })
        .catch(error => console.error('Error:', error));
    });
});
</script>