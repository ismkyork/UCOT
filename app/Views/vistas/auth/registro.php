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

                    <h4 class="text-center fw-bold mb-4">Registro de Estudiante</h4>

                    <?php if (session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('msg')): ?>
                        <div class="alert alert-warning">
                            <?= session()->getFlashdata('msg') ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('auth/registrarUsuario') ?>" method="POST">
                        <?= csrf_field() ?>

                        <input type="hidden" name="tipo_user" value="Estudiante">

                        <div class="form-group mb-4">
                            <label for="nombre" class="fw-bold mb-2">Nombre</label>
                            <input id="nombre" 
                                value="<?= old('nombre') ?>" 
                                type="text" 
                                name="nombre"  
                                class="form-control form-control-personalizado" 
                                placeholder="Ej: Jose" required
                                oninput="validarSoloLetras(this)">
                        </div>

                        <div class="form-group mb-4">
                            <label for="apellido" class="fw-bold mb-2">Apellido</label>
                            <input type="text" 
                                name="apellido" 
                                id="apellido" 
                                value="<?= old('apellido') ?>"
                                class="form-control form-control-personalizado"
                                placeholder="Ej: Marcano" required
                                oninput="validarSoloLetras(this)">
                        </div>                            

                        <div class="form-group mb-4">
                            <label for="correo" class="fw-bold mb-2">Email</label>
                            <input id="correo" 
                                value="<?= old('correo') ?>"
                                class="form-control form-control-personalizado"
                                type="email" 
                                name="correo" 
                                placeholder="Ej: usuario@correo.com" required>
                        </div>                                   

                        <div class="form-group mb-4">
                            <label for="password" class="fw-bold mb-2">Contraseña</label>
                            <div class="position-relative d-flex align-items-center">
                                <input type="password" 
                                    name="contraseña" 
                                    id="password" 
                                    class="form-control form-control-personalizado" 
                                    placeholder="Ej: Contraseña.2004" required
                                    oninput="actualizarSeguridad(this)">
                                
                                <div class="position-absolute end-0 me-3 d-flex align-items-center">
                                    <i id="toggleIcon" class="fas fa-eye text-muted me-3" 
                                        style="cursor: pointer; font-size: 1.1rem;" 
                                        onclick="togglePassword()"></i>
                                    
                                <a tabindex="0" 
                                    class="btn btn-sm btn-link text-info p-0 ms-2" 
                                    role="button" 
                                    data-bs-toggle="popover" 
                                    data-bs-trigger="focus" 
                                    title="Requisitos de Contraseña" 
                                    data-bs-content="La contraseña debe tener al menos 6 caracteres, una mayúscula, un número y un carácter especial.">
                                    <i class="fas fa-info-circle fa-lg"></i>
                                </a>
                                </div>
                            </div>
                            
                            <div class="progress mt-3" style="height: 6px; background-color: #e9ecef; border-radius: 10px;">
                                <div id="password-strength-bar" class="progress-bar" role="progressbar" style="width: 0%; transition: width 0.4s ease;"></div>
                            </div>
                            <small id="password-feedback" class="fw-bold mt-1 d-block" style="font-size: 0.75rem; color: #dc3545;">Seguridad: Muy débil</small>
                        </div>   

                        <div class="d-grid mt-5">
                            <button type="submit" class="btn btn-success btn-redondeado btn-lg shadow">
                                <i class="fas fa-user-plus me-2"></i> Registrarse
                            </button>
                        </div>

                    </form>                       
                    
                </div>
            </div>

        </div>
    </div>
</div>
<?= $this->endSection() ?>