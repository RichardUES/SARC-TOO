<?php
/* ==================================================
   resources/views/profile/profile.php - Vista de Perfil
   ================================================== */

// Iniciar captura de contenido
ob_start();

use App\Models\enums\RolType;

if (
  !isset($_SESSION["autorizado"])
  && $_SESSION["autorizado"]->rolID !== RolType::CLIENT->value
) {
  header("Location: errors/unauthorized");
}
?>

<!-- Banner de bienvenida -->
<section class="bg-primary text-white py-5 mb-4">

  <article class="container">
    <div class="row align-items-center">
      <div class="col-md-8">
        <h1 class="display-4 text-secondary">
          Bienvenido/a, <?php
          
            $name = 'Usuario';

            if ( isset($_SESSION["cliente"]) ){
              $name = $_SESSION["cliente"]->getShortFullName() ;
            } else {
              $name = $_SESSION["autorizado"]->username;
            }

            echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
          ?>
        </h1>
        <p class="lead mb-0 text-secondary">Panel de Control de Usuario</p>
      </div>
    </div>
  </article>

</section>

<!-- Todos inician con esto, en el footer terminan y cierro -->
<section class="container">
  <div class="row">