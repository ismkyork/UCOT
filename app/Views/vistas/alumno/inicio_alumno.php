<?= $this->extend('Template/main') ?>

<?= $this->section('content') ?>

<style>
    .welcome-banner {
        background: linear-gradient(135deg, #f39c12 0%, #8e44ad 100%);
        border-radius: 20px; padding: 40px; color: white;
        margin-bottom: 30px; box-shadow: 0 10px 25px rgba(142, 68, 173, 0.2);
    }
    .card-dashboard { border: none; border-radius: 15px; }
    .notificacion-item {
        border-left: 4px solid #f39c12; margin-bottom: 15px;
        padding: 15px; background: #f8f9fa; border-radius: 0 12px 12px 0;
    }
    .text-ucot { color: #8e44ad; font-weight: bold; }
    .contact-info-box {
        background-color: #f9f9f9; border-radius: 12px;
        padding: 12px; margin-bottom: 15px; text-align: left;
    }
</style>

<div class="container-fluid py-4">
    
    <div class="row mb-4">
        <div class="col-12">
            <div class="welcome-banner text-center text-md-start">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="fw-bold">¡Bienvenido, <?= session()->get('name') ?>!</h1>
                        <p class="lead mb-0">Gestiona tus clases de <strong>UCOT</strong> de forma rápida.</p>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <a href="<?= base_url('alumno/calendario') ?>" class="btn btn-light btn-lg rounded-pill fw-bold shadow-sm">
                            <i class="fas fa-plus-circle me-2 text-warning"></i> Reservar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card card-dashboard shadow-sm h-100">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold"><i class="fas fa-clock me-2 text-ucot"></i> Próxima Clase Programada</h5>
                </div>
                <div class="card-body px-4">
                    <?php if (!empty($proxima_cita)): ?>
                        <?php 
                            $nombre_completo = esc($proxima_cita['nombre_profesor'] . ' ' . ($proxima_cita['apellido_profesor'] ?? '')); 
                        ?>
                        <div class="notificacion-item shadow-sm">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-1 text-dark fw-bold"><?= esc($proxima_cita['materia']) ?></h5>
                                    <p class="mb-0 text-muted small">
                                        <i class="fas fa-chalkboard-teacher me-2"></i>Profesor: <?= $nombre_completo ?>
                                    </p>
                                    <p class="mb-0 text-muted small">
                                        <i class="fas fa-calendar-day me-2"></i><?= date('d/m/Y - h:i A', strtotime($proxima_cita['fecha_hora_inicio'])) ?>
                                    </p>
                                </div>
                                <span class="badge bg-success text-white p-2 px-3 rounded-pill shadow-sm">Confirmada</span>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-check fa-4x text-muted mb-3 opacity-25"></i>
                            <p class="text-muted">No tienes actividades pendientes por el momento.</p>
                        </div>
                    <?php endif; ?>

                    <div class="text-center mt-3">
                        <a href="<?= base_url('alumno/mis_citas') ?>" class="btn btn-link text-decoration-none text-muted small">
                            Ver historial completo de clases <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card card-dashboard shadow-sm border-0">
                <div class="card-body text-center p-4">
                    <?php 
                        $profe_display = (!empty($proxima_cita)) ? esc($proxima_cita['nombre_profesor'] . ' ' . ($proxima_cita['apellido_profesor'] ?? '')) : 'Soporte UCOT';
                        $avatar_name = urlencode($profe_display);
                    ?>
                    <img src="https://ui-avatars.com/api/?name=<?= $avatar_name ?>&background=8e44ad&color=fff" class="rounded-circle mb-3 shadow" width="90">
                    <h6 class="fw-bold mb-1">Información del Instructor</h6>
                    <p class="small text-muted mb-3"><?= $profe_display ?></p>
                    
                    <div class="contact-info-box border">
                        <div class="mb-2">
                            <i class="fas fa-phone-alt text-success me-2 small"></i>
                            <span class="small fw-bold">+58 412-0000000</span>
                        </div>
                        <div>
                            <i class="fas fa-envelope text-primary me-2 small"></i>
                            <span class="small fw-bold">
                                <?= (!empty($proxima_cita['email_profesor'])) ? esc($proxima_cita['email_profesor']) : 'ayuda@ucot.com.ve' ?>
                            </span>
                        </div>
                    </div>
                    <a href="<?= base_url('alumno/feedback') ?>" class="btn btn-warning w-100 rounded-pill fw-bold text-dark shadow-sm py-2">
                        <i class="fas fa-comment-dots me-2"></i> Realizar un comentario
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>