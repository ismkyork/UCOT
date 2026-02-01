<?= $header ?> 
<?= $menu ?>  

    <div class="row justify-content-center">
         <div class="col-md-5">
            <div class="card card-personalizada shadow-lg">
                <div class="card-header card-header-personalizado bg-primary text-white text-center py-4">
                    <h3 class="mb-0"> Insertar Datos de Pago</h3>
                </div>  
                <div class="card-body p-4">

                    <?php if(session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger">
                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="<?= base_url('alumno/guardar_pago') ?>" enctype="multipart/form-data">
                        
                        <input type="hidden" name="id_cita" value="<?= $id_cita ?? 0 ?>">

                        <div class="form-group mb-4">
                            <label for="id_pago" class="fw-bold mb-2" >Número de referencia</label>
                            <input 
                                id="id_pago" 
                                name="id_pago" 
                                value="<?= old('id_pago') ?>" 
                                class="form-control form-control-personalizado" 
                                type="text" 
                                placeholder="Ej: 0000987654321"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                required>
                        </div>

                        <div class="form-group mb-4">
                            <label for="screenshot" class="fw-bold mb-2" >Comprobante de Pago (Imagen)</label>
                            <input 
                                id="screenshot" 
                                name="imagen_pago" 
                                class="form-control form-control-personalizado" 
                                type="file" 
                                accept="image/*"
                                required>
                            <small class="form-text text-muted">Sube una captura de pantalla legible.</small>
                        </div>
                    
                        <div class="form-group mb-4">
                                <label for="monto" class="fw-bold mb-2">Monto en (Bs.)</label>
                                <input type="text" 
                                    id="monto" 
                                    name="monto"
                                    value="0,00" 
                                    class="form-control" 
                                    style="text-align: right !important;" 
                                    inputmode="numeric" 
                                    autocomplete="off"
                                    oninput="validarYFormatearMonto(this)"
                                    required>
                                <div id="error-monto" style="color: red; display: none; font-size: 14px; margin-top: 5px;">
                                    El monto no puede ser igual a 0
                                </div>
                            </div>

                            <script>
                            function validarYFormatearMonto(input) {
                                // 1. Lógica idéntica a tu código: Limpia todo lo que NO sea número
                                let valorRaw = input.value.replace(/[^0-9]/g, '');

                                // 2. Si está vacío o es 0, resetear al estado inicial "0,00"
                                if (!valorRaw || parseInt(valorRaw) === 0) {
                                    input.value = "0,00";
                                    document.getElementById('error-monto').style.display = 'block';
                                    return;
                                }

                                document.getElementById('error-monto').style.display = 'none';

                                // 3. Convertir a formato moneda (Ej: 1500 -> 15,00)
                                // Dividimos entre 100 para que los últimos dos dígitos siempre sean decimales
                                const opciones = { minimumFractionDigits: 2, maximumFractionDigits: 2 };
                                const formateado = new Intl.NumberFormat('de-DE', opciones).format(parseFloat(valorRaw) / 100);

                                input.value = formateado;
                            }

                            // Bloqueo adicional para evitar que muevan el cursor y rompan el formato
                            const montoInput = document.getElementById('monto');
                            ['click', 'focus'].forEach(evt => {
                                montoInput.addEventListener(evt, function() {
                                    this.setSelectionRange(this.value.length, this.value.length);
                                });
                            });
                            </script>

                            <div class="form-group mb-4">
                                <label for="fecha_pago" class="fw-bold mb-2">Fecha de la Transacción</label>
                                <input 
                                    id="fecha_pago" 
                                    name="fecha_pago"
                                    value="<?= old('fecha_pago') ?>" 
                                    class="form-control form-control-personalizado" 
                                    type="date" 
                                    required>
                                <small class="form-text text-muted">Ingresa la fecha que indica el comprobante.</small>
                            </div>

                            <div class="card-footer bg-light d-flex justify-content-center align-items-center py-3 gap-2">
                                <button type="submit" class="btn btn-success btn-redondeado btn-sm shadow">
                                    <i class="fas fa-paper-plane me-2"></i> Enviar Pago
                                </button>
                            </div>  
                    </form>     
                </div>
            </div>
        </div>
    </div>

<?= $footer ?>