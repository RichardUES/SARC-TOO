<?php
/* ==================================================
   resources/views/home/contact.php - Vista de Contacto
   ================================================== */

// Iniciar captura de contenido
ob_start();
?>

<!-- Hero About Section -->
<section class="hero-about d-flex align-items-center position-relative mb-5">
  <div class="container index-up">
    <div class="row align-items-center">
      <div class="col-lg-6">
        <h1 class="display-2 fw-bold text-white mb-4">
          <span class="neon-glow">Contáctanos</span>
        </h1>
        <p class="lead text-primary fs-3 mb-4">
          <em>"Hacemos que el mundo brille."</em>
        </p>
        <p class="fs-5 text-light">
          Desde 2008, Luz El Faro ha sido el faro que guía el desarrollo
          energético de El Salvador, conectando sueños y alimentando el progreso
          de nuestro país con energía confiable y eficiente.
        </p>
      </div>
      <div class="col-lg-6 text-center">
        <div class="floating-slow">
          <img src="<?= VIRTUAL_PATH ?>/assets/images/contact-banner.svg"
               alt="Contactar a Luz El Faro" class="img-fluid rounded-4 neon-border">
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Formulario de Contacto -->
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-12 col-md-10 col-lg-7">
      
      <!-- Card principal -->
      <div class="card shadow-lg border-0">
        
        <!-- Header con gradiente -->
        <div class="card-header bg-secondary text-white text-center py-4 border-0">
          <h2 class="mb-0 fw-bold">
            <i class="bi bi-envelope-heart me-2"></i>Formulario de Contacto
          </h2>
          <p class="mb-0 mt-2 opacity-75">Estamos aquí para ayudarte</p>
        </div>
        
        <!-- Body del card -->
        <div class="card-body p-4 p-md-5">
          
          <!-- Alertas de éxito/error -->
          <?php if (isset($success)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <i class="bi bi-check-circle-fill me-2"></i>
              <strong><?= htmlspecialchars($success) ?></strong>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          <?php endif; ?>

          <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <i class="bi bi-exclamation-triangle-fill me-2"></i>
              <strong><?= htmlspecialchars($error) ?></strong>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          <?php endif; ?>
          
          <!-- Formulario -->
          <form method="POST" action="/home/contact">
            
            <!-- Campo nombre -->
            <div class="mb-4">
              <label for="name" class="form-label fw-semibold text-secondary">
                <i class="bi bi-person-fill me-2"></i>Nombre completo
              </label>
              <input
                type="text"
                id="name"
                name="name"
                value="<?= htmlspecialchars($old_data['name'] ?? '') ?>"
                required
                class="form-control form-control-lg border-2"
                placeholder="Ingresa tu nombre completo">
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
                placeholder="tu@correo.com">
            </div>

            <!-- Campo mensaje -->
            <div class="mb-4">
              <label for="message" class="form-label fw-semibold text-secondary">
                <i class="bi bi-chat-left-text-fill me-2"></i>Mensaje
              </label>
              <textarea
                id="message"
                name="message"
                rows="6"
                required
                class="form-control form-control-lg border-2"
                placeholder="Escribe tu mensaje o consulta aquí..."><?= htmlspecialchars($old_data['message'] ?? '') ?></textarea>
              <small class="text-muted">Mínimo 10 caracteres</small>
            </div>

            <!-- Botón de envío -->
            <div class="d-grid gap-2 mt-4">
              <button type="submit" class="btn btn-primary btn-lg py-3 fw-bold">
                <i class="bi bi-send-fill me-2"></i>Enviar Mensaje
              </button>
            </div>
            
          </form>
          
        </div>
        
        <!-- Footer del card -->
        <div class="card-footer bg-light text-center py-4 border-0">
          <div class="row text-center g-3">
            <div class="col-md-4">
              <i class="bi bi-telephone-fill text-primary fs-4"></i>
              <p class="mb-0 mt-2 small text-muted">Teléfono</p>
              <p class="mb-0 fw-semibold">2222-1234</p>
            </div>
            <div class="col-md-4">
              <i class="bi bi-envelope-fill text-primary fs-4"></i>
              <p class="mb-0 mt-2 small text-muted">Email</p>
              <p class="mb-0 fw-semibold">info@luzelfaro.com</p>
            </div>
            <div class="col-md-4">
              <i class="bi bi-clock-fill text-primary fs-4"></i>
              <p class="mb-0 mt-2 small text-muted">Horario</p>
              <p class="mb-0 fw-semibold">Lun-Vie 8am-5pm</p>
            </div>
          </div>
        </div>
        
      </div>
      
    </div>
  </div>
</div>

<?php
// Capturar el contenido y pasarlo al layout
$content = ob_get_clean();
require_once RESOURCES_PATH . '/views/layouts/base.php';
?>
