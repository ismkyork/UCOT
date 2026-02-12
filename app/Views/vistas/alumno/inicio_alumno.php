<?= $this->extend('Template/main') ?>

<?= $this->section('content') ?>


<?php if(session()->getFlashdata('bienvenida')): ?>
    <div class="alert alert-success alert-dismissible fade show container mt-3 shadow-sm border-0" style="border-radius: 15px; background: rgba(255,255,255,0.8); backdrop-filter: blur(10px); border-left: 5px solid #28a745 !important;" role="alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-smile-wink me-3 fs-4 text-success"></i>
            <div>
                <strong>¡Hola de nuevo!</strong><br>
                <?= session()->getFlashdata('bienvenida') ?>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h2 class="fw-bold mb-1" style="color: var(--ucot-negro);">Panel del Estudiante</h2>
            <div class="text-muted small">
                <i class="far fa-calendar-alt me-1"></i> <?= date('l, d F Y') ?> 
                <span class="mx-2">•</span> 
                <span class="text-success fw-bold"><i class="fas fa-circle me-1" style="font-size: 8px;"></i>Activo</span>
            </div>
        </div>
        
        <div class="d-flex gap-2">
            <a href="<?= base_url('alumno/elegir_profesor') ?>" class="btn btn-ucot shadow-sm">
                <i class="fas fa-plus me-2"></i>Agendar Cita
            </a>
            <a href="<?= base_url('alumno/comprobantes_pagos') ?>" class="btn btn-ucot-outline">
                <i class="fas fa-receipt me-2"></i>Mis Pagos
            </a>
        </div>
    </div>

    <div class="row g-4">
        
        <div class="col-lg-8">
            <div class="card card-dashboard">
                <div class="card-header-clean d-flex justify-content-between align-items-center">
                    <h5 class="card-title-dashboard">
                        <i class="fas fa-rocket me-2" style="color: var(--ucot-cian);"></i> 
                        Tu Próxima Clase
                    </h5>
                    <?php if ($proxima_cita): ?>
                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2" style="font-size: 0.7rem; font-weight: 800;">
                            <i class="fas fa-clock me-1"></i> EN AGENDA
                        </span>
                    <?php endif; ?>
                </div>

                <div class="card-body p-0">
                    <?php if ($proxima_cita): ?>
                        <?php $nombre_completo = esc($proxima_cita['nombre_profesor'] . ' ' . ($proxima_cita['apellido_profesor'] ?? '')); ?>
                        
                        <div class="info-box-row">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-cyan-soft me-3">
                                    <i class="fas fa-book-open fa-lg"></i>
                                </div>
                                
                                <div class="flex-grow-1">
                                    <h5 class="fw-bold text-dark mb-1" style="font-size: 1.1rem;"><?= esc($proxima_cita['materia']) ?></h5>
                                    
                                    <div class="d-flex flex-wrap gap-3 text-muted small">
                                        <span title="Profesor">
                                            <i class="fas fa-chalkboard-teacher me-1 text-primary"></i> 
                                            <?= $nombre_completo ?>
                                        </span>
                                        <span title="Fecha y Hora">
                                            <i class="far fa-clock me-1 text-warning"></i> 
                                            <?= date('d/m/Y', strtotime($proxima_cita['fecha_hora_inicio'])) ?> • 
                                            <strong><?= date('h:i A', strtotime($proxima_cita['fecha_hora_inicio'])) ?></strong>
                                        </span>
                                        <?php if(!empty($proxima_cita['sistema'])): ?>
                                            <span title="Plataforma">
                                                <i class="fas fa-video me-1 text-secondary"></i> 
                                                <?= esc($proxima_cita['sistema']) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="ms-3 d-none d-md-block">
                                    <a href="<?= base_url('alumno/mis_citas') ?>" class="btn btn-sm btn-white border fw-bold text-muted rounded-pill px-3 shadow-sm">
                                        Detalles <i class="fas fa-chevron-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="p-3 text-center bg-light-transparent">
                            <a href="<?= base_url('alumno/mis_citas') ?>" class="text-decoration-none fw-bold small" style="color: var(--ucot-cian);">
                                <i class="fas fa-list-ul me-1"></i> Ver historial completo de clases
                            </a>
                        </div>

                    <?php else: ?>
                        <div class="text-center py-5">
                            <div class="mb-3 opacity-25">
                                <i class="fas fa-calendar-check fa-4x text-muted"></i>
                            </div>
                            <h6 class="fw-bold text-dark">¡Todo listo!</h6>
                            <p class="text-muted small mb-4">No tienes clases próximas pendientes.</p>
                            <a href="<?= base_url('alumno/elegir_profesor') ?>" class="btn btn-ucot btn-sm">
                                <i class="fas fa-plus me-1"></i> Programar nueva clase
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card card-dashboard">
                <div class="card-header-clean">
                    <h5 class="card-title-dashboard">
                        <i class="fas fa-user-tie me-2 text-warning"></i>
                        Instructor Actual
                    </h5>
                </div>
                
                <div class="card-body p-4 text-center">
                    <?php 
                        $profe_display = ($proxima_cita) ? esc($proxima_cita['nombre_profesor'] . ' ' . ($proxima_cita['apellido_profesor'] ?? '')) : 'Sin Asignar';
                        $avatar_bg = '33C2D1'; 
                        $avatar_col = 'fff';
                        if(!$proxima_cita) { $avatar_bg = 'e3e6f0'; $avatar_col = '858796'; }
                    ?>
                    
                    <div class="position-relative d-inline-block mb-3">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($profe_display) ?>&background=<?= $avatar_bg ?>&color=<?= $avatar_col ?>&size=128" 
                             class="rounded-circle shadow-sm" 
                             width="80" 
                             style="border: 3px solid rgba(255,255,255,0.8);">
                        <?php if($proxima_cita): ?>
                            <span class="position-absolute bottom-0 end-0 p-2 bg-success border border-light rounded-circle shadow-sm"></span>
                        <?php endif; ?>
                    </div>

                    <h5 class="fw-bold text-dark mb-1"><?= $profe_display ?></h5>
                    <p class="small text-muted mb-4">Docente UCOT</p>

                    <div class="rounded-3 p-3 text-start mb-3 border" style="background: rgba(0,0,0,0.02);">
                        <div class="d-flex align-items-center mb-2">
                            <div class="icon-circle bg-white shadow-sm me-3" style="width: 30px; height: 30px; border-radius: 8px;">
                                <i class="fas fa-envelope text-primary small"></i>
                            </div>
                            <div>
                                <small class="d-block text-muted" style="font-size: 0.7rem; font-weight: 700;">EMAIL SOPORTE</small>
                                <span class="text-dark small fw-bold">ucot2025@gmail.com</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-start gap-2"> 
                            <i class="fas fa-info-circle text-info mt-1"></i>                        
                            <div>
                                <span class="text-dark small fw-medium">Recuerda revisar tu correo para el enlace de la clase.</span>
                            </div>
                        </div>
                    </div>

                    <a href="<?= base_url('alumno/feedback') ?>" class="btn btn-outline-warning w-100 rounded-pill fw-bold border-2 shadow-sm" style="background: white;">
                        <i class="fas fa-star me-2"></i> Calificar Docente
                    </a>
                </div>
            </div>
        </div>

    </div> 
</div>

<?= $this->endSection() ?>