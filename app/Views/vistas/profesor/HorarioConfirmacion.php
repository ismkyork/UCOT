
<?= $this->extend('Template/main') ?>

<?= $this->section('content') ?>
 
  
        <div class="alert alert-success text-black fw-bolder text-center rounded shadow">
            ✅ El horario se guardó correctamente.
        </div>
        <div class="text-center mt-3">
            <a href="<?= base_url('profesor/HorarioLeer') ?>" class="btn fw-bold btn-light">Volver a la lista</a>
            <a href="<?= base_url('profesor/agg_horarios') ?>" class="btn fw-bold btn-primary">Crear otro horario</a>
        </div>
   

<?= $this->endSection() ?>
