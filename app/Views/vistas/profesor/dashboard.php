<?=$header?>

<?=$menu?>   
            <?php if(session()->getFlashdata('bienvenida')): ?>
                <div class="alert alert-success alert-dismissible fade show container mt-3" role="alert">
                    <strong>¡Hola!</strong> <?= session()->getFlashdata('bienvenida') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            
<div class="row mb-4">
    <div class="col-12">
        <h2 style="font-weight: 700; color: #2e3748;">Panel de Control</h2>
        <p style="color: #858796;">Bienvenido de nuevo, Admin. Aquí está el resumen de hoy.</p>
    </div>
</div>

<div class="row mb-4">
        <div class="col-md-4">
            <div class="card-personalizada p-4 mb-3">
                <label class="label-titulo">Citas Hoy</label>
                <h3 class="mb-0" style="font-weight: 700;">12</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-personalizada p-4 mb-3">
                <label class="label-titulo">Pendientes</label>
                <h3 class="mb-0" style="font-weight: 700;">5</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-personalizada p-4 mb-3">
                <label class="label-titulo">Completadas</label>
                <h3 class="mb-0" style="font-weight: 700; color: #4e73df;">28</h3>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card-personalizada p-0">
                <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0" style="font-weight: 700;">Próximas Citas</h5>
                    <button class="btn-redondeado" style="width: auto; padding: 8px 20px !important;">
                        + Nueva Cita
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-personalizada mb-0">
                        <thead>
                            <tr>
                                <th>Paciente</th>
                                <th>Hora</th>
                                <th>Estado</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Juan Pérez</td>
                                <td>10:00 AM</td>
                                <td><span class="badge text-bg-success" style="border-radius:0;">Confirmada</span></td>
                                <td><a href="#" class="text-primary"><i class="fa-solid fa-eye"></i></a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
     </div>

<?=$opiniones?>
<?=$footer?>    