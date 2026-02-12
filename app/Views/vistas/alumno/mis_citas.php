<?= $this->extend('Template/main') ?>

<?= $this->section('content') ?>

<?php if (session()->has('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> <?= session()->get('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="<?= base_url('alumno/elegir_profesor') ?>" class="text-decoration-none text-muted small mb-1 d-inline-block hover-opacity">
            <i class="fas fa-arrow-left me-1"></i> Volver a la lista
        </a>
        <h3 class="fw-bold mb-0" style="color: var(--ucot-negro);">Agenda de Clases</h3>
    </div>

    <div class="view-switcher shadow-sm bg-white p-1 rounded-pill border">
        <a href="<?= base_url('alumno/calendario_alumno') ?>" class="btn-switch py-2 px-3 d-inline-block rounded-pill text-muted" title="Vista Calendario">
            <i class="far fa-calendar-alt"></i>
        </a>
        <span class="btn-switch active py-2 px-3 d-inline-block rounded-pill shadow-sm" style="background: var(--ucot-cian); color: white;">
            <i class="fas fa-list-ul"></i>
        </span>
    </div>
</div>

<div class="card border-0 shadow-sm overflow-hidden mb-4" style="border-radius: 15px;">
    <div class="card-body p-0">
        <div class="row g-0">
            <div class="col-md-7 p-4 bg-white">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center shadow-sm" style="width: 60px; height: 60px; border: 2px solid #f8f9fa;">
                            <i class="fas fa-user-tie fa-lg text-primary"></i>
                        </div>
                    </div>
                    <div class="ms-3">
                        <h5 class="fw-bold mb-0 text-dark">
                            <?= esc($profesor_actual['nombre_profesor'] ?? 'Docente') ?> 
                            <?= esc($profesor_actual['apellido_profesor'] ?? '') ?>
                        </h5>
                        <p class="text-muted small mb-0">Docente Verificado por UCOT</p>
                    </div>
                </div>
            </div>
            <div class="col-md-5 p-4 d-flex align-items-center justify-content-md-end justify-content-start" style="background: linear-gradient(45deg, #f8f9fa, #ffffff); border-left: 1px solid #eee;">
                <div class="text-md-end">
                    <span class="badge bg-soft-primary text-primary px-3 py-1 rounded-pill mb-2 fw-bold" style="background-color: rgba(13, 202, 240, 0.1); font-size: 0.7rem;">
                        <i class="fas fa-tag me-1"></i> TARIFA POR CLASE
                    </span>
                    <h2 class="fw-bold mb-0" style="color: var(--ucot-cian); font-size: 2rem;">
                        $<?= number_format($profesor_actual['precio_clase'] ?? 0, 2) ?>
                    </h2>
                    <div class="text-muted small fw-medium mt-1">
                        <i class="fas fa-exchange-alt me-1"></i> 
                        ~ <?= number_format(($profesor_actual['precio_clase'] ?? 0) * ($tasa_bcv ?? 36.50), 2) ?> Bs.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<h5 class="fw-bold text-dark mb-3 ps-2 border-start border-4 border-info">&nbsp;Horarios Disponibles</h5>
<div class="card-personalizada shadow-sm border-0 mb-5" style="border-radius: 15px;">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4 border-0 text-muted small fw-bold">FECHA Y DÍA</th>
                    <th class="border-0 text-muted small fw-bold">HORARIO</th>
                    <th class="border-0 text-muted small fw-bold">CUPOS</th>
                    <th class="border-0 text-muted small fw-bold">MATERIA</th>
                    <th class="border-0 text-muted small fw-bold">PLATAFORMA</th>
                    <th class="text-center border-0 text-muted small fw-bold pe-4">ACCIÓN</th>
                </tr>
            </thead>
            <tbody class="border-top-0">
                <?php if (!empty($horarios)): ?>
                    <?php foreach($horarios as $h): 
                        $fecha_db = $h['fecha'];
                        $fecha_formateada = date('d/m/Y', strtotime($fecha_db));
                        $dias_semana_espanol = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
                        $indice_dia = date('w', strtotime($fecha_db));
                        $nombre_dia = $dias_semana_espanol[$indice_dia];
                        
                        $hora_inicio = substr($h['hora_inicio'], 0, 5);
                        $hora_fin = substr($h['hora_fin'], 0, 5);
                        
                        $formId = "form_reserva_" . $h['id_horario'];
                        $materia_fija = $h['nombre_materia'] ?? null;
                        $sistema_fijo = $h['nombre_sistema'] ?? null;

                        // ==========================================
                        // LÓGICA DE BLOQUEO DE BOTONES
                        // ==========================================
                        $ya_reservado = false;
                        if (!empty($citas_reservadas)) {
                            foreach ($citas_reservadas as $cr_check) {
                                // Si coincide el ID del horario y la cita está activa (no cancelada)
                                if ($cr_check['id_horario'] == $h['id_horario'] && $cr_check['estado_cita'] != 'cancelado') {
                                    $ya_reservado = true;
                                    break;
                                }
                            }
                        }
                    ?> 
                    <tr class="<?= $ya_reservado ? 'bg-light text-muted' : '' ?>">
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="icon-shape bg-soft-info p-2 rounded-3 me-3 text-info" style="background-color: rgba(13, 202, 240, 0.1);">
                                    <i class="far fa-calendar-alt"></i>
                                </div>
                                <div>
                                    <span class="d-block fw-bold text-dark mb-0"><?= $nombre_dia ?></span>
                                    <span class="text-muted small"><?= $fecha_formateada ?></span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-success me-2" style="width: 8px; height: 8px; border-radius: 50%; padding: 0;"> </span>
                                <span class="fw-bold text-dark"><?= $hora_inicio ?> - <?= $hora_fin ?></span>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-info text-white px-3 py-2 rounded-pill">
                                <i class="fas fa-users me-1"></i> <?= $h['cupos_disponibles'] ?> / <?= $h['cupos_totales'] ?>
                            </span>
                        </td>
                        
                        <td>
                            <?php if ($materia_fija): ?>
                                <div class="d-flex align-items-center text-primary bg-soft-primary px-3 py-1 rounded-pill" style="width: fit-content;">
                                    <i class="fas fa-book me-2 small"></i> <?= esc($materia_fija) ?>
                                </div>
                                <input type="hidden" name="materia_nombre" value="<?= esc($materia_fija) ?>" form="<?= $formId ?>">
                            <?php else: ?>
                                <select name="materia_nombre" form="<?= $formId ?>" class="form-select form-select-sm border-secondary bg-white text-dark fw-medium shadow-sm" style="max-width: 180px; border-radius: 8px;" required <?= $ya_reservado ? 'disabled' : '' ?>>
                                    <option value="" selected disabled>-- Elegir Tema --</option>
                                    <?php if (!empty($materias_profe)): ?>
                                        <?php foreach ($materias_profe as $mat): ?>
                                            <option value="<?= esc($mat['nombre_materia']) ?>"><?= esc($mat['nombre_materia']) ?></option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="Clase General">Clase General</option>
                                    <?php endif; ?>
                                </select>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if ($sistema_fijo): ?>
                                <div class="d-flex align-items-center text-dark bg-light px-3 py-1 rounded-pill border" style="width: fit-content;">
                                    <i class="fas fa-desktop me-2 text-muted small"></i> <?= esc($sistema_fijo) ?>
                                </div>
                                <input type="hidden" name="sistema_nombre" value="<?= esc($sistema_fijo) ?>" form="<?= $formId ?>">
                            <?php else: ?>
                                <select name="sistema_nombre" form="<?= $formId ?>" class="form-select form-select-sm border-secondary bg-white text-dark fw-medium shadow-sm" style="max-width: 150px; border-radius: 8px;" required <?= $ya_reservado ? 'disabled' : '' ?>>
                                    <option value="" selected disabled>-- Elegir --</option>
                                    <?php if (!empty($sistemas_profe)): ?>
                                        <?php foreach ($sistemas_profe as $sis): ?>
                                            <option value="<?= esc($sis['nombre']) ?>"><?= esc($sis['nombre']) ?></option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="Online">Online</option>
                                    <?php endif; ?>
                                </select>
                            <?php endif; ?>
                        </td>

                        <td class="text-center pe-4">
                            <?php if ($ya_reservado): ?>
                                <button type="button" class="btn btn-secondary btn-sm px-3 shadow-sm fw-bold rounded-pill disabled" style="cursor: not-allowed; opacity: 0.6; background-color: #e2e6ea; border-color: #e2e6ea; color: #6c757d;">
                                    <i class="fas fa-check-circle me-1"></i> En tu Agenda
                                </button>
                            <?php else: ?>
                                <form action="<?= site_url('alumno/store_citas') ?>" method="POST" id="<?= $formId ?>">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id_horario" value="<?= $h['id_horario']; ?>">
                                    <input type="hidden" name="fecha_seleccionada" value="<?= $fecha_db; ?>">
                                    
                                    <button type="submit" class="btn btn-primary btn-sm px-4 shadow-sm fw-bold rounded-pill transition-all" style="background-color: var(--ucot-cian); border-color: var(--ucot-cian);">
                                        Reservar <i class="fas fa-chevron-right ms-1" style="font-size: 0.7rem;"></i>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="fas fa-calendar-times fa-2x mb-3 text-muted" style="opacity: 0.3;"></i>
                            <p class="text-muted fw-medium mb-0">Este docente no tiene bloques disponibles por ahora.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    .bg-soft-primary { background-color: rgba(13, 110, 253, 0.1); }
    .bg-soft-info { background-color: rgba(13, 202, 240, 0.1); }
    .transition-all { transition: all 0.3s ease; }
    .transition-all:hover { transform: translateY(-2px); box-shadow: 0 4px 10px rgba(51, 194, 209, 0.2) !important; }
    .hover-opacity:hover { opacity: 0.7; }
    .table-hover tbody tr:hover { background-color: #fcfcfc; }
</style>

<?= $this->endSection() ?>