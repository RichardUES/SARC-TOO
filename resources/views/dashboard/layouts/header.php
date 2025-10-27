
<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
?>
<header class="navbar navbar-expand-lg navbar-dark bg-secondary shadow-sm px-3 header">

  <ul class="navbar-nav ms-auto align-items-center">
    <!-- Notificaciones -->
    <li class="nav-item dropdown mx-2">
      <a class="nav-link position-relative" href="#" id="alertsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-bell fs-5"></i>
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span>
      </a>
      <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="alertsDropdown">
        <li><h6 class="dropdown-header">Notificaciones</h6></li>
        <li><a class="dropdown-item" href="#">Alerta 1</a></li>
        <li><a class="dropdown-item" href="#">Alerta 2</a></li>
        <li><a class="dropdown-item" href="#">Alerta 3</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item text-center" href="#">Ver todas</a></li>
      </ul>
    </li>

    <!-- Usuario -->
    <?php if (isset($_SESSION["autorizado"]) && is_object($_SESSION["autorizado"])): ?>
      <li class="nav-item dropdown mx-2">
        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-person-circle fs-5 me-1"></i>
          <span class="fw-semibold">
            <?= htmlspecialchars($_SESSION["autorizado"]->username ?? 'Usuario') ?>
          </span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
          <li><a class="dropdown-item" href="/profile">Perfil</a></li>
          <li><a class="dropdown-item" href="/settings">Configuración</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item text-danger" href="/auth/logout"><i class="bi bi-box-arrow-right me-2"></i>Salir</a></li>
        </ul>
      </li>
    <?php else: ?>
      <li class="nav-item mx-2">
        <a class="btn btn-outline-primary" href="/auth/login">
          <i class="bi bi-person-circle"></i> Iniciar sesión
        </a>
      </li>
    <?php endif; ?>

    <!-- Settings (turca) -->
    <li class="nav-item dropdown mx-2">
      <a class="nav-link dropdown-toggle" href="#" id="settingsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-gear fs-5"></i>
      </a>
      <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="settingsDropdown">
        <li><a class="dropdown-item" href="/settings"><i class="bi bi-sliders me-2"></i>Configuración</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item text-danger" href="/auth/logout"><i class="bi bi-box-arrow-right me-2"></i>Salir</a></li>
      </ul>
    </li>
  </ul>
</header>