<?= $this->extend('Template/main') ?>
<?= $this->section('content') ?>

<main class="main-content">
    <div class="container-fluid py-4">
        <div class="mb-4">
            <h2 class="card-header-personalizado mb-0">Validación de Pagos</h2>
            <p class="text-muted">Revisa y aprueba las transferencias de los estudiantes.</p>
        </div>

        <?php if (session()->getFlashdata('msg')): ?>
            <div class="alert alert-success border-0 shadow-sm" style="border-radius:15px;">
                <i class="fas fa-check-circle me-2"></i> <?= session()->getFlashdata('msg') ?>
            </div>
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger border-0 shadow-sm" style="border-radius:15px;">
                <i class="fas fa-exclamation-circle me-2"></i> <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <div class="card-personalizada border-0">
            <div class="table-responsive">
                <table class="table table-personalizada mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Estudiante</th>
                            <th>Monto</th>
                            <th>Referencia / ID</th>
                            <th>Comprobante</th>
                            <th>Estado</th>
                            <th class="text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pagos as $p): ?>
                        <tr>
                            <td class="ps-4">
                                <span class="fw-bold"><?= $p['nombre_estudiante'] . ' ' . $p['apellido_estudiante'] ?></span><br>
                                <small class="text-muted"><?= $p['materia'] ?></small>
                            </td>
                            <td class="fw-bold text-success">$<?= number_format($p['monto'], 2) ?></td>
                            <td><code><?= $p['id_pago'] ?></code></td>
                            <td>
                                <?php if($p['screenshot'] == 'CONFIRMADO_PAYPAL'): ?>
                                    <span class="badge bg-primary"><i class="fab fa-paypal"></i> PayPal Auto</span>
                                <?php else: ?>
                                    <a href="<?= base_url('uploads/comprobantes/' . $p['screenshot']) ?>" target="_blank" class="btn btn-sm btn-outline-info rounded-pill">
                                        <i class="fas fa-image"></i> Ver Foto
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge rounded-pill px-3 <?= $p['estado_pago'] == 'confirmado' ? 'bg-success' : 'bg-warning text-dark' ?>">
                                    <?= strtoupper($p['estado_pago']) ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <?php if ($p['estado_pago'] == 'pendiente'): ?>
                                    <a href="<?= base_url('admin/confirmar_pago/' . $p['id_pago']) ?>" 
                                       class="btn btn-sm btn-success rounded-pill px-3"
                                       onclick="return confirm('¿Confirmas que el dinero ya está en cuenta? Esto bloqueará el horario definitivamente.')">
                                        <i class="fas fa-check me-1"></i> Aprobar
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted small"><i class="fas fa-lock"></i> Verificado</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?= $this->endSection() ?>