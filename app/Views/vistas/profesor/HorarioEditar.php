<?= $this->extend('Template/main') ?>

<?= $this->section('content') ?>
 

    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card card-personalizada shadow-lg">
                <div class="card-header card-header-personalizado bg-primary text-white text-center py-4">
                    <h3 class="mb-0">Editar Horario</h3>
                </div>   
                <!--Tabla para datos-->
                <div class="card-body p-4">    
                    <form action="<?= base_url('profesor/update_horario/'.$horario['id_horario']) ?>" method="post" >
                                
                        <div class="form-group mb-4">
                            <label for="week_day" class="fw-bold mb-2">Día de la semana</label>
                            <select class="form-select form-control-personalizado" id="week_day" name="week_day" required>
                                <option value="">Seleccione...</option>
                                <option value="Lunes" <?= $horario['week_day']=='Lunes'?'selected':'' ?>>Lunes</option>
                                <option value="Martes" <?= $horario['week_day']=='Martes'?'selected':'' ?>>Martes</option>
                                <option value="Miércoles" <?= $horario['week_day']=='Miércoles'?'selected':'' ?>>Miércoles</option>
                                <option value="Jueves" <?= $horario['week_day']=='Jueves'?'selected':'' ?>>Jueves</option>
                                <option value="Viernes" <?= $horario['week_day']=='Viernes'?'selected':'' ?>>Viernes</option>
                                <option value="Sábado" <?= $horario['week_day']=='Sábado"'?'selected':'' ?>>Sábado</option>
                                <option value="Domingo" <?= $horario['week_day']=='Domingo'?'selected':'' ?>>Domingo</option>
                            </select>
                        </div>

                        <div class="form-group mb-4">
                            <label for="hora_inicio" class="fw-bold mb-2">Hora Inicio</label>
                            <input type="time" class="form-control form-control-personalizado" id="hora_inicio" name="hora_inicio" 
                                value="<?= $horario['hora_inicio'] ?>" required>
                        </div>

                        <div class="form-group mb-4">
                            <label for="hora_fin" class="fw-bold mb-2">Hora Fin</label>
                            <input type="time" class="form-control form-control-personalizado" id="hora_fin" name="hora_fin" 
                                value="<?= $horario['hora_fin'] ?>" required>
                        </div>

                        <div class="form-group mb-4">
                            <label for="estado" class="fw-bold mb-2">Estado</label>
                            <select class="form-select form-control-personalizado" id="estado" name="estado" required>
                                <option value="Disponible" <?= $horario['estado']=='Disponible'?'selected':'' ?>>Disponible</option>
                                <option value="Reservado" <?= $horario['estado']=='Reservado'?'selected':'' ?>>Reservado</option>
                                <option value="No_Trabaja" <?= $horario['estado']=='No_Trabaja'?'selected':'' ?>>No_Trabaja</option>
                            </select>
                        </div>

                        <td class="text-center">
                            <div class="card-footer bg-light d-flex justify-content-center align-items-center py-3 gap-2">
                                <button type="submit" class="btn btn-success btn-redondeado btn-sm shadow">
                                    Guardar Cambios
                                </button>
                                
                                <a href="<?= base_url('profesor/HorarioLeer') ?>" 
                                class="btn btn-danger btn-redondeado btn-sm shadow text-decoration-none">
                                    Cancelar
                                </a>
                            </div>              
                        </td>

                    </form>
                </div>
            </div>
        </div>
    </div>

<?= $this->endSection() ?>
