<?php
/* ==================================================
   resources/views/layouts/base.php - Layout base
   ================================================== */
?>
<!DOCTYPE html>
<html lang="es">

<?php require_once __DIR__ . "/head.php" ?>

<body>

<?php require_once __DIR__ . "/header.php" ?>

<main class="main-content">
  <!-- Aquí va el contenido específico de cada página -->
  <?= $content ?? '' ?>
</main>

<?php require_once __DIR__ . "/footer.php" ?>

</body>
</html>
