
            
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card shadow-lg border-0 text-center" style="border-radius: 20px;">
                    <div class="card-body p-5">
                        <div class="mb-3">
                            <i class="fas fa-user-secret fa-4x text-info"></i>
                        </div>
                        <h3 class="card-title fw-bold">Opiniones del Alumnado</h3>
                        <p class="text-muted">Revisa los comentarios y sugerencias anónimas para mejorar tus clases.</p>
                        
                        <button type="button" 
                                class="btn btn-info btn-lg shadow rounded-pill px-5 text-white fw-bold"
                                onclick="document.getElementById('modalOpiniones').style.display='block'">
                            Ver Comentarios
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div id="modalOpiniones" class="modal-feedback" style="display:none;">
            <div class="modal-content-feedback" style="max-width: 600px; border-radius: 20px;">
                <span class="close-modal" onclick="document.getElementById('modalOpiniones').style.display='none'" style="cursor:pointer; float:right; font-size:28px;">&times;</span>
                
                <div class="text-center">
                    <i class="fas fa-user-secret fa-3x text-info mb-3"></i>
                    <h2 class="fw-bold">Opiniones Anónimas</h2>
                </div>
                
                <div class="lista-comentarios mt-4" style="max-height: 400px; overflow-y: auto;">
                    <?php if(!empty($comentario)): ?>
                        <?php foreach($comentario as $op): ?>
                            <div class="card mb-3 shadow-sm border-start border-info border-4">
                                <div class="card-body">
                                    <p class="mb-1 text-dark">"<?= esc($op['comentario']) ?>"</p>
                                    <small class="text-muted">Recibido: <?= date('d/m/Y', strtotime($op['fecha_envio'])) ?></small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <p class="text-muted">Aún no hay comentarios recibidos.</p>
                            <button class="btn btn-secondary rounded-pill mt-3" onclick="document.getElementById('modalOpiniones').style.display='none'">Cerrar</button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>