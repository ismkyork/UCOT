// ==========================================
// 1. CONFIGURACIÓN
// ==========================================
const materiasGlobales = (typeof window.listaMaterias !== 'undefined') ? window.listaMaterias : [];
const sistemasGlobales = (typeof window.listaSistemas !== 'undefined') ? window.listaSistemas : [];

let fechaReferencia = new Date(); 
fechaReferencia.setHours(0,0,0,0);
let lunesSemanaActual = obtenerLunes(fechaReferencia);

const hoyReal = new Date();
const mesRestringido = hoyReal.getMonth(); 
const anioRestringido = hoyReal.getFullYear();

document.addEventListener('DOMContentLoaded', function() {
    renderizarHeaders();
    renderizarCeldas(); 
    actualizarEstadoBotones();
    if(typeof window.idProfesorSeleccionado !== 'undefined' && window.idProfesorSeleccionado) {
        cargarHorariosProfesor();
    }
    setInterval(() => { actualizarEstadoCeldas(); }, 60000);
});

// ==========================================
// 2. CARGA Y PINTADO (LÓGICA CORE)
// ==========================================
window.cargarHorariosProfesor = function() {
    let idProfesor = window.idProfesorSeleccionado;
    if(!idProfesor) return;

    limpiarVisualmente();

    fetch(baseURL + `/alumno/obtener_horarios_profesor_api?id_profesor=${idProfesor}`)
    .then(res => res.json())
    .then(data => {
        data.forEach(horario => {
            pintarBloque(horario);
        });
    })
    .catch(err => console.error(err));
}

function pintarBloque(horario) {
    let inicioCorto = horario.hora_inicio.substring(0, 5); 
    let fechaBloque = new Date(horario.fecha + 'T00:00:00');
    let diffDays = Math.round((fechaBloque - new Date(lunesSemanaActual)) / (1000 * 60 * 60 * 24));

    if (diffDays >= 0 && diffDays <= 6) {
        let celda = document.getElementById(`celda-${diffDays}-${inicioCorto.replace(':','')}`);
        
        if (celda && !celda.classList.contains('celda-pasada')) {
            // Guardamos datos
            celda.dataset.idHorario = horario.id_horario;
            celda.dataset.fecha = horario.fecha;
            celda.dataset.hora = inicioCorto;
            celda.dataset.materiaFija = horario.nombre_materia || ''; 
            celda.dataset.sistemaFijo = horario.nombre_sistema || ''; 
            
            // Textos informativos
            let txtMateria = horario.nombre_materia || 'Tema Libre';
            let txtSistema = horario.nombre_sistema || 'A convenir';
            let cuposTxt = `${horario.cupos_disponibles}/${horario.cupos_totales}`;
            
            celda.dataset.infoMateria = txtMateria;
            celda.dataset.infoSistema = txtSistema;

            let estilo = '';
            let contenido = '';
            let estadoLogico = ''; 
            let clickeable = false;

            // =========================================================
            // REGLAS DE NEGOCIO VISUALES
            // =========================================================

            // 1. MI RESERVA (Prioridad Máxima)
            if (horario.tipo_visual === 'mi_confirmada' || horario.tipo_visual === 'mi_pendiente') {
                if (horario.tipo_visual === 'mi_confirmada') {
                    // AZUL CYAN UCOT (Confirmado)
                    estilo = 'background-color: #33C2D1; color: white; border: 1px solid rgba(0,0,0,0.1);';
                    contenido = `
                        <div style="font-size:0.7rem; font-weight:bold;"><i class="fas fa-check-circle me-1"></i>Reservado</div>
                        <div style="font-size:0.55rem;">Confirmado</div>`;
                    estadoLogico = 'info_confirmado';
                } else {
                    // AMARILLO (Pendiente Pago Manual)
                    estilo = 'background-color: #ffc107; color: #212529; border: 2px solid #212529;';
                    contenido = `
                        <div style="font-size:0.7rem; font-weight:bold;"><i class="fas fa-clock me-1"></i>Pendiente</div>
                        <div style="font-size:0.55rem;">Validando Pago</div>`;
                    estadoLogico = 'info_pendiente';
                }
                celda.classList.add('mi-reserva');
                clickeable = true; // Permite clic para ver info
            }

            // 2. DISPONIBLE (Verde Success)
            else if (horario.tipo_visual === 'disponible') {
                estilo = 'background-color: #198754; color: white; cursor: pointer;';
                contenido = `
                    <div style="font-size:0.6rem; font-weight:bold; width:100%; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; text-align:center;">
                        ${txtMateria}
                    </div>
                    <div class="badge bg-white text-success rounded-pill mt-1" style="font-size:0.6rem; padding: 1px 6px;">
                        <i class="fas fa-users me-1"></i>${cuposTxt}
                    </div>`;
                estadoLogico = 'reservar';
                clickeable = true;
                celda.classList.add('ocupado');
            }

            // 3. NO DISPONIBLE (Gris con candado)
            else {
                estilo = 'background-color: #e9ecef; color: #6c757d; cursor: default; opacity: 0.9; border: 1px dashed #ccc;';
                contenido = `
                    <div style="font-size:1.2rem; color:#adb5bd; margin-bottom:2px;"><i class="fas fa-lock"></i></div>
                    <div style="font-size:0.6rem; font-weight:bold;">No disponible</div>`;
                estadoLogico = 'bloqueado';
                clickeable = true; // Permitimos clic solo para decir "No disponible"
                celda.classList.add('bloqueado-ajeno');
            }

            // Render
            celda.dataset.estadoLogico = estadoLogico;
            celda.innerHTML = `
                <div class="materia-asignada shadow-sm" style="${estilo} width:96%; height:92%; display:flex; flex-direction:column; justify-content:center; align-items:center; border-radius:4px; padding:2px;">
                    ${contenido}
                </div>`;
            
            celda.onclick = clickeable ? function() { toggleDisponibilidad(this); } : null;
        }
    }
}

// =========================================================
// 3. INTERACCIÓN (SWEET ALERT)
// =========================================================
window.toggleDisponibilidad = function(elemento) {
    if (elemento.classList.contains('celda-pasada')) return; 

    let estado = elemento.dataset.estadoLogico;
    let hora = elemento.dataset.hora;
    
    // --- A. SOLO INFORMACIÓN (Mío o Bloqueado) ---
    if (estado === 'info_confirmado' || estado === 'info_pendiente' || estado === 'bloqueado') {
        let titulo, icono, msg;
        let materia = elemento.dataset.infoMateria;
        let sistema = elemento.dataset.infoSistema;

        if (estado === 'bloqueado') {
            titulo = 'No Disponible';
            icono = 'error';
            msg = 'Este horario ya no está disponible para reservar.';
        } else if (estado === 'info_confirmado') {
            titulo = 'Tu Reserva Confirmada';
            icono = 'success';
            msg = 'Ya tienes tu cupo asegurado.';
        } else {
            titulo = 'Pago Pendiente';
            icono = 'warning';
            msg = 'Estamos verificando tu pago.';
        }

        Swal.fire({
            title: titulo,
            html: `
                <div class="text-start bg-light p-3 rounded-3 border">
                    <div class="mb-1"><strong>Materia:</strong> ${materia}</div>
                    <div class="mb-1"><strong>Plataforma:</strong> ${sistema}</div>
                    <p class="mt-2 mb-0 small text-muted fst-italic">${msg}</p>
                </div>
            `,
            icon: icono,
            confirmButtonText: 'Cerrar',
            confirmButtonColor: '#6c757d'
        });
        return;
    }

    // --- B. FORMULARIO DE RESERVA (Solo si es Verde) ---
    if (estado === 'reservar') {
        let idHorario = elemento.dataset.idHorario;
        let matFija = elemento.dataset.materiaFija;
        let sisFijo = elemento.dataset.sistemaFijo;

        // Construir selects
        let htmlForm = '<div class="text-start">';

        // Materia
        htmlForm += '<label class="form-label small fw-bold text-muted mb-1">Materia</label>';
        if (matFija && matFija.trim() !== "") {
            htmlForm += `<input type="text" class="form-control bg-light mb-3" value="${matFija}" readonly disabled>`;
            htmlForm += `<input type="hidden" id="swal-materia" value="${matFija}">`;
        } else {
            let opts = '<option value="" selected disabled>-- Selecciona --</option>';
            if (materiasGlobales.length > 0) materiasGlobales.forEach(m => opts += `<option value="${m.nombre_materia || m}">${m.nombre_materia || m}</option>`);
            else opts += '<option value="Clase General">Clase General</option>';
            htmlForm += `<select id="swal-materia" class="form-select mb-3">${opts}</select>`;
        }

        // Plataforma
        htmlForm += '<label class="form-label small fw-bold text-muted mb-1">Plataforma</label>';
        if (sisFijo && sisFijo.trim() !== "") {
            htmlForm += `<input type="text" class="form-control bg-light" value="${sisFijo}" readonly disabled>`;
            htmlForm += `<input type="hidden" id="swal-sistema" value="${sisFijo}">`;
        } else {
            let optsS = '<option value="" selected disabled>-- Selecciona --</option>';
            if (sistemasGlobales.length > 0) sistemasGlobales.forEach(s => optsS += `<option value="${s.nombre || s}">${s.nombre || s}</option>`);
            else optsS += '<option value="Online">Online</option>';
            htmlForm += `<select id="swal-sistema" class="form-select">${optsS}</select>`;
        }
        htmlForm += '</div>';

        Swal.fire({
            title: `Reservar: ${hora}`,
            html: htmlForm,
            showCancelButton: true,
            confirmButtonText: 'Confirmar y Pagar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#33C2D1',
            preConfirm: () => {
                const mat = document.getElementById('swal-materia').value;
                const sis = document.getElementById('swal-sistema').value;
                if(!mat || !sis) return Swal.showValidationMessage('Completa todos los campos');
                return { id_horario: idHorario, materia: mat, sistema: sis };
            }
        }).then((res) => {
            if (res.isConfirmed) enviarReserva(res.value);
        });
    }
};

// ==========================================
// 4. ENVIAR RESERVA
// ==========================================
function enviarReserva(datos) {
    Swal.fire({ title: 'Procesando...', didOpen: () => Swal.showLoading() });
    fetch(baseURL + '/alumno/reservar_cita_api', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify(datos)
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            window.location.href = baseURL + '/alumno/pago_estatico/' + data.id_cita;
        } else {
            Swal.fire('Error', data.msg, 'error');
            cargarHorariosProfesor();
        }
    })
    .catch(() => Swal.fire('Error', 'Fallo de conexión', 'error'));
}

// --------------------------------------------------------
// 5. FUNCIONES BASE (Igual que antes)
// --------------------------------------------------------
function obtenerLunes(d) { let date = new Date(d); date.setHours(0,0,0,0); var day = date.getDay(), diff = date.getDate() - day + (day == 0 ? -6 : 1); return new Date(date.setDate(diff)); }
function cambiarSemana(dir) {
    let nueva = new Date(lunesSemanaActual); nueva.setDate(nueva.getDate() + (dir * 7));
    if (semanaPerteneceAMes(nueva, mesRestringido)) { lunesSemanaActual = nueva; recargarCalendario(); }
}
function irAHoy() {
    let hoy = new Date(); hoy.setHours(0,0,0,0);
    if (hoy.getMonth() === mesRestringido && hoy.getFullYear() === anioRestringido) { lunesSemanaActual = obtenerLunes(hoy); recargarCalendario(); }
}
function recargarCalendario() { renderizarHeaders(); limpiarVisualmente(); actualizarEstadoCeldas(); dibujarLineaTiempo(); actualizarEstadoBotones(); cargarHorariosProfesor(); }
function limpiarVisualmente() {
    document.querySelectorAll('.grid-celda').forEach(celda => {
        if(!celda.classList.contains('columna-hora')) {
            celda.innerHTML = ''; celda.onclick = null; celda.style.cursor = 'default';
            celda.classList.remove('ocupado', 'mi-reserva', 'bloqueado-ajeno');
        }
    });
}
function semanaPerteneceAMes(lunes, mesObj) { let domingo = new Date(lunes); domingo.setDate(domingo.getDate() + 6); return (lunes.getMonth() === mesObj && lunes.getFullYear() === anioRestringido) || (domingo.getMonth() === mesObj && domingo.getFullYear() === anioRestringido); }
function actualizarEstadoBotones() {
    let ant = new Date(lunesSemanaActual); ant.setDate(ant.getDate()-7); let sig = new Date(lunesSemanaActual); sig.setDate(sig.getDate()+7);
    let btnPrev = document.getElementById('btnPrev'); let btnNext = document.getElementById('btnNext');
    if(btnPrev) { btnPrev.disabled = !semanaPerteneceAMes(ant, mesRestringido); btnPrev.style.opacity = btnPrev.disabled ? '0.3' : '1'; }
    if(btnNext) { btnNext.disabled = !semanaPerteneceAMes(sig, mesRestringido); btnNext.style.opacity = btnNext.disabled ? '0.3' : '1'; }
}
function renderizarHeaders() {
    const contenedor = document.getElementById('contenedorGrid'); if(!contenedor) return;
    const dias = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];
    const meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
    let lbl = document.getElementById('lblMesAnio'); if(lbl) lbl.innerText = `${meses[lunesSemanaActual.getMonth()]} ${lunesSemanaActual.getFullYear()}`;
    if (!document.querySelector('.header-columna')) { contenedor.innerHTML = '<div class="header-columna">Hora</div>'; for (let i = 0; i < 7; i++) { let d = document.createElement('div'); d.className = 'header-columna'; d.id=`header-dia-${i}`; contenedor.appendChild(d); } }
    let temp = new Date(lunesSemanaActual); temp.setHours(0,0,0,0); let hoy = new Date(); hoy.setHours(0,0,0,0);
    for (let i = 0; i < 7; i++) {
        let div = document.getElementById(`header-dia-${i}`); div.className = (temp.getTime()===hoy.getTime()) ? 'header-columna es-hoy' : 'header-columna';
        div.innerHTML = `<div class="contenido-header"><span class="dia-nombre">${dias[i]}</span><span class="dia-numero">${temp.getDate()}</span></div>`;
        temp.setDate(temp.getDate() + 1);
    }
}
function renderizarCeldas() {
    const c = document.getElementById('contenedorGrid'); if (!c || c.children.length > 8) return;
    const dias = ['l', 'm', 'x', 'j', 'v', 's', 'd'];
    const bloques = [{i:'07:15',f:'08:00'},{i:'08:00',f:'08:45'},{i:'08:45',f:'09:30'},{i:'09:30',f:'10:15'},{i:'10:15',f:'11:00'},{i:'11:00',f:'11:45'},{i:'12:45',f:'13:30'},{i:'13:30',f:'14:15'},{i:'14:15',f:'15:00'},{i:'15:00',f:'15:45'},{i:'15:45',f:'16:30'},{i:'16:30',f:'17:15'},{i:'17:15',f:'18:00'},{i:'18:00',f:'18:45'},{i:'18:45',f:'19:30'},{i:'19:30',f:'20:15'},{i:'20:15',f:'21:00'},{i:'21:00',f:'21:45'},{i:'21:45',f:'22:30'}];
    bloques.forEach(b => {
        let ch=document.createElement('div'); ch.className='grid-celda columna-hora'; ch.innerHTML=`<span>${b.i}</span>`; c.appendChild(ch);
        dias.forEach((d,idx)=>{
            let cell=document.createElement('div'); cell.className='grid-celda';
            cell.id=`celda-${idx}-${b.i.replace(':','')}`; c.appendChild(cell);
        });
    });
}
function actualizarEstadoCeldas() {
    const ahora = new Date();
    document.querySelectorAll('.grid-celda').forEach(c => {
        if(c.id && c.id.startsWith('celda-')) {
            let parts = c.id.split('-');
            let d = new Date(lunesSemanaActual); d.setDate(d.getDate() + parseInt(parts[1])); d.setHours(0,0,0,0);
            let horaStr = parts[2]; 
            let h = parseInt(horaStr.substring(0,2)); let m = parseInt(horaStr.substring(2));
            d.setHours(h,m,0,0);
            if(d<=ahora){ c.classList.add('celda-pasada'); if(c.onclick) c.style.opacity='0.6'; } 
            else { c.classList.remove('celda-pasada'); if(c.onclick) c.style.opacity='1'; }
        }
    });
}
function dibujarLineaTiempo() {
    let old=document.querySelector('.linea-tiempo'); if(old) old.remove();
    let ahora=new Date(), ini=new Date(lunesSemanaActual); ini.setHours(0,0,0,0); let fin=new Date(ini); fin.setDate(fin.getDate()+6);
    let hoy=new Date(); hoy.setHours(0,0,0,0);
    if(hoy<ini || hoy>fin) return;
    let idx=(ahora.getDay()===0)?6:ahora.getDay()-1; let mins=ahora.getHours()*60+ahora.getMinutes();
    document.querySelectorAll(`[data-dia-index="${idx}"]`).forEach(c=>{
        let [h1,m1]=c.dataset.inicio.split(':').map(Number); let start=h1*60+m1;
        let [h2,m2]=c.dataset.fin.split(':').map(Number); let end=h2*60+m2;
        if(mins>=start && mins<end){ let l=document.createElement('div'); l.className='linea-tiempo'; l.style.top=((mins-start)/(end-start)*100)+'%'; c.appendChild(l); }
    });
}
function timeToMinutes(time) { let [h, m] = time.split(':').map(Number); return h * 60 + m; }