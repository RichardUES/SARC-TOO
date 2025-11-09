<?php

use App\Models\enums\RolType;

require_once "layouts/head.php";
require_once "layouts/header.php";

// Validación de acceso: Admin, Supervisor y Agente
if (
  !isset($_SESSION["autorizado"]) ||
  !in_array($_SESSION["autorizado"]->rolID, [
    RolType::ADMIN->value,
    RolType::SUPERVISOR->value,
    RolType::AGENT->value
  ])
) {
  header("Location: http://luzelfaro.com/errors/unauthorized");
  exit;
}

require_once "layouts/sidebar.php";

?>

<!-- Registro de Clientes -->
<div class="container-fluid py-4 main">
  <div class="row justify-content-center">
    <div class="col-12 col-md-10 col-lg-8">

      <!-- Card principal -->
      <div class="card shadow-lg border-0">

        <!-- Header con gradiente -->
        <div class="card-header bg-secondary text-white text-center py-4 border-0">
          <h2 class="mb-0 fw-bold">
            <i class="bi bi-person-plus-fill me-2"></i>Registro de Cliente
          </h2>
          <p class="mb-0 mt-2 opacity-75">Crear cuenta completa para nuevo cliente</p>
        </div>

        <!-- Body del card -->
        <div class="card-body p-4 p-md-5">

          <!-- Alerta de error -->
          <?php if (isset($_SESSION['Error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <i class="bi bi-exclamation-triangle-fill me-2"></i>
              <strong><?= $_SESSION['Error'] ?></strong>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['Error']); ?>
          <?php endif; ?>

          <!-- Alerta de éxito -->
          <?php if (isset($_SESSION['Success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <i class="bi bi-check-circle-fill me-2"></i>
              <strong><?= $_SESSION['Success'] ?></strong>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['Success']); ?>
          <?php endif; ?>

          <!-- Formulario -->
          <form method="POST" action="/profile/crearCliente">

            <!-- Sección: Datos de Usuario -->
            <div class="mb-5">
              <h5 class="text-secondary fw-bold mb-3 border-bottom pb-2">
                <i class="bi bi-person-gear me-2"></i>Datos de Usuario
              </h5>

              <div class="row">
                <!-- Campo username -->
                <div class="col-md-6 mb-4">
                  <label for="username" class="form-label fw-semibold text-secondary">
                    <i class="bi bi-person-fill me-2"></i>Nombre de usuario
                  </label>
                  <input
                    type="text"
                    id="username"
                    name="username"
                    required
                    class="form-control form-control-lg border-2"
                    placeholder="Ej: juan.perez">
                </div>

                <!-- Campo email -->
                <div class="col-md-6 mb-4">
                  <label for="email" class="form-label fw-semibold text-secondary">
                    <i class="bi bi-envelope-fill me-2"></i>Correo electrónico
                  </label>
                  <input
                    type="email"
                    id="email"
                    name="email"
                    required
                    class="form-control form-control-lg border-2"
                    placeholder="ejemplo@correo.com">
                </div>
              </div>

              <!-- Contraseñas en fila -->
              <div class="row">
                <div class="col-md-6 mb-4">
                  <label for="password" class="form-label fw-semibold text-secondary">
                    <i class="bi bi-lock-fill me-2"></i>Contraseña
                  </label>
                  <input
                    type="password"
                    id="password"
                    name="txtPassword"
                    required
                    class="form-control form-control-lg border-2"
                    placeholder="Mínimo 6 caracteres">
                </div>

                <div class="col-md-6 mb-4">
                  <label for="password2" class="form-label fw-semibold text-secondary">
                    <i class="bi bi-shield-lock-fill me-2"></i>Repetir contraseña
                  </label>
                  <input
                    type="password"
                    id="password2"
                    name="txtPassword2"
                    required
                    class="form-control form-control-lg border-2"
                    placeholder="Confirma contraseña">
                </div>
              </div>

              <!-- Campo agencia -->
              <div class="mb-4">
                <label for="agencia" class="form-label fw-semibold text-secondary">
                  <i class="bi bi-building me-2"></i>Agencia del cliente
                </label>
                <select
                  id="agencia"
                  name="agencia"
                  required
                  class="form-select form-select-lg border-2">
                  <option value="" selected>Selecciona la agencia</option>
                  <?php if (isset($data["agencias"]) && !empty($data["agencias"])): ?>
                    <?php foreach ($data["agencias"] as $agencia): ?>
                      <option value="<?= $agencia->codigo ?>">
                        <?= htmlspecialchars($agencia->nombre) ?>
                      </option>
                    <?php endforeach; ?>
                  <?php endif; ?>
                </select>
              </div>
            </div>

            <!-- Sección: Datos Personales -->
            <div class="mb-5">
              <h5 class="text-secondary fw-bold mb-3 border-bottom pb-2">
                <i class="bi bi-person-vcard me-2"></i>Datos Personales del Cliente
              </h5>

              <div class="row">
                <!-- Primer Nombre -->
                <div class="col-md-6 mb-4">
                  <label for="primer_nombre" class="form-label fw-semibold text-secondary">
                    <i class="bi bi-person-fill me-2"></i>Primer Nombre
                  </label>
                  <input
                    type="text"
                    id="primer_nombre"
                    name="primer_nombre"
                    required
                    class="form-control form-control-lg border-2"
                    placeholder="Ej: Juan">
                </div>

                <!-- Segundo Nombre -->
                <div class="col-md-6 mb-4">
                  <label for="segundo_nombre" class="form-label fw-semibold text-secondary">
                    <i class="bi bi-person-fill me-2"></i>Segundo Nombre
                  </label>
                  <input
                    type="text"
                    id="segundo_nombre"
                    name="segundo_nombre"
                    class="form-control form-control-lg border-2"
                    placeholder="Ej: Carlos (opcional)">
                </div>
              </div>

              <div class="row">
                <!-- Primer Apellido -->
                <div class="col-md-6 mb-4">
                  <label for="primer_apellido" class="form-label fw-semibold text-secondary">
                    <i class="bi bi-person-badge me-2"></i>Primer Apellido
                  </label>
                  <input
                    type="text"
                    id="primer_apellido"
                    name="primer_apellido"
                    required
                    class="form-control form-control-lg border-2"
                    placeholder="Ej: Pérez">
                </div>

                <!-- Segundo Apellido -->
                <div class="col-md-6 mb-4">
                  <label for="segundo_apellido" class="form-label fw-semibold text-secondary">
                    <i class="bi bi-person-badge me-2"></i>Segundo Apellido
                  </label>
                  <input
                    type="text"
                    id="segundo_apellido"
                    name="segundo_apellido"
                    class="form-control form-control-lg border-2"
                    placeholder="Ej: López (opcional)">
                </div>
              </div>

              <div class="row">
                <!-- Fecha de Nacimiento -->
                <div class="col-md-6 mb-4">
                  <label for="fecha_nacimiento" class="form-label fw-semibold text-secondary">
                    <i class="bi bi-calendar-event me-2"></i>Fecha de Nacimiento
                  </label>
                  <input
                    type="date"
                    id="fecha_nacimiento"
                    name="fecha_nacimiento"
                    required
                    class="form-control form-control-lg border-2">
                </div>

                <!-- Teléfono -->
                <div class="col-md-6 mb-4">
                  <label for="telefono" class="form-label fw-semibold text-secondary">
                    <i class="bi bi-telephone-fill me-2"></i>Teléfono
                  </label>
                  <input
                    type="tel"
                    id="telefono"
                    name="telefono"
                    required
                    maxlength="10"
                    class="form-control form-control-lg border-2"
                    placeholder="7123-4567">
                </div>
              </div>

              <!-- DUI -->
              <div class="mb-4">
                <label for="dui" class="form-label fw-semibold text-secondary">
                  <i class="bi bi-card-text me-2"></i>DUI (Documento Único de Identidad)
                </label>
                <input
                  type="text"
                  id="dui"
                  name="dui"
                  required
                  maxlength="10"
                  class="form-control form-control-lg border-2"
                  placeholder="12345678-9">
              </div>
            </div>

            <!-- Botones -->
            <div class="row">
              <div class="col-md-6 mb-3">
                <button type="button" class="btn btn-outline-secondary btn-lg w-100" onclick="history.back()">
                  <i class="bi bi-arrow-left me-2"></i>Cancelar
                </button>
              </div>
              <div class="col-md-6 mb-3">
                <button type="submit" class="btn btn-primary btn-lg w-100 py-3 fw-bold">
                  <i class="bi bi-person-plus-fill me-2"></i>Registrar Cliente
                </button>
              </div>
            </div>

          </form>

        </div>

        <!-- Footer del card -->
        <div class="card-footer bg-light text-center py-3 border-0">
          <small class="text-muted">
            <i class="bi bi-info-circle me-1"></i>
            Se creará automáticamente la cuenta de usuario y el perfil del cliente
          </small>
        </div>

      </div>

    </div>
  </div>
</div>

<?php require_once "layouts/footer.php" ?>