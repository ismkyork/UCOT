<?=$header?> 
<?=$menu?>   

<h2>Citas Disponibles</h2>

        <!-- Mostrar errores -->
        <?php if(session('errors')): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach(session('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Mostrar éxito -->
        <?php if(session('success')): ?>
            <div class="alert alert-success">
                <?= esc(session('success')) ?>
            </div>
        <?php endif; ?>

        <form action="<?= site_url('alumno/store_citas') ?>" method="POST">
            <?= csrf_field() ?>
          
            <div class="table-responsive">
                <table class="table table-hover table-personalizada align-middle">
                    <thead class="text-white">
                        <tr>
                            <th style="width: 40%;">Fecha y Hora</th>
                            <th>Duración</th>
                            <th>Materia</th>
                            <th class="text-center">Seleccionar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($horarios as $modelHorario): ?>
                            <tr>
                                <td>
                                    <select name="fecha[<?= $modelHorario['id_horario']; ?>]" 
                                            class="form-select form-control-tabla fecha-horario w-75 d-inline-block" 
                                            data-dia="<?= $modelHorario['week_day']; ?>">
                                        <option value="">Selecciona fecha</option>
                                    </select>
                                    <span class="ms-2 fw-bold text-dark"><?= $modelHorario['hora_inicio']; ?></span>
                                </td>

                                <td class="text-muted">
                                    <?php
                                        $inicio = new DateTime($modelHorario['hora_inicio']);
                                        $fin    = new DateTime($modelHorario['hora_fin']);
                                        $duracion = $inicio->diff($fin);
                                        $totalMinutos = ($duracion->days * 24 * 60) + ($duracion->h * 60) + $duracion->i;
                                        echo '<span class="badge bg-light text-dark">' . $totalMinutos . ' min</span>';
                                    ?>
                                </td>

                                <td>
                                    <input type="text" 
                                        name="materias[<?= $modelHorario['id_horario']; ?>]" 
                                        class="form-control form-control-tabla" 
                                        placeholder="Ej: Matemáticas"
                                        value="">
                                </td>

                                <td class="text-center">
                                    <input type="checkbox" 
                                        name="horarios[]" 
                                        value="<?= $modelHorario['id_horario']; ?>" 
                                        class="form-check-input cita-checkbox border-success">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Campo oculto global para el alumno en sesión -->
            <input type="hidden" name="id_alumno" value="<?= session()->get('id_auth'); ?>">

            <button type="submit" class="btn btn-success btn-redondeado btn-lg shadow">
                <i class="fas fa-check me-2"></i> Reservar
            </button>
        </form>

        <!-- Script para generar solo las fechas válidas -->
        <script>
            function normalizarDia(dia) {
                return dia.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
            }

            document.querySelectorAll('.fecha-horario').forEach(select => {
                const diaPermitido = normalizarDia(select.dataset.dia);
                const diasSemana = ["domingo","lunes","martes","miercoles","jueves","viernes","sabado"];

                let hoy = new Date();
                for (let i = 0; i < 60; i++) {
                    let fecha = new Date();
                    fecha.setDate(hoy.getDate() + i);

                    let diaSeleccionado = diasSemana[fecha.getDay()];
                    if (diaSeleccionado === diaPermitido) {
                        let valor = fecha.toISOString().split('T')[0];
                        let opcion = document.createElement("option");
                        opcion.value = valor;
                        opcion.textContent = valor + " (" + diaSeleccionado + ")";
                        select.appendChild(opcion);
                    }
                }
            });
        </script>

        <!-- Script para poner la fila en verde cuando se selecciona -->
        <script>
            document.querySelectorAll('.cita-checkbox').forEach(chk => {
                chk.addEventListener('change', function() {
                    if (this.checked) {
                        this.closest('tr').classList.add('table-success');
                    } else {
                        this.closest('tr').classList.remove('table-success');
                    }
                });
            });
        </script>

<?=$footer?>
