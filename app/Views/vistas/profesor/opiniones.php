<?= $this->extend('Template/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid p-4">
    
    <div class="row mb-4">
        <div class="col-md-8">
            <h3 class="fw-bold" style="color: #2c3e50;">
                <i class="fas fa-user-secret me-2"></i> Buzón de Opiniones
            </h3>
            <p class="text-muted">
                Aquí encontrarás el feedback anónimo de tus estudiantes.
            </p>
        </div>
        <div class="col-md-4 text-end">
            <span class="badge bg-primary fs-6 rounded-pill">
                Total: <?= count($comentarios) ?> Opiniones
            </span>
        </div>
    </div>

    <div class="row">
        
        <?php if (empty($comentarios)): ?>
            <div class="col-12">
                <div class="alert alert-info text-center shadow-sm">
                    <i class="fa-solid fa-circle-info me-2"></i> Aún no hay comentarios recibidos.
                </div>
            </div>
        <?php else: ?>

            <?php foreach ($comentarios as $op): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm border-0 border-start border-4 border-primary">
                        <div class="card-body">
                            
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="text-warning fs-5">
                                    <?php 
                                    for($i = 1; $i <= 5; $i++) {
                                        echo ($i <= $op['puntuacion']) 
                                            ? '<i class="fa-solid fa-star"></i>' 
                                            : '<i class="fa-regular fa-star text-secondary opacity-25"></i>';
                                    }
                                    ?>
                                </div>
                                <small class="text-muted" style="font-size: 0.8rem;">
                                    <i class="fa-regular fa-calendar me-1"></i>
                                    <?= date('d/m/Y', strtotime($op['fecha_evaluacion'])) ?>
                                </small>
                            </div>

                            <div class="card-text text-dark">
                                <i class="fa-solid fa-quote-left text-primary opacity-25 me-2"></i>
                                <?= esc($op['comentario']) ?>
                            </div>

                            <div class="mt-3 text-end">
                                <span class="badge bg-light text-dark border">
                                    Anónimo
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
