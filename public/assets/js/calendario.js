// ==========================================
// 1. CONFIGURACIÓN Y VARIABLES
// ==========================================
let fechaReferencia = new Date(); 
fechaReferencia.setHours(0,0,0,0); 
let lunesSemanaActual = obtenerLunes(fechaReferencia);
let idsParaEliminar = []; 

const hoyReal = new Date();
const mesRestringido = hoyReal.getMonth(); 
const anioRestringido = hoyReal.getFullYear();

document.addEventListener('DOMContentLoaded', function() {
    inicializarCalendario();
    // Refresco automático cada minuto
    setInterval(() => {
        dibujarLineaTiempo();
        actualizarEstadoCeldas();
    }, 60000);
});

function inicializarCalendario() {
    renderizarHeaders();
    renderizarCeldas();
    dibujarLineaTiempo();
    actualizarEstadoBotones(); 
    cargarHorariosDesdeBD();
}

// ==========================================
// 2. CARGA DE DATOS (VISUALIZACIÓN)
// ==========================================
function cargarHorariosDesdeBD() {
    const url = 'obtener_horarios_api'; 
    fetch(url).then(res => res.json()).then(data => {
        data.forEach(horario => {
            let inicioCorto = horario.hora_inicio.substring(0, 5); 
            let fechaBloque = new Date(horario.fecha + 'T00:00:00');
            let diffDays = Math.round((fechaBloque - new Date(lunesSemanaActual)) / (1000 * 60 * 60 * 24));

            if (diffDays >= 0 && diffDays <= 6) {
                let celda = document.getElementById(`celda-${diffDays}-${inicioCorto.replace(':','')}`);
                
                if (celda && !celda.classList.contains('celda-pasada')) {
                    celda.classList.add('ocupado');
                    
                    // --- GUARDAR DATOS EN EL DOM ---
                    celda.dataset.idDb = horario.id_horario;
                    celda.dataset.ocupados = horario.ocupados || 0; 
                    celda.dataset.totales = horario.cupos_totales;
                    celda.dataset.disponibles = horario.cupos_disponibles;
                    celda.dataset.materia = horario.nombre_materia || 'Tema Libre';
                    celda.dataset.sistema = horario.nombre_sistema || 'A convenir';
                    celda.dataset.alumnos = horario.alumnos_inscritos || ''; 
                    celda.dataset.tipoVisual = horario.tipo_visual; // Viene del PHP

                    // --- ESTILOS VISUALES ---
                    let colorFondo = '#198754'; 
                    let icono = '';
                    let textoEstado = '';
                    let textoDetalle = ''; 
                    let borde = '1px solid rgba(255,255,255,0.2)';
                    let colorTexto = 'white';

                    // 1. LLENO Y CONFIRMADO (CYAN UCOT)
                    if (horario.tipo_visual === 'lleno_confirmado') {
                        colorFondo = '#33C2D1'; 
                        icono = '<i class="fas fa-check-circle me-1"></i>';
                        textoEstado = 'RESERVADO';
                        textoDetalle = horario.alumnos_inscritos;
                    } 
                    // 2. LLENO Y PENDIENTE PAGO (AMARILLO WARNING)
                    else if (horario.tipo_visual === 'lleno_pendiente') {
                        colorFondo = '#ffc107'; 
                        colorTexto = '#212529'; // Texto oscuro
                        borde = '2px solid #e0a800';
                        icono = '<i class="fas fa-exclamation-triangle me-1"></i>';
                        textoEstado = 'PENDIENTE';
                        textoDetalle = 'Pago por verificar';
                    }
                    // 3. PARCIAL (NARANJA - Clase Grupal)
                    else if (horario.tipo_visual === 'parcial') {
                        colorFondo = '#fd7e14'; 
                        icono = '<i class="fas fa-users me-1"></i>';
                        textoEstado = `${horario.cupos_disponibles} LIBRES`;
                        textoDetalle = 'En proceso';
                    }
                    // 4. LIBRE (VERDE)
                    else {
                        colorFondo = '#198754';
                        icono = '<i class="fas fa-check me-1"></i>';
                        textoEstado = 'DISPONIBLE';
                        textoDetalle = horario.nombre_materia || 'Tema Libre';
                    }

                    // Limitar largo del texto detalle
                    if (textoDetalle && textoDetalle.length > 20) {
                        textoDetalle = textoDetalle.substring(0, 18) + '...';
                    }

                    // Renderizar
                    celda.innerHTML = `
                        <div class="materia-asignada shadow-sm" style="background-color: ${colorFondo}; color: ${colorTexto}; border:${borde}; width:95%; height:92%; display:flex; flex-direction:column; justify-content:center; align-items:center; padding: 4px;">
                            <div style="font-size: 0.6rem; font-weight:bold; line-height:1.1; text-transform:uppercase; margin-bottom:2px;">
                                ${icono}${textoEstado}
                            </div>
                            <div style="font-size:0.55rem; width:100%; text-align:center; overflow:hidden; white-space:nowrap; border-top:1px solid rgba(0,0,0,0.1); padding-top:2px;">
                                ${textoDetalle}
                            </div>
                        </div>`;
                }
            }
        });
    });
}

// ==========================================
// 3. INTERACCIÓN (SWEET ALERTS)
// ==========================================
window.toggleDisponibilidad = function(elemento) {
    if (elemento.classList.contains('celda-pasada')) return; 

    let horaTexto = elemento.dataset.inicio;

    // --- A. ESCENARIO: QUITAR O VER DETALLES (Ya ocupado) ---
    if (elemento.classList.contains('ocupado')) {
        
        // Si es un bloque NUEVO que aún no se ha guardado en BD, permitir borrarlo rápido
        if (!elemento.dataset.idDb) {
            elemento.innerHTML = '';
            elemento.classList.remove('ocupado');
            delete elemento.dataset.tempCupos;
            delete elemento.dataset.tempMateria;
            delete elemento.dataset.tempSistema;
            return;
        }

        let tipo = elemento.dataset.tipoVisual;
        let idHorario = elemento.dataset.idDb;
        let materia = elemento.dataset.materia;
        let sistema = elemento.dataset.sistema;
        let alumnos = elemento.dataset.alumnos || 'Ninguno';
        let cuposInfo = `${elemento.dataset.ocupados} ocupados de ${elemento.dataset.totales}`;

        let htmlInfo = `
            <div class="text-start bg-light p-3 rounded-3 border mb-3">
                <div class="d-flex justify-content-between mb-1"><span class="text-muted small">Hora:</span> <strong>${horaTexto}</strong></div>
                <div class="d-flex justify-content-between mb-1"><span class="text-muted small">Materia:</span> <strong>${materia}</strong></div>
                <div class="d-flex justify-content-between mb-1"><span class="text-muted small">Plataforma:</span> <strong>${sistema}</strong></div>
            </div>
        `;

        if (tipo === 'lleno_confirmado' || tipo === 'lleno_pendiente') {
            Swal.fire({
                title: tipo === 'lleno_confirmado' ? 'Clase Reservada' : 'Pago Pendiente',
                html: `${htmlInfo}<div class="alert alert-info py-2 small text-start border-0"><strong>Estudiantes:</strong><br>${alumnos}</div>`,
                icon: 'info',
                confirmButtonText: 'Cerrar'
            });
        } else if (tipo === 'parcial') {
            Swal.fire({
                title: 'Clase en Curso',
                html: `${htmlInfo}<div class="alert alert-warning py-2 small text-start text-dark"><strong>${cuposInfo}</strong><br>Solo puedes modificar cupos totales.</div>`,
                showCancelButton: true,
                confirmButtonText: 'Editar Cupos'
            }).then((res) => { if(res.isConfirmed) window.location.href = `edit_horario/${idHorario}`; });
        } else {
            Swal.fire({
                title: 'Gestionar Horario',
                html: htmlInfo,
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonText: 'Eliminar',
                denyButtonText: 'Editar',
                customClass: { confirmButton: 'btn btn-danger mx-1', denyButton: 'btn btn-primary mx-1' }
            }).then((res) => {
                if (res.isConfirmed) {
                    if (idHorario) idsParaEliminar.push(idHorario);
                    elemento.innerHTML = '';
                    elemento.classList.remove('ocupado');
                    delete elemento.dataset.idDb;
                } else if (res.isDenied) {
                    window.location.href = `edit_horario/${idHorario}`;
                }
            });
        }
    } 
    // --- B. ESCENARIO: CREAR NUEVO (FORMULARIO CON LÍMITE DE 5 CUPOS) ---
    else {
        // Generar opciones de materia
        let optsMaterias = '<option value="">-- Tema Libre --</option>';
        if(typeof listaMaterias !== 'undefined') {
            listaMaterias.forEach(m => optsMaterias += `<option value="${m.id_materia}">${m.nombre_materia}</option>`);
        }

        // Generar opciones de plataforma
        let optsSistemas = '<option value="">-- A convenir --</option>';
        if(typeof listaSistemas !== 'undefined') {
            listaSistemas.forEach(s => optsSistemas += `<option value="${s.id}">${s.nombre}</option>`);
        }

        Swal.fire({
            title: `Nuevo Horario ${horaTexto}`,
            html: `
                <div class="text-start">
                    <label class="form-label small fw-bold text-muted">Cupos Totales (Máx. 5)</label>
                    <input type="number" id="swal-cupos" class="form-control mb-3 text-center fw-bold" value="1" min="1" max="5">
                    
                    <label class="form-label small fw-bold text-muted">Materia</label>
                    <select id="swal-materia" class="form-select mb-3">${optsMaterias}</select>
                    
                    <label class="form-label small fw-bold text-muted">Plataforma</label>
                    <select id="swal-sistema" class="form-select">${optsSistemas}</select>
                    
                    <small id="swal-aviso" class="text-danger d-none mt-1" style="font-size:0.75rem;">* Obligatorio para grupos (>1)</small>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Agregar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#198754',
            didOpen: () => {
                const inputC = document.getElementById('swal-cupos');
                const selectS = document.getElementById('swal-sistema');
                const aviso = document.getElementById('swal-aviso');
                
                // Validación visual en tiempo real y LÍMITE DE 5
                inputC.oninput = () => {
                    let val = parseInt(inputC.value);
                    
                    // --- AQUÍ ESTÁ EL LÍMITE QUE PEDISTE ---
                    if (val > 5) {
                        inputC.value = 5;
                        val = 5; 
                    }
                    // ---------------------------------------

                    if (val > 1) { 
                        selectS.classList.add('border-warning'); 
                        aviso.classList.remove('d-none'); 
                    } else { 
                        selectS.classList.remove('border-warning'); 
                        aviso.classList.add('d-none'); 
                    }
                };
            },
            preConfirm: () => {
                const cupos = document.getElementById('swal-cupos').value;
                const materia = document.getElementById('swal-materia').value;
                const sistema = document.getElementById('swal-sistema').value;
                
                if (parseInt(cupos) < 1 || isNaN(parseInt(cupos))) {
                    Swal.showValidationMessage('Mínimo 1 cupo'); return false;
                }
                // Validación extra por seguridad
                if (parseInt(cupos) > 5) {
                    Swal.showValidationMessage('Máximo 5 cupos permitidos'); return false;
                }
                
                if (parseInt(cupos) > 1 && !sistema) {
                    Swal.showValidationMessage('Para grupos debes elegir una Plataforma');
                    return false;
                }
                return { cupos, materia, sistema };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const d = result.value;
                
                // Guardar datos temporalmente en el dataset
                elemento.dataset.tempCupos = d.cupos;
                elemento.dataset.tempMateria = d.materia;
                elemento.dataset.tempSistema = d.sistema;

                // Buscar nombre de materia para la visualización
                let nomMat = "Tema Libre";
                if(d.materia && typeof listaMaterias !== 'undefined') {
                    let m = listaMaterias.find(x => x.id_materia == d.materia);
                    if(m) nomMat = m.nombre_materia;
                }

                elemento.innerHTML = `
                    <div class="materia-asignada shadow-sm" style="background-color: #198754; opacity: 0.8; border:1px dashed white; width:95%; height:92%; display:flex; flex-direction:column; justify-content:center; align-items:center; border-radius:4px; padding:4px;">
                        <div style="font-size:0.6rem; color:white; font-weight:bold;">NUEVO</div>
                        <small style="color:white; font-size:0.55rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; width:100%; text-align:center;">${nomMat}</small>
                        <div class="badge bg-white text-success rounded-pill mt-1" style="font-size: 0.55rem;">${d.cupos} Cupos</div>
                    </div>`;
                elemento.classList.add('ocupado');
            }
        });
    }
};

// ==========================================
// 4. GUARDAR CAMBIOS (API)
// ==========================================
window.guardarHorario = function() {
    let nuevosHorarios = [];
    document.querySelectorAll('.ocupado').forEach(el => {
        if (!el.dataset.idDb) {
            let index = el.dataset.diaIndex;
            let fechaBloque = new Date(lunesSemanaActual);
            fechaBloque.setDate(fechaBloque.getDate() + parseInt(index));
            
            let yyyy = fechaBloque.getFullYear();
            let mm = String(fechaBloque.getMonth() + 1).padStart(2, '0');
            let dd = String(fechaBloque.getDate()).padStart(2, '0');
            
            nuevosHorarios.push({
                fecha: `${yyyy}-${mm}-${dd}`,
                inicio: el.dataset.inicio,
                fin: el.dataset.fin,
                // Recoger datos del formulario
                cupos_totales: el.dataset.tempCupos || 1, 
                id_materia: el.dataset.tempMateria || null,
                id_sistema: el.dataset.tempSistema || null
            });
        }
    });

    if (nuevosHorarios.length === 0 && idsParaEliminar.length === 0) {
        Swal.fire({ title: 'Sin cambios', text: 'No has realizado modificaciones.', icon: 'info', confirmButtonText: 'Ok' });
        return;
    }

    Swal.fire({ title: 'Guardando...', didOpen: () => Swal.showLoading() });

    fetch('guardar_horarios_api', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ nuevos: nuevosHorarios, eliminar: idsParaEliminar })
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            Swal.fire('¡Guardado!', data.msg, 'success').then(() => {
                idsParaEliminar = [];
                limpiarVisualmente();
                cargarHorariosDesdeBD(); 
            });
        } else {
            Swal.fire('Error', data.msg, 'error');
        }
    })
    .catch(error => Swal.fire('Error', 'Error de conexión', 'error'));
}

// ==========================================
// 5. FUNCIONES AUXILIARES (NAVEGACIÓN Y GRID)
// ==========================================

function obtenerLunes(d) { let date = new Date(d); date.setHours(0,0,0,0); var day = date.getDay(), diff = date.getDate() - day + (day == 0 ? -6 : 1); return new Date(date.setDate(diff)); }

function cambiarSemana(dir) {
    if (idsParaEliminar.length > 0 || document.querySelectorAll('.ocupado:not([data-id-db])').length > 0) {
        if(!confirm('Cambios sin guardar. ¿Continuar?')) return;
    }
    let nueva = new Date(lunesSemanaActual); nueva.setDate(nueva.getDate() + (dir * 7));
    if (semanaPerteneceAMes(nueva, mesRestringido)) { lunesSemanaActual = nueva; resetearTodo(); }
    else { animarBoton(dir > 0 ? 'btnNext' : 'btnPrev'); }
}

function irAHoy() {
    let hoy = new Date(); hoy.setHours(0,0,0,0);
    if (hoy.getMonth() === mesRestringido && hoy.getFullYear() === anioRestringido) {
        if (idsParaEliminar.length > 0 || document.querySelectorAll('.ocupado:not([data-id-db])').length > 0) {
            if(!confirm('Cambios sin guardar. ¿Ir a hoy?')) return;
        }
        lunesSemanaActual = obtenerLunes(hoy); resetearTodo();
    }
}

function resetearTodo() {
    idsParaEliminar = [];
    renderizarHeaders();
    limpiarVisualmente();
    actualizarEstadoCeldas();
    dibujarLineaTiempo();
    actualizarEstadoBotones();
    cargarHorariosDesdeBD();
}

function limpiarVisualmente() {
    document.querySelectorAll('.celda-interactiva').forEach(celda => {
        celda.classList.remove('ocupado');
        delete celda.dataset.idDb; 
        delete celda.dataset.materia;
        delete celda.dataset.tipoVisual;
        // Limpiar temporales
        delete celda.dataset.tempCupos;
        delete celda.dataset.tempMateria;
        delete celda.dataset.tempSistema;
        celda.innerHTML = '';
    });
}

function semanaPerteneceAMes(lunes, mesObj) {
    let domingo = new Date(lunes); domingo.setDate(domingo.getDate() + 6);
    return (lunes.getMonth() === mesObj && lunes.getFullYear() === anioRestringido) || (domingo.getMonth() === mesObj && domingo.getFullYear() === anioRestringido);
}

function actualizarEstadoBotones() {
    let ant = new Date(lunesSemanaActual); ant.setDate(ant.getDate()-7);
    let sig = new Date(lunesSemanaActual); sig.setDate(sig.getDate()+7);
    let btnPrev = document.getElementById('btnPrev'); let btnNext = document.getElementById('btnNext');
    if(btnPrev) { btnPrev.disabled = !semanaPerteneceAMes(ant, mesRestringido); btnPrev.style.opacity = btnPrev.disabled ? '0.3' : '1'; }
    if(btnNext) { btnNext.disabled = !semanaPerteneceAMes(sig, mesRestringido); btnNext.style.opacity = btnNext.disabled ? '0.3' : '1'; }
}

function renderizarHeaders() {
    const contenedor = document.getElementById('contenedorGrid'); if(!contenedor) return;
    const dias = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];
    const meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
    let lbl = document.getElementById('lblMesAnio'); if(lbl) lbl.innerText = `${meses[lunesSemanaActual.getMonth()]} ${lunesSemanaActual.getFullYear()}`;
    if (!document.querySelector('.header-columna')) { contenedor.innerHTML = '<div class="header-columna">Hora</div>'; for (let i = 0; i < 7; i++) { let d = document.createElement('div'); d.className = 'header-columna header-dia-dinamico'; d.id=`header-dia-${i}`; contenedor.appendChild(d); } }
    let temp = new Date(lunesSemanaActual); temp.setHours(0,0,0,0); let hoy = new Date(); hoy.setHours(0,0,0,0);
    for (let i = 0; i < 7; i++) {
        let div = document.getElementById(`header-dia-${i}`); div.className = (temp.getTime()===hoy.getTime()) ? 'header-columna header-dia-dinamico es-hoy' : 'header-columna header-dia-dinamico';
        let color = (temp.getMonth() !== mesRestringido) ? '#ccc' : 'inherit';
        div.innerHTML = `<div class="contenido-header"><span class="dia-nombre" style="color:${div.classList.contains('es-hoy')?'white':color}">${dias[i]}</span><span class="dia-numero" style="color:${div.classList.contains('es-hoy')?'white':color}">${temp.getDate()}</span></div>`;
        temp.setDate(temp.getDate() + 1);
    }
}

function renderizarCeldas() {
    const c = document.getElementById('contenedorGrid'); 
    if (c.children.length > 8) return;
    
    const dias = ['l', 'm', 'x', 'j', 'v', 's', 'd'];
    const bloques = [{i:'07:15',f:'08:00'},{i:'08:00',f:'08:45'},{i:'08:45',f:'09:30'},{i:'09:30',f:'10:15'},{i:'10:15',f:'11:00'},{i:'11:00',f:'11:45'},{i:'12:45',f:'13:30'},{i:'13:30',f:'14:15'},{i:'14:15',f:'15:00'},{i:'15:00',f:'15:45'},{i:'15:45',f:'16:30'},{i:'16:30',f:'17:15'},{i:'17:15',f:'18:00'},{i:'18:00',f:'18:45'},{i:'18:45',f:'19:30'},{i:'19:30',f:'20:15'},{i:'20:15',f:'21:00'},{i:'21:00',f:'21:45'},{i:'21:45',f:'22:30'}];
    
    // Calculamos la hora actual UNA SOLA VEZ antes del bucle para optimizar
    const ahora = new Date();

    bloques.forEach(b => {
        if(b.tipo==='sep'){ 
            let d=document.createElement('div'); 
            d.className='separador-turno'; 
            d.innerHTML=`<i class="fas fa-sun me-2" style="color:var(--ucot-cian)"></i>${b.t}`; 
            c.appendChild(d); 
        }
        else {
            let ch=document.createElement('div'); 
            ch.className='grid-celda columna-hora'; 
            ch.innerHTML=`<span>${b.i}</span><span style="color:#adb5bd;font-size:0.7rem">${b.f}</span>`; 
            c.appendChild(ch);
            
            dias.forEach((d,idx)=>{
                let cell=document.createElement('div'); 
                
                // 1. LÓGICA DE BLOQUEO INSTANTÁNEO
                // Calculamos la fecha exacta de esta celda
                let fechaCelda = new Date(lunesSemanaActual);
                fechaCelda.setDate(fechaCelda.getDate() + idx);
                fechaCelda.setHours(0,0,0,0);
                
                let [hora, min] = b.i.split(':').map(Number);
                fechaCelda.setHours(hora, min, 0, 0);

                // Definimos las clases base
                let clases = 'grid-celda celda-interactiva';
                let titulo = 'Asignar';

                // Si ya pasó, le agregamos la clase gris DE UNA VEZ (antes de pintarla)
                if (fechaCelda <= ahora) {
                    clases += ' celda-pasada';
                    titulo = 'Pasado';
                }

                cell.className = clases;
                cell.title = titulo;
                
                // Resto de atributos
                cell.dataset.diaIndex=idx; 
                cell.dataset.inicio=b.i; 
                cell.dataset.fin=b.f; 
                cell.id=`celda-${idx}-${b.i.replace(':','')}`;
                cell.onclick=function(){toggleDisponibilidad(this);}; 
                
                c.appendChild(cell);
            });
        }
    });
    // Ya no necesitamos llamar a actualizarEstadoCeldas() aquí porque ya nacen pintadas
}

function actualizarEstadoCeldas() {
    const ahora = new Date();
    document.querySelectorAll('.celda-interactiva').forEach(c => {
        let d = new Date(lunesSemanaActual); d.setDate(d.getDate() + parseInt(c.dataset.diaIndex)); d.setHours(0,0,0,0);
        let [h,m] = c.dataset.inicio.split(':').map(Number); d.setHours(h,m,0,0);
        if(d<=ahora){ c.classList.add('celda-pasada'); c.title="Pasado"; if(c.classList.contains('ocupado')) c.style.opacity='0.6'; }
        else { c.classList.remove('celda-pasada'); c.title="Asignar"; c.style.opacity='1'; }
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

function animarBoton(id) {
    let btn = document.getElementById(id);
    if(btn) { btn.classList.add('animate__animated', 'animate__headShake'); setTimeout(() => btn.classList.remove('animate__animated'), 500); }
}