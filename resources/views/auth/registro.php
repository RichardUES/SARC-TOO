<?php

// Iniciar con el contenido
ob_start();

?>

<div class="contact-section">
  <div class="container">

    <h1 class="display-3 text-center mt-5"><?= htmlspecialchars("Crea tu cuenta en segundos") ?></h1>

    <?php if (isset($data['Error'])): ?>

      <div class="alert alert-danger">
        <strong> <?= $data['Error'] ?> </strong>
      </div>

    <?php endif; ?>

    <?php if (isset($data['Success'])): ?>

      <div class="alert alert-success">
        <strong> <?= $data['Success'] ?> </strong>
        <strong> <?= $data['username'] ?> </strong>
      </div>

    <?php endif; ?>


    <section class="row">
      <div class="col-3"></div>
      <div class="col-6">
        <form method="POST" action="/auth/userRegister" class="contact-form">

          <div class="form-group">
            <label for="username">Nombre de usuario</label>
            <input
              type="text"
              id="username"
              name="username"
              value="<?= htmlspecialchars($old_data['username'] ?? '') ?>"
              required
              class="form-control">
          </div>

          <div class="form-group">
            <label for="email">Correo electrónico:</label>
            <input
              type="email"
              id="email"
              name="email"
              value="<?= htmlspecialchars($old_data['email'] ?? '') ?>"
              required
              class="form-control">
          </div>

          <div class="row">

            <div class="col">
              <div class="form-group">
                <label for="password">Clave:</label>
                <input
                  type="password"
                  id="password"
                  name="txtPassword"
                  required
                  class="form-control">
              </div>
            </div>

            <div class="col">
              <div class="form-group">
                <label for="password2">Repetir Clave:</label>
                <input
                  type="password"
                  id="password2"
                  name="txtPassword2"
                  required
                  class="form-control">
              </div>
            </div>

          </div>

          <div class="form-group">
            <label for="agencia">Agencia más cercana:</label>
            <select
              id="agencia"
              name="agencia"
              required
              class="form-control">
              <!-- TODO: Realizar for para ccargar las agencias -->
              <option >Seleccionar agencia</option>
              <?php foreach($data["agencias"] as $key => $value ): ?>
                <option value="<?= $value->codigo ?>">
                  <?= $value->nombre ?>
                </option>
                 
              <?php endforeach; ?>

            </select>
          </div>

          <div class="d-grid mt-4">
            <button type="submit" class="btn btn-lg btn-primary">Crear cuenta</button>
          </div>
        </form>
      </div>
      <div class="col-3"></div>
    </section>

  </div>
</div>

<?php
// pasarle el contenido al base.php
$content = ob_get_clean();
require_once RESOURCES_PATH . '/views/layouts/base.php';

?>