
<?= $this->extend('Template/main') ?>

<?= $this->section('content') ?>

   
   <div class="row justify-content-center mt-5 mb-5">
        <div class="col-md-8 col-lg-6">
            <div class="card card-personalizada border-0">
                
                <div class="card-header card-header-personalizado bg-white border-0 text-center pt-4 pb-3">
                    <h3 class="mb-0" style="font-size: 1.5rem;">
                        <i class="far fa-calendar-check me-2 text-info"></i>Asignar Bloques Horarios
                    </h3>
                    <p class="text-muted small">Haz clic sobre los horarios para seleccionarlos</p>
                </div>
                
                <div class="card-body p-4">    
                    <form action="<?= base_url('profesor/store_horarios') ?>" method="POST">
                        
                        <div class="mb-4">
                            <label for="fecha_cita" class="label-titulo">Seleccionar Fecha</label>
                            <input id="fecha_cita" name="fecha_cita" value="<?= date('Y-m-d') ?>" class="form-control form-control-personalizado" type="date" required>
                        </div>

                        <label class="label-titulo mb-3">Horarios Disponibles:</label>
                        
                        <div class="row g-2">
                            <?php 
                            $horarios = [
                                "Mañana" => ["07:15-08:00", "08:00-08:45", "08:45-09:30", "09:30-10:15", "10:15-11:00", "11:00-11:45"],
                                "Tarde/Noche" => ["12:45-13:30", "13:30-14:15", "14:15-15:00", "15:00-15:45", "15:45-16:30", "16:30-17:15", "17:15-18:00", "18:00-18:45", "18:45-19:30", "19:30-20:15", "20:15-21:00", "21:00-21:45", "21:45-22:30"]
                            ];
                            
                            foreach ($horarios as $turno => $bloques): ?>
                                <div class="col-12 mt-3 mb-1">
                                    <small class="fw-bold text-uppercase text-muted" style="letter-spacing: 1px; font-size: 0.7rem;"><?= $turno ?></small>
                                    <hr class="mt-1 mb-2 opacity-25">
                                </div>
                                <?php foreach ($bloques as $bloque): 
                                    $id_unico = "chk_" . str_replace([':', '-'], '', $bloque); 
                                ?>
                                    <div class="col-6 col-sm-4">
                                        <div class="bloque-check-wrapper">
                                            <input type="checkbox" name="bloque_horario[]" value="<?= $bloque ?>" id="<?= $id_unico ?>" class="input-oculto">
                                            <label for="<?= $id_unico ?>" class="label-bloque">
                                                <?= str_replace('-', ' - ', $bloque) ?>
                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </div>
                    
                        <div class="card-footer-limpio d-flex justify-content-center align-items-center gap-3 mt-5">
                            <a href="<?= base_url('profesor/HorarioLeer') ?>" 
                            class="btn btn-ucot-danger btn-redondeado text-decoration-none">
                                Cancelar
                            </a>    
                            <button type="submit" class="btn btn-ucot-success btn-redondeado">
                                Guardar Selección
                            </button>
                        </div>              
                    </form>                  
                </div>                   
            </div>
        </div>
    </div>

<?= $this->endSection() ?>
