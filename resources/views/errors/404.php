<?php
/* ==================================================
   resources/views/errors/404.php - Página de Error 404
   ================================================== */

// Iniciar captura de contenido
ob_start();
?>

<div class="error-page-container">
  <div class="error-content">
    <div class="error-icon error-404-icon">
      <svg viewBox="0 0 24 24" width="60" height="60">
        <path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
      </svg>
    </div>
    <div class="error-code" data-text="404">404</div>
    <h1 class="error-title">¡Oops! Página no encontrada</h1>
    <p class="error-message">
      La página que buscas parece haber desaparecido en el ciberespacio.
      Puede que haya sido movida, eliminada o simplemente esté jugando al escondite.
    </p>
    <div class="error-actions">
      <a href="/" class="btn btn-primary">Volver al Inicio</a>
      <a href="javascript:history.back()" class="btn btn-secondary">Ir Atrás</a>
    </div>
  </div>
  <div class="particles-bg"></div>
</div>

<?php
// Capturar el contenido y pasarlo al layout
$content = ob_get_clean();
require_once RESOURCES_PATH . '/views/layouts/base.php';
?>