            
           
        </div>
    </div>

        <footer class="bg-dark text-white p-4 text-center mt-4">
         <p class="mb-0">
                 &copy; 2025 Derechos Reservados.
          <br>
            </p>
        </footer>
        <?php if (session()->get('rol') == 'Estudiante'): ?>
                <button type="button" class="btn-feedback-flotante" onclick="document.getElementById('modalFeedback').style.display='block'">
                    📩 Danos tu Opinión
                </button>

                <div id="modalFeedback" class="modal-feedback">
                    <div class="modal-content-feedback">
                        <span style="cursor:pointer; float:right; font-size: 24px;" onclick="document.getElementById('modalFeedback').style.display='none'">&times;</span>
                        <h2>Tu opinión nos importa</h2>
                        <p>¿Cómo podemos mejorar la plataforma para ti?</p>
                        
                        <form action="<?= base_url('alumno/guardar_feedback') ?>" method="POST">
                            <textarea name="comentario" placeholder="Escribe aquí tus sugerencias..." required></textarea>
                            <br>
                            <button type="submit" style="margin-top:10px; padding:10px 20px; background:#27ae60; color:white; border:none; border-radius:5px; cursor:pointer;">
                                Enviar 
                            </button>
                        </form>
                    </div>
                </div>

                <script>
                    window.onclick = function(event) {
                        var modal = document.getElementById('modalFeedback');
                        if (event.target == modal) {
                            modal.style.display = "none";
                        }
                    }
                </script>
        <?php endif; ?>
</body>
</html>

