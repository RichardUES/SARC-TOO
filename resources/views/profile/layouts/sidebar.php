<!-- Menú Lateral -->
    <div class="col-md-3 mb-4">
      <div class="card shadow-sm">
        <div class="card-body p-0">
          <div class="list-group list-group-flush">
            <?php
            // Obtener la ruta solicitada (sin query string)
            $requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

            // Normalizar base si la app está en un subdirectorio (front controller)
            $base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
            if ($base !== '' && strpos($requestPath, $base) === 0) {
              $requestPath = substr($requestPath, strlen($base));
            }

            // Asegurar formato '/segmento/otro'
            $requestPath = '/' . trim($requestPath, '/');

            // Helper para marcar enlace activo (comparación exacta)
            $isActive = function(string $target) use ($requestPath) : string {
              $t = '/' . trim($target, '/');
              return $requestPath === $t ? 'active' : '';
            };

            // Helper para marcar activo por prefijo (útil para secciones)
            $isActivePrefix = function(string $prefix) use ($requestPath) : string {
              $p = '/' . trim($prefix, '/');
              return strpos($requestPath, $p) === 0 ? 'active' : '';
            };
            ?>

            <a href="/profile" class="list-group-item list-group-item-action <?php echo $isActive('/profile/') ?: $isActivePrefix('/profile/'); ?>">
              <i class="bi bi-speedometer2 me-2"></i>Panel Principal
            </a>

            <a href="/profile/personal_info" class="list-group-item list-group-item-action <?php echo $isActive('/profile/personal_info'); ?>">
              <i class="bi bi-person me-2"></i>Información Personal
            </a>

            <a href="/profile/create_ticket" class="list-group-item list-group-item-action <?php echo $isActive('/profile/create_ticket'); ?>">
              <i class="bi bi-ticket-detailed me-2"></i>Crear Ticket
            </a>

            <a href="/profile/ticket_history" class="list-group-item list-group-item-action <?php echo $isActive('/profile/ticket_history'); ?>">
              <i class="bi bi-clock-history me-2"></i>Historial de Tickets
            </a>

            <a href="/profile/notifications" class="list-group-item list-group-item-action <?php echo $isActive('/profile/notifications'); ?>">
              <i class="bi bi-bell me-2"></i>Notificaciones
              <span class="badge bg-danger rounded-pill float-end" id="notificationsBadge">0</span>
            </a>

            <!-- <a href="/test/testing" class="list-group-item list-group-item-action">
              <i class="bi bi-bell me-2"></i>Pruebas
            </a> -->

            <a href="/profile/settings" class="list-group-item list-group-item-action <?php echo $isActive('/profile/settings'); ?>">
              <i class="bi bi-gear me-2"></i>Configuración
            </a>
          </div>
        </div>
      </div>
    </div>