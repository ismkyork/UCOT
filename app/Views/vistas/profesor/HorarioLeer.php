<?= $header?>
<?=$menu?>   

        <!--Tabla para datos-->

        <table class="table table-white">
                    <thead class="thead-dark">

                        <!--titulos de campos-->

                        <tr>
                            <th>Día</th>
                            <th>Hora de Inicio</th>
                            <th>Hora de Finalización</th>
                            <th>Estado</th>
                            <th>
                                <a href="<?= base_url('profesor/agg_horarios') ?>" 
                                class="btn btn-dark border-white text-white btn-sm fw-bold" 
                                style="width: 132px;">
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
                            <td>
                                <a href="<?= base_url('profesor/edit_horario/'.$modelHorario['id_horario']) ?>"
                                class="btn btn-dark border border-white text-white btn-sm fw-bold" 
                                style="background-color: #00d9ff;">
                                Editar
                                </a>
                                
                                <a href="<?= base_url('profesor/dlt_horario/'.$modelHorario['id_horario']) ?>" 
                                class="btn btn-dark border border-white text-white btn-sm fw-bold btn-brillante" 
                                onclick="return confirm('¿Seguro que deseas eliminar este horario?')" 
                                style="background-color: #ff0000;"> 
                                    <i class="fas fa-trash me-1"></i> Eliminar
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
                
            
<?= $footer?>
