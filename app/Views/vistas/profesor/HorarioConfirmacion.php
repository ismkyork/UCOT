<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Confirmación</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white">
    <?= $header ?>

    <div class="container mt-5">
        <div class="alert alert-success text-black fw-bolder text-center rounded shadow">
            ✅ El horario se guardó correctamente.
        </div>
        <div class="text-center mt-3">
            <a href="<?= base_url('profesor/config_horarios') ?>" class="btn fw-bold btn-light">Volver a la lista</a>
            <a href="<?= base_url('profesor/agg_horarios') ?>" class="btn fw-bold btn-primary">Crear otro horario</a>
        </div>
    </div>

    <?= $footer ?>
</body>
</html>
