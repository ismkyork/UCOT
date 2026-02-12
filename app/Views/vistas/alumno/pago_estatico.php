<?= $this->extend('Template/main') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center mt-4">
    <div class="col-md-6"> 
        <div class="card card-personalizada shadow-lg border-0" style="border-radius: 20px;">
            <div class="card-header card-header-personalizado bg-primary text-white text-center py-4" style="border-radius: 20px 20px 0 0;">
                <h3 class="mb-0 fw-bold">Finalizar Reserva y Pago</h3>
                <small class="opacity-75">Cita #<?= $id_cita ?></small>
            </div>  
            <div class="card-body p-4">

                <div class="alert alert-info border-0 shadow-sm mb-4" style="border-radius: 15px; background-color: #eef9ff;">
                    <div class="row align-items-center text-center">
                        <div class="col-4">
                            <small class="text-uppercase fw-bold d-block text-muted">Precio</small>
                            <span class="h5 fw-bold text-dark"><?= number_format($precio_paypal, 2) ?> $</span>
                        </div>
                        <div class="col-4 border-start border-end">
                            <small class="text-uppercase fw-bold d-block text-muted">Tasa BCV</small>
                            <span class="fw-bold text-dark"><?= number_format($tasa_bcv, 2) ?> Bs.</span>
                        </div>
                        <div class="col-4">
                            <small class="text-uppercase fw-bold d-block text-muted">Total Bs.</small>
                            <span class="h5 fw-bold text-primary"><?= number_format($precio_bs, 2, ',', '.') ?> Bs.</span>
                        </div>
                    </div>
                </div>

                <div class="payment-automation-box text-center mb-4 p-4" style="background-color: #f0f7ff; border-radius: 15px; border: 2px dashed #0070ba;">
                    <h5 class="fw-bold text-primary mb-2">🚀 Pago Automático Instantáneo</h5>
                    <p class="small text-muted mb-3">Paga con PayPal y tu cita se confirmará al instante.</p>
                    <div id="paypal-button-container"></div>
                </div>

                <div class="text-center my-4 text-muted d-flex align-items-center justify-content-center">
                    <hr class="flex-grow-1"> <span class="mx-3 small fw-bold text-uppercase">O Pago Móvil</span> <hr class="flex-grow-1">
                </div>

                <div class="card bg-light border-0 mb-4 shadow-sm" style="border-radius: 15px;">
                    <div class="card-body p-3">
                        <h6 class="text-center fw-bold text-dark mb-3">
                            <i class="fas fa-mobile-alt me-2 text-primary"></i>Pago Móvil
                        </h6>
                        
                        <div class="row text-center g-2 mb-3">
                            <div class="col-4">
                                <div class="p-2 border rounded bg-white h-100 d-flex flex-column justify-content-center">
                                    <small class="d-block text-muted text-uppercase" style="font-size: 0.6rem;">Banco</small>
                                    <span class="fw-bold text-dark small">Banesco</span>
                                    <span class="text-muted" style="font-size: 0.65rem;">(0134)</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 border rounded bg-white h-100 d-flex flex-column justify-content-center">
                                    <small class="d-block text-muted text-uppercase" style="font-size: 0.6rem;">Cédula</small>
                                    <span class="fw-bold text-dark small">V-22.163.162</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 border rounded bg-white h-100 d-flex flex-column justify-content-center">
                                    <small class="d-block text-muted text-uppercase" style="font-size: 0.6rem;">Teléfono</small>
                                    <span class="fw-bold text-dark small">0414-826-8268</span>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="button" class="btn btn-outline-primary btn-sm fw-bold border-2" 
                                    style="border-radius: 12px; border-style: dashed;"
                                    onclick="copiarDatosCompletos()">
                                <i class="far fa-copy me-2"></i> COPIAR TODOS LOS DATOS
                            </button>
                        </div>

                        <div class="text-center mt-2">
                            <small class="text-success fst-italic" style="font-size: 0.65rem;">
                                <i class="fas fa-check-circle me-1"></i> Beneficiario verificado
                            </small>
                        </div>
                    </div>
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
                                <label for="monto" class="fw-bold mb-1 small text-uppercase">Monto a Reportar (Bs.)</label>
                                <input type="text" id="monto" name="monto" value="<?= number_format($precio_bs, 2, ',', '.') ?>" class="form-control text-end fw-bold bg-light" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label for="fecha_pago" class="fw-bold mb-1 small text-uppercase">Fecha Transacción</label>
                                <input id="fecha_pago" name="fecha_pago" value="<?= date('Y-m-d') ?>" class="form-control bg-light" type="date" readonly>
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
                        value: '<?= $precio_paypal ?>' 
                    }
                }]
            });
        },
        onApprove: function(data, actions) {
            return actions.order.capture().then(function(details) {
                // Redirige al controlador de éxito que ya tienes configurado
                window.location.href = "<?= base_url('alumno/pago_paypal_exito') ?>/" + details.id;
            });
        }
    }).render('#paypal-button-container');

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

  function copiarDatosCompletos() {
        // Datos limpios para el portapapeles (formato lista)
        const datos = ` Banesco (0134)\n22163162\n 04148268268`;

        navigator.clipboard.writeText(datos).then(function() {
            // Toast de confirmación
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            Toast.fire({
                icon: 'success',
                title: 'Datos copiados al portapapeles'
            });
        }, function(err) {
            console.error('Error al copiar: ', err);
            Swal.fire('Error', 'No se pudo copiar automáticamente', 'error');
        });
    }

</script>

<?= $this->endSection() ?>