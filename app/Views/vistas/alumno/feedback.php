<?= $this->extend('Template/main') ?>

<?= $this->section('content') ?>

<div class="container mt-4">

    <?php if (session()->getFlashdata('msg')): ?>
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fa-solid fa-check-circle me-2"></i> <?= session()->getFlashdata('msg') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row justify-content-center mb-5">
        <div class="col-md-8">
            <div class="card card-personalizada p-4 shadow-sm">
                <div class="text-center mb-3">
                    <i class="fa-solid fa-magnifying-glass-chart fa-2x" style="color: #2c3e50;"></i>
                    <h3 class="fw-bold mt-2" style="color: #2c3e50;">Opiniones y Feedback</h3>
                    <p class="text-muted">Selecciona un profesor para ver qué dicen tus compañeros y dejar tu opinión.</p>
                </div>

                <form method="GET" action="<?= base_url('alumno/feedback') ?>">
                    <div class="input-group input-group-lg">
                        <select name="id_profesor" class="form-select border-2" onchange="this.form.submit()">
                            <option value="">-- Selecciona un Profesor --</option>
                            <?php foreach($profesores as $p): ?>
                                <option value="<?= $p['id_profesor'] ?>" <?= ($id_seleccionado == $p['id_profesor']) ? 'selected' : '' ?>>
                                    <?= $p['nombre_profesor'] . ' ' . $p['apellido_profesor'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button class="btn btn-primary" type="submit">Ver</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php if($profe_actual): ?>
        
        <div class="row">
            <div class="col-md-7">
                <h4 class="text-muted mb-4">
                    <i class="fa-solid fa-comments me-2"></i> Opiniones sobre <?= $profe_actual['nombre_profesor'] ?>
                </h4>

                <?php if(empty($comentarios_publicos)): ?>
                    <div class="alert alert-light text-center border shadow-sm py-5">
                        <i class="fa-regular fa-comment-dots fa-3x mb-3 text-muted"></i>
                        <p class="mb-0">Nadie ha opinado sobre este profesor aún.</p>
                        <p class="fw-bold text-primary">¡Sé el primero!</p>
                    </div>
                <?php else: ?>
                    <div style="max-height: 600px; overflow-y: auto; padding-right: 10px;">
                        <?php foreach ($comentarios_publicos as $item): ?>
                            <div class="card mb-3 shadow-sm border-0">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-secondary text-white rounded-circle d-flex justify-content-center align-items-center me-2" style="width: 35px; height: 35px; font-weight: bold;">
                                                <?= substr($item['nombre_estudiante'], 0, 1) ?>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold text-dark"><?= $item['nombre_estudiante'] ?> <?= substr($item['apellido_estudiante'], 0, 1) ?>.</h6>
                                                <small class="text-muted" style="font-size: 0.75rem;">
                                                    <?= date('d/m/Y', strtotime($item['fecha_evaluacion'])) ?>
                                                </small>
                                            </div>
                                        </div>
                                        
                                        <div class="text-warning small">
                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                                <?= ($i <= $item['puntuacion']) ? '<i class="fa-solid fa-star"></i>' : '<i class="fa-regular fa-star text-secondary opacity-25"></i>' ?>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-2 p-2 bg-light rounded">
                                        <p class="mb-0 text-dark fst-italic">"<?= esc($item['comentario']) ?>"</p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-md-5">
                <div class="card card-personalizada shadow p-4 sticky-top" style="top: 20px;">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fa-solid fa-pen-nib text-primary fa-lg me-2"></i>
                        <h5 class="fw-bold mb-0">Dejar mi Opinión</h5>
                    </div>
                    
                    <form action="<?= base_url('alumno/guardar') ?>" method="post">
                        
                        <input type="hidden" name="id_profesor" value="<?= $profe_actual['id_profesor'] ?>">

                        <div class="mb-3">
                            <label class="form-label fw-bold small">Calificación</label>
                            <select name="puntuacion" class="form-select" required>
                                <option value="" selected disabled>Estrellas...</option>
                                <option value="5">⭐⭐⭐⭐⭐ Excelente</option>
                                <option value="4">⭐⭐⭐⭐ Muy buena</option>
                                <option value="3">⭐⭐⭐ Buena</option>
                                <option value="2">⭐⭐ Regular</option>
                                <option value="1">⭐ Mala</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small">Tu Comentario</label>
                            <textarea name="comentario" class="form-control" rows="4" placeholder="¿Cómo fue tu experiencia en su clase?" required minlength="5"></textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">
                                <i class="fa-regular fa-paper-plane me-2"></i> Enviar Opinión
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    <?php else: ?>
        <div class="text-center py-5 opacity-50">
            <i class="fa-solid fa-arrow-up fa-3x mb-3"></i>
            <h4>Selecciona un profesor arriba para comenzar</h4>
        </div>
    <?php endif; ?>

</div>

<?= $this->endSection() ?>