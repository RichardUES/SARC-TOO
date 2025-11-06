<?php if (!isset($_SESSION["autorizado"]))
  header("Location: /errors/401"); ?>


<?php

require_once "layouts/header.php";

?>

<!-- Menu lateral (sidebar) -->
<?php require_once "layouts/sidebar.php" ?>



<article class="card shadow-sm col-md-9">

  <div class="card-body">
    <h3 class="card-title h5 mb-4">Información Personal</h3>

    <?php if (isset($_SESSION["Error"])): ?>
      <div class="alert alert-danger">
        <strong>
          <?= $_SESSION["Error"]; ?>
        </strong>
      </div>
      <?php deleteSession('Error'); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION["cliente"])): ?>
      <div class="alert alert-success">
        <strong>
         Tu perfil se a creado exitosamete: <?= $_SESSION["cliente"]->getShortFullName() ?>
        </strong>
      </div>
    <?php endif; ?>

    <form method="POST" action="/profile/update_profile">
      <!-- Primera fila: Nombres -->
      <div class="row mb-3">
        <div class="col-md-6">
          <div class="form-floating">
            <input type="text" class="form-control" id="primer-nombre" name="primer-nombre" placeholder="Primer nombre"
              required>
            <label for="primer-nombre">Primer Nombre</label>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-floating">
            <input type="text" class="form-control" id="segundo-nombre" name="segundo-nombre"
              placeholder="Segundo nombre">
            <label for="segundo-nombre">Segundo Nombre</label>
          </div>
        </div>
      </div>

      <!-- Segunda fila: Apellidos -->
      <div class="row mb-3">
        <div class="col-md-6">
          <div class="form-floating">
            <input type="text" class="form-control" id="primer-apellido" name="primer-apellido"
              placeholder="Primer apellido" required>
            <label for="primer-apellido">Primer Apellido</label>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-floating">
            <input type="text" class="form-control" id="segundo-apellido" name="segundo-apellido"
              placeholder="Segundo apellido">
            <label for="segundo-apellido">Segundo Apellido</label>
          </div>
        </div>
      </div>

      <!-- Tercera fila: Fecha nacimiento y DUI -->
      <div class="row mb-3">
        <div class="col-md-6">
          <div class="form-floating">
            <input type="date" class="form-control" id="fechaNac" name="fechaNac" required>
            <label for="fechaNac">Fecha de Nacimiento</label>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-floating">
            <input type="text" class="form-control" id="dui" name="dui" placeholder="00000000-0"
              pattern="[0-9]{8}-[0-9]{1}" title="Formato: 00000000-0" required>
            <label for="dui">DUI</label>
          </div>
        </div>
      </div>

      <!-- Cuarta fila: Teléfono -->
      <div class="row mb-4">
        <div class="col-md-6">
          <div class="form-floating">
            <input type="tel" class="form-control" id="telefono" name="telefono" placeholder="0000-0000"
              pattern="[0-9]{4}-[0-9]{4}" title="Formato: 0000-0000">
            <label for="telefono">Teléfono</label>
          </div>
        </div>
      </div>

      <!-- Botones -->
      <div class="d-flex justify-content-end gap-2">
        <button type="reset" class="btn btn-light">Cancelar</button>
        <button
          type="submit"
          class="btn btn-primary" 
          <?= isset($_SESSION["cliente"]) ? 'disabled' : '' ?> 
        >
          <i class="bi bi-save me-2"></i>Guardar Cambios
        </button>
      </div>
    </form>
  </div>
</article>

<?php require_once "layouts/footer.php" ?>