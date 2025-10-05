<?php
/* ==================================================
   resources/views/home/contact.php - Vista de Contacto
   ================================================== */

// Iniciar captura de contenido
ob_start();
?>

<!-- Hero About Section -->
<section class="hero-about d-flex align-items-center position-relative mb-lg-5">
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


<div class="container">

  <h1 class="text-center"><?= htmlspecialchars($title) ?></h1>

  <div class="row">
    <div class="col-3"></div>

    <section class="col-6">
      <?php if (isset($success)): ?>
        <div class="alert alert-success">
          <?= htmlspecialchars($success) ?>
        </div>
      <?php endif; ?>

      <?php if (isset($error)): ?>
        <div class="alert alert-error">
          <?= htmlspecialchars($error) ?>
        </div>
      <?php endif; ?>
    </section>

    <div class="col-3"></div>
  </div>

  <div class="row">
    <div class="col-3"></div>

    <form method="POST" action="/home/contact" class="col-6">
      <div class="mb-3">
        <label class="form-label" for="name">Nombre:</label>
        <input
          type="text"
          id="name"
          name="name"
          value="<?= htmlspecialchars($old_data['name'] ?? '') ?>"
          required
          class="form-control"
        >
      </div>

      <div class="mb-3">
        <label class="form-label" for="email">Email:</label>
        <input
          type="email"
          id="email"
          name="email"
          value="<?= htmlspecialchars($old_data['email'] ?? '') ?>"
          required
          class="form-control"
        >
      </div>

      <div class="mb-3">
        <label class="form-label" for="message">Mensaje:</label>
        <textarea
          id="message"
          name="message"
          rows="5"
          required
          class="form-control"
        ><?= htmlspecialchars($old_data['message'] ?? '') ?></textarea>
      </div>

      <div class="d-grid">
        <button type="submit" class="btn btn-secondary btn-lg">Enviar Mensaje</button>
      </div>
    </form>

    <div class="col-3"></div>
  </div>

</div>

<?php
// Capturar el contenido y pasarlo al layout
$content = ob_get_clean();
require_once RESOURCES_PATH . '/views/layouts/base.php';
?>
