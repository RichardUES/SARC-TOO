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


<!-- Gestión de Áreas Mejorada -->
<div class="d-flex align-items-center bg-light py-5 main">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-md-8 col-lg-6">
        
        <!-- Card principal -->
        <div class="card shadow-lg border-0">
          
          <!-- Header con gradiente -->
          <div class="card-header bg-secondary text-white text-center py-4 border-0">
            <h2 class="mb-0 fw-bold">
              <i class="bi bi-diagram-3-fill me-2"></i>Gestión de Áreas
            </h2>
            <p class="mb-0 mt-2 opacity-75">Crear nuevas áreas organizacionales</p>
          </div>
          
          <!-- Body del card -->
          <div class="card-body p-4 p-md-5">
            
  
            
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
            <form action="/dashboard/crear_area" method="post">
              
              <!-- Campo nombre -->
              <div class="mb-4">
                <label for="nombre" class="form-label fw-semibold text-secondary">
                  <i class="bi bi-tag-fill me-2"></i>Nombre del área
                </label>
                <input
                  type="text"
                  id="nombre"
                  name="nombre"
                  required
                  class="form-control form-control-lg border-2"
                  placeholder="Ej: Contabilidad, Recursos Humanos, IT">
              </div>

              <!-- Campo descripción -->
              <div class="mb-4">
                <label for="descripcion" class="form-label fw-semibold text-secondary">
                  <i class="bi bi-card-text me-2"></i>Descripción
                </label>
                <textarea
                  id="descripcion"
                  name="descripcion"
                  rows="4"
                  required
                  class="form-control form-control-lg border-2"
                  placeholder="Describe las funciones y responsabilidades de esta área..."></textarea>
                <small class="text-muted">Ayuda a los usuarios a entender qué tipos de tickets dirigir a esta área</small>
              </div>

              <!-- Botón de envío -->
              <div class="d-grid gap-2 mt-4">
                <button type="submit" class="btn btn-primary btn-lg py-3 fw-bold">
                  <i class="bi bi-plus-circle-fill me-2"></i>Crear Área
                </button>
              </div>
              
            </form>
            
          </div>
          
          <!-- Footer del card -->
          <div class="card-footer bg-light text-center py-3 border-0">
            <small class="text-muted">
              Las áreas ayudan a categorizar y direccionar los tickets correctamente
            </small>
          </div>
          
        </div>
        
      </div>
    </div>
  </div>
</div>


<?php require_once VIEWS_PATH . "/dashboard/layouts/sidebar.php"; ?>

<?php require_once VIEWS_PATH . "/dashboard/layouts/footer.php" ?>