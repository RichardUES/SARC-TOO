<?php 

if (!isset($_SESSION["autorizado"])) header("Location: /errors/401");

require_once "layouts/header.php";

?>

<!-- Menu lateral (sidebar) -->
<?php require_once "layouts/sidebar.php" ?>

<div class="card shadow-sm col-md-9">
    <div class="card-body">
        <h3 class="card-title h5 mb-4">Configuración de la Cuenta</h3>

        <?php if (isset($_SESSION["success"])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $_SESSION["success"]; ?>
                <?php deleteSession("success"); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION["Error"])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $_SESSION["Error"];  ?>
                <?php deleteSession("Error"); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <!-- Cambio de Contraseña -->
        <form method="POST" action="/profile/update_password" class="mb-5">
            <h4 class="h6 mb-3">Cambiar Contraseña</h4>
            
            <div class="mb-3">
                <div class="form-floating">
                    <input type="password" class="form-control" id="passwordActual" 
                           name="passwordActual" required>
                    <label for="passwordActual">Contraseña Actual</label>
                </div>
            </div>

            <div class="mb-3">
                <div class="form-floating">
                    <input type="password" class="form-control" id="passwordNueva" 
                           name="passwordNueva" required>
                    <label for="passwordNueva">Nueva Contraseña</label>
                </div>
            </div>

            <div class="mb-3">
                <div class="form-floating">
                    <input type="password" class="form-control" id="passwordConfirmar" 
                           name="passwordConfirmar" required>
                    <label for="passwordConfirmar">Confirmar Nueva Contraseña</label>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-key me-2"></i>Cambiar Contraseña
                </button>
            </div>
        </form>

    </div>
</div>

<?php require_once "layouts/footer.php" ?>