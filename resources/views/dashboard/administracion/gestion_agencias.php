<?php require_once VIEWS_PATH . "/dashboard/layouts/head.php" ?>

<?php require_once VIEWS_PATH . "/dashboard/layouts/header.php" ?>


<section class="main container">

  <h2>Gestión de Agencias</h2>

  <?php if (isset($_SESSION["autorizado"])) : ?>
    <p>Estas autorizado <?= $_SESSION["autorizado"] ?> </p>
  <?php endif; ?>

  <?php if (isset($data['Error'])): ?>

    <div class="alert alert-danger">
      <strong> <?= $data['Error'] ?> </strong>
    </div>

  <?php endif; ?>

  <?php if (isset($data['Success'])): ?>

    <div class="alert alert-success">
      <strong> <?= $data['Success'] ?> </strong>
    </div>

  <?php endif; ?>

  <div class="row">
    <div class="col-12">

      <article class="card">
        <div class="card-header">
          <h5 class="card-title">Crear agencia</h5>
          <h6 class="card-subtitle text-muted">Formulario para crear agencia</h6>
        </div>

        <section class="card-body">

          <form action="/dashboard/crear_agencia" method="post">
            <div class="mb-3">
              <label class="form-label">Nombre de Agencia</label>
              <input type="text" name="nombre" class="form-control" placeholder="Agencia Central">
            </div>
            <div class="mb-3">
              <label class="form-label">Dirección</label>
              <input type="text" name="direccion" class="form-control" placeholder="Av. España #223">
            </div>
            <div class="mb-3">
              <label class="form-label">Teléfono</label>
              <input type="text" name="telefono" class="form-control" placeholder="22253455">
            </div>

            <!-- TODO: Poder usar check para activar o desactivar la agencioa en caso de update -->
            <!-- <div class="mb-3">
              <label class="form-check">
                <input type="checkbox" class="form-check-input">
                <span class="form-check-label">Activar/Desactivar agencia</span>
              </label>
            </div> -->

            <button type="submit" class="btn btn-primary">Crear agencia</button>
          </form>

        </section>

      </article>

    </div>
  </div>


</section>


<?php require_once VIEWS_PATH . "/dashboard/layouts/sidebar.php"; ?>

<?php require_once VIEWS_PATH . "/dashboard/layouts/footer.php" ?>