<?= $header ?>
<?= $menu ?>

<style>
    :root {
        --ucot-cian: #00bcd4;
        --ucot-negro: #212529;
    }

    /* ESTILOS DE PANTALLA */
    .factura-container { max-width: 850px; margin-bottom: 50px; }
    .card-personalizada {
        background: white; border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1); padding: 40px; border: none;
    }
    .rif-text { font-size: 0.9rem; color: #666; font-weight: bold; }
    .label-titulo { font-size: 0.75rem; font-weight: 800; color: var(--ucot-cian); letter-spacing: 1px; }
    .table-personalizada thead { background-color: var(--ucot-negro); color: white; }
    .badge-ucot { padding: 5px 15px; border-radius: 50px; font-weight: bold; font-size: 0.8rem; }
    .badge-confirmada { background: #d1fae5; color: #065f46; }

    /* ESTILOS DE IMPRESIÓN (PDF) */
    @media print {
        .main-sidebar, .sidebar, .main-header, .navbar, .footer, .main-footer, .no-print, .btn, aside, .content-header { 
            display: none !important; 
        }
        .content-wrapper { margin-left: 0 !important; padding: 0 !important; border: none !important; }
        * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        body { background-color: white !important; }
        .container, .factura-container { width: 100% !important; max-width: 100% !important; margin: 0 !important; padding: 0 !important; }
        .card-personalizada { box-shadow: none !important; border: 1px solid #ddd !important; }
    }
</style>

<div class="content-wrapper">
    <div class="container mt-5 factura-container">
        <div class="card-personalizada">
            <div class="row align-items-center border-bottom pb-3 mb-4">
                <div class="col-6">
                    <h2 class="mb-0 fw-bold" style="color: var(--ucot-cian);">UCOT</h2>
                    <p class="rif-text mb-0">RIF: J-41234567-0</p>
                    <p class="text-muted small mb-0">U Class On Time C.A. | La Guaira, Venezuela</p>
                </div>
                <div class="col-6 text-end">
                    <h4 class="fw-bold mb-0">FACTURA DIGITAL</h4>
                    <p class="mb-0 fw-bold text-danger">N° <?= esc($pago['id_pago']) ?></p>
                    <p class="text-muted small">Emisión: <?= date('d/m/Y', strtotime($pago['fecha_pago'] ?? date('Y-m-d'))) ?></p>
                </div>
            </div>

            <div class="row mb-5">
                <div class="col-6">
                    <label class="label-titulo text-uppercase">Estudiante</label>
                    <p class="h5 fw-bold"><?= esc($pago['nombre_alumno']) ?> <?= esc($pago['apellido_alumno'] ?? '') ?></p>
                </div>
                <div class="col-6 text-end">
                    <label class="label-titulo text-uppercase">Profesor</label>
                    <p class="h5 fw-bold"><?= esc($pago['nombre_profesor']) ?> <?= esc($pago['apellido_profesor'] ?? '') ?></p>
                </div>
            </div>

            <?php 
                // LÓGICA DE CONVERSIÓN INTELIGENTE
                $es_paypal = (strpos($pago['id_pago'], 'PAYPAL-') !== false);

                if ($es_paypal) {
                    // Si es PayPal, el monto en la base de datos ya está en USD
                    $total_usd = (float)$pago['monto'];
                    $total_bs  = $total_usd * (float)$tasa_bcv;
                } else {
                    // Si es Pago Móvil / Transferencia, el monto está en BS
                    $total_bs  = (float)$pago['monto'];
                    $total_usd = $total_bs / (float)$tasa_bcv;
                }

                $base_iva = $total_usd / 1.16;
                $iva = $total_usd - $base_iva;
            ?>

            <table class="table table-personalizada mb-4">
                <thead>
                    <tr>
                        <th style="width: 50%;">Descripción</th>
                        <th class="text-center">Cant.</th>
                        <th class="text-end">Base ($)</th>
                        <th class="text-end">Total ($)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <span class="fw-bold">Asesoría Académica: <?= esc($pago['materia'] ?? 'Asesoría General') ?></span><br>
                            <small class="text-muted">Cita: <?= date('d/m/Y H:i', strtotime($pago['fecha_hora_inicio'])) ?></small>
                        </td>
                        <td class="text-center">1</td>
                        <td class="text-end"><?= number_format($base_iva, 2) ?></td>
                        <td class="text-end fw-bold">$<?= number_format($total_usd, 2) ?></td>
                    </tr>
                </tbody>
            </table>

            <div class="row">
                <div class="col-7">
                    <div class="p-3 bg-light rounded shadow-sm border-start border-primary border-4">
                        <p class="small mb-1"><strong>Estado del Pago:</strong></p>
                        <span class="badge-ucot badge-confirmada text-uppercase">
                            <i class="fas fa-check-circle me-1"></i> <?= esc($pago['estado_pago']) ?>
                        </span>
                        <p class="small mt-2 mb-0"><strong>Método:</strong> <?= $es_paypal ? 'PayPal (Automático)' : 'Pago Móvil / Transferencia' ?></p>
                        <p class="small mt-1 mb-0"><strong>Referencia:</strong> <?= esc($pago['id_pago']) ?></p>
                    </div>
                </div>
                <div class="col-5">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td class="text-end">Subtotal:</td>
                            <td class="text-end">$<?= number_format($base_iva, 2) ?></td>
                        </tr>
                        <tr>
                            <td class="text-end">I.V.A. (16%):</td>
                            <td class="text-end">$<?= number_format($iva, 2) ?></td>
                        </tr>
                        <tr class="border-top">
                            <td class="text-end fw-bold">TOTAL USD:</td>
                            <td class="text-end fw-bold text-primary">$<?= number_format($total_usd, 2) ?></td>
                        </tr>
                        <tr>
                            <td class="text-end fw-bold">TOTAL BS:</td>
                            <td class="text-end fw-bold text-dark">Bs. <?= number_format($total_bs, 2, ',', '.') ?></td>
                        </tr>
                    </table>
                    <p class="text-end text-muted" style="font-size: 10px;">Tasa de cambio (BCV): Bs. <?= number_format($tasa_bcv, 2, ',', '.') ?></p>
                </div>
            </div>

            <div class="text-center no-print mt-5">
                <button onclick="window.print()" class="btn btn-info text-white rounded-pill px-4 shadow">
                    <i class="fas fa-print me-2"></i> Imprimir Factura
                </button>
                <a href="<?= base_url('alumno/comprobantes_pagos') ?>" class="btn btn-secondary rounded-pill px-4 ms-2">Volver</a>
            </div>
        </div>
    </div>
</div>

<?= $footer ?>