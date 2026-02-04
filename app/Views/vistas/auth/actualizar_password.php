<?= $this->extend('Template/public_main') ?>

<?= $this->section('content_publico') ?>
        <div class="container py-5"> 
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="card card-personalizada shadow-lg">   
                        <div class="card-body p-4">
                            <div class="mb-4">
                                <a href="<?= base_url('/') ?>" class="btn-volver-esquina">
                                    <i class="fas fa-arrow-left"></i>
                                </a>   
                            </div>
                        
                            <form action="<?= base_url('auth/guardar_nueva_password') ?>" method="POST" id="formPassword">
                                
                                <div class="form-group mb-3">
                                        <label class="fw-bold mb-2">Nueva Contraseña</label>
                                    <div class="position-relative d-flex align-items-center">
                                        <input type="password" name="password" id="password" 
                                            class="form-control form-control-personalizado pe-5" 
                                            placeholder="Ingresa tu nueva clave" required
                                            oninput="actualizarSeguridad(this); validarCoincidencia();">
                                        
                                        <div class="position-absolute end-0 me-3 d-flex align-items-center">
                                            <i id="toggleIcon" class="fas fa-eye text-muted me-3" 
                                            style="cursor: pointer; font-size: 1.1rem;" 
                                            onclick="togglePassword()"></i>
                                            
                                            <i class="fas fa-info-circle text-info" 
                                            style="cursor: pointer; font-size: 1.1rem;"
                                            data-bs-toggle="popover" data-bs-placement="top" 
                                            title="Reglas de seguridad" 
                                            data-bs-content="Mínimo 6 caracteres, una mayúscula, números y evitar palabras comunes."></i>
                                        </div>
                                    </div>
                                    
                                    <div class="progress mt-3" style="height: 6px; background-color: #e9ecef; border-radius: 10px;">
                                        <div id="password-strength-bar" class="progress-bar" role="progressbar" style="width: 0%; transition: width 0.4s ease;"></div>
                                    </div>
                                    <small id="password-feedback" class="fw-bold mt-1 d-block" style="font-size: 0.75rem;">Seguridad: Muy débil</small>
                        
                                </div>  

                                <div class="form-group mb-3">
                                    <label class="fw-bold mb-2">Confirmar Contraseña</label>
                                    <div class="position-relative d-flex align-items-center">
                                        <input type="password" name="confirm_password" id="confirm_password" 
                                            class="form-control form-control-personalizado pe-5" 
                                            placeholder="Repite tu contraseña" required
                                            oninput="validarCoincidencia()">
                                        <div class="position-absolute end-0 me-3">
                                            <i id="match-icon" class="fas fa-lock text-muted"></i>
                                        </div>
                                    </div>
                                    <small id="match-feedback" class="mt-1 d-block fw-bold" style="font-size: 0.75rem;"></small>
                                </div>

                                <div class="d-grid">
                                   <button type="submit" id="btn-submit" class="btn btn-success btn-redondeado btn-lg shadow" disabled>
                                        Actualizar Contraseña
                                    </button>
                                </div>
  
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

  <?= $this->endSection() ?>


   