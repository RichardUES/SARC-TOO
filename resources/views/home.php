

<?php
/* ==================================================
   resources/views/home.php - Vista del Home
   ================================================== */

// Iniciar captura de contenido
ob_start();
?>

    <div class="hero-section">
        <div class="container">
            <h1>¡Hola <?= htmlspecialchars($user_name) ?>!</h1>
            <h2><?= htmlspecialchars($title) ?></h2>
            <p>Bienvenido a nuestro sistema de gestión</p>
        </div>
    </div>

    <div class="stats-section">
        <div class="container">
            <h3>Estadísticas del Sistema</h3>
            <div class="stats-grid">
                <div class="stat-card">
                    <h4><?= $stats['total_users'] ?></h4>
                    <p>Usuarios Registrados</p>
                </div>
                <div class="stat-card">
                    <h4><?= $stats['total_products'] ?></h4>
                    <p>Productos</p>
                </div>
                <div class="stat-card">
                    <h4><?= $stats['total_orders'] ?></h4>
                    <p>Órdenes Procesadas</p>
                </div>
            </div>
        </div>
    </div>

    <div class="activities-section">
        <div class="container">
            <h3>Actividades Recientes</h3>
            <ul class="activities-list">
              <?php foreach ($recent_activities as $activity): ?>
                  <li><?= htmlspecialchars($activity) ?></li>
              <?php endforeach; ?>
            </ul>
        </div>
    </div>

<?php
// Capturar el contenido y pasarlo al layout
$content = ob_get_clean();
require_once RESOURCES_PATH . '/views/layouts/base.php';
?>
