
            <div class="row ">
                <div class="col-md-5 offset-md-6">
                    <div class="card card-personalizada shadow-lg">
                        
                        <div class="card-body p-4">
                                <?php if(session()->getFlashdata('msg')):?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <?= session()->getFlashdata('msg') ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php endif;?>

                                <form action="<?= base_url('auth/procesarlogin') ?>" method="POST">
                                    <?= csrf_field() ?>

                                    <div class="form-group mb-4">
                                        <label class="fw-bold mb-2">Correo Electrónico</label>
                                        <input 
                                        id="email"
                                        type="email" name="email"
                                        class="form-control form-control-personalizado" 
                                        placeholder="Ej: usuario@correo.com" 
                                        value="<?= old('email') ?>" required>
                                    </div>

                                    <div class="form-group mb-4">

                                        <label class="fw-bold mb-2">Contraseña</label>
                                        <input 
                                         id="password"
                                        type="password" name="password"
                                         class="form-control form-control-personalizado" 
                                        placeholder="********" required>
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-success btn-redondeado btn-lg shadow">
                                            <i class="fas fa-sign-in-alt me-2"></i> Iniciar sesión
                                        </button>
                                    </div>
                                </form>
                            </div>
                            
                            <div class="card-footer bg-light text-center py-3">
                                <p class="mb-0 text-muted">¿No tienes cuenta? <a href="<?= base_url('auth/registro') ?>" class="text-success fw-bold">Regístrate</a></p>
                            </div>
                         </div>      
                    </div>
                </div>
            </div>


        