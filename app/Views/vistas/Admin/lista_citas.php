<?= $this->extend('Template/main') ?>
<?= $this->section('content') ?>

<main class="main-content">
    <div class="container-fluid py-4">
        <div class="mb-4">
            <h2 class="card-header-personalizado mb-0">Supervisión de Citas</h2>
            <p class="text-muted">Listado global de todas las tutorías agendadas en el sistema (Solo lectura).</p>
        </div>

        <div class="card-personalizada border-0 mb-4 p-3">
            <form action="<?= base_url('admin/citas') ?>" method="get" class="row g-3 align-items-end">
                <div class="col-12 col-md-5">
                    <label class="form-label small fw-bold text-muted">Buscar</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="buscar" class="form-control border-start-0" 
                               placeholder="Materia, profesor o estudiante..." value="<?= $busqueda ?? '' ?>">
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label small fw-bold text-muted">Estado</label>
                    <select name="estado" class="form-select">
                        <option value="">Todos los estados</option>
                        <option value="pendiente" <?= ($estado_actual ?? '') == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                        <option value="confirmado" <?= ($estado_actual ?? '') == 'confirmado' ? 'selected' : '' ?>>Confirmado</option>
                        <option value="cancelado" <?= ($estado_actual ?? '') == 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-2"></i>Filtrar
                    </button>
                </div>
                <div class="col-6 col-md-2">
                    <a href="<?= base_url('admin/citas') ?>" class="btn btn-outline-secondary w-100">
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
                            <th class="ps-4">Estudiante</th>
                            <th>Profesor</th>
                            <th>Materia</th>
                            <th>Fecha y Hora</th>
                            <th class="text-center">Estado de la Cita</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($citas)): ?>
                            <?php foreach ($citas as $cita): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">
                                            <?= esc($cita['nombre_estudiante'] . ' ' . esc($cita['apellido_estudiante'] ?? '')) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-muted">
                                            <i class="fas fa-chalkboard-teacher me-1"></i> 
                                            <?= esc($cita['nombre_profesor'] . ' ' . esc($cita['apellido_profesor'] ?? '')) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border"><?= $cita['materia'] ?></span>
                                    </td>
                                    <td>
                                        <div class="small fw-bold"><?= date('d/m/Y', strtotime($cita['fecha_hora_inicio'])) ?></div>
                                        <div class="text-muted small"><?= date('h:i A', strtotime($cita['fecha_hora_inicio'])) ?></div>
                                    </td>
                                    <td class="text-center">
                                        <?php 
                                            $estadoActual = strtolower(trim($cita['estado_cita'])); 
                                            
                                            $clase = 'bg-secondary'; 
                                            if ($estadoActual == 'confirmado' || $estadoActual == 'confirmada') $clase = 'bg-success'; 
                                            if ($estadoActual == 'pendiente')  $clase = 'bg-warning text-dark'; 
                                            if ($estadoActual == 'cancelado' || $estadoActual == 'cancelada')  $clase = 'bg-danger';
                                        ?>
                                        <span class="badge <?= $clase ?> px-3 py-2 rounded-pill">
                                            <i class="fas fa-circle me-1 small"></i> <?= strtoupper($cita['estado_cita']) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fas fa-calendar-times fa-3x mb-3 d-block"></i>
                                    No se encontraron citas con los filtros aplicados.
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