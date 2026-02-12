document.addEventListener('DOMContentLoaded', function() {
    // ---------------------------------------------------------
    // 1. VARIABLES Y REFERENCIAS
    // ---------------------------------------------------------
    const fechaInput = document.getElementById('fecha_cita');
    const checkboxes = document.querySelectorAll('input[name="bloque_horario[]"]');
    
    // Referencias para la lógica de grupos (NUEVO)
    const inputCupos = document.getElementById('cupos_totales');
    const selectSistema = document.getElementById('id_sistema');
    const avisoSistema = document.getElementById('aviso_sistema');

    // ---------------------------------------------------------
    // 2. CONFIGURACIÓN DE FECHAS (Tu código original)
    // ---------------------------------------------------------
    const hoy = new Date();
    const yyyy = hoy.getFullYear();
    const mm = String(hoy.getMonth() + 1).padStart(2, '0');
    const dd = String(hoy.getDate()).padStart(2, '0');
    const fechaHoyString = `${yyyy}-${mm}-${dd}`;
    
    // Calcular último día del mes
    const ultimoDiaMes = new Date(yyyy, hoy.getMonth() + 1, 0); 
    const ddMax = String(ultimoDiaMes.getDate()).padStart(2, '0');
    const fechaMaxString = `${yyyy}-${mm}-${ddMax}`;

    // Asignar restricciones
    if(fechaInput) {
        fechaInput.min = fechaHoyString;
        fechaInput.max = fechaMaxString;
    }

    // ---------------------------------------------------------
    // 3. VALIDACIÓN DE HORARIOS PASADOS (Tu código original)
    // ---------------------------------------------------------
    function validarHorarios() {
        if(!fechaInput) return;

        const fechaSeleccionada = fechaInput.value;
        const ahora = new Date();
        const horaActualMinutos = (ahora.getHours() * 60) + ahora.getMinutes();

        const esHoy = (fechaSeleccionada === fechaHoyString);

        checkboxes.forEach(function(chk) {
            const wrapper = chk.closest('.bloque-check-wrapper');
            const valorHorario = chk.value; 
            const horaInicio = valorHorario.split('-')[0];
            const [horas, minutos] = horaInicio.split(':').map(Number);
            
            const bloqueMinutos = (horas * 60) + minutos;

            if (esHoy && bloqueMinutos <= horaActualMinutos) {
                chk.disabled = true;
                chk.checked = false;
                if(wrapper) wrapper.classList.add('bloque-deshabilitado');
            } else {
                chk.disabled = false;
                if(wrapper) wrapper.classList.remove('bloque-deshabilitado');
            }
        });
    }

    // ---------------------------------------------------------
    // 4. VALIDACIÓN DE GRUPOS / SISTEMA (NUEVO)
    // ---------------------------------------------------------
    function validarRequisitosGrupales() {
        if (!inputCupos || !selectSistema) return;

        const cupos = parseInt(inputCupos.value) || 1;

        if (cupos > 1) {
            // Caso GRUPAL: Sistema es obligatorio
            selectSistema.required = true;
            selectSistema.classList.add('border-warning'); // Resaltar borde
            
            if(avisoSistema) {
                avisoSistema.classList.add('text-danger', 'fw-bold');
                avisoSistema.innerHTML = '<i class="fas fa-exclamation-circle"></i> Obligatorio para grupos';
            }
        } else {
            // Caso INDIVIDUAL: Sistema es opcional
            selectSistema.required = false;
            selectSistema.classList.remove('border-warning');
            
            if(avisoSistema) {
                avisoSistema.classList.remove('text-danger', 'fw-bold');
                avisoSistema.innerHTML = 'Opcional (A convenir)';
            }
        }
    }

    // ---------------------------------------------------------
    // 5. INICIALIZACIÓN DE EVENTOS
    // ---------------------------------------------------------
    
    // Eventos de Fecha
    if(fechaInput) {
        if(fechaInput.value) validarHorarios();
        fechaInput.addEventListener('change', validarHorarios);
    }

    // Eventos de Cupos (Para activar validación de sistema)
    if(inputCupos) {
        inputCupos.addEventListener('input', validarRequisitosGrupales);
        inputCupos.addEventListener('change', validarRequisitosGrupales);
    }

    // Ejecutar validación inicial por si el navegador guardó valores
    validarRequisitosGrupales();
});