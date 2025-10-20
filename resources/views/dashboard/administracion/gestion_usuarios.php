<?php require_once VIEWS_PATH . "/dashboard/layouts/head.php" ?>

<?php require_once VIEWS_PATH . "/dashboard/layouts/header.php" ?>


<article class="usuarios">

  <h2>Gestión de Usuarios</h2>

  <?php if (isset($_SESSION["autorizado"])) : ?>
    <p>Estas autorizado <?= $_SESSION["autorizado"] ?> </p>
  <?php endif; ?>

</article>


<?php require_once VIEWS_PATH . "/dashboard/layouts/sidebar.php"; ?>

<?php require_once VIEWS_PATH . "/dashboard/layouts/footer.php" ?>