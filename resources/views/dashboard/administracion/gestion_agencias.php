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


<!-- Gestión de Agencias Mejorada -->
<div class=" d-flex align-items-center bg-light py-5 main">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-md-9 col-lg-7">

        <!-- Card principal -->
        <div class="card shadow-lg border-0">

          <!-- Header con gradiente -->
          <div class="card-header bg-secondary text-white text-center py-4 border-0">
            <h2 class="mb-0 fw-bold">
              <i class="bi bi-building-fill me-2"></i>Gestión de Agencias
            </h2>
            <p class="mb-0 mt-2 opacity-75">Crear nuevas sucursales y oficinas</p>
          </div>

          <!-- Body del card -->
          <div class="card-body p-4 p-md-5">



            <!-- Alerta de error -->
            <?php if (isset($data['Error'])): ?>
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong><?= $data['Error'] ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            <?php endif; ?>

            <!-- Alerta de éxito -->
            <?php if (isset($data['Success'])): ?>
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <strong><?= $data['Success'] ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            <?php endif; ?>

            <!-- Formulario -->
            <form action="/dashboard/crear_agencia" method="post">

              <div class="row">
                <!-- Campo nombre -->
                <div class="mb-4 col-md-6">
                  <label for="nombre" class="form-label fw-semibold text-secondary">
                    <i class="bi bi-shop-window me-2"></i>Nombre de la agencia
                  </label>
                  <input
                    type="text"
                    id="nombre"
                    name="nombre"
                    required
                    class="form-control form-control-lg border-2"
                    placeholder="Ej: Agencia Central, Sucursal Norte">
                </div>

                <!-- Campo teléfono -->
                <div class="mb-4 col-md-6">
                  <label for="telefono" class="form-label fw-semibold text-secondary">
                    <i class="bi bi-telephone-fill me-2"></i>Teléfono principal
                  </label>
                  <input
                    type="tel"
                    id="telefono"
                    name="telefono"
                    required
                    class="form-control form-control-lg border-2"
                    placeholder="2225-3455"
                    pattern="[0-9]{4}-[0-9]{4}">
                  <small class="text-muted">Formato: 0000-0000</small>
                </div>
              </div>

              <!-- Campo dirección -->
              <div class="mb-4">
                <label for="direccion" class="form-label fw-semibold text-secondary">
                  <i class="bi bi-geo-alt-fill me-2"></i>Dirección completa
                </label>
                <textarea
                  id="direccion"
                  name="direccion"
                  rows="3"
                  required
                  class="form-control form-control-lg border-2"
                  placeholder="Av. España #223, Colonia Escalón, San Salvador"></textarea>
              </div>

              <!-- Botón de envío -->
              <div class="d-grid gap-2 mt-4">
                <button type="submit" class="btn btn-primary btn-lg py-3 fw-bold">
                  <i class="bi bi-plus-circle-fill me-2"></i>Crear Agencia
                </button>
              </div>

            </form>

          </div>

          <!-- Footer del card -->
          <div class="card-footer bg-light text-center py-3 border-0">
            <small class="text-muted">
              Las agencias permiten gestionar tickets por ubicación geográfica
            </small>
          </div>

        </div>

      </div>
    </div>
  </div>
</div>


<?php require_once VIEWS_PATH . "/dashboard/layouts/sidebar.php"; ?>

<?php require_once VIEWS_PATH . "/dashboard/layouts/footer.php" ?>