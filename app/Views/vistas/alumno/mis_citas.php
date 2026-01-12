<?=$header?> 



    <h1>mis citas</h1>
<form action="<?= base_url('alumno/citas/guardar') ?>" method="POST">
<div class="card shadow-lg mb-4">
        <div class="card-header bg-primary text-white">
         <h5 class="mb-0">Insertar Datos para agendar una clase</h5>
        </div>
              <div class="card-body">

               <div class="form-group mb-3">
                    
                        <label for="fecha_hora_inicio">Indique la fecha y hora de inicio que quiera apartar</label>
                        <input id="fecha_hora_inicio" value="<?=old('fecha_hora_inicio')?>" class="fecha_hora_inicio" type="datetime-local" name="fecha_hora_inicio"     placeholder="Ej: 2024-10-25 10:30:00" required>
                        <small class="form-text text-muted">Ingresa la fecha en formato AAAA-MM-DD.</small>

                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="materia">Indique la materia </label>
                        <input id="materia" value="<?=old('materia')?>" class="form-control" type="text" name="materia" placeholder="Ej:Química" >

                    </div>
                
                    <div class="form-group">
                        <label for="duracion_min">Duración de la cita:</label>
                        <select name="duracion_min" id="duracion_min" required>
                            <option value="" disabled selected>Selecciona el tiempo...</option>
                            <option value="60">1 hora (60 min)</option>
                            <option value="120">2 horas (120 min)</option>
                            <option value="180">3 horas (180 min)</option>
                        </select>
                    </div>


                    <button type="submit" class="btn btn-success btn-lg w-100">
                        <i class="fas fa-save me-2"></i> Reservar
                    </button>

         
             </div>
</div>





<?=$footer?>    