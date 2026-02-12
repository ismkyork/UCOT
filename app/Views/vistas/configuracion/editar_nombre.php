<?= $this->extend('Template/main') ?>

<?= $this->section('content') ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-5">

            <div class="card card-personalizada shadow-lg border-0 rounded-4">
                <div class="card-body p-4 p-md-5">

                    <div class="text-center mb-4">
                        <h4 class="card-header-personalizado">CAMBIAR NOMBRE</h4>
                        <p class="text-muted small">Actualiza cómo te ven los demás en la plataforma</p>
                    </div>
                    
                    <form action="<?= base_url('configuracion/actualizar') ?>" method="post">
                        
                        <div class="form-group mb-4">
                            <label class="fw-bold mb-2">Nombre</label>
                            <input type="text" name="nombre" 
                                   class="form-control form-control-personalizado" 
                                   value="<?= $usuario['nombre_profesor'] ?? $usuario['nombre_estudiante'] ?? session('nombre') ?>" 
                                   placeholder="Ej: Jose" required>
                        </div>

                        <div class="form-group mb-4">
                            <label class="fw-bold mb-2">Apellido</label>
                            <input type="text" name="apellido" 
                                   class="form-control form-control-personalizado" 
                                   value="<?= $usuario['apellido_profesor'] ?? $usuario['apellido_estudiante'] ?? session('apellido') ?>" 
                                   placeholder="Ej: Marcano" required>
                        </div>

                        <div class="d-grid gap-2 mt-5">
                            <button type="submit" class="btn btn-ucot-primary btn-redondeado btn-lg shadow">
                                <i class="fas fa-save me-2"></i> GUARDAR CAMBIOS
                            </button>
                        
                             <a href="<?= base_url('configuracion') ?>" class="btn btn-ucot-danger btn-redondeado text-decoration-none text-white">
                                CANCELAR
                            </a> 
                        </div>

                    </form>
                </div>
            </div>

            <div class="mt-4 text-center">
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i> 
                    Tus cambios se verán reflejados inmediatamente.
                </small>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection() ?>