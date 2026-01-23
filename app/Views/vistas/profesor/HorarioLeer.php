<?= $header?>
<?=$menu?>   

        <!--Tabla para datos-->

        <div class="table-responsive">
                <table class="table table-hover table-personalizada align-middle">
                            <thead class="text-white">
                                <tr class="align-middle">
                                    <th>Día</th>
                                    <th>Hora de Inicio</th>
                                    <th>Hora de Finalización</th>
                                    <th>Estado</th>
                                    <th class="text-center">
                                        <a href="<?= base_url('profesor/agg_horarios') ?>" 
                                        class="btn btn-secondary border border-white btn-redondeado text-white btn-sm fw-bold shadow-sm">
                                            Crea un Horario
                                        </a>
                                    </th>
                                </tr>
                            </thead>

                        <!--datos de los campos-->
                        <tbody>

                                <!--bucle que busca los datos de la bdd-->
                            <?php foreach($horarios as $modelHorario): ?>

                                <tr>
                                    <td><?=  $modelHorario['week_day'];?></td>
                                    <td><?=  $modelHorario['hora_inicio'];?></td>
                                    <td><?=  $modelHorario['hora_fin'];?></td>
                                    <td><?=  $modelHorario['estado'];?></td>

                                    <!--botones Editar/Eliminar-->
                                    <td class="text-center">
                                            <a href="<?= base_url('profesor/edit_horario/'.$modelHorario['id_horario']) ?>"
                                            class="btn btn-info border border-white btn-redondeado text-white btn-sm fw-bold" >
                                            Editar
                                            </a>
                                            <a href="<?= base_url('profesor/dlt_horario/'.$modelHorario['id_horario']) ?>" 
                                            class="btn btn-danger border-white text-white btn-redondeado btn-sm fw-bold" 
                                             onclick="return confirm('¿Seguro que deseas eliminar este horario?')" >
                                            Eliminar
                                            </a>   
                                    </td>
                
                                </tr>

                                    <?php endforeach; ?>
                                <!--fin del bucle que busca los datos de la bdd-->
                                    <!--Mensaje de confimación de la eliminación-->
                                    <?php if(session()->getFlashdata('msg')): ?>
                                        <div class="alert bg-secondary text-white fw-bolder text-center">
                                            <?= session()->getFlashdata('msg') ?>
                                        </div>
                                    <?php endif; ?>

                         </tbody>

                 </table>
        </div>
            
<?= $footer?>
