<?php

// Iniciar con el contenido
ob_start();

?>

<div class="min-vh-100 d-flex align-items-center bg-light py-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-md-10 col-lg-7">
        
        <!-- Card principal -->
        <div class="card shadow-lg border-0">
          
          <!-- Header con gradiente -->
          <div class="card-header bg-secondary text-white text-center py-4 border-0">
            <h2 class="mb-0 fw-bold">Crea tu cuenta en segundos</h2>
            <p class="mb-0 mt-2 opacity-75">Registro de Nueva Cuenta</p>
          </div>
          
          <!-- Body del card -->
          <div class="card-body p-4 p-md-5">
            
            <!-- Título -->
            <!-- <h4 class="text-center mb-4 text-secondary fw-bold">Crea tu cuenta en segundos</h4> -->
            
            <!-- Alerta de error -->
            <?php if (isset($data['Error'])): ?>
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong><?= $data['Error'] ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            <?php endif; ?>
            
            <!-- Formulario -->
            <form method="POST" action="/auth/createAccount">
              
              <!-- Campo username -->
              <div class="mb-4">
                <label for="username" class="form-label fw-semibold text-secondary">
                  <i class="bi bi-person-fill me-2"></i>Nombre de usuario
                </label>
                <input
                  type="text"
                  id="username"
                  name="username"
                  value="<?= htmlspecialchars($old_data['username'] ?? '') ?>"
                  required
                  class="form-control form-control-lg border-2"
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
                  value="<?= htmlspecialchars($old_data['email'] ?? '') ?>"
                  required
                  class="form-control form-control-lg border-2"
                  placeholder="ejemplo@correo.com">
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
                    placeholder="Confirma tu contraseña">
                </div>
              </div>

              <!-- Campo agencia -->
              <div class="mb-4">
                <label for="agencia" class="form-label fw-semibold text-secondary">
                  <i class="bi bi-building me-2"></i>Agencia más cercana
                </label>
                <select
                  id="agencia"
                  name="agencia"
                  required
                  class="form-select form-select-lg border-2">
                  <option value="" selected>Selecciona tu agencia</option>
                  <?php foreach($data["agencias"] as $key => $value): ?>
                    <option value="<?= $value->codigo ?>">
                      <?= $value->nombre ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <!-- Botón de submit -->
              <div class="d-grid gap-2 mt-4">
                <button type="submit" class="btn btn-primary btn-lg py-3 fw-bold">
                  <i class="bi bi-person-plus-fill me-2"></i>Crear mi cuenta
                </button>
              </div>
              
            </form>
            
          </div>
          
          <!-- Footer del card -->
          <div class="card-footer bg-light text-center py-3 border-0">
            <small class="text-muted">
              ¿Ya tienes cuenta? 
              <a href="/auth/login" class="text-info fw-semibold text-decoration-none">Inicia sesión aquí</a>
            </small>
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