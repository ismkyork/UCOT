<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurar Horarios</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

</head>
<body class="bg-dark text-white">
        <?= $header?>
<!--Tabla para datos-->
    <div class="container-fluid bg-dark rounded p-3">

        <table class="table table-dark">
            <thead class="thead-dark">

<!--titulos de campos-->

                <tr>
                    <th>Día</th>
                    <th>Hora de Inicio</th>
                    <th>Hora de Finalización</th>
                    <th>Estado</th>
                    <th>

                        <a href="<?= base_url('profesor/agg_horarios') ?>"
                          class="btn btn-dark border border-white text-white btn-sm fw-bold" style="width: 132px;">
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
                          class="btn btn-dark border border-white text-white btn-sm fw-bold">
                          Editar
                        </a>
                        
                        <a href="<?= base_url('profesor/dlt_horario/'.$modelHorario['id_horario']) ?>" 
                          class="btn btn-dark border border-white text-white btn-sm fw-bold"
                          onclick="return confirm('¿Seguro que deseas eliminar este horario?')"> <!--mensaje de seguridad-->
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
</body>
</html>