<?php include(APPPATH . 'Views/Template/header.php'); ?>

<div class="bg-publico d-flex flex-column min-vh-100">
    
    <header class="header-ucot py-3 bg-white shadow-sm">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="logo-seccion d-flex align-items-center">
            <img src="<?= base_url('assets/images/UCOT-Original.svg') ?>" alt="UCOT" style="height: 65px; margin-right: 5px;">
                <span class="separador">|</span>
                <span class="modulo-texto text-uppercase">UCOT - U Class On Time</span>
            </div>
        </div>
    </header>

    <main class="flex-grow-1 d-flex align-items-center"> 
        <div class="container-fluid justify-content-center mb-3">
            <?= $this->renderSection('content_publico') ?>
        </div>
    </main>

    <?php include(APPPATH . 'Views/Template/footer.php'); ?>
</div>