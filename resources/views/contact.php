<?php
/* ==================================================
   resources/views/home/contact.php - Vista de Contacto
   ================================================== */

// Iniciar captura de contenido
ob_start();
?>

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
