<?php 

if (!isset($_SESSION["autorizado"])) header("Location: /errors/401");

if (!isset($_SESSION["cliente"]))
    $_SESSION["sin_perfil"] = "No tienes un perfil creado. Por favor, crea tu perfil para poder ver sus Notificaciones.";

require_once "layouts/header.php";

?>

<!-- Menu lateral (sidebar) -->
<?php require_once "layouts/sidebar.php" ?>

<?php if (isset($_SESSION["sin_perfil"])): ?>
    <div class="alert alert-warning col-md-9 d-flex justify-content-center align-items-center flex-wrap ">
        <strong class="h4 w-100 text-center">
            <?= $_SESSION["sin_perfil"]; ?>
        </strong>
        <a href="/profile/personal_info" class="btn btn-outline-info" >Crear perfil aquí</a>
    </div>
<?php else: ?>

<div class="card shadow-sm col-md-9">
    <div class="card-body">
        <h3 class="card-title h5 mb-4">
            Notificaciones
            <!-- <button class="btn btn-sm btn-outline-primary float-end" id="markAllRead">
                <i class="bi bi-check-all me-1"></i>Marcar todo como leído
            </button> -->
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

<?php endif; ?>
<?php require_once "layouts/footer.php" ?>