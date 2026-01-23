<?=$header?>

    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card card-personalizada shadow-lg">
                         <div class="card-body p-4">

                              
                                    <div class="form-group mb-5">
                                        <a href="<?= base_url('/') ?>" class="btn-volver-esquina" title="Volver al inicio">
                                            <i class="fas fa-arrow-left"></i>
                                        </a>   
                                    </div>
                               
                                <form action="<?=base_url('auth/registrarUsuario')?>" method="POST">

                                        <div class="form-group mb-3">
                                            <label for="name">Nombre</label>
                                            <input id="name" value="<?=old('name')?>" class="form-control" type="text" name="name" placeholder="Jose" required>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="apellido">Apellido</label>
                                            <input id="apellido" value="<?=old('apellido')?>" class="form-control" type="text" name="apellido" placeholder="Ej: Marcano" required>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="email">Email</label>
                                            <input id="email" value="<?=old('email')?>" class="form-control" type="email" name="email" placeholder="Ej: usuario@correo.com" required>
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="password">Contraseña</label>
                                            <input id="password" class="form-control" type="password" name="password" placeholder="*********" required>
                                        </div> <div class="form-group mb-3">
                                            <label for="tipo_user">Tipo de Usuario</label>
                                            <select id="tipo_user" class="form-control" name="tipo_user">
                                                <option value="" disabled selected>Seleccione un tipo de usuario</option required>
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

<?= $footer?>





