<?php

require_once "layouts/head.php";

use App\Models\enums\RolType;


if ( !isset( $_SESSION["autorizado"] )
      && ($_SESSION["autorizado"]->rolID !== RolType::ADMIN->value
      || $_SESSION["autorizado"]->rolID !== RolType::SUPERVISOR->value 
      || $_SESSION["autorizado"]->rolID !== RolType::AGENT->value ) 
    ){
      header("Location: errors/unauthorized");
    }


require_once "layouts/header.php" ;

?>


<main class="main">

  <h2>Main <?= $_SESSION["autorizado"]->username ?> </h2>

</main>


<?php require_once "layouts/sidebar.php"; ?>

<?php require_once "layouts/footer.php" ?>