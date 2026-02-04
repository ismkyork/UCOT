<?= $this->extend('Template/main') ?>

<?= $this->section('content') ?>
<div class="container mt-5">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="card-header-personalizado" style="font-size: 1.8rem;">Agendar Nueva Asesoría</h2>
            <p class="text-muted">Selecciona el horario y la materia para tu próxima clase en UCOT.</p>
        </div>
    </div>

    <?php if(session('errors')): ?>
        <div class="alert alert-danger shadow-sm border-0" style="border-radius: 15px;">
            <div class="fw-bold mb-1"><i class="fas fa-exclamation-triangle me-2"></i> Por favor corrige lo siguiente:</div>
            <ul class="mb-0 small">
                <?php foreach(session('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if(session('success')): ?>
        <div class="alert btn-ucot-success text-white shadow-sm border-0" style="border-radius: 15px; opacity: 1;">
            <i class="fas fa-check-circle me-2"></i> <?= esc(session('success')) ?>
        </div>
    <?php endif; ?>

    <form action="<?= site_url('alumno/store_citas') ?>" method="POST">
        <?= csrf_field() ?>
        
        <div class="card-personalizada p-0 overflow-hidden shadow-sm">
            <div class="table-responsive">
                <table class="table table-personalizada mb-0 align-middle">
                    <thead>
                        <tr>
                            <th style="width: 40%;">Fecha y Hora</th>
                            <th>Duración</th>
                            <th>Materia</th>
                            <th class="text-center">Seleccionar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($horarios as $modelHorario): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <select name="fecha[<?= $modelHorario['id_horario']; ?>]" 
                                                class="form-select form-control-tabla d-inline-block w-auto me-2" 
                                                data-dia="<?= $modelHorario['week_day']; ?>">
                                            <option value="">Selecciona fecha</option>
                                        </select>
                                        <span class="fw-bold" style="color: var(--ucot-negro);">
                                            <i class="far fa-clock me-1 text-muted"></i> <?= $modelHorario['hora_inicio']; ?>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <?php
                                        $inicio = new DateTime($modelHorario['hora_inicio']);
                                        $fin    = new DateTime($modelHorario['hora_fin']);
                                        $duracion = $inicio->diff($fin);
                                        $totalMinutos = ($duracion->days * 24 * 60) + ($duracion->h * 60) + $duracion->i;
                                    ?>
                                    <span class="badge-ucot" style="background: #e9ecef; color: var(--ucot-negro);">
                                        <?= $totalMinutos . ' min'; ?>
                                    </span>
                                </td>
                                <td>
                                    <input type="text" name="materias[<?= $modelHorario['id_horario']; ?>]" 
                                           class="form-control form-control-tabla" placeholder="Ej: Programación">
                                </td>
                                <td class="text-center">
                                    <input type="checkbox" name="horarios[]" value="<?= $modelHorario['id_horario']; ?>" 
                                           class="form-check-input cita-checkbox">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4 d-flex justify-content-center">
            <button type="submit" class="btn-redondeado btn-ucot-success btn-lg shadow px-5">
                <i class="fas fa-calendar-plus me-2"></i> Reservar y Pagar
            </button>
        </div>
    </form>

    <hr class="my-5" style="border-top: 2px dashed #dee2e6;">

    <div class="row mb-4">
        <div class="col-12">
            <h2 class="card-header-personalizado" style="font-size: 1.8rem;">Mis Comprobantes y Citas</h2>
            <p class="text-muted">Consulta tus citas agendadas y descarga tus facturas de pago.</p>
        </div>
    </div>

    <div class="card-personalizada p-0 overflow-hidden shadow-sm mb-5">
        <div class="table-responsive">
            <table class="table table-personalizada mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Fecha y Hora</th>
                        <th>Materia</th>
                        <th class="text-center">Estado Cita</th>
                        <th class="text-center">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($citas_reservadas)): ?>
                        <?php foreach ($citas_reservadas as $cita): ?>
                            <tr>
                                <td>
                                    <i class="far fa-calendar-alt me-2" style="color: var(--ucot-cian);"></i>
                                    <?= date('d/m/Y H:i', strtotime($cita['fecha_hora_inicio'])) ?>
                                </td>
                                <td><span class="fw-bold" style="color: var(--ucot-negro);"><?= esc($cita['materia']) ?></span></td>
                                <td class="text-center">
                                    <?php if ($cita['estado_cita'] === 'confirmado'): ?>
                                        <span class="badge-ucot badge-confirmada">CONFIRMADO</span>
                                    <?php else: ?>
                                        <span class="badge-ucot" style="background: #ffc107; color: #212529;">PENDIENTE</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if (!empty($cita['id_pago'])): ?>
                                        <a href="<?= base_url('alumno/factura/' . $cita['id_pago']) ?>" 
                                           class="btn-redondeado btn-ucot-primary py-1 px-3" style="font-size: 0.8rem;">
                                            <i class="fas fa-file-invoice-dollar me-1"></i> Ver Factura
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= base_url('alumno/pago_estatico/' . $cita['id_cita']) ?>" 
                                           class="btn-redondeado btn-ucot-danger py-1 px-3" style="font-size: 0.8rem;">
                                            <i class="fas fa-wallet me-1"></i> Pagar Cita
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="fas fa-calendar-times fa-3x mb-3 d-block" style="opacity: 0.3;"></i>
                                No tienes citas registradas actualmente.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/js/revisar_citas.js') ?>"></script>

<?= $this->endSection() ?>