<?= $this->extend('Template/main') ?>
<?= $this->section('content') ?>

<main class="main-content">
    <div class="container-fluid py-4">
        <div class="d-flex align-items-center mb-4">
            <a href="<?= base_url('admin/profesores') ?>" class="btn btn-light rounded-circle me-3 shadow-sm">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="mb-0">Expediente: <?= $profesor['nombre_profesor'] . ' ' . $profesor['apellido_profesor'] ?></h2>
                <span class="badge bg-<?= $profesor['status'] == 'activo' ? 'success' : 'warning' ?> rounded-pill">
                    Estado: <?= strtoupper($profesor['status']) ?>
                </span>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-3 text-center" style="border-radius: 20px;">
                    <div class="text-muted small fw-bold">RECAUDACIÓN TOTAL</div>
                    <h3 class="text-primary mt-2">Bs. <?= number_format($finanzas['total_bruto'], 2) ?></h3>
                    <div class="small text-muted">Bruto acumulado</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-3 text-center" style="border-radius: 20px; border-left: 5px solid #28a745 !important;">
                    <div class="text-muted small fw-bold">PARA EL PROFESOR (85%)</div>
                    <h3 class="text-success mt-2">Bs. <?= number_format($finanzas['ganancia_profe'], 2) ?></h3>
                    <div class="small text-muted">Neto a transferir</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm p-3 text-center" style="border-radius: 20px; border-left: 5px solid var(--ucot-azul) !important;">
                    <div class="text-muted small fw-bold">COMISIÓN UCOT (15%)</div>
                    <h3 class="text-info mt-2">Bs. <?= number_format($finanzas['comision_ucot'], 2) ?></h3>
                    <div class="small text-muted">Ganancia plataforma</div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card-personalizada p-4 mb-4">
                    <h5 class="mb-3">Información de Contacto</h5>
                    <div class="mb-3">
                        <label class="text-muted d-block small">Correo Electrónico</label>
                        <strong><?= $profesor['correo'] ?></strong>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted d-block small">Total de Tutorías</label>
                        <strong><?= $finanzas['total_citas'] ?> clases dictadas</strong>
                    </div>
                    <hr>
                    <a href="<?= base_url('admin/editar_profesor/'.$profesor['id_auth']) ?>" class="btn btn-outline-primary btn-sm w-100">
                        <i class="fas fa-edit me-1"></i> Editar Datos
                    </a>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card-personalizada p-0">
                    <div class="p-4 border-bottom">
                        <h5 class="mb-0">Actividad Reciente del Profesor</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Materia</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($ultimas_citas)): ?>
                                    <?php foreach($ultimas_citas as $cita): ?>
                                    <tr>
                                        <td class="ps-4 fw-bold"><?= $cita['materia'] ?></td>
                                        <td><?= date('d/m/Y', strtotime($cita['fecha_hora_inicio'])) ?></td>
                                        <td>
                                            <span class="badge bg-<?= ($cita['estado_cita'] == 'confirmado') ? 'success' : 'secondary' ?> rounded-pill px-3">
                                                <?= strtoupper($cita['estado_cita']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center py-4">Sin actividad registrada.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?= $this->endSection() ?>