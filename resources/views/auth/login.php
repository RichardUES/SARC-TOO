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

        <form method="POST" action="/auth/login" class="contact-form">
            <div class="form-group">
                <label for="username">Correo o username:</label>
                <input
                        type="text"
                        id="username"
                        name="username"
                        value="<?= htmlspecialchars($old_data['username'] ?? '') ?>"
                        required
                >
            </div>

            <div class="form-group">
                <label for="password">Clave:</label>
                <input
                        type="password"
                        id="password"
                        name="password"
                        value="<?= htmlspecialchars($old_data['password'] ?? '') ?>"
                        required
                >
            </div>

            <button type="submit" class="btn btn-primary">Iniciar sesión</button>
        </form>
    </div>
</div>

<?php
// pasarle el contenido al base.php
$content = ob_get_clean();
require_once RESOURCES_PATH . '/views/layouts/base.php';

?>
