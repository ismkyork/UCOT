<?= $this->extend('Template/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12 mb-4">
            <h1 class="card-header-personalizado">Gestión de Cuenta</h1>
            <p class="text-muted">Actualiza tu información personal y mantén tu cuenta segura.</p>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card-personalizada text-center">
                <div class="mb-3">
                    <i class="fas fa-user-circle fa-5x" style="color: var(--ucot-cian);"></i>
                </div>
                <h4 class="fw-bold mb-1"><?= session()->get('nombre') ?></h4>
                <span class="badge-ucot badge-confirmada mb-3">
                    <?= session()->get('rol') ?>
                </span>
                <hr>
                <p class="small text-muted px-3">
                    Como <b><?= session()->get('rol') ?></b>, puedes modificar tu nombre de visualización y tu clave de acceso. El correo electrónico está vinculado a tu identidad académica.
                </p>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card-personalizada">
                <div class="card-header-personalizado mb-4">
                    <i class="fas fa-edit me-2"></i> Editar Información
                </div>

                <?php if (session()->getFlashdata('msg')): ?>
                    <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 12px;">
                        <i class="fas fa-check-circle me-2"></i> <?= session()->getFlashdata('msg') ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('configuracion/actualizar') ?>" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="label-titulo">Nombre</label>
                            <input type="text" name="nombre" class="form-control-personalizado w-100" 
                                   value="<?= session()->get('nombre') ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="label-titulo">Apellido</label>
                            <input type="text" name="apellido" class="form-control-personalizado w-100" 
                                   placeholder="Ingresa tu apellido" required>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="label-titulo">Correo Electrónico (No editable)</label>
                            <input type="email" class="form-control-personalizado w-100" 
                                   value="<?= session()->get('email') ?>" 
                                   style="background-color: #f8f9fa; cursor: not-allowed;" disabled>
                        </div>

                        <div class="col-12 mb-4">
                            <label class="label-titulo">Nueva Contraseña</label>
                            <input type="password" name="password" class="form-control-personalizado w-100" 
                                   placeholder="Deja en blanco para mantener la actual">
                            <small class="text-muted mt-1 d-block">Mínimo 5 caracteres para mayor seguridad.</small>
                        </div>

                        <div class="col-12 d-flex justify-content-end gap-2">
                            <button type="reset" class="btn-redondeado btn-ucot-danger">
                                <i class="fas fa-times me-2"></i> Cancelar
                            </button>
                            <button type="submit" class="btn-redondeado btn-ucot-success">
                                <i class="fas fa-save me-2"></i> Guardar Cambios
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>