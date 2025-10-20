<header>
  <nav class="navbar navbar-expand-lg bg-secondary navbar-dark">
    <div class="container">
      <a class="navbar-brand text-primary" href="/">
        <img
          src="<?= VIRTUAL_PATH ?>/assets/brand/favicon-logo-light.svg"
          alt="Logo de luz el faro"
          class="w-25"
        >
        Luz el Faro
      </a>

      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarNav"
        aria-controls="navbarNav"
        aria-expanded="false"
        aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <section class="collapse navbar-collapse justify-content-between" id="navbarNav">

        <div class="navbar-nav">
          <a class="nav-link active" aria-current="page" href="/">Inicio</a>
          <a class="nav-link" href="/home/about">Acerca de</a>
          <a class="nav-link" href="/home/contact">Contacto</a>
        </div>

        <section>
          <a
            href="/auth/login"
            class="btn btn-outline-primary">
            <i class="bi bi-box-arrow-in-right"></i>
            <span>Ingresar</span>
          </a>
          <a
            href="/auth/register"
            class="btn btn-primary">
            <span>Registrarse</span>
            <i class="bi bi-person-plus"></i>
          </a>
        </section>

      </section>

    </div>
  </nav>
</header>