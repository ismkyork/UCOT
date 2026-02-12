<?= $this->extend('Template/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid p-4">
    
    <div class="row mb-4">
        <div class="col-md-8">
            <h3 class="fw-bold" style="color: #2c3e50;">
                <i class="fas fa-comments me-2"></i> Feedback de Estudiantes
            </h3>
            <p class="text-muted">
                Opiniones recibidas en tus clases.
            </p>
        </div>
        <div class="col-md-4 text-end">
            <span class="badge bg-primary fs-6 rounded-pill shadow-sm">
                Total: <?= count($comentarios) ?> Opiniones
            </span>
        </div>
    </div>

    <div class="row">
        
        <?php if (empty($comentarios)): ?>
            <div class="col-12">
                <div class="alert alert-info text-center shadow-sm py-5">
                    <i class="fa-solid fa-inbox fa-3x mb-3"></i>
                    <h5>Bandeja vacía</h5>
                    <p class="mb-0">Aún no has recibido comentarios de tus estudiantes.</p>
                </div>
            </div>
        <?php else: ?>

            <?php foreach ($comentarios as $op): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm border-0 border-top border-4 border-primary">
                        <div class="card-body">
                            
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-dark text-white rounded-circle d-flex justify-content-center align-items-center me-2 shadow-sm" style="width: 40px; height: 40px; font-weight: bold;">
                                    <?= substr($op['nombre_estudiante'], 0, 1) ?>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold text-dark">
                                        <?= esc($op['nombre_estudiante']) . ' ' . esc($op['apellido_estudiante']) ?>
                                    </h6>
                                    <small class="text-muted" style="font-size: 0.75rem;">
                                        Estudiante
                                    </small>
                                </div>
                            </div>
                            
                            <hr class="opacity-25 my-2">

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="text-warning">
                                    <?php 
                                    for($i = 1; $i <= 5; $i++) {
                                        echo ($i <= $op['puntuacion']) 
                                            ? '<i class="fa-solid fa-star"></i>' 
                                            : '<i class="fa-regular fa-star text-secondary opacity-25"></i>';
                                    }
                                    ?>
                                </div>
                                <small class="text-muted">
                                    <i class="fa-regular fa-calendar me-1"></i>
                                    <?= date('d/m/Y', strtotime($op['fecha_evaluacion'])) ?>
                                </small>
                            </div>

                            <div class="p-3 bg-light rounded mt-2">
                                <i class="fa-solid fa-quote-left text-primary opacity-25 me-2"></i>
                                <span class="text-dark fst-italic">
                                    <?= esc($op['comentario']) ?>
                                </span>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        <?php endif; ?>

    </div>
</div>

<?= $this->endSection() ?>