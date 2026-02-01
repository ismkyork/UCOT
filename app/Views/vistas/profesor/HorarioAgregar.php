
<?= $header?>
<?=$menu?>   
 
    <div class="row justify-content-center">
         <div class="col-md-5">
            <div class="card card-personalizada shadow-lg">
                <div class="card-header card-header-personalizado bg-primary text-white text-center py-4">
                    <h3 class="mb-0">Añadir Nuevo Horario</h3>
                </div>                              

                <div class="card-body p-4">    
                    <form action="<?= base_url('profesor/store_horarios') ?>" method="POST" >
                                
                        <div class="form-group mb-4">
                            <label for="week_day" class="fw-bold mb-2">Día de la semana</label>
                            <select class="form-select form-control-personalizado" id="week_day" name="week_day" required>
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

                        <div class="form-group mb-4">
                            <label for="hora_inicio" class="fw-bold mb-2">Hora inicio</label>
                            <input type="time" class="form-control form-control-personalizado " id="hora_inicio" name="hora_inicio" required>
                        </div>

                        <div class="form_group mb-4">
                            <label for="hora_fin" class="fw-bold mb-2">Hora fin</label>
                            <input type="time" class="form-control form-control-personalizado" id="hora_fin" name="hora_fin" required>
                        </div>

                        <div class="form_group mb-4">
                            <label for="estado" class="fw-bold mb-2">Estado</label>
                            <select class="form-select form-control-personalizado" id="estado" name="estado" required>
                                <option value="Disponible">Disponible</option>
                                <option value="Reservado">Reservado</option>
                                <option value="No_Trabaja">No_Trabaja</option>
                            </select>
                        </div>
                     
                        <td class="text-center">                   

                            <div class="card-footer bg-light d-flex justify-content-center align-items-center py-3 gap-2">
                                 <a href="<?= base_url('profesor/HorarioLeer') ?>" 
                                 class="btn btn-danger btn-redondeado btn-sm shadow text-decoration-none">
                                    Cancelar
                                </a>    

                                <button type="submit" class="btn btn-success btn-redondeado btn-sm shadow">
                                    Guardar
                                </button>
                                
                            </div>              
                        </td>

                    </form>                  
                </div>                   
            </div>
        </div>
    </div>   

 <?= $footer ?>
