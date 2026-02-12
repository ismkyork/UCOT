
            <div class="row ">
                <div class="col-12">
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
                                        id="correo"
                                        type="email" name="correo"
                                        class="form-control form-control-personalizado" 
                                        placeholder="Ingresa tu Correo" 
                                        value="<?= old('correo') ?>" required>
                                    </div>

                                   
                                    <div class="form-group mb-4">
                                            <label for="contraseña" class="fw-bold mb-2">Contraseña</label>
                                        <div class="position-relative d-flex align-items-center">
                                            <input type="password" name="contraseña" id="password" 
                                                class="form-control form-control-personalizado" 
                                                placeholder="Ingresa tu clave" required>
                                            
                                            <div class="position-absolute end-0 me-3 d-flex align-items-center">
                                                <i id="toggleIcon" class="fas fa-eye text-muted me-3" 
                                                style="cursor: pointer; font-size: 1.1rem;" 
                                                onclick="togglePassword()"></i>
                                                                                           
                                            </div>
                                        </div>
                                                    
                                    </div>                                          

                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-success btn-redondeado btn-lg shadow">
                                            <i class="fas fa-sign-in-alt me-2"></i> Iniciar sesión
                                        </button>
                                    </div>

                                     <div class="form-group mb-4 text-center py-3">
                                      <a href="<?= base_url('auth/password_olvidada') ?>" class="text-success fw-bold">¿Has olvidado la contraseña?</a>
                                    </div>
                                </form>
                            </div>
                            
                            <div class="card-footer bg-light text-center py-3">
                                <p class="mb-0 text-muted">¿No tienes cuenta? <a href="<?= base_url('auth/registro') ?>" class="text-success fw-bold">Regístrate</a></p>
                            </div>
                
                    </div>
                </div>
            </div>

