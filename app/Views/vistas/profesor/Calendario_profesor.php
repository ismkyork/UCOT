<?= $this->extend('Template/main') ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0" style="color: var(--ucot-negro);">Gestión de Horarios</h3>
        <p class="text-muted small mb-0">Asigna disponibilidad (solo fechas y horas futuras).</p>
    </div>
    
    <div class="d-flex align-items-center gap-3">
        <div class="view-switcher shadow-sm">
            <span class="btn-switch active" title="Ver Calendario Visual">
                <i class="far fa-calendar-alt"></i>
            </span>
            
            <a href="<?= base_url('profesor/HorarioLeer') ?>" class="btn-switch" title="Ver Lista de Horarios">
                <i class="fas fa-list-ul"></i>
            </a>
        </div>

        <button class="btn btn-primary rounded-pill px-4 shadow-sm" style="background-color: var(--ucot-cian); border: none;" onclick="guardarHorario()">
            <i class="fas fa-save me-2"></i> Guardar
        </button>
    </div>
</div>

<div class="horario-card">
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
        
        <div class="fecha-titulo" id="lblMesAnio"></div>
        
        <div style="width: 100px;"></div> 
    </div>

    <div id="contenedorGrid" class="grid-calendario">
    </div>
</div>

<script>
    var baseURL = "<?= base_url() ?>";
    // Convertimos los arrays de PHP a objetos JS
    var listaSistemas = <?= json_encode($sistemas) ?>;
    var listaMaterias = <?= json_encode($materias) ?>;
</script>

<script src="<?= base_url('assets/js/calendario.js') ?>"></script>
<?= $this->endSection() ?>

