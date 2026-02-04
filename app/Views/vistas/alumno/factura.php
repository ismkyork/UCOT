<?= $header ?>
<?= $menu ?>

<style>
    @media print {
        nav, footer, .btn, .no-print, .main-footer {
            display: none !important;
        }
        body {
            background-color: white !important;
        }
        .container {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        .card {
            border: 1px solid #eee !important;
            box-shadow: none !important;
        }
        .factura-container {
            margin-top: 0 !important;
        }
    }

    .factura-container {
        max-width: 850px;
        margin-bottom: 50px;
    }
    .card-header-ucot {
        border-bottom: 3px solid #007bff;
    }
</style>

<div class="container mt-5 factura-container">
    <div class="card shadow-sm">
        <div class="card-header bg-white py-4 card-header-ucot">
            <div class="row align-items-center">
                <div class="col-6">
                    <h2 class="mb-0 text-primary fw-bold">UCOT</h2>
                    <p class="mb-0 text-muted">U Class On Time | Comprobante de Asesoría</p>
                </div>
                <div class="col-6 text-end">
                    <h5 class="mb-1">Factura N°: <span class="text-dark"><?= $pago['id_pago'] ?></span></h5>
                    <p class="mb-0 text-muted">Fecha Pago: <?= date('d/m/Y', strtotime($pago['fecha_pago'])) ?></p>
                </div>
            </div>
        </div>
        
        <div class="card-body p-4">
            <div class="row mb-5">
                <div class="col-6">
                    <h6 class="text-muted text-uppercase fw-bold small">Estudiante:</h6>
                    <p class="h5"><?= esc($pago['nombre_alumno']) ?></p>
                </div>
                <div class="col-6 text-end">
                    <h6 class="text-muted text-uppercase fw-bold small">Profesor Asignado:</h6>
                    <p class="h5"><?= esc($pago['nombre_profesor']) ?></p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered mt-2">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 50%;">Descripción del Servicio</th>
                            <th class="text-center">Fecha y Hora Programada</th>
                            <th class="text-end">Monto Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <strong>Asesoría académica:</strong> <?= esc($pago['materia']) ?><br>
                                <small class="text-muted">Servicio de gestión de clases particulares vía UCOT</small>
                            </td>
                            <td class="text-center align-middle">
                                <?= date('d/m/Y', strtotime($pago['fecha_hora_inicio'])) ?><br>
                                <span class="badge bg-light text-dark border"><?= date('H:i', strtotime($pago['fecha_hora_inicio'])) ?> hrs</span>
                            </td>
                            <td class="text-end align-middle">
                                <span class="h5">$<?= number_format($pago['monto'], 2) ?></span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="row mt-4 justify-content-end">
                <div class="col-md-5 text-end">
                    <div class="p-3 bg-light rounded">
                        <h4 class="text-primary mb-2">Total Pagado: $<?= number_format($pago['monto'], 2) ?></h4>
                        <p class="mb-0">
                            <strong>Estado:</strong> 
                            <span class="badge <?= ($pago['estado_pago'] == 'confirmado' || $pago['estado_pago'] == 'completado') ? 'bg-success' : 'bg-warning text-dark' ?>">
                                <?= strtoupper($pago['estado_pago']) ?>
                            </span>
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="mt-5 pt-4 border-top">
                <p class="text-center text-muted small">
                    Este documento es un comprobante oficial emitido por la plataforma <strong>UCOT</strong>.<br>
                    Si tienes dudas sobre este pago, contacta al soporte técnico o a tu profesor.
                </p>
            </div>
        </div>

        <div class="card-footer text-center bg-white py-3 no-print">
            <button onclick="window.print()" class="btn btn-primary btn-lg me-2">
                <i class="fas fa-print me-2"></i> Imprimir o Guardar PDF
            </button>
            <a href="<?= base_url('alumno/mis_citas') ?>" class="btn btn-outline-secondary btn-lg">
                <i class="fas fa-arrow-left me-2"></i> Volver a mis citas
            </a>
        </div>
    </div>
</div>

<?= $footer ?>