<?= $this->extend('Template/main') ?>

<?= $this->section('content') ?>
 

        <!--Tabla para datos-->
        
        <div class="container mt-4">
            <div class="row">
                <div class="col-12">
                    <div class="card-personalizada p-0">
                        
                        <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 card-header-personalizado">Gestión de Horarios</h5>
                            <a href="<?= base_url('profesor/agg_horarios') ?>" 
                            class="btn-redondeado btn-ucot-primary text-white text-decoration-none">
                                <i class="fas fa-plus me-2"></i> Crea un Horario
                            </a>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-personalizada mb-0">
                                <thead>
                                    <tr>
                                        <th>Día</th>
                                        <th>Inicio</th>
                                        <th>Fin</th>
                                        <th>Estado</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($horarios as $modelHorario): ?>
                                    <tr>
                                        <td><?= $modelHorario['week_day'];?></td>
                                        <td><?= $modelHorario['hora_inicio'];?></td>
                                        <td><?= $modelHorario['hora_fin'];?></td>
                                        <td>
                                            <span class="badge-ucot badge-confirmada">
                                                <?= $modelHorario['estado'];?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?= base_url('profesor/edit_horario/'.$modelHorario['id_horario']) ?>"
                                            class="btn-redondeado btn-ucot-primary btn-sm px-3 me-1">
                                            <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            <a href="<?= base_url('profesor/dlt_horario/'.$modelHorario['id_horario']) ?>" 
                                            class="btn-redondeado btn-ucot-danger btn-sm px-3" 
                                            onclick="return confirm('¿Eliminar este horario?')">
                                            <i class="fas fa-trash"></i>
                                            </a>   
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <?php if(session()->getFlashdata('msg')): ?>
                        <div class="alert btn-ucot-primary text-white fw-bold text-center mt-3 border-0" style="border-radius: 15px;">
                            <?= session()->getFlashdata('msg') ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    <script src="<?= base_url('assets/js/revisar_citas.js') ?>"></script>  

<?= $this->endSection() ?>

