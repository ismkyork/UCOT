 // Esta función separa "07:15-08:00" en dos valores para los inputs ocultos
    function splitTime(value) {
        if(value.includes('-')) {
            const parts = value.split('-');
            document.getElementById('hora_inicio').value = parts[0];
            document.getElementById('hora_fin').value = parts[1];
        }
    }

    // Al cargar, si ya hay algo marcado (que lo habrá), ejecutamos la función por si acaso
    window.onload = function() {
        const selected = document.querySelector('input[name="bloque_horario_combo"]:checked');
        if(selected) splitTime(selected.value);
    };