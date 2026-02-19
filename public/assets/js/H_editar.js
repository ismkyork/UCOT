document.addEventListener('DOMContentLoaded', function() {
    // ---------------------------------------------------------
    // 1. VARIABLES Y REFERENCIAS
    // ---------------------------------------------------------
    const fechaInput = document.getElementById('fecha'); 
    const radios = document.querySelectorAll('input[name="bloque_horario"]');
    
    // Referencias para la validación de grupos y sistema
    const inputCupos = document.getElementById('cupos_totales');
    const selectSistema = document.getElementById('id_sistema');
    const avisoSistema = document.getElementById('aviso_sistema');

    // ---------------------------------------------------------
    // 2. CONFIGURACIÓN DE FECHAS (Límites min/max)
    // ---------------------------------------------------------
    const hoy = new Date();
    const yyyy = hoy.getFullYear();
    const mm = String(hoy.getMonth() + 1).padStart(2, '0');
    const dd = String(hoy.getDate()).padStart(2, '0');
    const fechaHoyString = `${yyyy}-${mm}-${dd}`;
    
    // Calcular último día del mes actual
    const ultimoDiaMes = new Date(yyyy, hoy.getMonth() + 1, 0);
    const ddMax = String(ultimoDiaMes.getDate()).padStart(2, '0');
    const fechaMaxString = `${yyyy}-${mm}-${ddMax}`;

    // Asignar restricciones al input date si existe
    if(fechaInput) {
        fechaInput.min = fechaHoyString;
        fechaInput.max = fechaMaxString;
    }

    // ---------------------------------------------------------
    // 3. INICIALIZACIÓN DE BLOQUEOS DEL SERVIDOR
    // ---------------------------------------------------------
    // Marcamos los radios que ya venían deshabilitados desde PHP (porque tienen alumnos inscritos)
    radios.forEach(r => {
        if (r.disabled) {
            r.setAttribute('data-bloqueado-original', 'true');
        }
    });

    // ---------------------------------------------------------
    // 4. ACTUALIZAR INPUTS OCULTOS
    // ---------------------------------------------------------
    function splitTime(bloque) {
        if (bloque && bloque.includes('-')) {
            var partes = bloque.split('-');
            if (partes.length === 2) {
                const hInicio = document.getElementById('hora_inicio');
                const hFin = document.getElementById('hora_fin');
                if(hInicio) hInicio.value = partes[0].trim();
                if(hFin) hFin.value = partes[1].trim();
            }
        }
    }

    radios.forEach(radio => {
        radio.addEventListener('change', function() {
            splitTime(this.value);
        });
    });

    // ---------------------------------------------------------
    // 5. VALIDACIÓN DE HORARIOS
    // ---------------------------------------------------------
    function validarHorarios() {
        if (!fechaInput) return;

        // Si la fecha entera está bloqueada (candado PHP), no hacemos nada
        if (fechaInput.disabled) return; 

        const fechaSeleccionada = fechaInput.value;
        const ahora = new Date();
        const horaActualMinutos = (ahora.getHours() * 60) + ahora.getMinutes();
        const esHoy = (fechaSeleccionada === fechaHoyString);

        radios.forEach(function(radio) {
            // Si estaba bloqueado originalmente por PHP (reservas), lo saltamos (sigue bloqueado)
            if (radio.getAttribute('data-bloqueado-original') === 'true') {
                return;
            }

            const wrapper = radio.closest('.bloque-check-wrapper');
            const valorHorario = radio.value; 
            const horaInicio = valorHorario.split('-')[0]; 
            const [horas, minutos] = horaInicio.split(':').map(Number);
            const bloqueMinutos = (horas * 60) + minutos;

            // Lógica de habilitar/deshabilitar dinámica
            if (esHoy && bloqueMinutos <= horaActualMinutos) {
                // ES HOY Y YA PASÓ LA HORA: BLOQUEAR
                radio.disabled = true;
                if (wrapper) wrapper.classList.add('bloque-deshabilitado');
                
                // Si estaba seleccionado, lo desmarcamos
                if (radio.checked) radio.checked = false;
            } else {
                // ES FUTURO (O ES HOY PERO TEMPRANO): HABILITAR
                radio.disabled = false;
                if (wrapper) wrapper.classList.remove('bloque-deshabilitado');
            }
        });
    }

    // ---------------------------------------------------------
    // 6. VALIDACIÓN DE GRUPOS
    // ---------------------------------------------------------
    function validarSistema() {
        if (!inputCupos || !selectSistema) return;
        
        // Si ya hay reservas (sistema bloqueado), no validamos visualmente
        if (selectSistema.disabled) return; 

        if (parseInt(inputCupos.value) > 1) {
            selectSistema.required = true;
            selectSistema.classList.add('border-warning');
            if(avisoSistema) {
                avisoSistema.classList.add('text-danger', 'fw-bold');
                avisoSistema.innerHTML = '<i class="fas fa-exclamation-circle"></i> Obligatorio para grupos';
            }
        } else {
            selectSistema.required = false;
            selectSistema.classList.remove('border-warning');
            if(avisoSistema) {
                avisoSistema.classList.remove('text-danger', 'fw-bold');
                avisoSistema.innerText = 'Opcional (A convenir)';
            }
        }
    }

    // ---------------------------------------------------------
    // 7. EJECUCIÓN
    // ---------------------------------------------------------
    
    // Ejecutar validaciones al cargar
    validarHorarios();
    if(fechaInput) {
        fechaInput.addEventListener('change', validarHorarios);
    }

    // Verificar selección inicial para inputs hidden
    var radioSeleccionado = document.querySelector('input[name="bloque_horario"]:checked');
    if (radioSeleccionado) {
        splitTime(radioSeleccionado.value);
    }

    // Eventos de cupos
    if(inputCupos) {
        validarSistema(); 
        inputCupos.addEventListener('input', validarSistema);
        inputCupos.addEventListener('change', validarSistema);
    }
});