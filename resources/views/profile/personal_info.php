<?php if (!isset($_SESSION["autorizado"])) header("Location: /errors/401"); ?>

<div class="card shadow-sm">
    <div class="card-body">
        <h3 class="card-title h5 mb-4">Información Personal</h3>
        
        <form method="POST" action="/profile/update-info">
            <!-- Primera fila: Nombres -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="nombre1" name="nombre1" placeholder="Primer nombre" required>
                        <label for="nombre1">Primer Nombre</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="nombre2" name="nombre2" placeholder="Segundo nombre">
                        <label for="nombre2">Segundo Nombre</label>
                    </div>
                </div>
            </div>

            <!-- Segunda fila: Apellidos -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="apellido1" name="apellido1" placeholder="Primer apellido" required>
                        <label for="apellido1">Primer Apellido</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="apellido2" name="apellido2" placeholder="Segundo apellido">
                        <label for="apellido2">Segundo Apellido</label>
                    </div>
                </div>
            </div>

            <!-- Tercera fila: Fecha nacimiento y DUI -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="date" class="form-control" id="fechaNacimiento" name="fechaNacimiento" required>
                        <label for="fechaNacimiento">Fecha de Nacimiento</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="text" class="form-control" id="dui" name="dui" placeholder="00000000-0" 
                               pattern="[0-9]{8}-[0-9]{1}" title="Formato: 00000000-0" required>
                        <label for="dui">DUI</label>
                    </div>
                </div>
            </div>

            <!-- Cuarta fila: Teléfono -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="form-floating">
                        <input type="tel" class="form-control" id="telefono" name="telefono" 
                               placeholder="0000-0000" pattern="[0-9]{4}-[0-9]{4}" 
                               title="Formato: 0000-0000" required>
                        <label for="telefono">Teléfono</label>
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="d-flex justify-content-end gap-2">
                <button type="reset" class="btn btn-light">Cancelar</button>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-2"></i>Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>