<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Horario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white">
    <?= $header ?>

<!--Tabla para datos-->
    <div class="container bg-dark rounded p-3 mt-5">

        <form action="<?= base_url('profesor/update_horario/'.$horario['id_horario']) ?>" method="post" class="mt-4">
        <div class="d-flex justify-content-center rounded bg-dark">
          <h2 class="mb-4">Editar Horario</h2>
        </div>
            


            <div class="mb-3">
                <label for="week_day" class="form-label">Día de la semana</label>
                <select class="form-select" id="week_day" name="week_day" required>
                    <option value="">Seleccione...</option>
                    <option value="Lunes" <?= $horario['week_day']=='Lunes'?'selected':'' ?>>Lunes</option>
                    <option value="Martes" <?= $horario['week_day']=='Martes'?'selected':'' ?>>Martes</option>
                    <option value="Miércoles" <?= $horario['week_day']=='Miércoles'?'selected':'' ?>>Miércoles</option>
                    <option value="Jueves" <?= $horario['week_day']=='Jueves'?'selected':'' ?>>Jueves</option>
                    <option value="Viernes" <?= $horario['week_day']=='Viernes'?'selected':'' ?>>Viernes</option>
                    <option value="Sábado" <?= $horario['week_day']=='Sábado"'?'selected':'' ?>>Sábado</option>
                    <option value="Domingo" <?= $horario['week_day']=='Domingo'?'selected':'' ?>>Domingo</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="hora_inicio" class="form-label">Hora Inicio</label>
                <input type="time" class="form-control" id="hora_inicio" name="hora_inicio" 
                       value="<?= $horario['hora_inicio'] ?>" required>
            </div>

            <div class="mb-3">
                <label for="hora_fin" class="form-label">Hora Fin</label>
                <input type="time" class="form-control" id="hora_fin" name="hora_fin" 
                       value="<?= $horario['hora_fin'] ?>" required>
            </div>

            <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select class="form-select" id="estado" name="estado" required>
                    <option value="Disponible" <?= $horario['estado']=='Disponible'?'selected':'' ?>>Disponible</option>
                    <option value="Reservado" <?= $horario['estado']=='Reservado'?'selected':'' ?>>Reservado</option>
                    <option value="No_Trabaja" <?= $horario['estado']=='No_Trabaja'?'selected':'' ?>>No_Trabaja</option>
                </select>
            </div>

            <button type="submit" class="btn btn-light fw-bold">Guardar Cambios</button>
            <a href="<?= base_url('profesor/config_horarios') ?>" class="btn btn-secondary fw-bold">Cancelar</a>
        </form>
    </div>

    <?= $footer ?>
</body>
</html>