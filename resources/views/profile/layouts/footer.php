

<!-- Con esto cierro el Row y container, includos en el header -->
</div> <!-- Fin del row -->
</section> <!-- Fin del container -->


<?php
// Capturar el contenido y pasarlo al layout
$content = ob_get_clean();
require_once RESOURCES_PATH . '/views/layouts/base.php';
?>