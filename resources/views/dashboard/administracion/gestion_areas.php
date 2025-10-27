<?php require_once VIEWS_PATH . "/dashboard/layouts/head.php" ?>

<?php require_once VIEWS_PATH . "/dashboard/layouts/header.php" ?>


<article class="container main">

  <h2>Gestión de Áreas</h2>

  <?php if (isset($_SESSION["autorizado"])) : ?>
    <p>Estas autorizado <?= $_SESSION["autorizado"]->username ?> </p>
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
          <h5 class="card-title">Crear Área</h5>
          <h6 class="card-subtitle text-muted">Formulario para crear áreas</h6>
        </div>

        <section class="card-body">

          <form action="/dashboard/crear_area" method="post">
            <div class="mb-3">
              <label class="form-label">Nombre de área</label>
              <input type="text" name="nombre" class="form-control" placeholder="Contabilidad">
            </div>
            <div class="mb-3">
              <label class="form-label">Descripción</label>
              <input type="text" name="descripcion" class="form-control" placeholder="Área contable">
            </div>

            <!-- TODO: Poder usar check para activar o desactivar la areas en caso de update -->
            <!-- <div class="mb-3">
              <label class="form-check">
                <input type="checkbox" class="form-check-input">
                <span class="form-check-label">Activar/Desactivar agencia</span>
              </label>
            </div> -->

            <button type="submit" class="btn btn-primary">Crear área</button>
          </form>

        </section>

      </article>

    </div>
  </div>

</article>


<?php require_once VIEWS_PATH . "/dashboard/layouts/sidebar.php"; ?>

<?php require_once VIEWS_PATH . "/dashboard/layouts/footer.php" ?>