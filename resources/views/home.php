

<?php
/* ==================================================
   resources/views/home.php - Vista del Home
   ================================================== */

// Iniciar captura de contenido
ob_start();
?>



<?php
// Capturar el contenido y pasarlo al layout
$content = ob_get_clean();
require_once RESOURCES_PATH . '/views/layouts/base.php';
?>
