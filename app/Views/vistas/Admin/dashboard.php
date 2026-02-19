<?= $this->extend('Template/main') ?>
<?= $this->section('content') ?>

<main class="main-content">
    <div class="container-fluid py-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4 gap-3">
            <div>
                <h2 class="card-header-personalizado mb-1">Panel de Control UCOT</h2>
                <p class="text-muted mb-0">Resumen general de la plataforma en tiempo real.</p>
            </div>
            <div class="text-md-end">
                <span class="badge bg-white text-dark border px-3 py-2 rounded-pill shadow-sm">
                    <i class="fas fa-sync-alt text-primary me-2"></i>
                    Tasa USD/VES: <b>Bs. <?= number_format($tasa_dia, 2) ?></b>
                </span>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card-personalizada h-100 border-0 text-center p-4 shadow-sm">
                    <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background: rgba(51, 194, 209, 0.1); color: var(--ucot-cian);">
                        <i class="fas fa-chalkboard-teacher fa-lg"></i>
                    </div>
                    <h3 class="fw-bold mb-0"><?= $total_profesores ?></h3>
                    <p class="text-muted small text-uppercase fw-bold mb-0">Profesores</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card-personalizada h-100 border-0 text-center p-4 shadow-sm">
                    <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background: rgba(33, 37, 41, 0.1); color: var(--ucot-negro);">
                        <i class="fas fa-user-graduate fa-lg"></i>
                    </div>
                    <h3 class="fw-bold mb-0"><?= $total_estudiantes ?></h3>
                    <p class="text-muted small text-uppercase fw-bold mb-0">Estudiantes</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card-personalizada h-100 border-0 text-center p-4 shadow-sm">
                    <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background: rgba(51, 194, 209, 0.1); color: var(--ucot-cian);">
                        <i class="fas fa-calendar-check fa-lg"></i>
                    </div>
                    <h3 class="fw-bold mb-0"><?= $total_citas ?></h3>
                    <p class="text-muted small text-uppercase fw-bold mb-0">Citas Totales</p>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card-personalizada h-100 border-0 p-4 border-start border-4 border-primary shadow-sm bg-white">
                    <div class="d-flex align-items-center mb-4">
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-light text-primary" style="width: 48px; height: 48px;">
                            <i class="fas fa-wallet fa-lg"></i>
                        </div>
                        <div class="ms-3">
                            <h5 class="mb-0 fw-bold text-dark">Ingresos Brutos</h5>
                            <small class="text-muted">Total recolectado (Base en Bs.)</small>
                        </div>
                    </div>
                    <div class="row g-0 align-items-center">
                        <div class="col-6 border-end">
                            <div class="ps-2">
                                <h4 class="mb-0 fw-bold text-primary">Bs. <?= number_format($total_bruto_bs, 2, ',', '.') ?></h4>
                                <small class="text-muted fw-bold">TOTAL BS.</small>
                            </div>
                        </div>
                        <div class="col-6 ps-4">
                            <h4 class="mb-0 fw-bold text-dark">$<?= number_format($total_bruto_usd, 2) ?></h4>
                            <small class="text-muted fw-bold">EQUIVALENTE USD</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card-personalizada h-100 border-0 p-4 border-start border-4 border-success shadow-sm bg-white">
                    <div class="d-flex align-items-center mb-4">
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-light text-success" style="width: 48px; height: 48px;">
                            <i class="fas fa-chart-pie fa-lg"></i>
                        </div>
                        <div class="ms-3">
                            <h5 class="mb-0 fw-bold text-success">Ganancia Neta (15%)</h5>
                            <small class="text-muted">Comisión por servicios de UCOT</small>
                        </div>
                    </div>
                    <div class="row g-0 align-items-center">
                        <div class="col-6 border-end">
                            <div class="ps-2">
                                <h4 class="mb-0 fw-bold text-success">Bs. <?= number_format($ganancia_ucot_bs, 2, ',', '.') ?></h4>
                                <small class="text-muted fw-bold">GANANCIA BS.</small>
                            </div>
                        </div>
                        <div class="col-6 ps-4">
                            <h4 class="mb-0 fw-bold text-success">$<?= number_format($ganancia_ucot_usd, 2) ?></h4>
                            <small class="text-muted fw-bold">GANANCIA USD</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?= $this->endSection() ?>