<?= $this->extend('Template/main') ?>

<?= $this->section('content') ?>

<?php if(session()->getFlashdata('bienvenida')): ?>
    <div class="alert alert-success alert-dismissible fade show container mt-3 shadow-sm border-0" style="border-radius: 15px; border-left: 5px solid #28a745 !important;" role="alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-smile-wink me-3 fs-4 text-success"></i>
            <div>
                <strong>¡Hola de nuevo, Profesor!</strong><br>
                <?= session()->getFlashdata('bienvenida') ?>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- ==================== HEADER ==================== -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
    <div>
        <h2 style="font-weight: 700; color: #2e3748; letter-spacing: -0.5px;">
            <i class="fas fa-chalkboard-teacher me-2" style="color: var(--ucot-cian);"></i>Panel del Docente
        </h2>
        <p style="color: #858796;" class="mb-0">
            <i class="far fa-calendar-alt me-1"></i> <?= date('l, d F Y') ?> • 
            <span class="text-success"><i class="fas fa-circle me-1" style="font-size: 0.6rem;"></i>Activo</span>
        </p>
    </div>
    
    <div class="d-flex gap-2 mt-3 mt-md-0">
        <a href="<?= base_url('profesor/agg_horarios') ?>" class="btn btn-ucot-primary btn-redondeado px-4 py-2 shadow-sm">
            <i class="fas fa-plus-circle me-2"></i>Nuevo Horario
        </a>
        <button type="button"
            class="btn btn-warning btn-md rounded-pill px-5 py-3 shadow-lg d-flex align-items-center"
                data-bs-toggle="modal" data-bs-target="#modalRetiro">
            <i class="fas fa-hand-holding-usd me-2 fa-lg"></i>
            <span class="fw-bold">Retirar Fondos</span>
        </button>
    </div>
</div>

<!-- ==================== MÉTRICAS PRINCIPALES ==================== -->
<div class="row mb-4 g-3">
    <div class="col-xl-3 col-md-6">
        <div class="card-personalizada p-4 h-100 border-0 shadow-sm d-flex flex-column" style="border-radius: 20px; background: linear-gradient(145deg, #ffffff, #f8faff);">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <label class="label-titulo text-muted mb-1" style="font-size: 0.8rem;">CITAS DE HOY</label>
                    <h3 class="mb-0" style="font-weight: 800; font-size: 2.2rem; color: var(--ucot-cian);"><?= $total_hoy_confirmadas ?></h3>
                    <small class="text-muted">Clases confirmadas</small>
                </div>
                <div class="rounded-circle p-3" style="background-color: rgba(13, 202, 240, 0.1);">
                    <i class="fas fa-calendar-check fa-2x" style="color: var(--ucot-cian);"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card-personalizada p-4 h-100 border-0 shadow-sm d-flex flex-column" style="border-radius: 20px; background: linear-gradient(145deg, #ffffff, #fef6e6);">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <label class="label-titulo text-muted mb-1" style="font-size: 0.8rem;">PENDIENTES</label>
                    <h3 class="mb-0" style="font-weight: 800; font-size: 2.2rem; color: #f39c12;"><?= $total_pendientes ?></h3>
                    <small class="text-muted">Esperando pago</small>
                </div>
                <div class="rounded-circle p-3" style="background-color: rgba(243, 156, 18, 0.1);">
                    <i class="fas fa-hourglass-half fa-2x" style="color: #f39c12;"></i>
                </div>
            </div>
            <div class="mt-2">
                <a href="<?= base_url('profesor/citas?estado=pendiente') ?>" class="small text-decoration-none">Ver pendientes →</a>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card-personalizada p-4 h-100 border-0 shadow-sm d-flex flex-column" style="border-radius: 20px; background: linear-gradient(145deg, #ffffff, #e8f5e9);">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <label class="label-titulo text-muted mb-1" style="font-size: 0.8rem;">COMPLETADAS</label>
                    <h3 class="mb-0" style="font-weight: 800; font-size: 2.2rem; color: #27ae60;"><?= $total_completadas_mes ?></h3>
                    <small class="text-muted">Este mes</small>
                </div>
                <div class="rounded-circle p-3" style="background-color: rgba(39, 174, 96, 0.1);">
                    <i class="fas fa-check-circle fa-2x" style="color: #27ae60;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card-personalizada p-4 h-100 border-0 shadow-sm d-flex flex-column" style="border-radius: 20px; background: linear-gradient(145deg, #ffffff, #ede7f6);">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <label class="label-titulo text-muted mb-1" style="font-size: 0.8rem;">INGRESOS HOY</label>
                    <h3 class="mb-0" style="font-weight: 800; font-size: 2.2rem; color: #8e44ad;">
                        $<?= number_format($ingresos_hoy_usd, 2) ?>
                    </h3>
                    <small class="text-muted">~ <?= number_format($ingresos_hoy_bs, 2) ?> Bs</small>
                </div>
                <div class="rounded-circle p-3" style="background-color: rgba(142, 68, 173, 0.1);">
                    <i class="fas fa-dollar-sign fa-2x" style="color: #8e44ad;"></i>
                </div>
            </div>
            <div class="mt-2 small">
                <span class="text-muted">Comisión UCOT: $<?= number_format($ingresos_hoy_usd * 0.15, 2) ?></span>
            </div>
        </div>
    </div>
</div>

<!-- ==================== PRÓXIMAS CLASES + FEEDBACK ==================== -->
<div class="row g-4 mb-4">
    <!-- COLUMNA IZQUIERDA: PRÓXIMAS CLASES -->
    <div class="col-lg-7">
        <div class="card-personalizada p-0 border-0 shadow-sm h-100" style="border-radius: 20px; overflow: hidden;">
            <div class="p-4 border-bottom d-flex justify-content-between align-items-center" style="background: linear-gradient(145deg, #ffffff, #f9fcff);">
                <div>
                    <h5 class="mb-1" style="font-weight: 700;">
                        <i class="fas fa-rocket me-2" style="color: var(--ucot-cian);"></i>Próximas Clases Confirmadas
                    </h5>
                    <p class="text-muted small mb-0">Clases pagadas y listas para impartir</p>
                </div>
                <span class="badge bg-soft-primary text-primary px-3 py-2 rounded-pill">
                    <i class="fas fa-video me-1"></i> <?= count($proximas_citas) ?> programadas
                </span>
            </div>
            
            <div class="p-0">
                <?php if(empty($proximas_citas)): ?>
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="fas fa-calendar-day fa-4x text-muted" style="opacity: 0.3;"></i>
                        </div>
                        <h6 class="fw-bold text-muted">No hay clases programadas</h6>
                        <p class="small text-muted mb-3">Tus estudiantes aún no han reservado contigo.</p>
                        <a href="<?= base_url('profesor/agg_horarios') ?>" class="btn btn-sm btn-ucot-primary btn-redondeado px-4">
                            <i class="fas fa-plus-circle me-2"></i>Crear disponibilidad
                        </a>
                    </div>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach($proximas_citas as $index => $pc): ?>
                        <div class="list-group-item p-4 border-0 border-bottom" style="background-color: <?= $index % 2 == 0 ? 'white' : '#fcfdff' ?>;">
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-soft-info d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <i class="fas fa-user-graduate fa-lg" style="color: var(--ucot-cian);"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1"><?= esc($pc['nombre_estudiante'] . ' ' . $pc['apellido_estudiante']) ?></h6>
                                        <div class="d-flex align-items-center gap-3 small">
                                            <span><i class="far fa-clock me-1 text-muted"></i><?= date('d/m/Y', strtotime($pc['fecha_hora_inicio'])) ?> • <?= date('h:i A', strtotime($pc['fecha_hora_inicio'])) ?></span>
                                            <span><i class="fas fa-book me-1 text-muted"></i><?= esc($pc['materia']) ?></span>
                                            <?php if(!empty($pc['sistema'])): ?>
                                                <span><i class="fas fa-desktop me-1 text-muted"></i><?= esc($pc['sistema']) ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex gap-2 mt-2 mt-md-0">
                                    <span class="badge bg-success px-3 py-2 rounded-pill">
                                        <i class="fas fa-check-circle me-1"></i>CONFIRMADA
                                    </span>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="p-3 text-center border-top">
                        <a href="<?= base_url('profesor/citas') ?>" class="text-decoration-none small fw-bold">
                            Ver todas las citas <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- COLUMNA DERECHA: FEEDBACK RECIENTE -->
    <div class="col-lg-5">
        <div class="card-personalizada p-4 border-0 shadow-sm h-100" style="border-radius: 20px;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle p-2 me-3" style="background-color: rgba(13, 202, 240, 0.1);">
                        <i class="fas fa-star" style="color: #ffc107;"></i>
                    </div>
                    <h5 class="mb-0 fw-bold">Opiniones Recientes</h5>
                </div>
                <a href="<?= base_url('profesor/opiniones') ?>" class="small text-decoration-none">Ver todas</a>
            </div>
            
            <?php if(isset($feedback_reciente) && count($feedback_reciente) > 0): ?>
                <div class="list-group list-group-flush">
                    <?php foreach($feedback_reciente as $fb): ?>
                    <div class="list-group-item px-0 py-3 border-0 border-bottom">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="fw-bold mb-1 small"><?= esc($fb['nombre_estudiante'] . ' ' . $fb['apellido_estudiante']) ?></h6>
                                <div class="mb-1">
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star <?= $i <= $fb['puntuacion'] ? 'text-warning' : 'text-muted' ?>" style="font-size: 0.7rem;"></i>
                                    <?php endfor; ?>
                                </div>
                                <p class="small text-muted mb-0">"<?= esc(substr($fb['comentario'], 0, 80)) ?><?= strlen($fb['comentario']) > 80 ? '...' : '' ?>"</p>
                            </div>
                            <small class="text-muted"><?= date('d/m', strtotime($fb['fecha_evaluacion'])) ?></small>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-comment-dots fa-3x text-muted mb-3" style="opacity: 0.3;"></i>
                    <p class="small text-muted mb-0">Aún no tienes opiniones de tus alumnos.</p>
                    <p class="small text-muted">Cuando recibas tu primera calificación, aparecerá aquí.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

   <!-- ==================== MODAL DE SOLICITUD DE RETIRO ==================== -->
    <div class="modal fade" id="modalRetiro" tabindex="-1" aria-labelledby="modalRetiroLabel" aria-hidden="true" data-bs-backdrop="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 25px;">
                <div class="modal-header border-0 pt-4 px-4">
                    <div>
                        <h4 class="modal-title fw-bold" id="modalRetiroLabel" style="color: #2e3748;">
                            <i class="fas fa-hand-holding-usd me-2" style="color: #f39c12;"></i>Solicitar Retiro
                        </h4>
                        <p class="text-muted small mb-0">Los fondos se transferirán en 24-48 horas hábiles</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body px-4 py-3">
                    <form id="formSolicitudRetiro">
                        <div class="row g-4">
                            <!-- Nombre y Apellido -->
                            <div class="col-md-6">
                                <label class="form-label small fw-bold mb-1" style="color: #4a5568;">👤 Nombre</label>
                                <input type="text" name="nombre" class="form-control form-control-lg fs-6" 
                                    placeholder="Tu nombre" required value="<?= $profesor_actual['nombre_profesor'] ?? '' ?>"
                                    style="border: 2px solid #e2e8f0; border-radius: 14px; padding: 12px 16px; background-color: #fafbfc;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold mb-1" style="color: #4a5568;">👤 Apellido</label>
                                <input type="text" name="apellido" class="form-control form-control-lg fs-6" 
                                    placeholder="Tu apellido" required value="<?= $profesor_actual['apellido_profesor'] ?? '' ?>"
                                    style="border: 2px solid #e2e8f0; border-radius: 14px; padding: 12px 16px; background-color: #fafbfc;">
                            </div>
                            
                            <!-- Cédula con desplegable V- / E- -->
                            <div class="col-md-6">
                                <label class="form-label small fw-bold mb-1" style="color: #4a5568;">🆔 Cédula / Pasaporte</label>
                                <div class="input-group">
                                    <select name="tipo_cedula" class="form-select" required
                                            style="border: 2px solid #e2e8f0; border-radius: 14px 0 0 14px; background-color: #fafbfc; font-weight: 600; max-width: 85px; padding: 12px 8px;">
                                        <option value="V-">🇻🇪 V-</option>
                                        <option value="E-">🌎 E-</option>
                                        <option value="P-">🛂 P-</option>
                                        <option value="R-">🏢 R-</option>
                                        <option value="J-">🏛️ J-</option>
                                        <option value="G-">⚖️ G-</option>
                                    </select>
                                    <input type="text" name="cedula" class="form-control form-control-lg fs-6" 
                                        placeholder="12345678" required pattern="[0-9]+" title="Solo números" 
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                        style="border: 2px solid #e2e8f0; border-left: none; border-radius: 0 14px 14px 0; padding: 12px 16px; background-color: #fafbfc;">
                                </div>
                            </div>
                            
                            <!-- Teléfono - Solo números -->
                            <div class="col-md-6">
                                <label class="form-label small fw-bold mb-1" style="color: #4a5568;">📱 Teléfono de contacto</label>
                                <input type="tel" name="telefono" class="form-control form-control-lg fs-6" 
                                    placeholder="04121234567" required pattern="[0-9]{11}" title="11 dígitos numéricos" 
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')" maxlength="11"
                                    style="border: 2px solid #e2e8f0; border-radius: 14px; padding: 12px 16px; background-color: #fafbfc;">
                                <small class="text-muted d-block mt-1 ms-1">📌 11 dígitos sin espacios ni guiones</small>
                            </div>
                            
                            <!-- Banco -->
                            <div class="col-12">
                                <label class="form-label small fw-bold mb-1" style="color: #4a5568;">🏦 Banco (Venezuela)</label>
                                <select name="banco" class="form-select form-select-lg fs-6" required
                                        style="border: 2px solid #e2e8f0; border-radius: 14px; padding: 12px 16px; background-color: #fafbfc;">
                                    <option value="" selected disabled>🔽 Selecciona tu banco...</option>
                                    <option value="Banco de Venezuela (BDV)">🏦 Banco de Venezuela (BDV) - 0102</option>
                                    <option value="Banesco">🏦 Banesco - 0134</option>
                                    <option value="Mercantil">🏦 Mercantil Banco Universal - 0105</option>
                                    <option value="BBVA Provincial">🏦 BBVA Provincial - 0108</option>
                                    <option value="Banco Nacional de Crédito (BNC)">🏦 Banco Nacional de Crédito (BNC) - 0191</option>
                                    <option value="Bancamiga">🏦 Bancamiga - 0172</option>
                                    <option value="Banco Exterior">🏦 Banco Exterior - 0115</option>
                                    <option value="Bancaribe">🏦 Bancaribe - 0114</option>
                                    <option value="Banco Plaza">🏦 Banco Plaza - 0138</option>
                                    <option value="Banco Occidental de Descuento (BOD)">🏦 Banco Occidental de Descuento (BOD) - 0151</option>
                                    <option value="Banco Venezolano de Crédito (BVC)">🏦 Banco Venezolano de Crédito (BVC) - 0104</option>
                                    <option value="Banco Fondo Común (BFC)">🏦 Banco Fondo Común (BFC) - 0151</option>
                                    <option value="Banco del Tesoro">🏦 Banco del Tesoro - 0163</option>
                                    <option value="Banco Agrícola de Venezuela">🏦 Banco Agrícola de Venezuela - 0166</option>
                                    <option value="Banco Bicentenario">🏦 Banco Bicentenario del Pueblo - 0175</option>
                                    <option value="Banco de la Fuerza Armada (BANFANB)">🏦 Banco de la Fuerza Armada (BANFANB) - 0177</option>
                                    <option value="Banco Activo">🏦 Banco Activo - 0171</option>
                                    <option value="Banplus">🏦 Banplus - 0174</option>
                                    <option value="100% Banco">🏦 100% Banco - 0156</option>
                                    <option value="Mi Banco">🏦 Mi Banco - 0169</option>
                                    <option value="Banco Caroní">🏦 Banco Caroní - 0128</option>
                                    <option value="Banco del Sur">🏦 Del Sur Banco Universal - 0157</option>
                                    <option value="Sofitasa">🏦 Sofitasa - 0137</option>
                                    <option value="Bangente">🏦 Bangente - 0146</option>
                                    <option value="Bancrecer">🏦 Bancrecer - 0168</option>
                                    <option value="Banco Industrial de Venezuela">🏦 Banco Industrial de Venezuela</option>
                                    <option value="Italcambio">🏦 Italcambio</option>
                                    <option value="N58 Banco Digital">🏦 N58 Banco Digital</option>
                                    <option value="Otra institución financiera">🔄 Otra institución financiera (especificar en comentarios)</option>
                                </select>
                                <small class="text-muted d-block mt-1 ms-1">📌 Código de banco SUDEBAN incluido para referencia</small>
                            </div>
                            
                            <!-- Cuenta - Solo números (OPCIONAL) -->
                            <div class="col-12">
                                <label class="form-label small fw-bold mb-1" style="color: #4a5568;">💳 Número de cuenta <span class="fw-normal text-muted">(opcional)</span></label>
                                <input type="text" name="cuenta" class="form-control form-control-lg fs-6" 
                                    placeholder="010201234512345678" pattern="[0-9]+" title="Solo números" 
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                    style="border: 2px solid #e2e8f0; border-radius: 14px; padding: 12px 16px; background-color: #fafbfc;">
                                <small class="text-muted d-block mt-1 ms-1">📌 Si no lo tienes a mano, te contactaremos. Solo números.</small>
                            </div>
                            
                            <!-- Comentarios adicionales (OPCIONAL) -->
                            <div class="col-12">
                                <label class="form-label small fw-bold mb-1" style="color: #4a5568;">💬 Comentarios <span class="fw-normal text-muted">(opcional)</span></label>
                                <textarea name="comentarios" class="form-control fs-6" rows="3" 
                                        placeholder="Ej: Prefiero pago por Pago Móvil, especificar banco no listado, etc."
                                        style="border: 2px solid #e2e8f0; border-radius: 14px; padding: 12px 16px; background-color: #fafbfc;"></textarea>
                            </div>
                            
                            <!-- Campos ocultos -->
                            <input type="hidden" name="id_profesor" value="<?= $profesor_actual['id_profesor'] ?? '' ?>">
                            <input type="hidden" name="correo_profesor" value="<?= session()->get('correo') ?? '' ?>">
                            
                            <!-- Mensaje de respuesta AJAX -->
                            <div id="respuestaRetiro" class="col-12 mt-2"></div>
                        </div>
                    </form>
                </div>
                
                <div class="modal-footer border-0 px-4 pb-4 pt-2">
                    <button type="submit" form="formSolicitudRetiro" id="btnEnviarRetiro" class="btn btn-warning btn-lg rounded-pill px-5 py-3 shadow-sm fw-bold" 
                            style="background: linear-gradient(145deg, #f39c12, #e67e22); border: none; color: white; font-size: 1.1rem;">
                        <i class="fas fa-paper-plane me-2"></i>Enviar Solicitud
                    </button>
                </div>
            </div>
        </div>
    </div>
            <!--Script MODAL-->
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('formSolicitudRetiro');
            if (!form) return;

            form.addEventListener('submit', function(e) {
                e.preventDefault(); // 🔥 EVITA RECARGAR LA PÁGINA
                
                const btn = document.getElementById('btnEnviarRetiro');
                const respuesta = document.getElementById('respuestaRetiro');
                
                // Deshabilitar botón y mostrar estado
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Enviando...';
                respuesta.innerHTML = '<div class="alert alert-info py-2">⏳ Procesando solicitud...</div>';
                
                // Recoger datos del formulario
                const formData = new FormData(this);
                
                // Enviar vía fetch
                fetch('<?= base_url('profesor/enviar_solicitud_retiro') ?>', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest' // Marcar como AJAX
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        respuesta.innerHTML = '<div class="alert alert-success py-2">✅ ' + data.message + '</div>';
                        this.reset(); // Limpiar formulario
                        // Cerrar modal después de 2 segundos
                        setTimeout(() => {
                            $('#modalRetiro').modal('hide');
                            respuesta.innerHTML = '';
                        }, 2000);
                    } else {
                        respuesta.innerHTML = '<div class="alert alert-danger py-2">❌ ' + data.message + '</div>';
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Enviar Solicitud';
                    }
                })
                .catch(error => {
                    respuesta.innerHTML = '<div class="alert alert-danger py-2">❌ Error de conexión. Intenta de nuevo.</div>';
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Enviar Solicitud';
                    console.error(error);
                });
            });
        });
        </script>


<?= $this->endSection() ?>