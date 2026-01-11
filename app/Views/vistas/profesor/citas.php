<?=$header?> 

<?=$menu?>   

   <div class="card-body">
    <?php if (empty($citas)): ?>
        <div class="alert alert-info">No hay solicitudes pendientes.</div>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Estudiante</th>
                    <th>Materia</th>
                    <th>Fecha y Hora</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($citas as $cita): ?>
                    <tr>
                        <td><?= esc($cita['estudiante_email']) ?></td>
                        
                        <td><?= esc($cita['materia']) ?></td>
                        
                        <td><?= esc($cita['fecha_hora_inicio']) ?></td>

                        <td>
                            <form action="<?= base_url('profesor/citas/procesar') ?>" method="post">
                                
                                <input type="hidden" name="id_cita" value="<?= $cita['id_cita'] ?>">
                                
                                <button type="submit" name="accion" value="aprobar" class="btn btn-success btn-sm">Aprobar</button>
                                <button type="submit" name="accion" value="rechazar" class="btn btn-danger btn-sm">Rechazar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

 <?=$footer?>    