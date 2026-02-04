<?= $this->extend('Template/public_main') ?>

<?= $this->section('content_publico') ?>
    
    <div class="row justify-content-center mb-4">
        <div class="col-md-12 text-center">
            <h1 class="texto-animado display-4 fw-bold">Bienvenido</h1>
        </div>
    </div>
    <div class="row w-100 justify-content-center align-items-center">
        <div class="col-md-5">
            <?= $login ?> </div>
    </div>
<?= $this->endSection() ?>



