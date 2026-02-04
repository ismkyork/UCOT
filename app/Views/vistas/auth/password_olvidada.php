<?= $this->extend('Template/public_main') ?>

<?= $this->section('content_publico') ?>

    <div class="container py-5">
        <div class="row justify-content-center ">
            <div class="col-md-5">
                <div class="card card-personalizada shadow-lg">
                    <div class="card-body p-4">

                       <div class="form-group mb-4">
                            <a href="<?= base_url('/') ?>" class="btn-volver-esquina" title="Volver al inicio">
                                <i class="fas fa-arrow-left"></i>
                            </a>   
                        </div>
                            
                        <form action="<?= base_url('') ?>" method="POST">

                            <div class="form-group mb-4">
                                <label class="fw-bold mb-2">Recupera tu cuenta</label>
                                <div class=" text-center py-3">
                                    <p class="mb-0 text-muted">Introduce tu correo electrónico para buscar tu cuenta.</p>
                                </div>
                                <input 
                                id="email"
                                type="email" name="email"
                                class="form-control form-control-personalizado" 
                                placeholder="Ej: usuario@correo.com" 
                                value="<?= old('email') ?>" required>
                            </div>
                          
                            <td class="text-center">
                                <div class="card-footer bg-light d-flex justify-content-center align-items-center py-3 gap-2">
                                    
                                    <button type="submit" class="btn btn-success btn-redondeado btn-sm shadow">
                                         Buscar
                                    </button>
                                    
                                </div>  

                            </td>
                        </form>
                    </div> 
                </div>
            </div>
        </div>
    </div>

<?= $this->endSection() ?>