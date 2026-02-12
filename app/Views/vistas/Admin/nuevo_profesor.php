<?= $this->extend('Template/main') ?>
<?= $this->section('content') ?>

<main class="main-content">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card-personalizada">
                    <div class="card-body">
                        <h2 class="card-header-personalizado text-center mb-4">Registrar Nuevo Docente</h2>
                        
                        <form action="<?= base_url('admin/guardar_profesor') ?>" method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Nombre</label>
                                    <input type="text" name="nombre" class="form-control-personalizado w-100" placeholder="Ej. Juan" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Apellido</label>
                                    <input type="text" name="apellido" class="form-control-personalizado w-100" placeholder="Ej. Pérez" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Correo Electrónico</label>
                                <input type="email" name="correo" class="form-control-personalizado w-100" placeholder="usuario@ucot.com" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Contraseña Temporal</label>
                                <input type="password" name="password" class="form-control-personalizado w-100" placeholder="********" required>
                                <small class="text-muted">El profesor deberá cambiarla al iniciar sesión por primera vez.</small>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn-concept-azul">
                                    <i class="fas fa-save me-2"></i> Crear Cuenta de Profesor
                                </button>
                                <a href="<?= base_url('admin/profesores') ?>" class="text-center text-muted mt-2 text-decoration-none">
                                    Cancelar y volver
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?= $this->endSection() ?>