<?= $this->extend('Template/public_main') ?>

<?= $this->section('content_publico') ?>
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-5">

                    <div class="card card-personalizada shadow-lg border-0 rounded-4">
                        <div class="card-body p-4 p-md-5">
                            
                            <div class="mb-4">
                                <a href="<?= base_url('/') ?>" class="btn-volver-esquina">
                                    <i class="fas fa-arrow-left"></i>
                                </a>   
                            </div>

                              <form action="<?=base_url('auth/registrarUsuario')?>" method="POST">

                                    <div class="form-group mb-4">
                                        <label for="name" class="fw-bold mb-2">Nombre</label>
                                        <input id="name" 
                                            value="<?=old('name')?>" 
                                            type="text" 
                                            name="name"  
                                            class="form-control form-control-personalizado" 
                                            placeholder="Ej: Jose" required
                                            oninput="validarSoloLetras(this)">
                                    </div>

                                    <div class="form-group mb-4">
                                        <label for="apellido" class="fw-bold mb-2">Apellido</label>
                                        <input type="text" 
                                            name="apellido" 
                                            id="apellido" 
                                            value="<?=old('apellido')?>"
                                            class="form-control form-control-personalizado"
                                            placeholder="Ej: Marcano" required
                                            oninput="validarSoloLetras(this)">
                                    </div>                                            

                                    <div class="form-group mb-4">
                                        <label for="email" class="fw-bold mb-2">Email</label>
                                        <input id="email" value="<?=old('email')?>"
                                            class="form-control form-control-personalizado"
                                            type="email" 
                                            name="email" 
                                            placeholder="Ej: usuario@correo.com" required>
                                    </div>                                   

                                    <div class="form-group mb-4">
                                            <label for="password" class="fw-bold mb-2">Contraseña</label>
                                        <div class="position-relative d-flex align-items-center">
                                            <input type="password" name="password" id="password" 
                                                class="form-control form-control-personalizado" 
                                                placeholder="Ej: Contraseña.2004" required
                                                oninput="actualizarSeguridad(this)">
                                            
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
                                        <small id="password-feedback" class="fw-bold mt-1 d-block" style="font-size: 0.75rem; color: #dc3545;">Seguridad: Muy débil</small>
                                    </div>  

                                    <div class="form-group mb-4">
                                        <label for="tipo_user" class="fw-bold mb-2">Tipo de Usuario</label>
                                        <select id="tipo_user" class="form-control" name="tipo_user" required>
                                            <option value="" disabled selected>Seleccione un tipo de usuario</option>
                                            <option value="Profesor">Profesor</option>
                                            <option value="Estudiante">Estudiante</option>
                                        </select>
                                    </div>
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-success btn-redondeado btn-lg shadow">
                                            <i class="fas fa-save me-2"></i> Registrar
                                        </button>
                                    </div>

                            </form>                           

                            
                        </div>
                    </div>

                </div>
            </div>
        </div>
<?= $this->endSection() ?>