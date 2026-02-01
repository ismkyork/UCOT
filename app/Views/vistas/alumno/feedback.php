        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card-personalizada text-center p-5">
                    <div class="mb-3">
                        <i class="fa-regular fa-comments"></i>
                    <h2 class="fw-bold mb-3" style="color: #2c3e50;">Opiniones del Alumnado</h2>
                    <p class="text-muted mb-4">
                        Revisa los comentarios y sugerencias anónimas para mejorar tus clases.
                    </p>
                    <button id="openFeedback" class="btn-concept-azul">
                        Ver Comentarios
                    </button>
                </div>
            </div>
        </div>

    <div id="feedbackModal" class="modal-overlay-concept">
        <div class="modal-content-concept">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-0">Opiniones Anónimas</h4>
                <button id="closeFeedback" class="btn-close"></button>
            </div>
            <hr>
            <div class="py-4 text-center">
                <p class="text-muted">Aún no hay comentarios recibidos.</p>
            </div>
            <button class="btn btn-secondary rounded-pill px-4" id="btnCerrarModal">Cerrar</button>
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