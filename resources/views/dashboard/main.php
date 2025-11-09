<?php

require_once "layouts/head.php";

use App\Models\enums\RolType;


// Validación de acceso: Solo administradores y supervisores
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


require_once "layouts/header.php" ;

?>


<main class="main">

  <h2>Main <?= $_SESSION["autorizado"]->username ?> </h2>

</main>


<?php require_once "layouts/sidebar.php"; ?>

<?php require_once "layouts/footer.php" ?>