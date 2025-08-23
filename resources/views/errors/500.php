<?php
/* ==================================================
   resources/views/errors/500.php - Página de Error 500
   ================================================== */
// Iniciar captura de contenido
ob_start();
?>

<div class="error-page-container">
    <div class="error-content">
        <div class="error-icon error-500-icon">
            <svg viewBox="0 0 24 24" width="60" height="60">
                <path fill="currentColor" d="M11,15H13V17H11V15M11,7H13V13H11V7M12,2C6.47,2 2,6.5 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z"/>
            </svg>
        </div>
        <div class="error-code" data-text="500">500</div>
        <h1 class="error-title">Error Interno del Servidor</h1>
        <p class="error-message">
            Algo ha salido mal en nuestros servidores. Nuestro equipo técnico ha sido notificado
            y está trabajando para resolver el problema. Inténtalo de nuevo en unos minutos.
        </p>
        <div class="error-actions">
            <a href="javascript:location.reload()" class="button button-primary">Reintentar</a>
            <a href="/" class="button button-secondary">Volver al Inicio</a>
        </div>
    </div>
    <div class="particles-bg"></div>
</div>

<?php
// Capturar el contenido y pasarlo al layout
$content = ob_get_clean();
require_once RESOURCES_PATH . '/views/layouts/base.php';
?>