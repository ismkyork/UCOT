<?= $this->extend('Template/main') ?>

<?= $this->section('content') ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-5">

            <div class="card card-personalizada shadow-lg border-0 rounded-4">
                <div class="card-body p-4 p-md-5">

                    <div class="text-center mb-4">
                        <h4 class="card-header-personalizado">IMAGEN DE PERFIL</h4>
                        <p class="text-muted small">Sube una nueva foto para tu perfil</p>
                    </div>
                    
                    <form action="<?= base_url('configuracion/actualizar_foto') ?>" method="post" enctype="multipart/form-data">
                        
                        <div class="d-flex flex-column align-items-center mb-4">
                            <div class="position-relative mb-3">
                                <div id="avatar-preview" class="avatar-circle shadow-sm overflow-hidden d-flex align-items-center justify-content-center" 
                                    style="width: 120px; height: 120px; font-size: 3rem; background-color: #F06543; color: white;">
                                    
                                    <?php 
                                        $foto_sesion = session('foto');
                                        if($foto_sesion && $foto_sesion != 'default.png' && file_exists(FCPATH . 'uploads/perfiles/' . $foto_sesion)): 
                                    ?>
                                        <img src="<?= base_url('uploads/perfiles/' . $foto_sesion) ?>" class="w-100 h-100" style="object-fit: cover;">
                                    <?php else: ?>
                                        <span id="avatar-initial"><?= substr(session('nombre') ?? 'U', 0, 1) ?></span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="position-absolute bottom-0 end-0 bg-white rounded-circle p-2 shadow-sm border" style="transform: translate(10%, 10%);">
                                    <i class="fas fa-camera text-muted"></i>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4 text-center">
                            <label class="btn btn-outline-secondary btn-sm rounded-pill px-4 cursor-pointer" for="foto_perfil">
                                <i class="fas fa-upload me-2"></i> Seleccionar archivo
                            </label>
                            <input type="file" name="foto_perfil" id="foto_perfil" class="d-none" accept="image/*" onchange="previewImage(this)">
                            
                            <small class="text-muted d-block mt-2" style="font-size: 0.75rem;">
                                Archivos JPG, PNG o GIF. Máximo 2MB.
                            </small>
                        </div>

                        <div class="d-grid gap-2 mt-5">
                            <button type="submit" class="btn btn-ucot-primary btn-redondeado btn-lg shadow">
                                <i class="fas fa-save me-2"></i> GUARDAR FOTO
                            </button>
                        
                             <a href="<?= base_url('configuracion') ?>" class="btn btn-ucot-danger btn-redondeado text-decoration-none text-white">
                                CANCELAR
                            </a> 
                        </div>

                    </form>
                </div>
            </div>

            <div class="mt-4 text-center">
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i> 
                    Tu nueva foto será visible para todos.
                </small>
            </div>

        </div>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        
        reader.onload = function(e) {
            var previewContainer = document.getElementById('avatar-preview');
            // Reemplazamos todo el contenido del círculo con la imagen
            previewContainer.innerHTML = '<img src="' + e.target.result + '" class="w-100 h-100 object-fit-cover">';
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?= $this->endSection() ?>