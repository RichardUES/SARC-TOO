<?php if (!isset($_SESSION["autorizado"])) header("Location: /errors/401"); ?>

<div class="card shadow-sm">
    <div class="card-body">
        <h3 class="card-title h5 mb-4">Crear Nuevo Ticket</h3>
        
        <form method="POST" action="/tickets/create" enctype="multipart/form-data">
            <!-- Asunto -->
            <div class="mb-3">
                <div class="form-floating">
                    <input type="text" class="form-control" id="asunto" name="asunto" 
                           placeholder="Asunto del ticket" required>
                    <label for="asunto">Asunto</label>
                </div>
            </div>

            <!-- Descripción -->
            <div class="mb-3">
                <div class="form-floating">
                    <textarea class="form-control" id="descripcion" name="descripcion" 
                              placeholder="Descripción detallada del problema" 
                              style="height: 120px" required></textarea>
                    <label for="descripcion">Descripción</label>
                </div>
            </div>

            <!-- Prioridad y Área -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-floating">
                        <select class="form-select" id="prioridad" name="prioridad" required>
                            <option value="">Seleccione...</option>
                            <option value="LOW">Baja</option>
                            <option value="MEDIUM">Media</option>
                            <option value="HIGH">Alta</option>
                            <option value="URGENT">Urgente</option>
                        </select>
                        <label for="prioridad">Prioridad</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating">
                        <select class="form-select" id="area" name="area" required>
                            <option value="">Seleccione...</option>
                            <!-- Se llenará dinámicamente desde el backend -->
                        </select>
                        <label for="area">Área</label>
                    </div>
                </div>
            </div>

            <!-- Archivos adjuntos -->
            <div class="mb-4">
                <label for="archivos" class="form-label">Archivos Adjuntos</label>
                <input type="file" class="form-control" id="archivos" name="archivos[]" 
                       multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                <div class="form-text">
                    Puede adjuntar hasta 5 archivos (máx. 5MB cada uno)
                </div>
            </div>

            <!-- Botones -->
            <div class="d-flex justify-content-end gap-2">
                <button type="reset" class="btn btn-light">Limpiar</button>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-send me-2"></i>Enviar Ticket
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Script para cargar áreas dinámicamente -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    fetch('/api/areas')
        .then(response => response.json())
        .then(areas => {
            const areaSelect = document.getElementById('area');
            areas.forEach(area => {
                const option = document.createElement('option');
                option.value = area.id;
                option.textContent = area.nombre;
                areaSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error cargando áreas:', error));
});
</script>