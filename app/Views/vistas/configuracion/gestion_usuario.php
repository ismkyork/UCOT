<?= $this->extend('Template/main') ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12 col-lg-4 mb-4">
        <div class="card-personalizada h-100">
            <div class="card-body">
                <div class="text-center mb-4 pb-3 border-bottom">
                    <div class="avatar-circle avatar-lg shadow-sm mx-auto mb-3">
                        <?php 
                            $foto_actual = $usuario['foto'] ?? session('foto'); 
                            if($foto_actual && $foto_actual != 'default.png' && file_exists(FCPATH . 'uploads/perfiles/' . $foto_actual)): 
                        ?>
                            <img src="<?= base_url('uploads/perfiles/' . $foto_actual) ?>" alt="Perfil">
                        <?php else: ?>
                            <span class="text-white h2 mb-0"><?= substr(session('nombre') ?? 'U', 0, 1) ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <h5 class="card-header-personalizado mb-0" style="font-size: 1.2rem;">
                        <?= session('nombre') ?> <?= session('apellido') ?>
                    </h5>
                    
                    <span class="badge rounded-pill mt-2 px-3 py-2 text-uppercase badge-rol-pill">
                        <?= ucfirst(session('rol')) ?>
                    </span>
                </div>

                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <button class="nav-link active d-flex align-items-center p-3 mb-2 rounded-4 border-0 nav-link-pill" 
                            id="v-pills-info-tab" data-bs-toggle="pill" data-bs-target="#v-pills-info" type="button" role="tab">
                        <div class="rounded-circle d-flex align-items-center justify-content-center icon-circle" 
                             style="background-color: rgba(51, 194, 209, 0.1); color: var(--ucot-blue);">
                            <i class="far fa-id-card fs-5"></i>
                        </div>
                        <span class="fw-bold text-dark">Información Personal</span>
                    </button>

                    <?php if (session('rol') == 'Profesor'): ?>
                        <button class="nav-link d-flex align-items-center p-3 mb-2 rounded-4 border-0 nav-link-pill" 
                                id="v-pills-pro-tab" data-bs-toggle="pill" data-bs-target="#v-pills-pro" type="button" role="tab">
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-success icon-circle">
                                <i class="fas fa-wallet fs-5"></i>
                            </div>
                            <span class="fw-bold text-dark">Tarifas y Cobros</span>
                        </button>

                        <button class="nav-link d-flex align-items-center p-3 mb-2 rounded-4 border-0 nav-link-pill" 
                                id="v-pills-materias-tab" data-bs-toggle="pill" data-bs-target="#v-pills-materias" type="button" role="tab">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-warning icon-circle" 
                                 style="background-color: rgba(255, 193, 7, 0.1);">
                                <i class="fas fa-book-open fs-5"></i>
                            </div>
                            <span class="fw-bold text-dark">Mis Materias</span>
                        </button>

                        <button class="nav-link d-flex align-items-center p-3 mb-2 rounded-4 border-0 nav-link-pill" 
                                id="v-pills-sistemas-tab" data-bs-toggle="pill" data-bs-target="#v-pills-sistemas" type="button" role="tab">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-primary icon-circle" 
                                 style="background-color: rgba(13, 110, 253, 0.1);">
                                <i class="fas fa-project-diagram fs-5"></i>
                            </div>
                            <span class="fw-bold text-dark">Sistemas de Clase</span>
                        </button>
                    <?php endif; ?>

                    <button class="nav-link d-flex align-items-center p-3 rounded-4 border-0 nav-link-pill" 
                            id="v-pills-security-tab" data-bs-toggle="pill" data-bs-target="#v-pills-security" type="button" role="tab">
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-danger icon-circle">
                            <i class="fas fa-shield-alt fs-5"></i>
                        </div>
                        <span class="fw-bold text-dark">Seguridad</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-8">
        <div class="card-ajustes-principal h-100 shadow-sm">
            <div class="card-body p-0"> 
                <div class="tab-content" id="v-pills-tabContent">
                    
                    <div class="tab-pane fade show active" id="v-pills-info" role="tabpanel">
                        <div class="p-4 border-bottom">
                            <h5 class="card-header-personalizado mb-1">Información Personal</h5>
                            <p class="text-muted small mb-0">Información básica de tu cuenta UCOT.</p>
                        </div>
                        <div class="list-group list-group-flush">
                            <a href="<?= base_url('configuracion/editar_foto') ?>" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between p-4 border-bottom">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-camera me-4 fs-4 icon-list-ucot"></i>
                                    <div>
                                        <span class="d-block fw-bold mb-0 text-dark">Imagen de perfil</span>
                                        <small class="text-muted">Cambia tu foto para que otros te reconozcan</small>
                                    </div>
                                </div>
                                <div class="avatar-circle avatar-sm shadow-sm">
                                    <?php if($foto_actual && $foto_actual != 'default.png' && file_exists(FCPATH . 'uploads/perfiles/' . $foto_actual)): ?>
                                        <img src="<?= base_url('uploads/perfiles/' . $foto_actual) ?>" alt="Perfil">
                                    <?php else: ?>
                                        <span class="text-white fw-bold"><?= substr(session('nombre') ?? 'U', 0, 1) ?></span>
                                    <?php endif; ?>
                                </div>
                            </a>
                            <a href="<?= base_url('configuracion/editar_nombre') ?>" class="list-group-item list-group-item-action p-4">
                                <div class="d-flex align-items-center">
                                    <i class="far fa-id-card me-4 fs-4 icon-list-ucot"></i>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="fw-bold mb-0 text-dark">Nombre completo</span>
                                            <i class="fas fa-chevron-right text-muted small"></i>
                                        </div>
                                        <span class="text-muted"><?= session('nombre') ?> <?= session('apellido') ?></span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <?php if (session('rol') == 'Profesor'): ?>
                        <div class="tab-pane fade" id="v-pills-pro" role="tabpanel">
                            <div class="p-4">
                                <h5 class="card-header-personalizado mb-4 border-bottom pb-3 text-success">
                                    <i class="fas fa-wallet me-2"></i> Gestión de Tarifas
                                </h5>
                                <form action="<?= base_url('configuracion/actualizar_precio') ?>" method="post">
                                    <div class="p-4 rounded-4 mb-4 container-tarifas-pro">
                                        <div class="row align-items-center">
                                            <div class="col-md-6 mb-4">
                                                <label class="label-titulo text-cian">PRECIO POR CLASE ($)</label>
                                                <div class="input-group shadow-sm">
                                                    <span class="input-group-text border-0 text-white input-group-text-cian">
                                                        <i class="fas fa-dollar-sign"></i>
                                                    </span>
                                                    <input type="number" step="0.01" name="precio_clase" id="precio_clase" class="form-control border-0 price-input-bold" value="<?= $usuario['precio_clase'] ?? '0.00' ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="p-3 bg-white rounded-4 shadow-sm card-bcv-info">
                                                    <small class="text-muted fw-bold d-block text-uppercase mb-1 label-bcv-small">A COBRAR EN BS (BCV):</small>
                                                    <div class="d-flex align-items-baseline gap-1">
                                                        <span class="h2 fw-bold mb-0 bcv-price-text" id="calc_bs">0,00</span>
                                                        <span class="fw-bold text-muted small">Bs.</span>
                                                    </div>
                                                    <div class="badge mt-2 bg-light text-dark border">Tasa: <?= number_format($tasa_bcv ?? 0, 2) ?> Bs.</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-success rounded-pill px-4">Actualizar Tarifa</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="v-pills-materias" role="tabpanel">
                            <div class="p-4">
                                <h5 class="card-header-personalizado mb-1" style="color: var(--ucot-naranja);">
                                    <i class="fas fa-graduation-cap me-2"></i> Materias que imparto
                                </h5>
                                <p class="text-muted small mb-4">Selecciona las áreas de conocimiento en las que ofreces asesorías.</p>
                                <form action="<?= base_url('configuracion/actualizar_materias_vinculo') ?>" method="post">
                                    <div class="row g-3">
                                        <?php if(isset($todas_las_materias)): foreach ($todas_las_materias as $materia): ?>
                                            <div class="col-md-6">
                                                <div class="d-flex align-items-center justify-content-between p-3 rounded-4 border shadow-sm materia-item-card">
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-circle icon-materia-circle shadow-sm me-3">
                                                            <i class="fas fa-book small"></i>
                                                        </div>
                                                        <span class="fw-bold text-dark"><?= $materia['nombre_materia'] ?></span>
                                                    </div>
                                                    <div class="form-check form-switch fs-4">
                                                        <input class="form-check-input cursor-pointer" type="checkbox" name="materias[]" value="<?= $materia['id_materia'] ?>" <?= (isset($mis_materias) && in_array($materia['id_materia'], $mis_materias)) ? 'checked' : '' ?>>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; endif; ?>
                                    </div>
                                    <div class="text-end mt-4 pt-3 border-top">
                                        <button type="submit" class="btn btn-primary text-white rounded-pill px-4 btn-guardar-materias">
                                            <i class="fas fa-save me-2"></i> Guardar Materias
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="v-pills-sistemas" role="tabpanel">
                            <div class="p-4">
                                <h5 class="card-header-personalizado mb-1 text-primary">Sistemas de Clase</h5>
                                <p class="text-muted small mb-4">Selecciona las plataformas que utilizas.</p>
                                <form action="<?= base_url('configuracion/guardar_sistemas') ?>" method="post">
                                    <div class="row g-3">
                                        <?php if(isset($todos_los_sistemas)): foreach ($todos_los_sistemas as $sistema): ?>
                                            <div class="col-md-6">
                                                <div class="d-flex align-items-center justify-content-between p-3 rounded-4 border bg-light">
                                                    <span class="fw-bold text-dark"><?= $sistema['nombre'] ?></span>
                                                    <div class="form-check form-switch fs-4">
                                                        <input class="form-check-input" type="checkbox" name="sistemas[]" value="<?= $sistema['id'] ?>" <?= (isset($mis_sistemas) && in_array($sistema['id'], $mis_sistemas)) ? 'checked' : '' ?>>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; endif; ?>
                                    </div>
                                    <div class="text-end mt-4">
                                        <button type="submit" class="btn btn-primary rounded-pill px-4">Guardar Cambios</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="tab-pane fade" id="v-pills-security" role="tabpanel">
                        <div class="p-4">
                            <h5 class="card-header-personalizado mb-4 border-bottom pb-3 text-danger">
                                <i class="fas fa-lock me-2"></i> Cambiar Contraseña
                            </h5>
                            <form action="<?= base_url('configuracion/cambiar_password') ?>" method="post">
                                <div class="mb-4">
                                    <label class="label-titulo">Contraseña Actual</label>
                                    <div class="position-relative d-flex align-items-center">
                                        <input type="password" class="form-control-personalizado bg-light w-100" name="password_actual" id="password_actual" placeholder="••••••••" required>
                                        <div class="position-absolute end-0 me-3">
                                            <i class="fas fa-eye text-muted cursor-pointer" onclick="togglePasswordById('password_actual', this)"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="label-titulo">Nueva Contraseña</label>
                                        <div class="position-relative d-flex align-items-center">
                                            <input type="password" class="form-control-personalizado w-100" name="password_nueva" id="password" oninput="actualizarSeguridad(this); validarCoincidencia();" required>
                                            <div class="position-absolute end-0 me-3">
                                                <i id="toggleIcon" class="fas fa-eye text-muted cursor-pointer" onclick="togglePassword()"></i>
                                            </div>
                                        </div>
                                        <div class="progress mt-2 progress-security">
                                            <div id="password-strength-bar" class="progress-bar" role="progressbar"></div>
                                        </div>
                                        <small id="password-feedback" class="fw-bold mt-1 d-block text-danger feedback-text-min">Seguridad: Muy débil</small>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label class="label-titulo">Confirmar Nueva</label>
                                        <div class="position-relative d-flex align-items-center">
                                            <input type="password" class="form-control-personalizado w-100" name="password_confirmar" id="confirm_password" oninput="validarCoincidencia()" required>
                                            <div class="position-absolute end-0 me-3">
                                                <i id="match-icon" class="fas fa-lock text-muted"></i>
                                            </div>
                                        </div>
                                        <small id="match-feedback" class="mt-1 d-block fw-bold feedback-text-min"></small>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <button type="submit" id="btn-submit" class="btn btn-primary rounded-pill px-4" disabled>Actualizar Clave</button>
                                </div>
                            </form>
                        </div>
                    </div>                   
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.tasaBCV = <?= isset($tasa_bcv) ? $tasa_bcv : 0 ?>;

    document.addEventListener("DOMContentLoaded", function() {
        const inputDolar = document.getElementById('precio_clase');
        const spanBs = document.getElementById('calc_bs');
        const tasa = window.tasaBCV;

        function calcular() {
            let dolares = parseFloat(inputDolar.value) || 0;
            let resultado = dolares * tasa;
            spanBs.innerText = resultado.toLocaleString('es-VE', { minimumFractionDigits: 2 });
        }

        if(inputDolar) {
            inputDolar.addEventListener('input', calcular);
            calcular();
        }
    });
</script>
<?= $this->endSection() ?>