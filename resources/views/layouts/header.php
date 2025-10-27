
<?php 

use App\Models\enums\RolType;

?>

<header>
  <nav class="navbar navbar-expand-lg bg-secondary navbar-dark">
    <div class="container">
      <a class="navbar-brand text-primary" href="/">
        <img
          src="<?= VIRTUAL_PATH ?>/assets/brand/favicon-logo-light.svg"
          alt="Logo de luz el faro"
          class="w-25">
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

        <?php if ( isset( $_SESSION["autorizado"] ) 
                  && $_SESSION["autorizado"]->rolID === RolType::CLIENT->value ) : 
        ?>

          <!-- En caso de estar logueado -->
          <section>
            <a
              href="/profile"
              class="btn btn-outline-primary">
              <i class="bi bi-person"></i>
              <span> <?= $_SESSION["autorizado"]->username ?> </span>
            </a>
            <a
              href="/auth/logout"
              class="btn btn-danger">
              <span>Salir</span>
              <i class="bi bi-box-arrow-in-left"></i>
            </a>
          </section>

        <?php else : ?>

          <!-- En caso de ser visitante -->
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



        <?php endif; ?>

      </section>

    </div>
  </nav>
</header>