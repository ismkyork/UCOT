
<?= $header?>
<?=$menu?>   

<!-- Formulario -->
        <form action="<?= base_url('profesor/store_horarios') ?>" method="POST" >
            <div class="card shadow-lg mb-3">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-4">Añadir Nuevo Horario</h2>
                </div>
                    <div class="form-group mb-3">
                        <!--Seleccionar profesor de la bdd-->
                        <label for="id_profesor" class="form-label">Profesor</label>
                        <select class="form-select" id="id_profesor" name="id_profesor" required>
                            <option value="">Seleccione un profesor...</option>
                                <?php foreach($profesores as $modelProfesor): ?>
                                <option value="<?= $modelProfesor['id_profesor']; ?>">
                                    <?= $modelProfesor['nombre_profesor']; ?>
                                </option>
                                <?php endforeach; ?>
                        </select>
                    </div>


                    <div class="form-group mb-3">
                        <label for="week_day" class="form-label">Día de la semana</label>
                        <select class="form-select" id="week_day" name="week_day" required>
                            <option value="">Seleccione...</option>
                            <option value="Lunes">Lunes</option>
                            <option value="Martes">Martes</option>
                            <option value="Miércoles">Miércoles</option>
                            <option value="Jueves">Jueves</option>
                            <option value="Viernes">Viernes</option>
                            <option value="Sábado">Sábado</option>
                            <option value="Domingo">Domingo</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="hora_inicio" class="form-label">Hora inicio</label>
                        <input type="time" class="form-control" id="hora_inicio" name="hora_inicio" required>
                    </div>

                    <div class="form_group mb-3">
                        <label for="hora_fin" class="form-label">Hora fin</label>
                        <input type="time" class="form-control" id="hora_fin" name="hora_fin" required>
                    </div>

                    <div class="form_group mb-3">
                        <label for="estado" class="form-label">Estado</label>
                        <select class="form-select" id="estado" name="estado" required>
                            <option value="Disponible">Disponible</option>
                            <option value="Reservado">Reservado</option>
                            <option value="No_Trabaja">No_Trabaja</option>
                        </select>

                    </div>

                    <button type="submit" class="btn fw-bold" style="background-color: #48ff00;">Guardar</button>
                    <a href="<?= base_url('profesor/HorarioLeer') ?>" class="btn fw-bold btn-light" style="background-color: #ff0000;">Cancelar</a>
            </div>       
        </form>
   
 <?= $footer ?>
