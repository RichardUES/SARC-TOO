<?php if (!isset($_SESSION["autorizado"])) header("Location: /errors/401"); ?>

<div class="card shadow-sm">
    <div class="card-body">
        <h3 class="card-title h5 mb-4">Configuración de la Cuenta</h3>
        
        <!-- Cambio de Contraseña -->
        <form method="POST" action="/profile/update-password" class="mb-5">
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

        <!-- Preferencias de Notificaciones -->
        <form method="POST" action="/profile/update-preferences">
            <h4 class="h6 mb-3">Preferencias de Notificaciones</h4>
            
            <div class="mb-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="notifEmail" 
                           name="notifications[email]" checked>
                    <label class="form-check-label" for="notifEmail">
                        Recibir notificaciones por correo electrónico
                    </label>
                </div>
            </div>

            <div class="mb-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="notifSistema" 
                           name="notifications[system]" checked>
                    <label class="form-check-label" for="notifSistema">
                        Mostrar notificaciones del sistema
                    </label>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-2"></i>Guardar Preferencias
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validación de contraseñas
    const form = document.querySelector('form[action="/profile/update-password"]');
    const newPass = document.getElementById('passwordNueva');
    const confirmPass = document.getElementById('passwordConfirmar');

    form.addEventListener('submit', function(e) {
        if (newPass.value !== confirmPass.value) {
            e.preventDefault();
            alert('Las contraseñas nuevas no coinciden');
        }
    });
});
</script>