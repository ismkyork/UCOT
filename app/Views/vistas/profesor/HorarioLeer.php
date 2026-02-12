<?= $this->extend('Template/main') ?>

<?= $this->section('content') ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card-personalizada p-0">
                
                <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 card-header-personalizado">Gestión de Horarios</h5>
                    
                    <div class="d-flex align-items-center gap-3">
                        <div class="view-switcher shadow-sm">
                            <a href="<?= base_url('profesor/calendario_profesor') ?>" class="btn-switch" title="Ver Calendario Visual">
                                <i class="far fa-calendar-alt"></i>
                            </a>
                            
                            <span class="btn-switch active" title="Ver Lista de Horarios">
                                <i class="fas fa-list-ul"></i>
                            </span>
                        </div>

                        <a href="<?= base_url('profesor/agg_horarios') ?>" 
                           class="btn-redondeado btn-ucot-primary text-white text-decoration-none shadow-sm">
                            <i class="fas fa-plus me-2"></i> Crear
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-personalizada mb-0">
                        <thead>
                            <tr>
                                <th>Bloque de Fecha</th>
                                <th>Horario</th>
                                <th>Modalidad / Tema</th>
                                <th>Cupos (disp/total)</th>
                                <th>Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            // --- FILTRO DE TIEMPO ---
                            // Filtramos el array para dejar solo los horarios que NO han terminado.
                            // Esto asegura que la tabla se limpie sola y el mensaje de "vacío" funcione.
                            $horariosActivos = array_filter($horarios, function($h) {
                                $fechaHoraFin = strtotime($h['fecha'] . ' ' . $h['hora_fin']);
                                return $fechaHoraFin >= time(); // Solo futuros o actuales
                            });
                            ?>

                            <?php foreach($horariosActivos as $modelHorario): 
                                $fecha_db = $modelHorario['fecha'];
                                $fecha_formateada = date('d/m/Y', strtotime($fecha_db));
                                
                                $dias_semana_espanol = ['DOMINGO', 'LUNES', 'MARTES', 'MIÉRCOLES', 'JUEVES', 'VIERNES', 'SÁBADO'];
                                $indice_dia = date('w', strtotime($fecha_db));
                                $nombre_dia = $dias_semana_espanol[$indice_dia];
                                
                                $hora_inicio = substr($modelHorario['hora_inicio'], 0, 5);
                                $hora_fin = substr($modelHorario['hora_fin'], 0, 5);

                                $cupos_totales = (int)($modelHorario['cupos_totales'] ?? 1);
                                $cupos_disponibles = (int)($modelHorario['cupos_disponibles'] ?? 0);
                                
                                // CÁLCULOS LÓGICOS PARA BOTONES
                                $ocupados = $cupos_totales - $cupos_disponibles;
                                $estaLleno = ($cupos_disponibles <= 0); // No hay cupos
                                $tieneReservas = ($ocupados > 0);        // Al menos 1 inscrito
                                
                                // Datos nuevos
                                $nombre_sistema = $modelHorario['nombre_sistema'] ?? null;
                                $nombre_materia = $modelHorario['nombre_materia'] ?? null;
                            ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-3" style="font-size: 1.2rem; color: var(--ucot-cian);">
                                            <i class="far fa-calendar-check"></i>
                                        </div>
                                        <div>
                                            <span class="fw-bold text-uppercase d-block" style="font-size: 0.9rem; color: #6c757d;">
                                                <?= $nombre_dia ?>
                                            </span>
                                            <small class="fw-bold" style="color: var(--ucot-cian);">
                                                <?= $fecha_formateada ?>
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge-ucot" style="background-color: #f1f4f9; color: var(--ucot-negro);">
                                        <i class="far fa-clock me-1"></i> <?= $hora_inicio ?> - <?= $hora_fin ?>
                                    </span>
                                </td>
                                
                                <td>
                                    <div class="d-flex flex-column gap-1 align-items-start">
                                        <?php if($nombre_sistema): ?>
                                            <span class="badge bg-light text-dark border fw-normal">
                                                <i class="fas fa-desktop me-1 text-muted"></i> <?= esc($nombre_sistema) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-light text-muted border fw-normal">
                                                <i class="fas fa-handshake me-1"></i> A convenir
                                            </span>
                                        <?php endif; ?>

                                        <?php if($nombre_materia): ?>
                                            <span class="badge" style="background-color: rgba(13, 110, 253, 0.1); color: #0d6efd; border: 1px solid rgba(13, 110, 253, 0.2);">
                                                <i class="fas fa-book me-1"></i> <?= esc($nombre_materia) ?>
                                            </span>
                                        <?php else: ?>
                                            <small class="text-muted ms-1" style="font-size: 0.75rem;">
                                                <i class="fas fa-comment-dots me-1"></i> Tema Libre
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                </td>

                                <td>
                                    <span class="badge bg-info text-white px-3 py-2 rounded-pill">
                                        <i class="fas fa-users me-1"></i> <?= $cupos_disponibles ?> / <?= $cupos_totales ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($estaLleno): ?>
                                        <span class="badge bg-danger text-white border fw-normal">
                                            Agotado
                                        </span>
                                    <?php else: ?>
                                        <span class="badge-ucot badge-confirmada">
                                            <?= $modelHorario['estado']; ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    
                                    <?php if ($estaLleno): ?>
                                        <button class="btn btn-secondary btn-sm px-3 me-1 rounded-pill" disabled title="Horario lleno, no se puede editar">
                                            <i class="fas fa-lock"></i>
                                        </button>
                                    <?php else: ?>
                                        <a href="<?= base_url('profesor/edit_horario/'.$modelHorario['id_horario']) ?>"
                                           class="btn-redondeado btn-ucot-primary btn-sm px-3 me-1" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($tieneReservas): ?>
                                        <button class="btn btn-secondary btn-sm px-3 rounded-pill" disabled title="No se puede eliminar porque hay alumnos inscritos">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    <?php else: ?>
                                        <a href="<?= base_url('profesor/dlt_horario/'.$modelHorario['id_horario']) ?>" 
                                           class="btn-redondeado btn-ucot-danger btn-sm px-3" 
                                           onclick="return confirm('¿Eliminar este horario?')" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </a>   
                                    <?php endif; ?>

                                </td>
                            </tr>
                            <?php endforeach; ?>
                            
                            <?php if(empty($horariosActivos)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-calendar-times fa-3x mb-3 d-block" style="opacity: 0.3;"></i>
                                    No tienes horarios activos o futuros.
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <?php if(session()->getFlashdata('msg')): ?>
                <div class="alert btn-ucot-primary text-white fw-bold text-center mt-3 border-0" style="border-radius: 15px;">
                    <i class="fas fa-check-circle me-2"></i> <?= session()->getFlashdata('msg') ?>
                </div>
            <?php endif; ?>
            <?php if(session()->getFlashdata('mensaje')): ?>
                <div class="alert btn-ucot-success text-white fw-bold text-center mt-3 border-0" style="border-radius: 15px;">
                    <i class="fas fa-check-circle me-2"></i> <?= session()->getFlashdata('mensaje') ?>
                </div>
            <?php endif; ?>
            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert btn-ucot-danger text-white fw-bold text-center mt-3 border-0" style="border-radius: 15px;">
                    <i class="fas fa-exclamation-circle me-2"></i> <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>