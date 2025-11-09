<?php

use App\Models\enums\RolType;

require_once "layouts/head.php";

// Validación de acceso: Solo admin y supervisor
if (
  !isset($_SESSION["autorizado"]) ||
  !in_array($_SESSION["autorizado"]->rolID, [
    RolType::ADMIN->value,
    RolType::SUPERVISOR->value
  ])
) {
  header("Location: http://luzelfaro.com/errors/unauthorized");
  exit;
}

require_once "layouts/header.php";

?>


<article class="areas">

  <h2>Generación de reportes</h2>

  <?php if (isset($_SESSION["autorizado"])) : ?>
    <p>Estas autorizado <?= $_SESSION["autorizado"] ?> </p>
  <?php endif; ?>

</article>


<?php require_once "layouts/sidebar.php"; ?>

<?php require_once "layouts/footer.php" ?>