<?= $header ?>
<?= $menu ?>

<div class="container mt-5">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="card-header-personalizado" style="font-size: 1.8rem;">Agendar Nueva Asesoría</h2>
            <p class="text-muted">Selecciona el horario y la materia para tu próxima clase en UCOT.</p>
        </div>
    </div>

    <?php if(session('errors')): ?>
        <div class="alert alert-danger shadow-sm" style="border-radius: 15px;">
            <ul class="mb-0">
                <?php foreach(session('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if(session('success')): ?>
        <div class="alert btn-ucot-success text-white shadow-sm" style="border-radius: 15px;">
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
                                    <select name="fecha[<?= $modelHorario['id_horario']; ?>]" 
                                            class="form-select form-control-tabla d-inline-block w-auto me-2" 
                                            data-dia="<?= $modelHorario['week_day']; ?>">
                                        <option value="">Selecciona fecha</option>
                                    </select>
                                    <span class="fw-bold" style="color: var(--ucot-negro);">
                                        <i class="far fa-clock me-1 text-muted"></i> <?= $modelHorario['hora_inicio']; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php
                                        $inicio = new DateTime($modelHorario['hora_inicio']);
                                        $fin    = new DateTime($modelHorario['hora_fin']);
                                        $duracion = $inicio->diff($fin);
                                        $totalMinutos = ($duracion->days * 24 * 60) + ($duracion->h * 60) + $duracion->i;
                                    ?>
                                    <span class="badge bg-light text-dark border">
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
            <button type="submit" class="btn-redondeado btn-ucot-success btn-lg shadow" style="width: 100%; max-width: 400px;">
                <i class="fas fa-calendar-plus me-2"></i> Reservar y Pagar
            </button>
        </div>
    </form>

    <hr class="my-5">

    <div class="row mb-4">
        <div class="col-12">
            <h2 class="card-header-personalizado" style="font-size: 1.8rem;">Mis Comprobantes y Citas</h2>
            <p class="text-muted">Consulta tus citas agendadas y descarga tus facturas de pago.</p>
        </div>
    </div>

    <div class="card-personalizada p-0 overflow-hidden shadow-sm mb-5">
        <div class="table-responsive">
            <table class="table table-personalizada mb-0 align-middle">
                <thead class="bg-light">
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
                                    <i class="far fa-calendar-alt me-2 text-primary"></i>
                                    <?= date('d/m/Y H:i', strtotime($cita['fecha_hora_inicio'])) ?>
                                </td>
                                <td><strong><?= esc($cita['materia']) ?></strong></td>
                                <td class="text-center">
                                    <?php if ($cita['estado_cita'] === 'confirmado'): ?>
                                        <span class="badge bg-success">CONFIRMADO</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">PENDIENTE</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if (!empty($cita['id_pago'])): ?>
                                        <a href="<?= base_url('alumno/factura/' . $cita['id_pago']) ?>" 
                                           class="btn btn-sm btn-info text-white shadow-sm">
                                            <i class="fas fa-file-invoice-dollar me-1"></i> Ver Factura
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= base_url('alumno/pago_estatico/' . $cita['id_cita']) ?>" 
                                           class="btn btn-sm btn-danger shadow-sm">
                                            <i class="fas fa-wallet me-1"></i> Pagar Cita
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">
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

<?= $footer ?>