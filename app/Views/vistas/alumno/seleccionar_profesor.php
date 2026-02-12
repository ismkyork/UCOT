<?= $this->extend('Template/main') ?>
<?= $this->section('content') ?>

<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold" style="color: var(--ucot-azul);">¿Con quién aprenderás hoy?</h2>
        <p class="text-muted">Selecciona un tutor para ver su disponibilidad.</p>
    </div>

    <div class="row justify-content-center">
        <?php foreach($profesores as $profe): ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0 card-hover-effect" style="border-radius: 20px;">
                <div class="card-body text-center p-4 d-flex flex-column">
                    
                    <div class="mb-3">
                        <div class="avatar-circle d-inline-flex align-items-center justify-content-center shadow-sm mx-auto" 
                             style="width: 90px; height: 90px; border-radius: 50%; background-color: var(--ucot-cian); overflow: hidden;">
                            <?php 
                                $foto = $profe['foto'] ?? 'default.png';
                                // Ruta física para comprobar si existe el archivo
                                $rutaFisica = FCPATH . 'uploads/perfiles/' . $foto;
                                
                                // Si tiene foto y el archivo existe:
                                if($foto != 'default.png' && file_exists($rutaFisica)): 
                            ?>
                                <img src="<?= base_url('uploads/perfiles/'.$foto) ?>" alt="Foto" style="width: 100%; height: 100%; object-fit: cover;">
                            <?php else: ?>
                                <span class="text-white display-5 fw-bold mb-0">
                                    <?= strtoupper(substr($profe['nombre_profesor'] ?? 'D', 0, 1)) ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <h4 class="card-title fw-bold text-dark mb-1">
                        <?= esc($profe['nombre_profesor']) ?> <?= esc($profe['apellido_profesor']) ?>
                    </h4>
                    <p class="text-muted small mb-3">Docente UCOT</p>
                    
                    <hr class="my-2 border-light">

                    <?php if (!empty($profe['materias'])): ?>
                        <div class="mb-3 mt-2">
                            <h6 class="text-uppercase text-muted x-small fw-bold mb-2" style="font-size: 0.7rem; letter-spacing: 1px;">
                                <i class="fas fa-graduation-cap me-1"></i> Materias
                            </h6>
                            <div class="d-flex flex-wrap justify-content-center gap-1">
                                <?php foreach($profe['materias'] as $m): ?>
                                    <span class="badge bg-soft-primary text-primary border border-primary-subtle rounded-pill fw-normal px-3">
                                        <?= esc($m['nombre_materia']) ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($profe['sistemas'])): ?>
                        <div class="mb-3">
                            <h6 class="text-uppercase text-muted x-small fw-bold mb-2" style="font-size: 0.7rem; letter-spacing: 1px;">
                                <i class="fas fa-laptop-house me-1"></i> Plataformas
                            </h6>
                            <div class="d-flex flex-wrap justify-content-center gap-1">
                                <?php foreach($profe['sistemas'] as $s): ?>
                                    <span class="badge bg-light text-dark border rounded-pill fw-normal px-3">
                                        <i class="fas fa-desktop me-1 text-muted small"></i> <?= esc($s['nombre']) ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="mt-auto pt-3">
                        <a href="<?= base_url('alumno/establecer_profesor/' . $profe['id_profesor']) ?>"
                           class="btn btn-primary w-100 rounded-pill py-2 shadow-sm"
                           style="background-color: var(--ucot-cian); border-color: var(--ucot-cian);">
                            Ver Horarios <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>

                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
    .bg-soft-primary { background-color: rgba(13, 110, 253, 0.08); }
    .card-hover-effect { transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .card-hover-effect:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important; }
    .x-small { font-size: 0.65rem; }
</style>

<?= $this->endSection() ?>