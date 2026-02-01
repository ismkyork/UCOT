<?=$header?>
       
        
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card card-personalizada shadow-lg">   
                    <div class="card-body p-4">
                        <form action="<?= base_url('auth/guardar_nueva_password') ?>" method="POST" id="formPassword">
                            
                            <div class="form-group mb-3">
                                    <label class="fw-bold mb-2">Nueva Contraseña</label>
                                <div class="position-relative d-flex align-items-center">
                                    <input type="password" name="password" id="password" 
                                        class="form-control form-control-personalizado pe-5" 
                                        placeholder="Ingresa tu nueva clave" required>
                                    
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

                            <div class="form-group mb-3">
                                <label class="fw-bold mb-2">Confirmar Contraseña</label>
                                <div class="position-relative d-flex align-items-center">
                                    <input type="password" name="confirm_password" id="confirm_password" 
                                        class="form-control form-control-personalizado pe-5" 
                                        placeholder="Repite tu contraseña" required>
                                    <div class="position-absolute end-0 me-3">
                                        <i id="match-icon" class="fas fa-lock text-muted"></i>
                                    </div>
                                </div>
                                <small id="match-feedback" class="mt-1 d-block fw-bold" style="font-size: 0.75rem;"></small>
                            </div>

                            <div class="d-grid">
                                <button type="submit" id="btn-submit" class="btn btn-success btn-redondeado btn-lg shadow">
                                    Actualizar Contraseña
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

             
      <script>
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


            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm_password');
            const matchIcon = document.getElementById('match-icon');
            const matchFeedback = document.getElementById('match-feedback');
            const btnSubmit = document.getElementById('btn-submit');

            function validarCoincidencia() {
                const val1 = password.value;
                const val2 = confirmPassword.value;

                if (val2.length === 0) {
                    matchIcon.className = "fas fa-lock text-muted";
                    matchFeedback.innerText = "";
                    btnSubmit.disabled = true;
                    return;
                }

                if (val1 === val2) {
                    // Coinciden
                    matchIcon.className = "fas fa-check-circle text-success";
                    matchFeedback.innerText = "Las contraseñas coinciden";
                    matchFeedback.style.color = "#198754"; // Verde
                    btnSubmit.disabled = false; // Habilitar botón
                } else {
                    // No coinciden
                    matchIcon.className = "fas fa-times-circle text-danger";
                    matchFeedback.innerText = "Las contraseñas no coinciden";
                    matchFeedback.style.color = "#dc3545"; // Rojo
                    btnSubmit.disabled = true; // Bloquear botón
                }
            }

            // Escuchar cambios en ambos campos
            password.addEventListener('input', validarCoincidencia);
            confirmPassword.addEventListener('input', validarCoincidencia);
    </script>

<?= $footer?>


   