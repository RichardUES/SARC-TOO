<?php

ob_start();

?>

  <section class="container">

    <form action="/auth/encriptador" class="form d-flex align-items-center flex-column" method="POST">

      <div class="form-group col-6 mt-5 card p-5">
        <h2 class="card-title">Encriptador</h2>
        <label for="in-pass" class="form-label">Escribe la clave a encriptar</label>
        <input type="password" class="form-control" id="in-pass" name="password">
        <div class="d-grid mt-4">
          <button class="btn btn-secondary">Encriptar</button>
        </div>
      </div>

      <?php if (isset($data) && count($data) > 0): ?>
        <div class="alert alert-warning mt-5">
          <p>La Password Encripotada es: <strong><?= $data["pass_encryp"] ?></strong></p>
        </div>
      <?php endif; ?>

    </form>

  </section>


<?php
$content = ob_get_clean();
require RESOURCES_PATH . '/views/layouts/base.php';
?>