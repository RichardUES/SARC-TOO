<?php
/* ==================================================
   resources/views/home/contact.php - Vista de Contacto
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

<h1>Bienvenido a tu perfil <?= $_SESSION["autorizado"]->username ?> </h1>

<?php
// Capturar el contenido y pasarlo al layout
$content = ob_get_clean();
require_once RESOURCES_PATH . '/views/layouts/base.php';
?>

