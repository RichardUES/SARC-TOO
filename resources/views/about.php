<?php
/* ==================================================
   resources/views/about.php - Vista About
   ================================================== */

// Iniciar captura de contenido
ob_start();
?>

  <div class="about-section">
    <div class="container">
      <h1><?= htmlspecialchars($title) ?></h1>

      <div class="company-info">
        <h2><?= htmlspecialchars($company_info['name']) ?></h2>
        <p><strong>Fundada:</strong> <?= htmlspecialchars($company_info['founded']) ?></p>
        <p><strong>Empleados:</strong> <?= htmlspecialchars($company_info['employees']) ?></p>
        <p><strong>Misión:</strong> <?= htmlspecialchars($company_info['mission']) ?></p>
      </div>

      <div class="team-section">
        <h3>Nuestro Equipo <i class="bi bi-alarm" style="font-size: 2rem;"></i></h3>
        <p>Contamos con un equipo de profesionales dedicados a brindar las mejores soluciones.</p>
      </div>
    </div>
  </div>

<?php
// Capturar el contenido y pasarlo al layout
$content = ob_get_clean();
require_once RESOURCES_PATH . '/views/layouts/base.php';
?>