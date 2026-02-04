 <?= $this->extend('Template/main') ?>

<?= $this->section('content') ?>               
                
                
               <div class="container-fluid p-4">
                    <div class="row mb-5 align-items-center">
                        <div class="col-md-8">
                            <h1 class="h3 mb-2 fw-bold" style="color: var(--ucot-blue);">
                                <i class="fas fa-user-secret me-2"></i> Buzón de Opiniones
                            </h1>
                            <p class="text-muted">
                                Aquí encontrarás el feedback anónimo de tus estudiantes. 
                                Usa esta información para potenciar tu metodología.
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="card shadow-sm border-0 bg-white">
                                <div class="card-body p-3">
                                    <span class="text-muted text-uppercase small fw-bold">Total Opiniones</span>
                                    <h3 class="mb-0 fw-bold text-dark"><? ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <?php if(!empty($comentarios)): ?>
                            <?php foreach($comentarios as $op): ?>
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100 border-0 shadow-sm card-opinion">
                                        <div class="card-body p-4 d-flex flex-column">
                                            
                                            <div class="mb-3">
                                                <i class="fas fa-quote-left fa-2x text-light-blue"></i>
                                            </div>

                                            <p class="card-text text-dark flex-grow-1 fst-italic">
                                                "<?= esc($op['comentario']) ?>"
                                            </p>

                                            <div class="mt-3 pt-3 border-top d-flex justify-content-between align-items-center">
                                                <span class="badge bg-light text-dark border">
                                                    <i class="far fa-calendar me-1"></i> 
                                                    <?= date('d/m/Y', strtotime($op['fecha_envio'])) ?>
                                                </span>
                                                <small class="text-muted">Anónimo</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        
                        <?php else: ?>
                            <div class="col-12 text-center py-5">
                                <div class="empty-state">
                                    <div class="mb-4">
                                        <div class="icon-circle bg-light d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px; border-radius: 50%;">
                                            <i class="far fa-comments fa-3x text-muted"></i>
                                        </div>
                                    </div>
                                    <h3 class="fw-bold text-muted">Aún no hay opiniones</h3>
                                    <p class="text-muted">Tus alumnos aún no han enviado comentarios anónimos.</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>



<?= $this->endSection() ?>
