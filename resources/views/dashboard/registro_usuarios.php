<?php require_once "layouts/head.php" ?>

<?php require_once "layouts/header.php" ?>


<article class="areas">

  <h2>Registro clientes</h2>

  <?php if (isset($_SESSION["autorizado"])) : ?>
    <p>Estas autorizado <?= $_SESSION["autorizado"] ?> </p>
  <?php endif; ?>

</article>


<?php require_once "layouts/sidebar.php"; ?>

<?php require_once "layouts/footer.php" ?>