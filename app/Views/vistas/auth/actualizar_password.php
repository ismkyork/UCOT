<?= $this->extend('Template/public_main') ?>

<?= $this->section('content_publico') ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card card-personalizada shadow-lg">
                <div class="card-body p-4 p-md-5">
                    
                    <div class="text-center mb-4">
                        <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-3" style="width: 70px; height: 70px;">
                            <i class="fas fa-key fa-2x" style="color: var(--ucot-cian);"></i>
                        </div>
                        <h3 class="fw-bold text-dark">Nueva Contraseña</h3>
                        <p class="text-muted small">Crea una clave segura para recuperar tu acceso.</p>
                    </div>

                    <form action="<?= base_url('auth/guardar_clave') ?>" method="POST" id="formReset">
                        <?= csrf_field() ?>
                        
                        <input type="hidden" name="token" value="<?= esc($token) ?>">

                        <div class="mb-3">
                            <label class="label-titulo">Nueva Contraseña</label>
                            <div class="position-relative d-flex align-items-center">
                                <input type="password" class="form-control form-control-personalizado w-100" 
                                       name="password" id="password" 
                                       placeholder="Mínimo 8 caracteres"
                                       oninput="actualizarSeguridad(this); validarCoincidencia();" required>
                                <div class="position-absolute end-0 me-3">
                                    <i id="toggleIcon" class="fas fa-eye text-muted cursor-pointer" onclick="togglePassword()"></i>
                                </div>
                            </div>
                            
                            <div class="progress mt-2 progress-security">
                                <div id="password-strength-bar" class="progress-bar" role="progressbar" style="width: 0%; transition: width 0.3s;"></div>
                            </div>
                            <small id="password-feedback" class="fw-bold mt-1 d-block text-danger feedback-text-min">Seguridad: Muy débil</small>
                        </div>

                        <div class="mb-4">
                            <label class="label-titulo">Confirmar Nueva</label>
                            <div class="position-relative d-flex align-items-center">
                                <input type="password" class="form-control form-control-personalizado w-100" 
                                       name="confirm_password" id="confirm_password" 
                                       placeholder="Repite la contraseña"
                                       oninput="validarCoincidencia()" required>
                                <div class="position-absolute end-0 me-3">
                                    <i id="match-icon" class="fas fa-lock text-muted"></i>
                                </div>
                            </div>
                            <small id="match-feedback" class="mt-1 d-block fw-bold feedback-text-min"></small>
                        </div>

                        <div class="d-grid">
                            <button type="submit" id="btn-submit" class="btn btn-ucot btn-lg shadow-sm" disabled>
                                <i class="fas fa-save me-2"></i> Actualizar Contraseña
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>