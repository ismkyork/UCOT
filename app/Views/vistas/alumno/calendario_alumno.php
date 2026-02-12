<?= $this->extend('Template/main') ?>

<?= $this->section('content') ?>

<link rel="stylesheet" href="<?= base_url('assets/css/calendario.css') ?>">

<?php
    // Buscar nombre del profesor seleccionado para mostrarlo en el título
    $nombre_profesor_texto = "Selecciona un docente";
    if(isset($profesores) && isset($id_preseleccionado)) {
        foreach($profesores as $profe) {
            if($profe['id_profesor'] == $id_preseleccionado) {
                $nombre_profesor_texto = $profe['nombre'] . ' ' . $profe['apellido'];
                break;
            }
        }
    }
?>

<div class="container-fluid mt-4 mb-5">
    
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <a href="<?= base_url('alumno/elegir_profesor') ?>" class="text-decoration-none text-muted small mb-1 d-inline-block hover-opacity">
                <i class="fas fa-arrow-left me-1"></i> Volver a docentes
            </a>
            
            <div class="d-flex align-items-center flex-wrap gap-3">
                <h3 class="fw-bold mb-0" style="color: var(--ucot-negro);">Reservar Clases</h3>
                
                <div class="d-flex align-items-center bg-white rounded-pill px-3 py-1 border shadow-sm" style="height: 38px;">
                    <span class="text-muted small fw-bold me-2" style="font-size: 0.7rem; letter-spacing: 0.5px;">DOCENTE:</span>
                    <span class="fw-bold text-primary">
                        <i class="fas fa-chalkboard-teacher me-1"></i>
                        <?= esc($nombre_profesor_texto) ?>
                    </span>
                </div>

                <input type="hidden" id="selectProfesor" value="<?= $id_preseleccionado ?>">
            </div>
        </div>

        <div class="view-switcher shadow-sm bg-white p-1 rounded-pill border">
            <span class="btn-switch active py-2 px-3 d-inline-block rounded-pill shadow-sm" style="background: var(--ucot-cian); color: white;" title="Vista Calendario">
                <i class="far fa-calendar-alt"></i>
            </span>
            <a href="<?= base_url('alumno/mis_citas') ?>" class="btn-switch py-2 px-3 d-inline-block rounded-pill text-muted" title="Vista Lista">
                <i class="fas fa-list-ul"></i>
            </a>
        </div>
    </div>

    <div class="horario-card position-relative">
        
        <div class="nav-calendario">
            <div class="btn-group shadow-sm" style="border-radius: 20px;">
                <button id="btnPrev" class="btn btn-nav" onclick="cambiarSemana(-1)">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="btn btn-nav" onclick="irAHoy()">Hoy</button>
                <button id="btnNext" class="btn btn-nav" onclick="cambiarSemana(1)">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            
            <div class="fecha-titulo" id="lblMesAnio">Cargando...</div>
            
            <div style="width: 100px;"></div> 
        </div>

        <div id="contenedorGrid" class="grid-calendario"></div>
    </div>
</div>

<script>
    var baseURL = "<?= base_url() ?>";
    
    // Listas para los selects del SweetAlert (vienen del controlador)
    window.listaMaterias = <?= $materias_json ?? '[]' ?>;
    window.listaSistemas = <?= $sistemas_json ?? '[]' ?>;
    
    // ID del profesor para cargar sus horarios
    window.idProfesorSeleccionado = "<?= $id_preseleccionado ?? '' ?>";
</script>

<script src="<?= base_url('assets/js/calendario_alumno.js') ?>"></script>

<?= $this->endSection() ?>