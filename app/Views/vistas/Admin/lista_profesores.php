<?= $this->extend('Template/main') ?>
<?= $this->section('content') ?>

<main class="main-content">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="card-header-personalizado mb-0">Gestión de Profesores</h2>
                <p class="text-muted">Panel de administración de personal docente.</p>
            </div>
            <a href="<?= base_url('admin/nuevo_profesor') ?>" class="btn-concept-azul text-decoration-none">
                <i class="fas fa-plus me-2"></i> Nuevo Profesor
            </a>
        </div>

        <?php if (session()->getFlashdata('msg')): ?>
            <div class="alert alert-success border-0 shadow-sm" role="alert" style="border-radius: 15px;">
                <i class="fas fa-check-circle me-2"></i> <?= session()->getFlashdata('msg') ?>
            </div>
        <?php endif; ?>

        <div class="card-personalizada border-0 mb-4 p-3 shadow-sm">
            <form action="<?= base_url('admin/profesores') ?>" method="get" class="row g-3 align-items-end">
                <div class="col-12 col-md-5">
                    <label class="form-label small fw-bold text-muted">Buscar Profesor</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="buscar" class="form-control border-start-0" 
                               placeholder="Nombre, apellido o correo..." value="<?= $busqueda ?? '' ?>">
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label small fw-bold text-muted">Estado de Cuenta</label>
                    <select name="status" class="form-select">
                        <option value="">Todos</option>
                        <option value="activo" <?= ($status_actual ?? '') == 'activo' ? 'selected' : '' ?>>Activo</option>
                        <option value="inactivo" <?= ($status_actual ?? '') == 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-2"></i>Filtrar
                    </button>
                </div>
                <div class="col-6 col-md-2">
                    <a href="<?= base_url('admin/profesores') ?>" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-sync-alt me-2"></i>Limpiar
                    </a>
                </div>
            </form>
        </div>

        <div class="card-personalizada border-0">
            <div class="table-responsive">
                <table class="table table-personalizada mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Profesor</th>
                            <th>Correo Electrónico</th>
                            <th>Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($profesores)): ?>
                            <?php foreach ($profesores as $profe): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-3 d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px; background: var(--ucot-cian); color: white; border-radius: 50%; font-weight: bold;">
                                                <?= strtoupper(substr($profe['nombre_profesor'], 0, 1)) ?>
                                            </div>
                                            <div>
                                                <span class="fw-bold"><?= $profe['nombre_profesor'] . ' ' . $profe['apellido_profesor'] ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?= $profe['correo'] ?></td>
                                    <td>
                                        <?php if ($profe['status'] == 'activo'): ?>
                                            <span class="badge rounded-pill px-3" style="background-color: rgba(51, 194, 209, 0.2); color: var(--ucot-cian); border: 1px solid var(--ucot-cian);">Activo</span>
                                        <?php else: ?>
                                            <span class="badge rounded-pill px-3" style="background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba;">Inactivo / Pendiente</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="<?= base_url('admin/perfil_profesor/' . $profe['id_auth']) ?>" 
                                                class="btn btn-sm shadow-sm" 
                                                title="Ver Dashboard"
                                                style="background: white; border-radius: 8px; border: 1px solid #eee;">
                                                <i class="fas fa-chart-line" style="color: var(--ucot-azul);"></i>
                                            </a>

                                            <a href="<?= base_url('admin/editar_profesor/' . $profe['id_auth']) ?>" 
                                                class="btn btn-sm shadow-sm" 
                                                title="Editar" 
                                                style="background: white; border-radius: 8px; border: 1px solid #eee;">
                                                <i class="fas fa-edit" style="color: var(--ucot-cian);"></i>
                                            </a>

                                            <a href="<?= base_url('admin/eliminar_profesor/' . $profe['id_auth']) ?>" 
                                                class="btn btn-sm btn-danger rounded-pill shadow-sm" 
                                                title="Eliminar"
                                                onclick="return confirm('¿Seguro que quieres eliminar a este profesor? Esta acción no se puede deshacer.')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="fas fa-user-slash fa-3x mb-3 d-block"></i>
                                    No se encontraron profesores con los filtros aplicados.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?= $this->endSection() ?>