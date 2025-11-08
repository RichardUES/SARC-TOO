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

<!-- Banner de bienvenida mejorado -->
<?php
  $now      = new DateTime('now');
  $hour     = (int) $now->format('H');
  $greeting = $hour < 12 ? 'Buenos días' : ($hour < 19 ? 'Buenas tardes' : 'Buenas noches');
  $fecha    = $now->format('d/m/Y');
  $hora     = $now->format('H:i:s');
?>
<section class="bg-info bg-gradient text-white py-5 mb-4 position-relative overflow-hidden">

  <article class="container">
    <div class="row align-items-center">
      <div class="col-md-8">
        <h1 class="display-5 text-secondary mb-2">
          <?= $greeting ?>,
          <?php
            $name = 'Usuario';
            if (isset($_SESSION['cliente'])) {
              $name = $_SESSION['cliente']->getShortFullName();
            } else {
              $name = $_SESSION['autorizado']->username;
            }
            echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
          ?>
        </h1>
        <p class="lead mb-3 text-secondary"> Éste es su panel de control de Tickets </p>

        <div class="d-flex align-items-center gap-3 flex-wrap">
          <span class="badge bg-white bg-opacity-25 text-secondary px-3 py-2">
            <i class="bi bi-calendar-event me-2"></i>
            <span id="liveDate"><?= $fecha ?></span>
          </span>
          <span class="badge bg-white bg-opacity-25 text-secondary px-3 py-2">
            <i class="bi bi-clock-history me-2"></i>
            <span id="liveTime"><?= $hora ?></span>
          </span>
        </div>
      </div>

      <div class="col-md-4 mt-4 mt-md-0">
        <div class="bg-white bg-opacity-10 border border-white border-opacity-25 rounded-4 p-4 text-secondary">
          <div class="d-flex align-items-center">
            <div class="me-3">
              <i class="bi bi-speedometer2 fs-1"></i>
            </div>
            <div>
              <div class="text-uppercase small opacity-75">Fecha y hora</div>
              <div class="fs-4 fw-bold"><span id="liveTime2"><?= $hora ?></span></div>
              <div class="small"><span id="liveDate2"><?= $fecha ?></span></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </article>

  <script>
    (function () {
      const elTime  = document.getElementById('liveTime');
      const elDate  = document.getElementById('liveDate');
      const elTime2 = document.getElementById('liveTime2');
      const elDate2 = document.getElementById('liveDate2');
      const fmtTime = new Intl.DateTimeFormat('es-ES', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false });
      const fmtDate = new Intl.DateTimeFormat('es-ES', { weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' });
      function tick() {
        const now = new Date();
        const t = fmtTime.format(now);
        const d = fmtDate.format(now);
        if (elTime)  elTime.textContent  = t;
        if (elDate)  elDate.textContent  = d;
        if (elTime2) elTime2.textContent = t;
        if (elDate2) elDate2.textContent = d;
      }
      tick();
      setInterval(tick, 1000);
    })();
  </script>

</section>

<!-- Todos inician con esto, en el footer terminan y cierro -->
<section class="container">
  <div class="row">