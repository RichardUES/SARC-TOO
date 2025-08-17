<?php

// Iniciar con el contenido
ob_start();

?>

<div class="contact-section">
    <div class="container">
        <h1><?= htmlspecialchars("Inicio de sesión") ?></h1>

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

        <form method="POST" action="/home/contact" class="contact-form">
            <div class="form-group">
                <label for="name">Nombre:</label>
                <input
                        type="text"
                        id="name"
                        name="name"
                        value="<?= htmlspecialchars($old_data['name'] ?? '') ?>"
                        required
                >
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input
                        type="email"
                        id="email"
                        name="email"
                        value="<?= htmlspecialchars($old_data['email'] ?? '') ?>"
                        required
                >
            </div>

            <button type="submit" class="btn btn-primary">Enviar Mensaje</button>
        </form>
    </div>
</div>

<?php
// pasarle el contenido al base.php
$content = ob_get_clean();
require_once RESOURCES_PATH . '/views/layouts/base.php';

?>
