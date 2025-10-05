<?php
/* ==================================================
   resources/views/about.php - Vista del About Us
   ================================================== */

// Iniciar captura de contenido
ob_start();
?>

  <!-- Hero About Section -->
  <section class="hero-about d-flex align-items-center position-relative">
    <div class="container index-up">
      <div class="row align-items-center">
        <div class="col-lg-6">
          <h1 class="display-2 fw-bold text-white mb-4">
            <span class="neon-glow">Sobre Nosotros</span>
          </h1>
          <p class="lead text-primary fs-3 mb-4">
            <em>"Hacemos que el mundo brille."</em>
          </p>
          <p class="fs-5 text-light">
            Desde 2008, Luz El Faro ha sido el faro que guía el desarrollo
            energético de El Salvador, conectando sueños y alimentando el progreso
            de nuestro país con energía confiable y eficiente.
          </p>
        </div>
        <div class="col-lg-6 text-center">
          <div class="floating-slow">
            <img src="<?= VIRTUAL_PATH ?>/assets/images/about-banner.svg"
                 alt="Historia Luz El Faro" class="img-fluid rounded-4 neon-border" width="400">
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Historia Section -->
  <section class="py-5 bg-light">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="display-4 fw-bold text-secondary">
          <i class="bi bi-clock-history me-3"></i>
          Nuestra Historia
        </h2>
        <p class="lead text-muted">Un viaje de más de 15 años iluminando El Salvador</p>
      </div>

      <div class="row g-4">
        <div class="col-lg-6">
          <div class="timeline-item p-4 rounded-4 mb-4">
            <div class="d-flex align-items-center mb-3">
              <div class="bg-primary rounded-circle p-3 me-3">
                <i class="bi bi-flag-fill text-secondary fs-4"></i>
              </div>
              <div>
                <h5 class="text-white mb-1">2008 - Los Inicios</h5>
                <small class="text-primary">Fundación de la empresa</small>
              </div>
            </div>
            <p class="text-light">
              Luz El Faro nace como una empresa familiar con la visión de llevar
              energía eléctrica confiable a las comunidades más necesitadas de El Salvador.
            </p>
          </div>

          <div class="timeline-item p-4 rounded-4 mb-4">
            <div class="d-flex align-items-center mb-3">
              <div class="bg-primary rounded-circle p-3 me-3">
                <i class="bi bi-graph-up-arrow text-secondary fs-4"></i>
              </div>
              <div>
                <h5 class="text-white mb-1">2012 - Expansión Nacional</h5>
                <small class="text-primary">Crecimiento acelerado</small>
              </div>
            </div>
            <p class="text-light">
              Alcanzamos los 10,000 clientes y expandimos nuestros servicios
              a 8 departamentos del país, consolidándonos como una opción confiable.
            </p>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="timeline-item p-4 rounded-4 mb-4">
            <div class="d-flex align-items-center mb-3">
              <div class="bg-primary rounded-circle p-3 me-3">
                <i class="bi bi-award-fill text-secondary fs-4"></i>
              </div>
              <div>
                <h5 class="text-white mb-1">2018 - Reconocimiento</h5>
                <small class="text-primary">Líderes del sector</small>
              </div>
            </div>
            <p class="text-light">
              Recibimos el premio "Empresa del Año" por nuestra contribución
              al desarrollo energético y servicio al cliente excepcional.
            </p>
          </div>

          <div class="timeline-item p-4 rounded-4">
            <div class="d-flex align-items-center mb-3">
              <div class="bg-primary rounded-circle p-3 me-3">
                <i class="bi bi-lightning-charge-fill text-secondary fs-4"></i>
              </div>
              <div>
                <h5 class="text-white mb-1">2024 - Innovación</h5>
                <small class="text-primary">Tecnología de punta</small>
              </div>
            </div>
            <p class="text-light">
              Implementamos sistemas inteligentes de distribución y alcanzamos
              más de 85,000 clientes en todo el territorio nacional.
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Misión, Visión, Valores Section -->
  <section class="values-section py-5">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="display-4 fw-bold text-white">
          <span class="neon-glow">Nuestros Pilares</span>
        </h2>
        <p class="lead text-light">Los valores que nos guían cada día</p>
      </div>

      <div class="row g-4 mb-5">
        <div class="col-lg-4">
          <div class="text-center p-4">
            <div class="value-icon pulse">
              <i class="bi bi-eye-fill"></i>
            </div>
            <h4 class="text-white mb-3">Nuestra Visión</h4>
            <p class="text-light">
              Ser la empresa líder en suministro eléctrico de Centroamérica,
              reconocida por nuestra excelencia, innovación y compromiso
              con el desarrollo sostenible de nuestras comunidades.
            </p>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="text-center p-4">
            <div class="value-icon pulse">
              <i class="bi bi-bullseye"></i>
            </div>
            <h4 class="text-white mb-3">Nuestra Misión</h4>
            <p class="text-light">
              Proporcionar energía eléctrica confiable y eficiente a hogares
              y empresas de El Salvador, contribuyendo al crecimiento
              económico y mejorando la calidad de vida de nuestros compatriotas.
            </p>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="text-center p-4">
            <div class="value-icon pulse">
              <i class="bi bi-heart-fill"></i>
            </div>
            <h4 class="text-white mb-3">Nuestros Valores</h4>
            <ul class="list-unstyled text-light">
              <li><i class="bi bi-check-circle-fill text-primary me-2"></i>Excelencia en el servicio</li>
              <li><i class="bi bi-check-circle-fill text-primary me-2"></i>Responsabilidad social</li>
              <li><i class="bi bi-check-circle-fill text-primary me-2"></i>Innovación constante</li>
              <li><i class="bi bi-check-circle-fill text-primary me-2"></i>Integridad y transparencia</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Equipo Section -->
  <section class="py-5 bg-light">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="display-4 fw-bold text-secondary">
          <i class="bi bi-people-fill me-3"></i>
          Nuestro Equipo
        </h2>
        <p class="lead text-muted">Profesionales comprometidos con la excelencia</p>
      </div>

      <div class="row g-4">
        <div class="col-lg-4 col-md-6">
          <div class="team-card p-4 rounded-4 text-center h-100">
            <img src="https://via.placeholder.com/120x120/B1F7FD/1F2E64?text=CEO"
                 alt="CEO" class="rounded-circle mb-3 neon-border">
            <h5 class="text-white">Ing. Roberto Martínez</h5>
            <p class="text-primary mb-3">Director General</p>
            <p class="text-light small">
              Con más de 20 años de experiencia en el sector energético,
              Roberto lidera nuestra visión de transformar el panorama
              eléctrico de El Salvador.
            </p>
            <div class="d-flex justify-content-center gap-2">
              <a href="#" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-linkedin"></i>
              </a>
              <a href="#" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-envelope-fill"></i>
              </a>
            </div>
          </div>
        </div>

        <div class="col-lg-4 col-md-6">
          <div class="team-card p-4 rounded-4 text-center h-100">
            <img src="https://via.placeholder.com/120x120/B1F7FD/1F2E64?text=CTO"
                 alt="CTO" class="rounded-circle mb-3 neon-border">
            <h5 class="text-white">Ing. Carmen Rodríguez</h5>
            <p class="text-primary mb-3">Directora Técnica</p>
            <p class="text-light small">
              Especialista en sistemas eléctricos y energías renovables.
              Carmen supervisa todas nuestras operaciones técnicas
              y proyectos de innovación.
            </p>
            <div class="d-flex justify-content-center gap-2">
              <a href="#" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-linkedin"></i>
              </a>
              <a href="#" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-envelope-fill"></i>
              </a>
            </div>
          </div>
        </div>

        <div class="col-lg-4 col-md-6">
          <div class="team-card p-4 rounded-4 text-center h-100">
            <img src="https://via.placeholder.com/120x120/B1F7FD/1F2E64?text=CCO"
                 alt="CCO" class="rounded-circle mb-3 neon-border">
            <h5 class="text-white">Lic. Miguel Herrera</h5>
            <p class="text-primary mb-3">Director Comercial</p>
            <p class="text-light small">
              Experto en atención al cliente y desarrollo comercial.
              Miguel se asegura de que cada cliente reciba el mejor
              servicio posible.
            </p>
            <div class="d-flex justify-content-center gap-2">
              <a href="#" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-linkedin"></i>
              </a>
              <a href="#" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-envelope-fill"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Certificaciones y Logros Section -->
  <section class="values-section py-5">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="display-4 fw-bold text-white">
          <i class="bi bi-trophy-fill me-3 neon-glow"></i>
          Certificaciones y Logros
        </h2>
        <p class="lead text-light">Reconocimientos que avalan nuestra calidad</p>
      </div>

      <div class="row g-4">
        <div class="col-lg-3 col-md-6">
          <div class="text-center p-4">
            <div class="value-icon">
              <i class="bi bi-patch-check-fill"></i>
            </div>
            <h5 class="text-white">ISO 9001:2015</h5>
            <p class="text-light small">Certificación de Calidad Internacional</p>
          </div>
        </div>

        <div class="col-lg-3 col-md-6">
          <div class="text-center p-4">
            <div class="value-icon">
              <i class="bi bi-shield-check"></i>
            </div>
            <h5 class="text-white">SIGET</h5>
            <p class="text-light small">Autorizado por la Superintendencia General de Electricidad</p>
          </div>
        </div>

        <div class="col-lg-3 col-md-6">
          <div class="text-center p-4">
            <div class="value-icon">
              <i class="bi bi-star-fill"></i>
            </div>
            <h5 class="text-white">Empresa del Año</h5>
            <p class="text-light small">Premio Cámara de Comercio 2018-2023</p>
          </div>
        </div>

        <div class="col-lg-3 col-md-6">
          <div class="text-center p-4">
            <div class="value-icon">
              <i class="bi bi-tree-fill"></i>
            </div>
            <h5 class="text-white">Carbono Neutral</h5>
            <p class="text-light small">Compromiso con el Medio Ambiente</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Datos Interesantes Section -->
  <section class="py-5 bg-light">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="display-4 fw-bold text-secondary">
          <i class="bi bi-lightbulb-fill me-3"></i>
          Datos Interesantes
        </h2>
        <p class="lead text-muted">Curiosidades sobre Luz El Faro</p>
      </div>

      <div class="row g-4">
        <div class="col-lg-6">
          <div class="d-flex align-items-center p-4 bg-white rounded-4 shadow-sm mb-3">
            <div class="bg-primary rounded-circle p-3 me-4">
              <i class="bi bi-lightning-charge-fill text-white fs-3"></i>
            </div>
            <div>
              <h5 class="text-secondary mb-1">1.2 Billones de kWh</h5>
              <p class="text-muted mb-0">Energía suministrada desde nuestra fundación</p>
            </div>
          </div>

          <div class="d-flex align-items-center p-4 bg-white rounded-4 shadow-sm mb-3">
            <div class="bg-success rounded-circle p-3 me-4">
              <i class="bi bi-geo-alt-fill text-white fs-3"></i>
            </div>
            <div>
              <h5 class="text-secondary mb-1">14 Departamentos</h5>
              <p class="text-muted mb-0">Presencia en todo El Salvador</p>
            </div>
          </div>

          <div class="d-flex align-items-center p-4 bg-white rounded-4 shadow-sm">
            <div class="bg-warning rounded-circle p-3 me-4">
              <i class="bi bi-people-fill text-white fs-3"></i>
            </div>
            <div>
              <h5 class="text-secondary mb-1">500+ Empleados</h5>
              <p class="text-muted mb-0">Generando empleo de calidad</p>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="d-flex align-items-center p-4 bg-white rounded-4 shadow-sm mb-3">
            <div class="bg-info rounded-circle p-3 me-4">
              <i class="bi bi-clock-fill text-white fs-3"></i>
            </div>
            <div>
              <h5 class="text-secondary mb-1">24/7</h5>
              <p class="text-muted mb-0">Servicio de atención al cliente</p>
            </div>
          </div>

          <div class="d-flex align-items-center p-4 bg-white rounded-4 shadow-sm mb-3">
            <div class="bg-danger rounded-circle p-3 me-4">
              <i class="bi bi-graph-up text-white fs-3"></i>
            </div>
            <div>
              <h5 class="text-secondary mb-1">15% Anual</h5>
              <p class="text-muted mb-0">Crecimiento promedio de clientes</p>
            </div>
          </div>

          <div class="d-flex align-items-center p-4 bg-white rounded-4 shadow-sm">
            <div class="bg-secondary rounded-circle p-3 me-4">
              <i class="bi bi-award-fill text-white fs-3"></i>
            </div>
            <div>
              <h5 class="text-secondary mb-1">98% Satisfacción</h5>
              <p class="text-muted mb-0">Índice de satisfacción del cliente</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="hero-about py-5">
    <div class="container text-center">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <h2 class="display-4 fw-bold text-white mb-4">
            <span class="neon-glow">Únete a Nuestra Historia</span>
          </h2>
          <p class="lead text-light mb-5">
            Forma parte de la familia Luz El Faro y descubre por qué somos
            la opción preferida de miles de salvadoreños. Tu futuro brillante
            comienza con nosotros.
          </p>
          <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="/home/contact" class="btn btn-primary btn-lg neon-border pulse">
              <i class="bi bi-telephone-fill me-2"></i>
              Contáctanos Hoy
            </a>
            <a href="/" class="btn btn-outline-light btn-lg">
              <i class="bi bi-house-fill me-2"></i>
              Volver al Inicio
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

<?php
// Capturar el contenido y pasarlo al layout
$content = ob_get_clean();
require_once RESOURCES_PATH . '/views/layouts/base.php';
?>