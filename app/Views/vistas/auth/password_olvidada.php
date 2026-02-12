<?= $this->extend('Template/public_main') ?>

<?= $this->section('content_publico') ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card card-personalizada shadow-lg border-0 rounded-4">
                <div class="card-body p-4">

                    <div class="form-group mb-4">
                        <a href="<?= base_url('/') ?>" class="btn-volver-esquina" title="Volver al Login">
                            <i class="fas fa-arrow-left"></i>
                        </a>   
                    </div>

                    <?php if(session()->getFlashdata('msg')): ?>
                        <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                            <small><?= session()->getFlashdata('msg') ?></small>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if(session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                            <small><?= session()->getFlashdata('error') ?></small>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                        
                    <form action="<?= base_url('auth/enviar_recovery') ?>" method="POST">
                        <?= csrf_field() ?>

                        <div class="form-group mb-4">
                            <div class="text-center mb-3">
                                <label class="fw-bold h5 mb-2">Recupera tu cuenta</label>
                                <p class="mb-0 text-muted small">Introduce tu correo electrónico para buscar tu cuenta.</p>
                            </div>
                            
                            <input 
                                id="correo"
                                type="email" 
                                name="correo" 
                                class="form-control form-control-personalizado" 
                                placeholder="Ej: usuario@correo.com" 
                                value="<?= old('correo') ?>" required>
                        </div>
                      
                        <div class="text-center">
                            <div class="d-grid justify-content-center align-items-center py-2">
                                <button type="submit" class="btn btn-success btn-redondeado shadow px-5">
                                    Buscar
                                </button>
                            </div>  
                        </div>

                    </form>
                </div> 
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>