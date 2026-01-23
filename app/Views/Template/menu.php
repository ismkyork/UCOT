
        <?php $rol = session()->get('rol'); ?>

        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">


                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        
                        <?php if ($rol == 'Estudiante'): ?>
                        
                            <li class="nav-item"><a class="nav-link" href="calendario">Calendario</a></li>
                            <li class="nav-item"><a class="nav-link" href="factura">Factura</a></li>
                            <li class="nav-item"><a class="nav-link" href="mis_citas">Mis Citas</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= base_url('salir') ?>">Cerrar Sesion</a></li>


                        <?php elseif ($rol == 'Profesor'): ?>
                            <li class="nav-item"><a class="nav-link" href="HorarioLeer"> Horarios</a></li>
                            <li class="nav-item"><a class="nav-link" href="dashboard">Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link" href="citas">Citas</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= base_url('salir') ?>">Cerrar Sesion</a></li>

                        <?php endif; ?>

                    </ul>
                </div>
            </div>
         </nav>
    