<?= $this->extend('Template/main') ?>
<?= $this->section('content') ?>

<main class="main-content">
    <div class="container py-4">
        <div class="card-personalizada col-lg-8 mx-auto">
            <h2 class="card-header-personalizado mb-4">Editar Profesor</h2>
            
            <form action="<?= base_url('admin/actualizar_profesor/' . $profesor['id_auth']) ?>" method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Nombre</label>
                        <input type="text" name="nombre" class="form-control-personalizado" value="<?= $profesor['nombre_profesor'] ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Apellido</label>
                        <input type="text" name="apellido" class="form-control-personalizado" value="<?= $profesor['apellido_profesor'] ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Correo Electrónico</label>
                    <input type="email" name="correo" class="form-control-personalizado" value="<?= $profesor['correo'] ?>" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Estado de Cuenta</label>
                        <select name="status" class="form-control-personalizado">
                            <option value="activo" <?= $profesor['status'] == 'activo' ? 'selected' : '' ?>>Activo</option>
                            <option value="pendiente" <?= $profesor['status'] == 'pendiente' ? 'selected' : '' ?>>Pendiente / Suspendido</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Nueva Contraseña (Opcional)</label>
                        <input type="password" name="password" class="form-control-personalizado" placeholder="Dejar en blanco para no cambiar">
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn-concept-azul">
                        <i class="fas fa-sync-alt me-2"></i> Actualizar Datos
                    </button>
                    <a href="<?= base_url('admin/profesores') ?>" class="btn btn-light rounded-pill px-4 border">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</main>

<?= $this->endSection() ?>