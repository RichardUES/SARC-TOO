<?php
// Función para determinar si un menú está activo
function isActiveMenu($path) {
    $currentPath = $_SERVER['REQUEST_URI'] ?? '';
    return str_contains($currentPath, $path) ? 'menu__link--active' : '';
}

// Función para determinar si el submenu debe estar abierto
function shouldShowSubmenu() {
    $currentPath = $_SERVER['REQUEST_URI'] ?? '';
    $adminPaths = ['/dashboard/gestion_usuarios', '/dashboard/gestion_agencias', '/dashboard/gestion_areas'];
    
    foreach ($adminPaths as $path) {
        if (str_contains($currentPath, $path)) {
            return true;
        }
    }
    return false;
}

$showAdminSubmenu = shouldShowSubmenu();
?>

<aside class="sidebar">

  <section class="brand" id="brand-collapse">
    <img src="<?= VIRTUAL_PATH ?>/assets/brand/favicon-logo-light.svg" alt="Logo en Dashboard">
    <h2 class="mx-2 mb-0">Sistema SARC</h2>
  </section>

  <section class="sidebar_nav">
    <nav class="sidebar_menu">
      <ul class="menu">

        <li class="menu__item">
          <a href="/dashboard/" class="menu__link <?= isActiveMenu('/dashboard/main') ?>">
            <i class="bi bi-graph-up-arrow"></i>
            <span class="text">Dashboard</span>
          </a>
        </li>

        <li class="menu__item">
          <a href="/dashboard/registro_usuarios" class="menu__link <?= isActiveMenu('/dashboard/registro_usuarios') ?>">
            <i class="bi bi-person-add"></i>
            <span class="text">Registro Clientes</span>
          </a>
        </li>

        <li class="menu__item">
          <!-- Los tickets asignados al Usuario con Rol de Agente -->
          <a href="/dashboard/mis_tickets/1" class="menu__link <?= isActiveMenu('/dashboard/mis_tickets') ?>">
            <i class="bi bi-ticket"></i>
            <span class="text">Mis Tickets <span class="badge bg-danger mx-3">5</span></span>
          </a>
        </li>

        <li class="menu__item">
          <a href="/dashboard/tickets" class="menu__link <?= isActiveMenu('/dashboard/tickets') ?>">
            <i class="bi bi-list-ul"></i>
            <span class="text">Consultar Tickets <span class="badge bg-danger mx-3">5</span></span>
          </a>
        </li>

        <li class="menu__item">
          <a href="/dashboard/cola_tickets" class="menu__link <?= isActiveMenu('/dashboard/cola_tickets') ?>">
            <i class="bi bi-stack"></i>
            <span class="text">Bitácora de Tickets</span>
          </a>
        </li>

        <li class="menu__item">
          <a href="/dashboard/reporteria" class="menu__link <?= isActiveMenu('/dashboard/reporteria') ?>">
            <i class="bi bi-file-earmark-bar-graph"></i>
            <span class="text">Generar reportes</span>
          </a>
        </li>

        <!-- Submenu Administracion -->
        <li class="menu__item">
          <a class="menu__link d-flex align-items-center <?= $showAdminSubmenu ? 'menu__link--active' : '' ?>" 
             data-bs-toggle="collapse" 
             href="#adminCollapse" 
             role="button" 
             aria-expanded="<?= $showAdminSubmenu ? 'true' : 'false' ?>" 
             aria-controls="adminCollapse">
            <i class="bi bi-gear"></i>
            <span class="text">Administración TI</span>
            <i class="bi bi-chevron-down ms-auto"></i>
          </a>

          <div class="collapse <?= $showAdminSubmenu ? 'show' : '' ?>" id="adminCollapse">
            <ul class="list-unstyled ps-3 mb-0">

              <li class="sidebar-item">
                <a href="/dashboard/gestion_usuarios" class="menu__link <?= isActiveMenu('/dashboard/gestion_usuarios') ?>">
                  <i class="bi bi-person-gear"></i>
                  <span class="text">Gestión de Usuarios</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a href="/dashboard/gestion_agencias" class="menu__link <?= isActiveMenu('/dashboard/gestion_agencias') ?>">
                  <i class="bi bi-building-gear"></i>
                  <span class="text">Gestión de Agencias</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a href="/dashboard/gestion_areas" class="menu__link <?= isActiveMenu('/dashboard/gestion_areas') ?>">
                  <i class="bi bi-columns-gap"></i>
                  <span class="text">Gestión de Áreas</span>
                </a>
              </li>
            </ul>
          </div>
        </li>

      </ul>
    </nav>
  </section>

  <footer class="footer" id="footer-collapse">
    <i class="bi bi-chevron-double-right"></i>
    <span>Colapsar menú</span>
  </footer>
</aside>