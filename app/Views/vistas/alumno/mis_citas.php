<?=$header?> 
<?=$menu?>   


                        <?php if(session()->getFlashdata('msg_error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Error:</strong> <?= session()->getFlashdata('msg_error') ?>

                            </div>
                        <?php endif; ?>

                        <?php if(session()->getFlashdata('msg')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?= session()->getFlashdata('msg') ?>
                                <button type="button" class="btn-close" data-bs-alert="alert" aria-label="Close"> </button>
                            </div>
                
                        <?php endif; ?>
                                            
                <h1>Mis Citas</h1>
                
                <form action="<?= base_url('alumno/citas/guardar') ?>" method="POST">
                    <div class="card shadow-lg mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Insertar Datos para agendar una clase</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3">

                                    <label for="fecha_hora_inicio">Indique la fecha y hora de inicio</label>
                                    <input id="fecha_hora_inicio" 
                                        value="<?= old('fecha_hora_inicio') ?>" 
                                        class="form-control" 
                                        type="datetime-local" 
                                        name="fecha_hora_inicio" 
                                        step="60" 
                                        required>
                                    <small class="text-muted">Recuerde: Solo se permiten horas en punto (:00) o media hora (:30).</small>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="materia">Indique la materia</label>
                                    <input id="materia" 
                                    value="<?=old('materia')?>" 
                                    class="form-control" 
                                    type="text"
                                    name="materia" 
                                    placeholder="Ej: Química" 
                                    required>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="duracion_min">Duración de la cita:</label>
                                    <select name="duracion_min" id="duracion_min" class="form-control" required>
                                        <option value="" disabled selected>Selecciona el tiempo...</option>
                                        <option value="60">1 hora (60 min)</option>
                                        <option value="120">2 horas (120 min)</option>
                                        <option value="180">3 horas (180 min)</option>

                                    </select>
                                </div>

                                <button type="submit" class="btn btn-success btn-lg w-100">
                                    <i class="fas fa-save me-2"></i> Reservar
                                </button>
                                
                            </div>
                    </div>
                </form>
                        
<?=$footer?>    