<?php if (!isset($_SESSION["autorizado"])) header("Location: /errors/401"); ?>

<div class="card shadow-sm">
    <div class="card-body">
        <h3 class="card-title h5 mb-4">
            Notificaciones
            <button class="btn btn-sm btn-outline-primary float-end" id="markAllRead">
                <i class="bi bi-check-all me-1"></i>Marcar todo como leído
            </button>
        </h3>

        <!-- Lista de Notificaciones -->
        <div class="list-group list-group-flush" id="notificationsList">
            <!-- Se llenará dinámicamente -->
        </div>

        <!-- Paginación -->
        <nav aria-label="Navegación de notificaciones" class="mt-3">
            <ul class="pagination pagination-sm justify-content-center" id="notificationsPagination">
                <!-- Se llenará dinámicamente -->
            </ul>
        </nav>
    </div>
</div>

<script>
let currentPage = 1;
const itemsPerPage = 10;

// Cargar notificaciones
function loadNotifications(page = 1) {
    fetch(`/api/notifications?page=${page}&limit=${itemsPerPage}`)
        .then(response => response.json())
        .then(data => {
            renderNotifications(data.notifications);
            renderPagination(data.totalPages, page);
        })
        .catch(error => console.error('Error:', error));
}

// Renderizar notificaciones
function renderNotifications(notifications) {
    const list = document.getElementById('notificationsList');
    list.innerHTML = '';
    
    if (notifications.length === 0) {
        list.innerHTML = `
            <div class="text-center text-muted py-4">
                <i class="bi bi-bell-slash fs-2"></i>
                <p class="mt-2">No hay notificaciones</p>
            </div>
        `;
        return;
    }

    notifications.forEach(notification => {
        const item = document.createElement('div');
        item.className = `list-group-item list-group-item-action ${!notification.leida ? 'bg-light' : ''}`;
        item.innerHTML = `
            <div class="d-flex w-100 justify-content-between align-items-center">
                <h6 class="mb-1">
                    ${getNotificationIcon(notification.tipo)}
                    ${notification.titulo}
                </h6>
                <small class="text-muted">${timeAgo(notification.fecha)}</small>
            </div>
            <p class="mb-1">${notification.mensaje}</p>
            <small class="text-muted">
                ${notification.leida ? 
                    '<i class="bi bi-check2-all text-primary"></i> Leída' : 
                    '<i class="bi bi-check2"></i> No leída'}
            </small>
            ${!notification.leida ? `
                <button class="btn btn-sm btn-link float-end" 
                        onclick="markAsRead(${notification.id})">
                    Marcar como leída
                </button>
            ` : ''}
        `;
        list.appendChild(item);
    });
}

// Obtener ícono según tipo de notificación
function getNotificationIcon(tipo) {
    const icons = {
        'TICKET_UPDATE': '<i class="bi bi-ticket-detailed text-primary me-2"></i>',
        'COMMENT': '<i class="bi bi-chat-dots text-success me-2"></i>',
        'ASSIGNMENT': '<i class="bi bi-person-check text-info me-2"></i>',
        'STATUS_CHANGE': '<i class="bi bi-arrow-repeat text-warning me-2"></i>',
        'SYSTEM': '<i class="bi bi-gear text-secondary me-2"></i>'
    };
    return icons[tipo] || '<i class="bi bi-bell me-2"></i>';
}

// Formatear tiempo relativo
function timeAgo(date) {
    const now = new Date();
    const past = new Date(date);
    const diff = Math.floor((now - past) / 1000);

    const intervals = {
        año: 31536000,
        mes: 2592000,
        semana: 604800,
        día: 86400,
        hora: 3600,
        minuto: 60
    };

    for (let [unit, seconds] of Object.entries(intervals)) {
        const interval = Math.floor(diff / seconds);
        
        if (interval >= 1) {
            return `hace ${interval} ${unit}${interval !== 1 ? 's' : ''}`;
        }
    }

    return 'hace un momento';
}

// Renderizar paginación
function renderPagination(totalPages, currentPage) {
    const pagination = document.getElementById('notificationsPagination');
    pagination.innerHTML = '';
    
    if (totalPages <= 1) return;

    // Botón Anterior
    const prevBtn = document.createElement('li');
    prevBtn.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
    prevBtn.innerHTML = `
        <button class="page-link" onclick="loadNotifications(${currentPage - 1})" 
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
            <button class="page-link" onclick="loadNotifications(${i})">${i}</button>
        `;
        pagination.appendChild(pageItem);
    }
    
    // Botón Siguiente
    const nextBtn = document.createElement('li');
    nextBtn.className = `page-item ${currentPage === totalPages ? 'disabled' : ''}`;
    nextBtn.innerHTML = `
        <button class="page-link" onclick="loadNotifications(${currentPage + 1})"
                ${currentPage === totalPages ? 'disabled' : ''}>
            <i class="bi bi-chevron-right"></i>
        </button>
    `;
    pagination.appendChild(nextBtn);
}

// Marcar notificación como leída
function markAsRead(notificationId) {
    fetch(`/api/notifications/${notificationId}/read`, {
        method: 'PUT'
    })
    .then(response => response.json())
    .then(() => {
        loadNotifications(currentPage);
    })
    .catch(error => console.error('Error:', error));
}

// Marcar todas las notificaciones como leídas
function markAllAsRead() {
    fetch('/api/notifications/read-all', {
        method: 'PUT'
    })
    .then(response => response.json())
    .then(() => {
        loadNotifications(1);
    })
    .catch(error => console.error('Error:', error));
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    loadNotifications(1);
    
    document.getElementById('markAllRead').addEventListener('click', markAllAsRead);
});
</script>