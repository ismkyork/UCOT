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

                            <div class="form-group mb-4">
                                <label for="name" class="fw-bold mb-2">Nombre</label>
                                <input id="name" 
                                    value="<?=old('name')?>" 
                                    class="form-control"
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
                                <input id="email" value="<?=old('email')?>" class="form-control" type="email" name="email" placeholder="Ej: usuario@correo.com" required>
                            </div>                                   

                            <div class="form-group mb-4">
                                    <label for="password" class="fw-bold mb-2">Contraseña</label>
                                <div class="position-relative d-flex align-items-center">
                                    <input type="password" name="password" id="password" 
                                        class="form-control form-control-personalizado" 
                                        placeholder="Ej: Contraseña.2004" required>
                                    
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
    <script>
        function validarSoloLetras(input) {
            // Expresión regular: letras A-Z, a-z, espacios, ñ, Ñ y vocales con tilde
            let regex = /[^a-zA-ZáéíóúÁÉÍÓÚñÑ ]/g;
            
            // Reemplaza cualquier carácter que NO coincida con la regex por nada
            input.value = input.value.replace(regex, "");
        }

         function togglePassword() {
            const passInput = document.getElementById('password');
            const icon = document.getElementById('toggleIcon');
            if (passInput.type === "password") {
                passInput.type = "text";
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passInput.type = "password";
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
         }

        // Medidor de fuerza mejorado
        document.getElementById('password').addEventListener('input', function(e) {
            const pass = e.target.value;
            const bar = document.getElementById('password-strength-bar');
            const feedback = document.getElementById('password-feedback');
            let strength = 0;

            if (pass.length === 0) strength = 0;
            else {
                if (pass.length >= 8) strength += 25;
                if (/[A-Z]/.test(pass)) strength += 25;
                if (/[0-9]/.test(pass)) strength += 25;
                if (/[^A-Za-z0-9]/.test(pass)) strength += 25;
            }

            bar.style.width = strength + "%";
            
            // Limpiar clases de color
            bar.classList.remove('bg-danger', 'bg-warning', 'bg-info', 'bg-success');

            if (strength === 0) {
                bar.style.width = "0%";
                feedback.innerText = "Seguridad: Muy débil";
                feedback.style.color = "#dc3545";
            } else if (strength <= 25) {
                bar.classList.add('bg-danger');
                feedback.innerText = "Seguridad: Débil";
                feedback.style.color = "#dc3545";
            } else if (strength <= 50) {
                bar.classList.add('bg-warning');
                feedback.innerText = "Seguridad: Media";
                feedback.style.color = "#ffc107";
            } else if (strength <= 75) {
                bar.classList.add('bg-info');
                feedback.innerText = "Seguridad: Buena";
                feedback.style.color = "#0dcaf0";
            } else {
                bar.classList.add('bg-success');
                feedback.innerText = "Seguridad: Excelente";
                feedback.style.color = "#198754";
            }
        });

        // Inicializar Popovers
        document.addEventListener('DOMContentLoaded', function () {
            var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
            var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl)
            })
        });

    </script>
<?= $footer?>





