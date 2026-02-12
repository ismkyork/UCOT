<?= $this->extend('Template/main') ?>

<?= $this->section('content') ?>

<?php
    // Definir estado de bloqueo visual
    $hayReservas = ($cuposOcupados > 0);
    $atributoBloqueo = $hayReservas ? 'disabled style="background-color: #e9ecef; cursor: not-allowed;"' : '';
    $claseBloqueoRadio = $hayReservas ? 'opacity-50 pe-none' : '';
?>

<div class="row justify-content-center mt-5 mb-5">
    <div class="col-md-10 col-lg-8">
        <div class="card card-personalizada border-0">
            
            <div class="card-header card-header-personalizado bg-white border-0 text-center pt-4 pb-3">
                <h3 class="mb-0" style="font-size: 1.5rem;">
                    <i class="fas fa-edit me-2 text-info"></i>Editar Bloque Horario
                </h3>
                <p class="text-muted small">Modifica los detalles de tu clase</p>
            </div>
            
            <div class="card-body p-4">      
                
                <?php if ($hayReservas): ?>
                    <div class="alert alert-warning border-0 shadow-sm mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-lock me-3 fs-4"></i>
                            <div>
                                <strong>Edición Restringida:</strong><br>
                                Ya hay <b><?= $cuposOcupados ?></b> estudiante(s) inscrito(s). 
                                Solo puedes ampliar o reducir los cupos totales (mínimo <?= $cuposOcupados ?>). 
                                Fecha, hora y materia no se pueden cambiar.
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4">
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('profesor/update_horario/'.$horario['id_horario']) ?>" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="row justify-content-center mb-4">
                        <div class="col-md-6">
                            <label for="fecha" class="label-titulo">Fecha del horario</label>
                            <input type="date" id="fecha" name="fecha" 
                                value="<?= date('Y-m-d', strtotime($horario['fecha'])) ?>" 
                                class="form-control form-control-personalizado text-center" 
                                min="<?= date('Y-m-d') ?>" 
                                max="<?= date('Y-m-t') ?>" 
                                required <?= $atributoBloqueo ?>>
                            
                            <?php if(!$hayReservas): ?>
                                <small class="text-muted d-block text-center mt-1">Solo mes actual</small>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row justify-content-center mb-4">
                        <div class="col-md-6">
                            <label for="cupos_totales" class="label-titulo">Cupos totales</label>
                            <input type="number" id="cupos_totales" name="cupos_totales" 
                                value="<?= $horario['cupos_totales'] ?? 1 ?>" 
                                class="form-control form-control-personalizado text-center" 
                                min="<?= ($hayReservas) ? $cuposOcupados : 1 ?>" 
                                max="5" required>
                            <small class="text-muted d-block text-center mt-1">
                                Ocupados: <strong class="text-danger"><?= $cuposOcupados ?></strong> | 
                                Disponibles: <strong class="text-success"><?= $horario['cupos_disponibles'] ?></strong>
                            </small>
                        </div>
                    </div>

                    <div class="row justify-content-center mb-4 g-3">
                        <div class="col-md-6">
                            <label for="id_sistema" class="label-titulo">Plataforma</label>
                            <select name="id_sistema" id="id_sistema" class="form-select form-control-personalizado text-center" <?= $atributoBloqueo ?>>
                                <option value="">-- A convenir --</option>
                                <?php if(!empty($sistemas)): ?>
                                    <?php foreach($sistemas as $sis): ?>
                                        <option value="<?= $sis['id'] ?>" <?= ($horario['id_sistema'] == $sis['id']) ? 'selected' : '' ?>>
                                            <?= $sis['nombre'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <small class="text-muted d-block text-center mt-1" id="aviso_sistema">
                                <?= $hayReservas ? 'Fijado por reserva existente' : 'Opcional (A convenir)' ?>
                            </small>
                        </div>

                        <div class="col-md-6">
                            <label for="id_materia" class="label-titulo">Materia</label>
                            <select name="id_materia" id="id_materia" class="form-select form-control-personalizado text-center" <?= $atributoBloqueo ?>>
                                <option value="">-- Tema Libre --</option>
                                <?php if(!empty($materias)): ?>
                                    <?php foreach($materias as $m): ?>
                                        <option value="<?= $m['id_materia'] ?>" <?= ($horario['id_materia'] == $m['id_materia']) ? 'selected' : '' ?>>
                                            <?= $m['nombre_materia'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>

                    <label class="label-titulo mb-3">Horario:</label>
                    <div class="row <?= $claseBloqueoRadio ?>">
                        <?php 
                        // ... (Tu array de $bloques_predefinidos igual que antes) ...
                        $bloques_predefinidos = [
                            "Mañana" => ["07:15-08:00", "08:00-08:45", "08:45-09:30", "09:30-10:15", "10:15-11:00", "11:00-11:45"],
                            "Tarde/Noche" => ["12:45-13:30", "13:30-14:15", "14:15-15:00", "15:00-15:45", "15:45-16:30", "16:30-17:15", "17:15-18:00", "18:00-18:45", "18:45-19:30", "19:30-20:15", "20:15-21:00", "21:00-21:45", "21:45-22:30"]
                        ];

                        $inicio_db = substr($horario['hora_inicio'], 0, 5);
                        $fin_db = substr($horario['hora_fin'], 0, 5);
                        $bloque_actual = $inicio_db . "-" . $fin_db;

                        foreach ($bloques_predefinidos as $turno => $bloques): ?>
                            <div class="col-12 mt-4 mb-2">
                                <h6 class="fw-bold text-uppercase text-muted border-bottom pb-2" style="font-size: 0.8rem; letter-spacing: 1px;">
                                    <i class="<?= ($turno == 'Mañana') ? 'fas fa-sun text-warning' : 'fas fa-moon text-primary' ?> me-2"></i><?= $turno ?>
                                </h6>
                            </div>
                            <?php foreach ($bloques as $bloque): 
                                $id_unico = "chk_" . str_replace([':', '-'], '', $bloque); 
                                $checked = ($bloque == $bloque_actual) ? 'checked' : '';
                            ?>
                                <div class="col-6 col-sm-4 col-lg-3">
                                    <div class="bloque-check-wrapper">
                                            <input type="radio" name="bloque_horario" value="<?= $bloque ?>" id="<?= $id_unico ?>" 
                                            class="input-oculto" 
                                            <?= $checked ?> 
                                            <?= ($hayReservas) ? 'disabled' : 'required' ?> > 
                                            <label for="<?= $id_unico ?>" class="label-bloque">
                                                <?= $bloque ?> 
                                            </label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </div>

                    <input type="hidden" name="hora_inicio" id="hora_inicio" value="<?= $inicio_db ?>">
                    <input type="hidden" name="hora_fin" id="hora_fin" value="<?= $fin_db ?>">

                    <div class="card-footer-limpio d-flex justify-content-center align-items-center gap-3 mt-5">
                        <a href="<?= base_url('profesor/HorarioLeer') ?>" class="btn btn-ucot-danger btn-redondeado text-decoration-none shadow-sm px-4">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-ucot-success btn-redondeado shadow-sm px-4">
                            <i class="fas fa-save me-2"></i>Guardar Cambios
                        </button>
                    </div>              
                </form>                  
            </div>                   
        </div>
    </div>
</div>

<script src="<?= base_url('assets/js/H_editar.js') ?>"></script>

<?= $this->endSection() ?>