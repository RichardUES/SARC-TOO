<?php

// Iniciar con el contenido
ob_start();

?>

<div class="mt-5 d-flex align-items-center bg-light">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-md-8 col-lg-5">
        
        <!-- Card principal -->
        <div class="card shadow-lg border-0">
          
          <!-- Header con gradiente -->
          <div class="card-header bg-secondary text-white text-center py-4 border-0">
            <h2 class="mb-0 fw-bold">Luz el faro</h2>
            <p class="mb-0 mt-2 opacity-75">Hacemos que el mundo brille</p>
          </div>
          
          <!-- Body del card -->
          <div class="card-body p-4 p-md-5">
            
            <!-- Título -->
            <h4 class="text-center mb-4 text-secondary fw-bold">Iniciar Sesión</h4>
            
            <!-- Alerta de error -->
            <?php if (isset($_SESSION['Error'])): ?>
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong><?= $_SESSION['Error'] ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <?php deleteSession('Error'); ?>
              </div>
            <?php endif; ?>
            
            <!-- Formulario -->
            <form method="POST" action="/auth/signin">
              
              <!-- Campo usuario/email -->
              <div class="mb-4">
                <label for="username" class="form-label fw-semibold text-secondary">
                  <i class="bi bi-person-fill me-2"></i>Usuario o Email
                </label>
                <input
                  type="text"
                  id="username"
                  name="userOrEmail"
                  value="<?= htmlspecialchars($old_data['username'] ?? '') ?>"
                  required
                  class="form-control form-control-lg border-2"
                  placeholder="Ingresa tu usuario o correo">
              </div>

              <!-- Campo contraseña -->
              <div class="mb-4">
                <label for="password" class="form-label fw-semibold text-secondary">
                  <i class="bi bi-lock-fill me-2"></i>Contraseña
                </label>
                <input
                  type="password"
                  id="password"
                  name="txtPassword"
                  value="<?= htmlspecialchars($old_data['password'] ?? '') ?>"
                  required
                  class="form-control form-control-lg border-2"
                  placeholder="Ingresa tu contraseña">
              </div>

              <!-- Botón de submit -->
              <div class="d-grid gap-2 mt-4">
                <button type="submit" class="btn btn-primary btn-lg py-3 fw-bold">
                  <i class="bi bi-box-arrow-in-right me-2"></i>Ingresar al Sistema
                </button>
              </div>
              
            </form>
            
          </div>
          
          <!-- Footer del card -->
          <div class="card-footer bg-light text-center py-3 border-0">
            <small class="text-muted">¿Olvidaste tu contraseña? Contacta al administrador</small>
          </div>
          
        </div>
        
      </div>
    </div>
  </div>
</div>

<?php
// pasarle el contenido al base.php
$content = ob_get_clean();
require_once RESOURCES_PATH . '/views/layouts/base.php';

?>