<?php

if (!isset($_SESSION["autorizado"]))
  header("Location: /errors/401");

// En caso de que no haya creado su perfil de usuario
if (!isset($_SESSION["cliente"]))
  $_SESSION["sin_perfil"] = "No tienes un perfil creado. Por favor, crea tu perfil para poder enviar tickets.";

require_once "layouts/header.php";

?>

<!-- Menu lateral (sidebar) -->
<?php require_once "layouts/sidebar.php" ?>

<?php if (isset($_SESSION["sin_perfil"])): ?>
  <div class="alert alert-warning col-md-9 d-flex justify-content-center align-items-center flex-wrap ">
    <strong class="h4 w-100 text-center">
      <?= $_SESSION["sin_perfil"]; ?>
      <?php deleteSession('sin_perfil'); ?>
    </strong>
    <a href="/profile/personal_info" class="btn btn-outline-info">Crear perfil aquí</a>
  </div>
<?php else: ?>

  <div class="card shadow-sm col-md-9">
    <div class="card-body">
      <h3 class="card-title h5 mb-4">Crear Nuevo Ticket</h3>

      <?php if (isset($_SESSION["Error"])): ?>
        <div class="alert alert-danger">
          <strong> <?= $_SESSION["Error"] ?> </strong>
        </div>
        <?php deleteSession('Error'); ?>
      <?php endif; ?>

      <?php if (isset($_SESSION["success"])): ?>
        <div class="alert alert-success">
          <strong> <?= $_SESSION["success"] ?> </strong>
        </div>
        <?php deleteSession('success'); ?>
      <?php endif; ?>

      <form method="POST" action="/tickets/create" enctype="multipart/form-data">

        <input type="hidden" name="cliente_id" value="<?= $_SESSION["cliente"]->codigo; ?>">
        <input type="hidden" name="agencia_id" value="<?= $_SESSION["autorizado"]->agenciaID; ?>">

        <!-- Asunto -->
        <div class="mb-3">
          <div class="form-floating">
            <input type="text" class="form-control" id="asunto" name="asunto" placeholder="Asunto del ticket"
              required>
            <label for="asunto">Razón del ticket</label>
          </div>
        </div>

        <!-- Descripción -->
        <div class="mb-3">
          <div class="form-floating">
            <textarea class="form-control" id="descripcion" name="descripcion"
              placeholder="Descripción detallada del problema" style="height: 120px" required></textarea>
            <label for="descripcion">Descripción</label>
          </div>
        </div>

        <!-- Prioridad y Área -->
        <!-- <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-floating">
                        <select class="form-select" id="prioridad" name="prioridad" required>
                            <option value="">Seleccione...</option>
                            <option value="LOW">Baja</option>
                            <option value="MEDIUM">Media</option>
                            <option value="HIGH">Alta</option>
                            <option value="URGENT">Urgente</option>
                        </select>
                        <label for="prioridad">Prioridad</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <select class="form-select" id="area" name="area" required>
                            <option value="">Seleccione...</option>
                            Se llenará dinámicamente desde el backend
                        </select>
                        <label for="area">Área</label>
                    </div>
                </div>
            </div> -->


        <!-- Botones -->
        <div class="d-flex justify-content-end gap-2">
          <button type="reset" class="btn btn-light">Limpiar</button>
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-send me-2"></i>Enviar Ticket
          </button>
        </div>
      </form>
    </div>
  </div>

<?php endif; ?>


<?php require_once "layouts/footer.php" ?>