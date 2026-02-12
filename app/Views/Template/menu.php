<?php $rol = session()->get('rol'); ?>

<aside class="sidebar-ucot" id="sidebar">
    <div class="sidebar-header">
        <button class="toggle-btn" id="toggleSidebar">
            <i class="fas fa-chevron-left"></i>
        </button>
        
        <span class="menu-label-header">Menú Principal</span>
    </div>
    <nav class="sidebar-main-nav">
        <ul class="nav-list">

            <?php if ($rol == 'Estudiante'): ?>
                <li>
                    <a href="<?= base_url('alumno/inicio_alumno') ?>" class="nav-item-link <?= (uri_string() == 'alumno/inicio_alumno') ? 'active' : '' ?>">
                        <i class="fas fa-home"></i>
                        <span>Inicio</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('alumno/mis_citas') ?>" class="nav-item-link <?= (uri_string() == 'alumno/mis_citas') ? 'active' : '' ?>">
                        <i class="fas fa-clock"></i>
                        <span>Agendar Citas</span>
                    </a>
                </li>

                <li>
                    <a href="<?= base_url('alumno/comprobantes_pagos') ?>" class="nav-item-link <?= (uri_string() == 'alumno/comprobantes_pagos') ? 'active' : '' ?>">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <span>Comprobantes de pagos</span>
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
                    <a href="<?= base_url('profesor/HorarioLeer') ?>" class="nav-item-link <?= (uri_string() == 'profesor/HorarioLeer') ? 'active' : '' ?>">
                        <i class="fas fa-calendar-day"></i>
                        <span>Gestión de Horarios</span>
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
                        <i class="fas fa-star"></i>
                        <span>Opiniones</span>
                    </a>
                </li>

            <?php elseif ($rol == 'admin'): ?>
                <li>
                    <a href="<?= base_url('admin/dashboard') ?>" class="nav-item-link <?= (uri_string() == 'admin/dashboard') ? 'active' : '' ?>">
                        <i class="fas fa-shield-alt"></i>
                        <span>Panel Control</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/citas') ?>" class="nav-item-link <?= (uri_string() == 'admin/citas') ? 'active' : '' ?>">
                        <i class="fas fa-copy"></i>
                        <span>Ver Citas Globales</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/profesores') ?>" class="nav-item-link <?= (uri_string() == 'admin/profesores') ? 'active' : '' ?>">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <span>Gestionar Profes</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/nuevo_profesor') ?>" class="nav-item-link <?= (uri_string() == 'admin/nuevo_profesor') ? 'active' : '' ?>">
                        <i class="fas fa-user-plus"></i>
                        <span>Registrar Profe</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/pagos') ?>" class="nav-item-link <?= (uri_string() == 'admin/pagos') ? 'active' : '' ?>">
                        <i class="fas fa-money-bill-wave"></i>
                        <span>Validar Pagos</span>
                    </a>
                </li>
            <?php endif; ?>
            
        </ul>
    </nav>

  
</aside>

