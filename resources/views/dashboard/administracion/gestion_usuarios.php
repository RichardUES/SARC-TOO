<?php

use App\Models\enums\RolType;

require_once VIEWS_PATH . "/dashboard/layouts/head.php";

// Validación de acceso: Solo administradores
if (
  !isset($_SESSION["autorizado"]) ||
  $_SESSION["autorizado"]->rolID !== RolType::ADMIN->value
) {
  header("Location: http://luzelfaro.com/errors/unauthorized");
  exit;
}

require_once VIEWS_PATH . "/dashboard/layouts/header.php";

?>


<!-- Gestión de Usuarios Mejorada -->
<div class=" d-flex align-items-center bg-light py-5 main">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-md-10 col-lg-8">

        <!-- Card principal -->
        <div class="card shadow-lg border-0">

          <!-- Header con gradiente -->
          <div class="card-header bg-secondary text-white text-center py-4 border-0">
            <h2 class="mb-0 fw-bold">
              <i class="bi bi-person-plus-fill me-2"></i>Gestión de Usuarios
            </h2>
            <p class="mb-0 mt-2 opacity-75">Crear nuevos usuarios del sistema</p>
          </div>

          <!-- Body del card -->
          <div class="card-body p-4 p-md-5">

            <!-- Usuario autorizado info -->
            <!-- <?php //if (isset($_SESSION["autorizado"])): ?>
              <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-info-circle-fill me-2"></i>
                <strong>Sesión activa:</strong> <?php //$_SESSION["autorizado"]->username ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            <?php //endif; ?> -->

            <!-- Alerta de error -->
            <?php if (isset($_SESSION['Error'])): ?>
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong><?= $_SESSION['Error'] ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <?php deleteSession("Error"); ?>
              </div>
            <?php endif; ?>

            <!-- Alerta de éxito -->
            <?php if (isset($_SESSION['Success'])): ?>
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <strong><?= $_SESSION['Success'] ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <?php deleteSession('Success'); ?>
              </div>
            <?php endif; ?>

            <!-- Formulario -->
            <form action="/dashboard/crear_usuario" method="post">

              <!-- Campo username -->
              <div class="mb-4">
                <label for="username" class="form-label fw-semibold text-secondary">
                  <i class="bi bi-person-fill me-2"></i>Nombre de usuario
                </label>
                <input
                  type="text"
                  id="username"
                  name="username"
                  required
                  class="form-control form-control border-2"
                  placeholder="Ej: juan.perez">
              </div>

              <!-- Campo email -->
              <div class="mb-4">
                <label for="email" class="form-label fw-semibold text-secondary">
                  <i class="bi bi-envelope-fill me-2"></i>Correo electrónico
                </label>
                <input
                  type="email"
                  id="email"
                  name="email"
                  required
                  class="form-control form-control border-2"
                  placeholder="juan.perez@empresa.com">
              </div>

              <!-- Campo contraseña -->
              <div class="mb-4">
                <label for="password" class="form-label fw-semibold text-secondary">
                  <i class="bi bi-lock-fill me-2"></i>Contraseña temporal
                </label>
                <input
                  type="password"
                  id="password"
                  name="password"
                  required
                  class="form-control form-control border-2"
                  placeholder="Mínimo 6 caracteres">
                <small class="text-muted">El usuario podrá cambiarla en su primer acceso</small>
              </div>

              <div class="row">
                <!-- Campo rol -->
                <div class="mb-4 col-md-6">
                  <label for="role_id" class="form-label fw-semibold text-secondary">
                    <i class="bi bi-shield-check me-2"></i>Rol de usuario
                  </label>
                  <select
                    id="role_id"
                    name="role_id"
                    required
                    class="form-select form-select border-2">
                    <option value="" selected>Seleccione un rol</option>
                    <?php if (isset($_SESSION['roles'])): ?>
                      <?php foreach ($_SESSION['roles'] as $rol): ?>
                        <option value="<?= $rol->codigo ?>">
                          <?= htmlspecialchars($rol->nombre) ?>
                        </option>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </select>
                </div>

                <!-- Campo agencia -->
                <div class="mb-4 col-md-6">
                  <label for="agency_id" class="form-label fw-semibold text-secondary">
                    <i class="bi bi-building me-2"></i>Agencia asignada
                  </label>
                  <select
                    id="agency_id"
                    name="agency_id"
                    required
                    class="form-select form-select border-2">
                    <option value="" selected>Seleccione una agencia</option>
                    <?php if (isset($_SESSION['agencias'])): ?>
                      <?php foreach ($_SESSION['agencias'] as $agency): ?>
                        <option value="<?= $agency->codigo ?>">
                          <?= htmlspecialchars($agency->nombre) ?>
                        </option>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </select>
                </div>
              </div>

              <!-- Botón de envío -->
              <div class="d-grid gap-2 mt-4">
                <button type="submit" class="btn btn-primary btn-lg py-3 fw-bold">
                  <i class="bi bi-person-plus-fill me-2"></i>Crear Usuario
                </button>
              </div>

            </form>

          </div>

          <!-- Footer del card -->
          <div class="card-footer bg-light text-center py-3 border-0">
            <small class="text-muted">
              Los usuarios creados recibirán un correo con sus credenciales de acceso
            </small>
          </div>

        </div>

      </div>
    </div>
  </div>
</div>


<?php require_once VIEWS_PATH . "/dashboard/layouts/sidebar.php"; ?>

<?php require_once VIEWS_PATH . "/dashboard/layouts/footer.php" ?>