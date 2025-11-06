<?php require_once VIEWS_PATH . "/dashboard/layouts/head.php" ?>

<?php require_once VIEWS_PATH . "/dashboard/layouts/header.php" ?>



<article class="main">

  <h2>Bitácora de tickets</h2>
  <?php if (isset($_SESSION["autorizado"])): ?>
    <p>Estas autorizado <?= $_SESSION["autorizado"]->username ?> </p>
  <?php endif; ?>maquina para pintar

  <div class="container">
    <div class="row">
      <div class="col-md-12 border border-white">
        <table class="table w-100">
          <thead>
            <tr>
              <th scope="col">Código</th>
              <th scope="col">Cliente</th>
              <th scope="col">Agencia</th>
              <th scope="col">Area</th>
              <th scope="col">Estado TKT</th>
              <th scope="col">Asunto</th>
              <th scope="col">Descripcion</th>
              <th scope="col">Creado</th>
              <th scope="col">F. Asignado</th>
              <th scope="col">F. Cerrado</th>
              <th scope="col">Prioridad</th>
              <th scope="col">Origen</th>
              <th scope="col">Estado</th>
            </tr>
          </thead>
          <tbody class="table-group-divider">

            <?php if (isset($data)): ?>
              <?php foreach ($data as $ticket): ?>
                <tr>
                  <td><?= htmlspecialchars($ticket["CODIGO"], ENT_QUOTES, 'UTF-8') ?></td>
                  <td><?= htmlspecialchars($ticket["NOMBRE"], ENT_QUOTES, 'UTF-8') ?></td>
                  <td><?= htmlspecialchars($ticket["AGENCIA"], ENT_QUOTES, 'UTF-8') ?></td>
                  <td><?= htmlspecialchars(isset($ticket["AREA"]), ENT_QUOTES, 'UTF-8') ?></td>
                  <td> <span class="badge bg-warning"><?= htmlspecialchars($ticket["ESTADO"], ENT_QUOTES, 'UTF-8') ?></span> </td>
                  <td><?= htmlspecialchars($ticket["ASUNTO"], ENT_QUOTES, 'UTF-8') ?></td>
                  <td><?= htmlspecialchars($ticket["DESCRIPCION"], ENT_QUOTES, 'UTF-8') ?></td>
                  <td><?= $ticket["FECHA_CREACION"] ?></td>
                  <td><?= isset($ticket["FECHA_ASIGNACION"]) ?></td>
                  <td><?= isset($ticket["FECHA_CIERRE"]) ?></td>
                  <td> <span class="badge bg-danger"><?= htmlspecialchars($ticket["PRIORIDAD"], ENT_QUOTES, 'UTF-8') ?></span> </td>
                  <td><?= htmlspecialchars($ticket["ORIGEN"], ENT_QUOTES, 'UTF-8') ?></td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>


          </tbody>
        </table>
      </div>
    </div>
  </div>

</article>


<?php require_once VIEWS_PATH . "/dashboard/layouts/sidebar.php"; ?>

<?php require_once VIEWS_PATH . "/dashboard/layouts/footer.php" ?>