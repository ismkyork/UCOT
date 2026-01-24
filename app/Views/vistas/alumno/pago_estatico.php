<?= $header ?> 
<?= $menu ?>  

<div class="container mt-4">
    <div class="card shadow-lg mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Insertar Datos de Pago</h5>
        </div>
        <div class="card-body">
            
            <?php if(session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach ?>
                </div>
            <?php endif; ?>

            <form method="post" action="<?= base_url('alumno/guardar_pago') ?>" enctype="multipart/form-data">
                
                <input type="hidden" name="id_cita" value="<?= $id_cita ?? 0 ?>">

                <div class="form-group mb-3">
                    <label for="id_pago">Número de referencia</label>
                    <input 
                        id="id_pago" 
                        name="id_pago" 
                        value="<?= old('id_pago') ?>" 
                        class="form-control" 
                        type="text" 
                        placeholder="Ej: 0000987654321"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                        required>
                </div>

                <div class="form-group mb-3">
                    <label for="screenshot">Comprobante de Pago (Imagen)</label>
                    <input 
                        id="screenshot" 
                        name="imagen_pago" 
                        class="form-control" 
                        type="file" 
                        accept="image/*"
                        required>
                    <small class="form-text text-muted">Sube una captura de pantalla legible.</small>
                </div>
                
                <div class="form-group mb-3">
                    <label for="monto">Monto Pagado</label>
                    <input 
                        id="monto" 
                        name="monto"
                        value="<?= old('monto') ?>" 
                        class="form-control" 
                        type="number" 
                        step="0.01"
                        placeholder="Ej: 1500"
                        required>
                </div>

                <div class="form-group mb-3">
                    <label for="fecha_pago">Fecha de la Transacción</label>
                    <input 
                        id="fecha_pago" 
                        name="fecha_pago"
                        value="<?= old('fecha_pago') ?>" 
                        class="form-control" 
                        type="date" 
                        required>
                    <small class="form-text text-muted">Ingresa la fecha que indica el comprobante.</small>
                </div>

                <button type="submit" class="btn btn-success btn-lg w-100">
                    <i class="fas fa-paper-plane me-2"></i> Enviar Pago
                </button>
            </form>     
        </div>
    </div>
</div>

<?= $footer ?>