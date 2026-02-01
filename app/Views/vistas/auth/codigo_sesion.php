<?=$header?>
                  
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card card-personalizada shadow-lg">
            
                    <div class="card-body p-4">
                       
                        <form action="<?= base_url('auth/verificar_codigo') ?>" method="POST">

                            <div class="row align-items-center mb-4">
                                <label class="fw-bold mb-2">Introduce el código de seguridad</label>

                                <p class="text-muted mb-4">
                                    Comprueba si has recibido en el correo electrónico un mensaje con tu código de 6 dígitos.
                                </p>                             

                                <div class="col-6">
                                    <label class="fw-bold small mb-1">Código de verificación</label>
                                    <input type="tel" 
                                        name="codigo" 
                                        id="codigo_seguridad"
                                        class="form-control form-control-personalizado text-center fw-bold" 
                                        placeholder="000000" 
                                        required 
                                        maxlength="6"
                                        pattern="\d{6}"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);">
                                </div>
                                <div class="col-6 ">
                                    <small class="text-muted d-block">Hemos enviado el código a:</small>
                                    <small class="fw-bold text-dark text-break">
                                        <?= session()->get('email_recuperacion') ?? 'tu correo electrónico' ?>
                                    </small>
                                </div>
                                <div class="mb-3">
                                    <a href="#" class="text-decoration-none small">¿No has recibido el código?</a>
                                </div>

                                <div class="card-footer bg-light d-flex justify-content-center align-items-center py-3 gap-2">
                                    <a href="<?= base_url('/') ?>" class="btn btn-danger btn-redondeado px-4">
                                        Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-success btn-redondeado px-4">
                                        Continuar
                                    </button>
                                </div>
                            </div>       
                        </form>
                    </div>
                </div>
            </div>
        </div>

<?= $footer?>


   