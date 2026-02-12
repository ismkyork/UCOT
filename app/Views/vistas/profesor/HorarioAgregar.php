<?= $this->extend('Template/main') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center mt-5 mb-5">
    <div class="col-md-10 col-lg-8">
        <div class="card card-personalizada border-0">
            
            <div class="card-header card-header-personalizado bg-white border-0 text-center pt-4 pb-3">
                <h3 class="mb-0" style="font-size: 1.5rem;">
                    <i class="far fa-calendar-check me-2 text-info"></i>Asignar Bloques Horarios
                </h3>
                <p class="text-muted small">Selecciona una fecha, los horarios disponibles y el número de cupos</p>
            </div>
            
            <div class="card-body p-4">      
                
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-left: 5px solid #dc3545 !important;">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-circle me-3 fs-4 text-danger"></i>
                            <div>
                                <strong>Atención:</strong><br>
                                <?= session()->getFlashdata('error') ?>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('mensaje')): ?>
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-left: 5px solid #28a745 !important;">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle me-3 fs-4 text-success"></i>
                            <div>
                                <?= session()->getFlashdata('mensaje') ?>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>            

                <form action="<?= base_url('profesor/store_horarios') ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="row justify-content-center mb-4">
                        <div class="col-md-6">
                            <label for="fecha_cita" class="label-titulo">Seleccionar Fecha</label>
                            <input id="fecha_cita" name="fecha_cita" 
                                value="<?= date('Y-m-d') ?>" 
                                class="form-control form-control-personalizado text-center" 
                                type="date" 
                                min="<?= date('Y-m-d') ?>" 
                                max="<?= date('Y-m-t') ?>" required>
                        </div>
                    </div>

                    <div class="row justify-content-center mb-4">
                        <div class="col-md-6">
                            <label for="cupos_totales" class="label-titulo">Cupos totales por bloque</label>
                            <input id="cupos_totales" name="cupos_totales" 
                                value="1" 
                                class="form-control form-control-personalizado text-center" 
                                type="number" 
                                min="1" 
                                max="5" 
                                placeholder="Número de estudiantes" 
                                required>
                            <small class="text-muted d-block text-center mt-1">Cantidad de alumnos que pueden reservar este mismo horario.</small>
                        </div>
                    </div>

                    <div class="row justify-content-center mb-4 g-3">
                        
                        <div class="col-md-6">
                            <label for="id_sistema" class="label-titulo">Plataforma / Modalidad</label>
                            <select name="id_sistema" id="id_sistema" class="form-select form-control-personalizado text-center">
                                <option value="">-- A convenir --</option>
                                <?php if(!empty($sistemas)): ?>
                                    <?php foreach($sistemas as $sis): ?>
                                        <option value="<?= $sis['id'] ?>"><?= $sis['nombre'] ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <small class="text-muted d-block text-center mt-1" id="aviso_sistema">
                                Opcional (A convenir)
                            </small>
                        </div>

                        <div class="col-md-6">
                            <label for="id_materia" class="label-titulo">Materia Específica (Opcional)</label>
                            <select name="id_materia" id="id_materia" class="form-select form-control-personalizado text-center">
                                <option value="">-- Tema Libre (El estudiante elige) --</option>
                                <?php if(!empty($materias)): ?>
                                    <?php foreach($materias as $m): ?>
                                        <option value="<?= $m['id_materia'] ?>"><?= $m['nombre_materia'] ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <small class="text-muted d-block text-center mt-1">
                                Si seleccionas una, será el tema fijo.
                            </small>
                        </div>
                    </div>

                    <div class="alert alert-info border-0 shadow-sm mb-4 py-2" style="background-color: #eef9ff;">
                        <div class="d-flex justify-content-center align-items-center">
                            <i class="fas fa-info-circle me-2 text-primary"></i>
                            <small>Los horarios bloqueados (grises) ya han pasado por el día de hoy.</small>
                        </div>
                    </div>
                    
                    <div class="row">
                        <?php 
                        $horarios = [
                            "Mañana" => ["07:15-08:00", "08:00-08:45", "08:45-09:30", "09:30-10:15", "10:15-11:00", "11:00-11:45"],
                            "Tarde/Noche" => ["12:45-13:30", "13:30-14:15", "14:15-15:00", "15:00-15:45", "15:45-16:30", "16:30-17:15", "17:15-18:00", "18:00-18:45", "18:45-19:30", "19:30-20:15", "20:15-21:00", "21:00-21:45", "21:45-22:30"]
                        ];
                        
                        foreach ($horarios as $turno => $bloques): ?>
                            <div class="col-12 mt-4 mb-2">
                                <h6 class="fw-bold text-uppercase text-muted border-bottom pb-2" style="font-size: 0.8rem; letter-spacing: 1px;">
                                    <i class="<?= ($turno == 'Mañana') ? 'fas fa-sun text-warning' : 'fas fa-moon text-primary' ?> me-2"></i><?= $turno ?>
                                </h6>
                            </div>
                            <?php foreach ($bloques as $bloque): 
                                $id_unico = "chk_" . str_replace([':', '-'], '', $bloque); 
                            ?>
                                <div class="col-6 col-sm-4 col-lg-3">
                                    <div class="bloque-check-wrapper">
                                        <input type="checkbox" name="bloque_horario[]" value="<?= $bloque ?>" id="<?= $id_unico ?>" class="input-oculto">
                                        <label for="<?= $id_unico ?>" class="label-bloque">
                                            <?= $bloque ?> </label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </div>
                
                    <div class="card-footer-limpio d-flex justify-content-center align-items-center gap-3 mt-5">
                        <a href="<?= base_url('profesor/HorarioLeer') ?>" 
                        class="btn btn-ucot-danger btn-redondeado text-decoration-none px-4">
                            Cancelar
                        </a>    
                        <button type="submit" class="btn btn-ucot-success btn-redondeado px-4">
                            <i class="fas fa-save me-2"></i>Guardar
                        </button>
                    </div>              
                </form>                  
            </div>                   
        </div>
    </div>
</div>

<script src="<?= base_url('assets/js/H_agregar.js') ?>"></script> 

<?= $this->endSection() ?>