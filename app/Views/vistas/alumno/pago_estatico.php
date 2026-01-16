<?=$header?> 
<?=$menu?>  
 
 <div class="card shadow-lg mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Insertar Datos de pago</h5>
            </div>
        <div class="card-body">
      
            <form method="post" action="<?= base_url('procesar') ?>" enctype="multipart/form-data">
                    <div class="form-group mb-3">
                                        <label for="id_pago">Número de referencia</label>
                                        <input 
                                            id="id_pago" 
                                            value="<?=old('id_pago')?>" 
                                            class="form-control" 
                                            type="text" 
                                            name="id_pago" 
                                            placeholder="Ej: 0000987654321"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                            pattern="\d*">
                                    </div>

                                    <div class="form-group mb-3">
                                            <label for="screenshot">imagen de pago</label>
                                            <input id="screenshot" 
                                            class="form-control-file form-control" 
                                            type="file" 
                                            name="screenshot">
                                            <small class="form-text text-muted">Sube una imagen (se guardará la ruta).</small>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label for="monto">Monto</label>
                                        <input 
                                            id="monto" 
                                            name="monto"
                                            value="<?=old('monto')?>" 
                                            class="form-control" 
                                            type="text" 
                                            placeholder="Ej: 1500"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                            inputmode="numeric"
                                            required>
                                    </div>
                                    <div class="form-group mb-3">
                                            <label for="fecha_pago">Fecha del pago</label>
                                            <input id="fecha_pago" 
                                            class="form-control" 
                                            type="date" 
                                            name="fecha_pago"
                                             value="<?=old('fecha_lanzamiento')?>"
                                            placeholder="Ej: AAAA-MM-DD o 2015-10-05">
                                            <small class="form-text text-muted">Ingresa la fecha en formato AAAA-MM-DD.</small>
                                    </div>

                                    <button type="submit" class="btn btn-success btn-lg w-100">
                                        <i class="fas fa-save me-2"></i> Reservar
                                    </button>

                    </div>
                </form>     
        </div>
</div>
<?=$footer?>