<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UCOT - Ur Class On Time</title>
    <?= $this->include('Template/header', ['notificaciones' => $notificaciones ?? [], 'no_leidas' => $no_leidas ?? 0]) ?>
    <?= $this->renderSection('css') ?>
</head>

<body class="main-wrapper-ucot">

<?php 
    // Lógica automática para cualquier vista de UCOT
    $notifModel = new \App\Models\NotificacionModel();
    $id_auth = session()->get('id_auth');
    
    $notificaciones = [];
    $no_leidas = 0;

    if ($id_auth) {
        $notificaciones = $notifModel->misNotificaciones($id_auth);
        $no_leidas = $notifModel->contarNoLeidas($id_auth);
    }
?>

    <?= view('Template/menu') ?>

    <main class="content-area-ucot" id="panel-contenido">
        
        <header class="top-header-ucot">
            
            <div class="header-left">
                <img src="<?= base_url('assets/images/UCOT-Original.svg') ?>" alt="UCOT">
                <span class="titulo-sistema d-none d-sm-flex">
                    UCOT - Ur CLASS ON TIME
                </span>
            </div>

            <div class="header-right d-flex align-items-center">
                
                <div class="dropdown me-4"> <a class="text-dark position-relative d-flex align-items-center justify-content-center" href="#" id="notifDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="text-decoration: none; width: 40px; height: 40px;">
                        <i class="fas fa-bell fa-lg text-secondary"></i>
                        
                        <?php if(isset($no_leidas) && $no_leidas > 0): ?>
                            <span class="position-absolute badge rounded-pill bg-danger" 
                                  style="top: 0px; right: 0px; font-size: 0.65rem; padding: 0.25em 0.5em;">
                                <?= $no_leidas ?>
                                <span class="visually-hidden">mensajes no leídos</span>
                            </span>
                        <?php endif; ?>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="notifDropdown" style="width: 320px; max-height: 400px; overflow-y: auto;">
                        <li><h6 class="dropdown-header fw-bold text-uppercase text-secondary">Notificaciones</h6></li>
                        <li><hr class="dropdown-divider"></li>
                        
                        <?php if(isset($notificaciones) && !empty($notificaciones)): ?>
                            <?php foreach($notificaciones as $n): ?>
                                <li>
                                    <a class="dropdown-item py-2" href="<?= base_url('ver-notificacion/' . $n['id_notificacion']) ?>">
                                        <div class="d-flex align-items-start">
                                            <div class="me-3 mt-1">
                                                <?php if($n['tipo'] == 'cita'): ?>
                                                    <i class="fas fa-calendar-check text-primary fa-lg"></i>
                                                <?php elseif($n['tipo'] == 'feedback'): ?>
                                                    <i class="fas fa-star text-warning fa-lg"></i>
                                                <?php else: ?>
                                                    <i class="fas fa-info-circle text-info fa-lg"></i>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <strong class="d-block text-dark" style="font-size: 0.9rem;"><?= esc($n['titulo']) ?></strong>
                                                <p class="text-muted text-wrap mb-1" style="font-size: 0.8rem; line-height: 1.3;">
                                                    <?= esc($n['mensaje']) ?>
                                                </p>
                                                <small class="text-secondary fst-italic" style="font-size: 0.7rem;">
                                                    <?= date('d/m H:i', strtotime($n['created_at'])) ?>
                                                </small>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="text-center py-4 text-muted">
                                <i class="fas fa-bell-slash fa-2x mb-2 opacity-50"></i><br>
                                <small>No tienes notificaciones nuevas</small>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
                <div class="dropdown">
                    <button class="btn-perfil-header" type="button" id="headerProfileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="info-texto d-none d-md-flex flex-column text-end me-2">
                            <span class="nombre-user"><?= session('nombre') ?></span>
                            <span class="rol-user"><?= session('rol') ?></span>
                        </div>
                        <div class="avatar-wrapper">
                            <?php 
                                $foto_session = session('foto');
                                if($foto_session && $foto_session != 'default.png' && file_exists(FCPATH . 'uploads/perfiles/' . $foto_session)): 
                            ?>
                                <img src="<?= base_url('uploads/perfiles/' . $foto_session) ?>" alt="Perfil">
                            <?php else: ?>
                                <div class="avatar-initials">
                                    <?= substr(session('nombre') ?? 'U', 0, 1) ?>
                                </div>
                            <?php endif; ?>
                            <span class="status-indicator"></span>
                        </div>
                    </button>
                    
                    <ul class="dropdown-menu dropdown-menu-end profile-dropdown shadow-lg border-0" aria-labelledby="headerProfileDropdown">
                        <li class="dropdown-header-custom">
                            <h6 class="mb-0 text-dark fw-bold" style="font-size: 0.9rem;">Mi Cuenta</h6>
                            <small class="text-muted" style="font-size: 0.75rem;"><?= session('correo') ?? 'Usuario Verificado' ?></small>
                        </li>
                        <li><hr class="dropdown-divider opacity-50"></li>
                        
                        <li>
                            <a class="dropdown-item" href="<?= base_url('configuracion') ?>">
                                <div class="icon-circle-small bg-soft-primary">
                                    <i class="fas fa-cog"></i>
                                </div>
                                <span>Configuración</span>
                            </a>
                        </li>
                        
                        <li>
                            <a class="dropdown-item text-danger" href="<?= base_url('salir') ?>">
                                <div class="icon-circle-small bg-soft-danger">
                                    <i class="fas fa-sign-out-alt"></i>
                                </div>
                                <span class="fw-bold">Cerrar Sesión</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <div class="scroll-content">
            <div class="container-fluid pt-4"> 
                <?= $this->renderSection('content') ?>
            </div>
            <?= view('Template/footer') ?>
        </div>

    </main>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
