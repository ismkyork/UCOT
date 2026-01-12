<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Horario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="text-white">
    <?= $header ?>

    <div class="container mt-5">

<!-- Formulario -->
        <form action="<?= base_url('profesor/store_horarios') ?>" method="post" class="bg-dark p-4 rounded">
        <div class="d-flex justify-content-center rounded bg-dark">
          <h2 class="mb-4">Añadir Nuevo Horario</h2>
        </div>
            
        <!--Seleccionar profesor de la bdd-->
           <div class="mb-3">
             <label for="id_profesor" class="form-label">Profesor</label>
               <select class="form-select" id="id_profesor" name="id_profesor" required>
                  <option value="">Seleccione un profesor...</option>
                    <?php foreach($profesores as $modelProfesor): ?>
                      <option value="<?= $modelProfesor['id_profesor']; ?>">
                         <?= $modelProfesor['nombre_profesor']; ?>
                       </option>
                    <?php endforeach; ?>
                </select>
            </div>


            <div class="mb-3">
                <label for="week_day" class="form-label">Día de la semana</label>
                <select class="form-select" id="week_day" name="week_day" required>
                    <option value="">Seleccione...</option>
                    <option value="Lunes">Lunes</option>
                    <option value="Martes">Martes</option>
                    <option value="Miércoles">Miércoles</option>
                    <option value="Jueves">Jueves</option>
                    <option value="Viernes">Viernes</option>
                    <option value="Sábado">Sábado</option>
                    <option value="Domingo">Domingo</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="hora_inicio" class="form-label">Hora inicio</label>
                <input type="time" class="form-control" id="hora_inicio" name="hora_inicio" required>
            </div>

            <div class="mb-3">
                <label for="hora_fin" class="form-label">Hora fin</label>
                <input type="time" class="form-control" id="hora_fin" name="hora_fin" required>
            </div>

            <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                  <select class="form-select" id="estado" name="estado" required>
                     <option value="Disponible">Disponible</option>
                     <option value="Reservado">Reservado</option>
                     <option value="No_Trabaja">No_Trabaja</option>
                  </select>

            </div>

            <button type="submit" class="btn fw-bold" style="background-color: #00d4ff;">Guardar</button>
            <a href="<?= base_url('profesor/config_horarios') ?>" class="btn fw-bold btn-light">Cancelar</a>
        </form>
    </div>

    <?= $footer ?>
</body>
</html>