<?php $rol = session()->get('rol'); ?>

<aside class="sidebar-ucot" id="sidebar">
        
         <button class="toggle-btn" id="toggleSidebar">
            <i class="fas fa-chevron-left"></i>
        </button>

    <nav class="sidebar-main-nav">
        <div class="nav-section-label">Menú Principal</div>
        <ul class="nav-list">

            <?php if ($rol == 'Estudiante'): ?>
                <li>
                    <a href="<?= base_url('alumno/inicio_alumno') ?>" class="nav-item-link <?= (uri_string() == 'alumno/inicio_alumno') ? 'active' : '' ?>">
                        <i class="fas fa-home"></i>
                        <span>Inicio</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('alumno/calendario_alumno') ?>" class="nav-item-link <?= (uri_string() == 'alumno/calendario_alumno') ? 'active' : '' ?>">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Calendario</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('alumno/mis_citas') ?>" class="nav-item-link <?= (uri_string() == 'alumno/mis_citas') ? 'active' : '' ?>">
                        <i class="fas fa-clock"></i>
                        <span>Agendar Citas</span>
                    </a>
                </li>

            <?php elseif ($rol == 'Profesor'): ?>
                <li>
                    <a href="<?= base_url('profesor/dashboard') ?>" class="nav-item-link <?= (uri_string() == 'profesor/dashboard') ? 'active' : '' ?>">
                        <i class="fas fa-chart-pie"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('profesor/calendario_profesor') ?>" class="nav-item-link <?= (uri_string() == 'profesor/calendario_profesor') ? 'active' : '' ?>">
                        <i class="fas fa-calendar-check"></i>
                        <span>Calendario</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('profesor/HorarioLeer') ?>" class="nav-item-link <?= (uri_string() == 'profesor/HorarioLeer') ? 'active' : '' ?>">
                        <i class="fas fa-calendar-check"></i>
                        <span>Horarios</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('profesor/citas') ?>" class="nav-item-link <?= (uri_string() == 'profesor/citas') ? 'active' : '' ?>">
                        <i class="fas fa-user-friends"></i>
                        <span>Citas</span>
                    </a>
                </li>
                 <li>
                    <a href="<?= base_url('profesor/opiniones') ?>" class="nav-item-link <?= (uri_string() == 'profesor/opiniones') ? 'active' : '' ?>">
                        <i class="fas fa-user-secret"></i>
                        <span>Opiniones</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>

    <div class="sidebar-footer">
        <div class="profile-dropdown">
            <button class="profile-btn" id="profileBtn">
                <i class="fas fa-user-circle"></i>
                <div class="user-info-text">
                    <span class="user-name">Usuario</span>
                    <span class="user-role-label"><?= $rol ?></span>
                </div>
                <i class="fas fa-chevron-up arrow-icon"></i>
            </button>
            
            <div class="dropdown-content" id="profileMenu">
                <a href="<?= base_url('configuracion') ?>" class="dropdown-item">
                    <i class="fas fa-cog"></i> <span>Gestionar Cuenta</span>
                </a>
                <a href="<?= base_url('salir') ?>" class="dropdown-item logout">
                    <i class="fas fa-sign-out-alt"></i> <span>Cerrar Sesión</span>
                </a>
            </div>
        </div>
    </div>
</aside>