<?= $header ?> 
<?= $menu ?>  

<div class="row justify-content-center mt-4">
    <div class="col-md-6"> 
        <div class="card card-personalizada shadow-lg border-0" style="border-radius: 20px;">
            <div class="card-header card-header-personalizado bg-primary text-white text-center py-4" style="border-radius: 20px 20px 0 0;">
                <h3 class="mb-0 fw-bold">Finalizar Reserva y Pago</h3>
                <small class="opacity-75">Cita #<?= $id_cita ?></small>
            </div>  
            <div class="card-body p-4">

                <div class="payment-automation-box text-center mb-4 p-4" style="background-color: #f0f7ff; border-radius: 15px; border: 2px dashed #0070ba;">
                    <h5 class="fw-bold text-primary mb-2">🚀 Pago Automático Instantáneo</h5>
                    <p class="small text-muted mb-3">La cita se confirmará al instante sin esperar revisión.</p>
                    
                    <div id="paypal-button-container"></div>
                </div>

                <div class="text-center my-4 text-muted d-flex align-items-center justify-content-center">
                    <hr class="flex-grow-1"> <span class="mx-3 small fw-bold text-uppercase">O Pago Manual</span> <hr class="flex-grow-1">
                </div>

                <?php if(session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger shadow-sm">
                        <ul class="mb-0 small">
                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="post" action="<?= base_url('alumno/guardar_pago') ?>" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id_cita" value="<?= $id_cita ?>">

                    <div class="form-group mb-3">
                        <label for="id_pago" class="fw-bold mb-1 small text-uppercase">Número de Referencia</label>
                        <input id="id_pago" name="id_pago" value="<?= old('id_pago') ?>" class="form-control form-control-lg" type="text" placeholder="Ej: 987654321" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="screenshot" class="fw-bold mb-1 small text-uppercase">Comprobante (Captura/Foto)</label>
                        <input id="screenshot" name="imagen_pago" class="form-control" type="file" accept="image/*" required>
                    </div>
                
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label for="monto" class="fw-bold mb-1 small text-uppercase">Monto (Bs.)</label>
                                <input type="text" id="monto" name="monto" value="0,00" class="form-control text-end fw-bold" inputmode="numeric" oninput="validarYFormatearMonto(this)" required>
                                <div id="error-monto" style="color: red; display: none; font-size: 12px; margin-top: 5px;">El monto debe ser mayor a 0</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label for="fecha_pago" class="fw-bold mb-1 small text-uppercase">Fecha Transacción</label>
                                <input id="fecha_pago" name="fecha_pago" value="<?= date('Y-m-d') ?>" class="form-control" type="date" required>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-dark btn-lg shadow-sm py-3">
                            <i class="fas fa-file-invoice-dollar me-2"></i> Notificar Pago Manual
                        </button>
                    </div>  
                </form>     
            </div>
            <div class="card-footer bg-light text-center py-3" style="border-radius: 0 0 20px 20px;">
                <a href="<?= base_url('alumno/mis_citas') ?>" class="text-decoration-none text-muted small">
                    <i class="fas fa-arrow-left me-1"></i> Volver a mis bloques
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://www.paypal.com/sdk/js?client-id=AY5m-6e4MzC8fB7TRV4fURarPgj88_NgeMp4PoNduNhdgwFQXuSnaoIC8mGg1_CWaNtBBIMpHdFsBO7x&currency=USD"></script>

<script>
    paypal.Buttons({
        style: {
            layout: 'vertical',
            color:  'blue',
            shape:  'rect',
            label:  'paypal'
        },
        createOrder: function(data, actions) {
            return actions.order.create({
                purchase_units: [{
                    description: "Pago de Asesoría UCOT - Cita #<?= $id_cita ?>",
                    amount: {
                        currency_code: 'USD',
                        // Usamos el precio enviado desde el controlador Alumno.php
                        value: '<?= $precio_paypal ?? "10.00" ?>' 
                    }
                }]
            });
        },
        onApprove: function(data, actions) {
            return actions.order.capture().then(function(details) {
                // Redirección al controlador de éxito pasándole el Order ID
                window.location.href = "<?= base_url('alumno/pago_paypal_exito') ?>/" + details.id;
            });
        },
        onError: function(err) {
            console.error('Error PayPal:', err);
            alert('No se pudo abrir la ventana de PayPal. Intente nuevamente o use el pago manual.');
        }
    }).render('#paypal-button-container');

    // Función para el formato de moneda en Bolívares
    function validarYFormatearMonto(input) {
        let valorRaw = input.value.replace(/[^0-9]/g, '');
        if (!valorRaw || parseInt(valorRaw) === 0) {
            input.value = "0,00";
            document.getElementById('error-monto').style.display = 'block';
            return;
        }
        document.getElementById('error-monto').style.display = 'none';
        const opciones = { minimumFractionDigits: 2, maximumFractionDigits: 2 };
        const formateado = new Intl.NumberFormat('de-DE', opciones).format(parseFloat(valorRaw) / 100);
        input.value = formateado;
    }
</script>

<?= $footer ?>