<?=$header?> 

<div class="container mt-5"> <h1>Login</h1>

    <form action="<?= base_url('auth/procesarlogin') ?>" method="POST">
        
        <?= csrf_field() ?>
        
        <?php if(session()->getFlashdata('msg')):?>
            <div class="alert alert-danger"><?= session()->getFlashdata('msg') ?></div>
        <?php endif;?>

        <div class="form-group mb-3">
            <label for="email">Email</label>
            <input id="email" value="<?= old('email') ?>" class="form-control" type="text" name="email" placeholder="Ej: panconqueso@gmail.com" required>
        </div>

        <div class="form-group mb-3">
            <label for="password">Contraseña</label>
            <input id="password" class="form-control" type="password" name="password" placeholder="Ej:*********" required>
            </div>

        <div class="form-group mb-3">
            <button type="submit" class="btn btn-success btn-lg w-100">
                <i class="fas fa-save me-2"></i> Entrar
            </button>
        </div>

    </form> </div>

<?= $footer ?>