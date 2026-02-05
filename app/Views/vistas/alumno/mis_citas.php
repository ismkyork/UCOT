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
                    $dia_ingles = $dias_traduccion[strtoupper($h['week_day'])] ?? 'today';
                    // Esto asegura que si hoy es el día, tome hoy, no el de la otra semana
                    $fecha_proxima = (date('l') == $dia_ingles) ? date('Y-m-d') : date('Y-m-d', strtotime("next $dia_ingles"));
                ?> 
                
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="me-3" style="font-size: 1.2rem; color: var(--ucot-cian);">
                                <i class="far fa-calendar-check"></i>
                            </div>
                            <div>
                                <span class="fw-bold text-uppercase d-block" style="font-size: 0.9rem; color: var(--ucot-negro);">
                                    <?= esc($h['week_day']); ?>
                                </span>
                                <small class="fw-bold" style="color: var(--ucot-cian);">
                                    <?= date('d/m/Y', strtotime($fecha_proxima)); ?>
                                </small>
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
                            
                            <input type="hidden" name="horarios[]" value="<?= $h['id_horario']; ?>">
                            <input type="hidden" name="fecha[<?= $h['id_horario']; ?>]" value="<?= $fecha_proxima; ?>">
                            
                            <div class="flex-grow-1">
                                <input type="text" name="materias[<?= $h['id_horario']; ?>]" 
                                       class="form-control" 
                                       placeholder="Ej: Cálculo Diferencial" required>
                            </div>

                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus-circle me-1"></i> Agendar
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?> 
            </tbody>
        </table>
    </div>
</div>



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