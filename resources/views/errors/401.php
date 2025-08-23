<?php
/* ==================================================
   resources/views/errors/401.php - Página de Error 401
   ================================================== */
// Iniciar captura de contenido
ob_start();
?>

<div class="error-page-container">
  <div class="error-content">
    <div class="error-icon error-401-icon">
      <svg viewBox="0 0 24 24" width="60" height="60">
        <path fill="currentColor" d="M18,8A2,2 0 0,1 20,10V20A2,2 0 0,1 18,22H6A2,2 0 0,1 4,20V10A2,2 0 0,1 6,8H7V6A5,5 0 0,1 12,1A5,5 0 0,1 17,6V8H18M12,3A3,3 0 0,0 9,6V8H15V6A3,3 0 0,0 12,3Z"/>
      </svg>
    </div>
    <div class="error-code" data-text="401">401</div>
    <h1 class="error-title">Acceso Denegado</h1>
    <p class="error-message">
      Lo sentimos, pero no tienes autorización para acceder a esta área.
      Por favor, inicia sesión con las credenciales correctas o contacta al administrador.
    </p>
    <div class="error-actions">
      <a href="/login" class="button button-primary">Iniciar Sesión</a>
      <a href="/" class="button button-secondary">Ir al Inicio</a>
    </div>
  </div>
  <div class="particles-bg"></div>
</div>

<?php
// Capturar el contenido y pasarlo al layout
$content = ob_get_clean();
require_once RESOURCES_PATH . '/views/layouts/base.php';
?>