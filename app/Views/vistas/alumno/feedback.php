<?= $header ?>
<?= $menu ?>

<div class="container mt-4">

    <?php if (session()->getFlashdata('msg')): ?>
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fa-solid fa-check-circle me-2"></i> <?= session()->getFlashdata('msg') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i> <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card-personalizada text-center p-5">
                <div class="mb-3">
                    <i class="fa-solid fa-pen-to-square fa-3x" style="color: #2c3e50;"></i>
                </div>
                <h2 class="fw-bold mb-3" style="color: #2c3e50;">Califica tu Experiencia</h2>
                <p class="text-muted mb-4">
                    Tu opinión es importante para mejorar nuestras clases.
                    <br>Es totalmente anónimo.
                </p>
                <button id="openFeedback" class="btn-concept-azul">
                    Dejar un Comentario
                </button>
            </div>
        </div>
    </div>

    <hr class="my-5">

    <div class="row justify-content-center mb-5">
        <div class="col-md-8">
            <h4 class="text-muted mb-4 text-center">
                <i class="fa-solid fa-clock-rotate-left me-2"></i> Historial de Opiniones
            </h4>

            <?php if (empty($historial)): ?>
                <div class="alert alert-light text-center border shadow-sm">
                    No hay opiniones registradas todavía. ¡Sé el primero!
                </div>
            <?php else: ?>
                <?php foreach ($historial as $item): ?>
                    <div class="card mb-3 shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-warning">
                                    <?php 
                                    for($i = 1; $i <= 5; $i++) {
                                        echo ($i <= $item['puntuacion']) 
                                            ? '<i class="fa-solid fa-star"></i>' 
                                            : '<i class="fa-regular fa-star text-secondary opacity-25"></i>';
                                    }
                                    ?>
                                </div>
                                <small class="text-muted">
                                    <i class="fa-regular fa-calendar me-1"></i>
                                    <?= date('d/m/Y', strtotime($item['fecha_evaluacion'])) ?>
                                </small>
                            </div>
                            <p class="mt-2 mb-0 text-dark">
                                <?= esc($item['comentario']) ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

</div>

<div id="feedbackModal" class="modal-overlay-concept">
    <div class="modal-content-concept">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0">Nuevo Comentario</h4>
            <button id="closeFeedback" class="btn-close"></button>
        </div>
        <hr>

        <div class="py-2 text-start">
            <form action="<?= base_url('alumno/feedback/guardar') ?>" method="post">
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Calificación</label>
                    <select name="puntuacion" class="form-select" required>
                        <option value="" selected disabled>Selecciona estrellas...</option>
                        <option value="5">⭐⭐⭐⭐⭐ Excelente</option>
                        <option value="4">⭐⭐⭐⭐ Muy buena</option>
                        <option value="3">⭐⭐⭐ Buena</option>
                        <option value="2">⭐⭐ Regular</option>
                        <option value="1">⭐ Mala</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Tu Opinión</label>
                    <textarea name="comentario" class="form-control" rows="4" placeholder="Escribe aquí tus sugerencias..." required></textarea>
                </div>

                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">Enviar Feedback</button>
                    <button type="button" class="btn btn-secondary" id="btnCerrarModal">Cancelar</button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('feedbackModal');
    const openBtn = document.getElementById('openFeedback');
    const closeBtn = document.getElementById('closeFeedback');
    const closeBtnAux = document.getElementById('btnCerrarModal');

    if (openBtn) {
        openBtn.onclick = () => modal.style.display = 'flex';
        
        const cerrar = () => modal.style.display = 'none';
        
        closeBtn.onclick = cerrar;
        closeBtnAux.onclick = cerrar;
        window.onclick = (e) => { if (e.target === modal) cerrar(); };
    }
});
</script>

<?= $footer ?>