<?= $this->extend('Template/main') ?>

<?= $this->section('content') ?>
<div class="card-personalizada">
    <div class="table-responsive">
        <table class="table table-personalizada align-middle mb-0">
            <thead>
                <tr>
                    <th>Bloque de Fecha</th>
                    <th>Horario Disponible</th>
                    <th>Materia a Tratar</th>
                    <th class="text-center">Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($horarios as $h): 
                    $dias_traduccion = [
                        'LUNES' => 'Monday', 'MARTES' => 'Tuesday', 'MIÉRCOLES' => 'Wednesday', 
                        'MIERCOLES' => 'Wednesday', 'JUEVES' => 'Thursday', 'VIERNES' => 'Friday', 
                        'SÁBADO' => 'Saturday', 'SABADO' => 'Saturday', 'DOMINGO' => 'Sunday'
                    ];
                    $dia_ingles = $dias_traduccion[strtoupper($h['fecha'])] ?? 'today';
                    $fecha_proxima = (date('l') == $dia_ingles) ? date('Y-m-d') : date('Y-m-d', strtotime("next $dia_ingles"));
                ?> 
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="me-3" style="font-size: 1.2rem; color: var(--ucot-cian);"><i class="far fa-calendar-check"></i></div>
                            <div>
                                <span class="fw-bold text-uppercase d-block" style="font-size: 0.9rem;"><?= esc($h['fecha']); ?></span>
                                <small class="fw-bold" style="color: var(--ucot-cian);"><?= date('d/m/Y', strtotime($fecha_proxima)); ?></small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge-ucot" style="background-color: #f1f4f9; color: var(--ucot-negro);">
                            <i class="far fa-clock me-1"></i> <?= esc($h['hora_inicio']); ?> - <?= esc($h['hora_fin']); ?>
                        </span>
                    </td>
                    
                    <td colspan="2">
                        <form action="<?= site_url('alumno/store_citas') ?>" method="POST" class="d-flex align-items-center gap-2 m-0">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id_horario" value="<?= $h['id_horario']; ?>">
                            <input type="hidden" name="fecha_seleccionada" value="<?= $fecha_proxima; ?>">
                            
                            <div class="flex-grow-1">
                                <input type="text" name="materia_nombre" class="form-control" placeholder="Ej: Cálculo Diferencial" required> 
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Agendar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?> 
            </tbody>
        </table>
    </div>
</div>

<hr class="my-5" style="border-top: 2px dashed #dee2e6;">

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
                            <td><?= date('d/m/Y H:i', strtotime($cita['fecha_hora_inicio'])) ?></td>
                            <td><span class="fw-bold"><?= esc($cita['materia']) ?></span></td>
                            <td class="text-center">
                                <span class="badge-ucot <?= ($cita['estado_cita'] === 'confirmado') ? 'badge-confirmada' : '' ?>" 
                                      style="<?= ($cita['estado_cita'] !== 'confirmado') ? 'background: #ffc107;' : '' ?>">
                                    <?= strtoupper($cita['estado_cita']) ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <?php if (!empty($cita['id_pago'])): ?>
                                    <a href="<?= base_url('alumno/factura/' . $cita['id_pago']) ?>" class="btn btn-sm btn-info">Ver Factura</a>
                                <?php else: ?>
                                    <a href="<?= base_url('alumno/pago_estatico/' . $cita['id_cita']) ?>" class="btn btn-sm btn-danger">Pagar Cita</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4" class="text-center py-4">No tienes citas.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>