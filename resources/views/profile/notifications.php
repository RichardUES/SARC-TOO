<?php

if (!isset($_SESSION["autorizado"]))
  header("Location: /errors/401");

// En caso de que no haya creado su perfil de usuario
if (!isset($_SESSION["cliente"]))
  $_SESSION["sin_perfil"] = "No tienes un perfil creado. Por favor, crea tu perfil para poder enviar tickets.";

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
<?php else: ?>

<div class="card shadow-sm col-md-9">
    <div class="card-body">
        <h3 class="card-title h5 mb-4">
            Notificaciones
            <!-- <button class="btn btn-sm btn-outline-primary float-end" id="markAllRead">
                <i class="bi bi-check-all me-1"></i>Marcar todo como leído
            </button> -->
        </h3>

        <!-- Lista de Notificaciones en formato horizontal -->
        <div id="notificationsList" class="d-flex flex-column gap-3">
            <!-- Ejemplo de item (se reemplazará dinámicamente por JS) -->
            
            <div class="card border-0 shadow-sm">
              <div class="card-body py-3 border border-secondary rounded">
                <div class="d-flex align-items-center gap-3 flex-wrap flex-md-nowrap">
                  <div class="text-primary shrink-0">
                    <i class="bi bi-bell-fill fs-3 text-info"></i>
                  </div>
                  <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start flex-column flex-md-row gap-2">
                      <div>
                        <h6 class="mb-1">Nueva actualización en tu ticket TKT00023</h6>
                        <p class="mb-0 text-muted small">El agente asignado ha cambiado el estado a <span class="badge bg-warning">En Proceso</span></p>
                      </div>
                      <div class="text-end text-muted small">
                        <i class="bi bi-clock me-1"></i>Hace 5 min
                      </div>
                    </div>
                    <div class="mt-2 d-flex gap-2">
                      <a href="#" class="btn btn-sm btn-outline-info">
                        <i class="bi bi-eye me-1"></i> Ver detalle
                      </a>
                      <button class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-check2-all me-1"></i> Marcar como leído
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
        
    </div>
</div>

<?php endif; ?>
<?php deleteSession('sin_perfil'); ?>

<?php require_once "layouts/footer.php" ?>