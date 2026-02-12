<?= $this->extend('Template/main') ?>

<?= $this->section('content') ?>

<div class="row mb-4">
    <div class="col-12">
        <h2 style="font-weight: 700; color: #2e3748;"><?= $titulo ?></h2>
        <p style="color: #858796;">Listado completo de tus clases y solicitudes.</p>
    </div>
</div>

<div class="card-personalizada p-0">
    <div class="table-responsive">
        <?php if (empty($citas)): ?>
            <div class="p-4 text-center">No tienes citas registradas.</div>
        <?php else: ?>
            <table class="table table-personalizada mb-0">
                <thead>
                    <tr>
                        <th>Estudiante</th>
                        <th>Materia</th>
                        <th>Fecha y Hora</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($citas as $cita): ?>
                        <tr>
                            <td>
                                <strong><?= esc($cita['nombre_estudiante'] . ' ' . $cita['apellido_estudiante']) ?></strong><br>
                                <small class="text-muted"><?= esc($cita['estudiante_email']) ?></small>
                            </td>
                            <td><?= esc($cita['materia']) ?></td>
                            <td><?= date('d/m/Y h:i A', strtotime($cita['fecha_hora_inicio'])) ?></td>
                            <td>
                                <?php 
                                $estado = strtolower(trim($cita['estado_cita'] ?? ''));
                                
                                if($estado == 'pendiente'): 
                                ?>
                                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill">
                                        <i class="fas fa-hourglass-half me-1"></i> Pendiente de Pago
                                    </span>
                                
                                <?php elseif($estado == 'confirmado'): ?>
                                    <span class="badge bg-success px-3 py-2 rounded-pill">
                                        <i class="fas fa-check-circle me-1"></i> Confirmada / Pagada
                                    </span>
                                
                                <?php elseif($estado == 'finalizada'): ?>
                                    <span class="badge bg-primary px-3 py-2 rounded-pill">
                                        <i class="fas fa-check-double me-1"></i> Clase Dictada
                                    </span>
                                
                                <?php elseif($estado == 'cancelada'): ?>
                                    <span class="badge bg-danger px-3 py-2 rounded-pill">
                                        <i class="fas fa-times-circle me-1"></i> Cancelada
                                    </span>
                                
                                <?php else: ?>
                                    <span class="badge bg-secondary px-3 py-2 rounded-pill">
                                        <?= ucfirst($estado) ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($cita['estado_cita'] == 'pagada'): ?>
                                    <a href="<?= base_url('profesor/finalizar_cita/'.$cita['id_cita']) ?>" class="btn btn-sm btn-outline-primary rounded-pill">
                                        Finalizar Clase
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted small">Sin acciones</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>