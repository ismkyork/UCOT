<?=$header?> 
<?=$menu?>   

<div class="container mt-5">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="card-header-personalizado" style="font-size: 1.8rem;">Citas Disponibles</h2>
            <p class="text-muted">Selecciona el horario y la materia para agendar tu asesoría.</p>
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
      
        <div class="card-personalizada p-0 overflow-hidden">
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
                                    <span class="badge-ucot badge-confirmada" style="background-color: #f8f9fa !important; color: #495057 !important; border: 1px solid #dee2e6;">
                                        <?= $totalMinutos . ' min'; ?>
                                    </span>
                                </td>

                                <td>
                                    <input type="text" 
                                        name="materias[<?= $modelHorario['id_horario']; ?>]" 
                                        class="form-control form-control-tabla" 
                                        placeholder="Ej: Matemáticas">
                                </td>

                                <td class="text-center">
                                    <input type="checkbox" 
                                        name="horarios[]" 
                                        value="<?= $modelHorario['id_horario']; ?>" 
                                        class="form-check-input cita-checkbox">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <input type="hidden" name="id_alumno" value="<?= session()->get('id_auth'); ?>">

        <div class="mt-5 mb-5 d-flex justify-content-center">
            <button type="submit" class="btn-redondeado btn-ucot-success btn-lg shadow" style="width: 100%; max-width: 400px;">
                <i class="fas fa-credit-card me-2"></i> Reservar y Continuar al Pago
            </button>
        </div>
    </form>
</div>

<script src="<?= base_url('assets/js/revisar_citas.js') ?>"></script>  

<?=$footer?>