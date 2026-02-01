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
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalOpiniones">
                                    Ver Comentarios
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="modalOpiniones" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                            <div class="modal-header border-0 pb-0">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4 pt-0">
                                <div class="text-center mb-4">
                                    <i class="fas fa-user-secret fa-3x text-info mb-3"></i>
                                    <h2 class="fw-bold">Opiniones Anónimas</h2>
                                </div>
                                
                                <div class="lista-comentarios" style="max-height: 400px; overflow-y: auto;">
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
                                            <button class="btn btn-secondary rounded-pill mt-3" data-bs-dismiss="modal">Cerrar</button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>