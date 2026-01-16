<?=$header?>

<?=$menu?>   
            <?php if(session()->getFlashdata('bienvenida')): ?>
                <div class="alert alert-success alert-dismissible fade show container mt-3" role="alert">
                    <strong>¡Hola!</strong> <?= session()->getFlashdata('bienvenida') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

    
<?=$opiniones?>
<?=$footer?>    