<?php
/* ==================================================
   resources/views/home.php - Vista del Home
   ================================================== */

// Iniciar captura de contenido
ob_start();
?>

<!-- Hero Section -->
<section class="hero-section d-flex align-items-center position-relative">
  <div class="container index-up">
    <div class="row align-items-center">
      <div class="col-lg-6">
        <h1 class="display-2 fw-bold text-white mb-4">
          <span class="neon-glow">Luz El Faro</span>
        </h1>
        <p class="lead text-light mb-4 fs-3">
          <em class="text-primary">"Hacemos que el mundo brille."</em>
        </p>
        <p class="fs-5 text-light mb-5">
          Somos la empresa líder en suministro de energía eléctrica en El Salvador.
          Conectamos hogares y empresas con energía confiable y eficiente las 24 horas del día.
        </p>
        <div class="d-flex gap-3 flex-wrap">
          <a href="#servicios" class="btn btn-primary btn-lg neon-border pulse-animation">
            <i class="bi bi-lightning-charge-fill me-2"></i>
            Nuestros Servicios
          </a>
          <a href="#estadisticas" class="btn btn-outline-light btn-lg">
            <i class="bi bi-graph-up-arrow me-2"></i>
            Ver Estadísticas
          </a>
        </div>
      </div>
      <div class="col-lg-6 text-center">
        <div class="floating">
          <img src="<?= VIRTUAL_PATH ?>/assets/images/main-banner.svg"
               alt="Suministro Eléctrico" class="img-fluid rounded-4 neon-border" width="500">
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Servicios Section -->
<section id="servicios" class="py-5 bg-light">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="display-4 fw-bold text-secondary">
        <i class="bi bi-gear-fill me-3"></i>
        Nuestros Servicios
      </h2>
      <p class="lead text-muted">Soluciones integrales en energía eléctrica para todo El Salvador</p>
    </div>

    <div class="row g-4">
      <div class="col-lg-4 col-md-6">
        <div class="service-card p-4 rounded-4 h-100 text-center">
          <div class="mb-4">
            <i class="bi bi-house-fill display-1 text-primary"></i>
          </div>
          <h4 class="text-white mb-3">Suministro Residencial</h4>
          <p class="text-light">
            Energía confiable para tu hogar. Instalación y mantenimiento
            de conexiones eléctricas residenciales con la más alta calidad.
          </p>
          <ul class="list-unstyled text-start text-light">
            <li><i class="bi bi-check-circle-fill text-success me-2"></i>Instalación de medidores</li>
            <li><i class="bi bi-check-circle-fill text-success me-2"></i>Mantenimiento preventivo</li>
            <li><i class="bi bi-check-circle-fill text-success me-2"></i>Soporte técnico 24/7</li>
          </ul>
        </div>
      </div>

      <div class="col-lg-4 col-md-6">
        <div class="service-card p-4 rounded-4 h-100 text-center">
          <div class="mb-4">
            <i class="bi bi-building display-1 text-primary"></i>
          </div>
          <h4 class="text-white mb-3">Suministro Comercial</h4>
          <p class="text-light">
            Soluciones energéticas para empresas y comercios.
            Garantizamos el suministro continuo para tu negocio.
          </p>
          <ul class="list-unstyled text-start text-light">
            <li><i class="bi bi-check-circle-fill text-success me-2"></i>Conexiones de alta capacidad</li>
            <li><i class="bi bi-check-circle-fill text-success me-2"></i>Tarifas preferenciales</li>
            <li><i class="bi bi-check-circle-fill text-success me-2"></i>Asesoría energética</li>
          </ul>
        </div>
      </div>

      <div class="col-lg-4 col-md-6">
        <div class="service-card p-4 rounded-4 h-100 text-center">
          <div class="mb-4">
            <i class="bi bi-tools display-1 text-primary"></i>
          </div>
          <h4 class="text-white mb-3">Mantenimiento</h4>
          <p class="text-light">
            Servicios de mantenimiento preventivo y correctivo
            para garantizar el funcionamiento óptimo de tu instalación.
          </p>
          <ul class="list-unstyled text-start text-light">
            <li><i class="bi bi-check-circle-fill text-success me-2"></i>Inspecciones programadas</li>
            <li><i class="bi bi-check-circle-fill text-success me-2"></i>Reparaciones urgentes</li>
            <li><i class="bi bi-check-circle-fill text-success me-2"></i>Certificaciones técnicas</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Estadísticas Section -->
<section id="estadisticas" class="stats-section py-5">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="display-4 fw-bold text-white">
        <i class="bi bi-graph-up me-3 neon-glow"></i>
        Números que nos Respaldan
      </h2>
      <p class="lead text-light">Más de una década iluminando El Salvador</p>
    </div>

    <div class="row text-center">
      <div class="col-lg-3 col-md-6 mb-4">
        <div class="p-4">
          <div class="stat-number neon-glow">85K+</div>
          <h5 class="text-white">Hogares Conectados</h5>
          <p class="text-light">Familias salvadoreñas confían en nosotros</p>
        </div>
      </div>

      <div class="col-lg-3 col-md-6 mb-4">
        <div class="p-4">
          <div class="stat-number neon-glow">2.5K+</div>
          <h5 class="text-white">Empresas Atendidas</h5>
          <p class="text-light">Negocios que impulsan el país</p>
        </div>
      </div>

      <div class="col-lg-3 col-md-6 mb-4">
        <div class="p-4">
          <div class="stat-number neon-glow">99.8%</div>
          <h5 class="text-white">Confiabilidad</h5>
          <p class="text-light">Disponibilidad del servicio anual</p>
        </div>
      </div>

      <div class="col-lg-3 col-md-6 mb-4">
        <div class="p-4">
          <div class="stat-number neon-glow">15+</div>
          <h5 class="text-white">Años de Experiencia</h5>
          <p class="text-light">Liderando el sector energético</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Testimonios Section -->
<section class="py-5 bg-light">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="display-4 fw-bold text-secondary">
        <i class="bi bi-chat-quote-fill me-3"></i>
        Lo que Dicen Nuestros Clientes
      </h2>
      <p class="lead text-muted">Testimonios reales de quienes confían en Luz El Faro</p>
    </div>

    <div class="row g-4">
      <div class="col-lg-4">
        <div class="testimonial-card p-4 h-100">
          <div class="d-flex align-items-center mb-3">
            <img src="https://via.placeholder.com/60x60/B1F7FD/1F2E64?text=M"
                 alt="Cliente" class="rounded-circle me-3">
            <div>
              <h6 class="text-white mb-1">María González</h6>
              <small class="text-primary">Propietaria de Hogar</small>
            </div>
          </div>
          <p class="text-light">
            "Desde que Luz El Faro instaló nuestro servicio, nunca hemos tenido problemas.
            El servicio es excelente y el personal muy profesional."
          </p>
          <div class="text-warning">
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="testimonial-card p-4 h-100">
          <div class="d-flex align-items-center mb-3">
            <img src="https://via.placeholder.com/60x60/B1F7FD/1F2E64?text=C"
                 alt="Cliente" class="rounded-circle me-3">
            <div>
              <h6 class="text-white mb-1">Carlos Mendoza</h6>
              <small class="text-primary">Gerente de Fábrica</small>
            </div>
          </div>
          <p class="text-light">
            "Nuestra fábrica necesita energía las 24 horas. Luz El Faro nos ha dado
            la confiabilidad que necesitamos para mantener nuestra producción."
          </p>
          <div class="text-warning">
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="testimonial-card p-4 h-100">
          <div class="d-flex align-items-center mb-3">
            <img src="https://via.placeholder.com/60x60/B1F7FD/1F2E64?text=A"
                 alt="Cliente" class="rounded-circle me-3">
            <div>
              <h6 class="text-white mb-1">Ana Rodríguez</h6>
              <small class="text-primary">Dueña de Restaurante</small>
            </div>
          </div>
          <p class="text-light">
            "El soporte técnico es increíble. Cuando tuvimos una emergencia,
            llegaron en menos de 30 minutos y solucionaron todo rápidamente."
          </p>
          <div class="text-warning">
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
            <i class="bi bi-star-fill"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CTA Final Section -->
<section class="hero-section py-5">
  <div class="container text-center">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <h2 class="display-4 fw-bold text-white mb-4">
          <span class="neon-glow">¿Listo para Brillar?</span>
        </h2>
        <p class="lead text-light mb-5">
          Únete a miles de salvadoreños que ya confían en Luz El Faro para
          iluminar sus hogares y negocios. Obtén tu conexión hoy mismo.
        </p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
          <a href="/home/contact" class="btn btn-primary btn-lg neon-border pulse-animation">
            <i class="bi bi-telephone-fill me-2"></i>
            Solicitar Conexión
          </a>
          <a href="/about" class="btn btn-outline-light btn-lg">
            <i class="bi bi-info-circle-fill me-2"></i>
            Conocer Más
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
