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

    // Script para resaltar la fila seleccionada
    document.querySelectorAll('.cita-checkbox').forEach(chk => {
        chk.addEventListener('change', function() {
            if (this.checked) {
                this.closest('tr').classList.add('table-success');
            } else {
                this.closest('tr').classList.remove('table-success');
            }
        });
    });