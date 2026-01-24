            
           
        </div>
    </div>

        <footer class="bg-dark text-white p-4 text-center mt-4">
         <p class="mb-0">
                 &copy; 2025 Derechos Reservados.
          <br>
            </p>
        </footer>
       
        <?php if (session()->get('rol') == 'Estudiante'): ?>


                    <button id="openFeedback" class="feedback-btn">
                     <span class="icon">📩</span>Deja tu reseña
                    </button>

                    <div id="feedbackModal" class="modal-overlay">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h2>Tu opinión nos importa</h2>
                            <button id="closeFeedback" class="close-btn">&times;</button>
                            </div>
                            <p>Deja tu reseña sobre el profesor aquí</p>
                            <textarea placeholder="Escribe aquí tus sugerencias..."></textarea>
                            <button class="submit-btn">Enviar Opinión</button>
                        </div>
                  </div>


                    <script>
                    // Usamos el evento 'DOMContentLoaded' para asegurar que el HTML existe
                    document.addEventListener('DOMContentLoaded', function() {
                        const openBtn = document.getElementById('openFeedback');
                        const closeBtn = document.getElementById('closeFeedback');
                        const modal = document.getElementById('feedbackModal');

                        // Solo si los elementos existen en el DOM
                        if (openBtn && modal) {
                            openBtn.addEventListener('click', () => {
                                modal.style.display = 'flex';
                            });

                            closeBtn.addEventListener('click', () => {
                                modal.style.display = 'none';
                            });

                            // Cerrar al hacer clic fuera de la caja blanca
                            window.addEventListener('click', (event) => {
                                if (event.target === modal) {
                                    modal.style.display = 'none';
                                }
                            });
                        }
                    });
                </script>
        <?php endif; ?>


 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>       
</body>
</html>

