<?= $this->extend('Template/main') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center mt-5 mb-5">
    <div class="col-md-8 col-lg-6">
        <div class="card card-personalizada border-0">
            
            <div class="card-header card-header-personalizado bg-white border-0 text-center pt-4 pb-3">
                <h3 class="mb-0" style="font-size: 1.5rem;">
                    <i class="fas fa-edit me-2 text-info"></i>Editar Bloque Horario
                </h3>
                <p class="text-muted small">Modifica el día o selecciona un nuevo rango de tiempo</p>
            </div>
            
            <div class="card-body p-4">    
                <form action="<?= base_url('profesor/update_horario/'.$horario['id_horario']) ?>" method="post">
                    
                    <div class="mb-4">
                        <label for="week_day" class="label-titulo">Día de la semana</label>
                        <select class="form-select form-control-personalizado" id="week_day" name="week_day" required>
                            <option value="">Seleccione...</option>
                            <?php 
                                $dias = ['LUNES', 'MARTES', 'MIERCOLES', 'JUEVES', 'VIERNES', 'SABADO', 'DOMINGO'];
                                foreach($dias as $dia): 
                                    $selected = (strtoupper($horario['week_day']) == $dia) ? 'selected' : '';
                            ?>
                                <option value="<?= $dia ?>" <?= $selected ?>><?= ucfirst(strtolower($dia)) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <label class="label-titulo mb-3">Selecciona el nuevo Horario:</label>
                    
                    <div class="row g-2">
                        <?php 
                        // Definición de bloques igual a horarioAgregar
                        $bloques_predefinidos = [
                            "Mañana" => ["07:15-08:00", "08:00-08:45", "08:45-09:30", "09:30-10:15", "10:15-11:00", "11:00-11:45"],
                            "Tarde/Noche" => ["12:45-13:30", "13:30-14:15", "14:15-15:00", "15:00-15:45", "15:45-16:30", "16:30-17:15", "17:15-18:00", "18:00-18:45", "18:45-19:30", "19:30-20:15", "20:15-21:00", "21:00-21:45", "21:45-22:30"]
                        ];

                        // Construimos el bloque actual para marcarlo como seleccionado
                        // Limpiamos segundos si vienen de la BD (07:15:00 -> 07:15)
                        $inicio_db = substr($horario['hora_inicio'], 0, 5);
                        $fin_db = substr($horario['hora_fin'], 0, 5);
                        $bloque_actual = $inicio_db . "-" . $fin_db;

                        foreach ($bloques_predefinidos as $turno => $bloques): ?>
                            <div class="col-12 mt-3 mb-1">
                                <small class="fw-bold text-uppercase text-muted" style="letter-spacing: 1px; font-size: 0.7rem;"><?= $turno ?></small>
                                <hr class="mt-1 mb-2 opacity-25">
                            </div>
                            
                            <?php foreach ($bloques as $bloque): 
                                $id_unico = "chk_" . str_replace([':', '-'], '', $bloque); 
                                // Verificamos si este bloque es el que ya está guardado
                                $checked = ($bloque == $bloque_actual) ? 'checked' : '';
                            ?>
                                <div class="col-6 col-sm-4">
                                    <div class="bloque-check-wrapper">
                                        <input type="radio" name="bloque_horario_combo" value="<?= $bloque ?>" id="<?= $id_unico ?>" class="input-oculto" <?= $checked ?> required onchange="splitTime(this.value)">
                                        <label for="<?= $id_unico ?>" class="label-bloque">
                                            <?= str_replace('-', ' - ', $bloque) ?>
                                        </label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </div>

                    <input type="hidden" name="hora_inicio" id="hora_inicio" value="<?= $horario['hora_inicio'] ?>">
                    <input type="hidden" name="hora_fin" id="hora_fin" value="<?= $horario['hora_fin'] ?>">
                    <input type="hidden" name="estado" value="Disponible">

                    <div class="card-footer-limpio d-flex justify-content-center align-items-center gap-3 mt-5">
                        <a href="<?= base_url('profesor/HorarioLeer') ?>" 
                           class="btn btn-ucot-danger btn-redondeado text-decoration-none shadow-sm">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </a>
                        
                        <button type="submit" class="btn btn-ucot-success btn-redondeado shadow-sm">
                            <i class="fas fa-save me-2"></i>Guardar Cambios
                        </button>
                    </div>              
                </form>                  
            </div>                   
        </div>
    </div>
</div>


<?= $this->endSection() ?>
