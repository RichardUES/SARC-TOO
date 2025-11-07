<?php

// Iniciar con el contenido
ob_start();

?>

<div class="contact-section">
  <div class="container">

    <h1 class="display-3 text-center mt-5"><?= htmlspecialchars("Inicio de sesión") ?></h1>

    <?php if (isset($_SESSION['Error'])): ?>

      <div class="row">
        <div class="col-4"></div>
        <div class="alert alert-danger alert-dismissible fade show col-4">
          <strong> <?= $_SESSION['Error'] ?> </strong>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          <?php deleteSession('Error'); ?>
        </div>
        <div class="col-4"></div>
      </div>

    <?php endif; ?>


    <section class="row">
      <div class="col-4"></div>
      <div class="col-4">
        <form method="POST" action="/auth/signin" class="contact-form">
          <div class="form-group">
            <label for="username">Correo o username:</label>
            <input
              type="text"
              id="username"
              name="userOrEmail"
              value="<?= htmlspecialchars($old_data['username'] ?? '') ?>"
              required
              class="form-control">
          </div>

          <div class="form-group">
            <label for="password">Clave:</label>
            <input
              type="password"
              id="password"
              name="txtPassword"
              value="<?= htmlspecialchars($old_data['password'] ?? '') ?>"
              required
              class="form-control">
          </div>

          <div class="d-grid mt-4">
            <button type="submit" class="btn btn-lg btn-primary">Iniciar sesión</button>
          </div>
        </form>
      </div>
      <div class="col-4"></div>
    </section>

  </div>
</div>

<?php
// pasarle el contenido al base.php
$content = ob_get_clean();
require_once RESOURCES_PATH . '/views/layouts/base.php';

?>