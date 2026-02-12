<?= $this->extend('Template/main') ?>

<?= $this->section('content') ?>
 <div class="card-personalizada p-0 overflow-hidden shadow-sm mb-5">
    <div class="table-responsive">
        <table class="table table-personalizada mb-0 align-middle">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">Fecha y Hora</th>
                    <th>Docente</th> <th>Materia</th>
                    <th class="text-center">Estado Cita</th>
                    <th class="text-center">Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($citas_reservadas)): ?>
                    <?php foreach ($citas_reservadas as $cita): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-dark"><?= date('d/m/Y', strtotime($cita['fecha_hora_inicio'])) ?></span>
                                    <span class="text-muted small"><?= date('H:i', strtotime($cita['fecha_hora_inicio'])) ?> hrs</span>
                                </div>
                            </td>
                            
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle-sm me-2 bg-primary text-white d-flex align-items-center justify-content-center rounded-circle" style="width: 35px; height: 35px; font-size: 0.8rem;">
                                        <i class="fas fa-chalkboard-teacher"></i>
                                    </div>
                                    <div>
                                        <p class="mb-0 fw-bold text-dark">
                                            <?= esc($cita['nombre_profesor'] ?? 'Profesor') ?> <?= esc($cita['apellido_profesor'] ?? '') ?>
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <td><span class="fw-semibold text-secondary"><?= esc($cita['materia']) ?></span></td>
                            
                            <td class="text-center">
                            <?php 
                                $estado = strtolower($cita['estado_cita']); 
                                $colorBadge = '';
                                
                                switch($estado) {
                                    case 'confirmado':
                                    case 'pagada': // Agregué 'pagada' por si usas ese término
                                        $colorBadge = 'bg-success'; 
                                        break;
                                    case 'pendiente':
                                        $colorBadge = 'bg-warning text-dark'; 
                                        break;
                                    case 'cancelado':
                                    case 'finalizada':
                                        $colorBadge = 'bg-secondary'; 
                                        break;
                                    default:
                                        $colorBadge = 'bg-light text-dark border'; 
                                }
                            ?>
                            <span class="badge rounded-pill <?= $colorBadge ?> p-2 px-3" style="min-width: 90px;">
                                <?= ucfirst($estado) ?>
                            </span>
                        </td>
                            <td class="text-center">
                                <?php if (!empty($cita['id_pago'])): ?>
                                    <a href="<?= base_url('alumno/factura/' . $cita['id_pago']) ?>" class="btn btn-sm btn-outline-info rounded-pill px-3">
                                        <i class="fas fa-file-invoice me-1"></i> Factura
                                    </a>
                                <?php else: ?>
                                    <?php if($estado != 'cancelado' && $estado != 'finalizada'): ?>
                                        <a href="<?= base_url('alumno/pago_estatico/' . $cita['id_cita']) ?>" class="btn btn-sm btn-ucot-blue rounded-pill px-3 text-white" style="background-color: var(--ucot-blue);">
                                            <i class="fas fa-credit-card me-1"></i> Pagar
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted small">-</span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" class="text-center py-5 text-muted">No tienes citas programadas.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>