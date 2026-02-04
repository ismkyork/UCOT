<!DOCTYPE html>
<html lang="es">
<head>
    <?php include(APPPATH . 'Views/Template/header.php'); ?>
</head>

<body class="main-wrapper-ucot"> 

    <?php include(APPPATH . 'Views/Template/menu.php'); ?>

    <main class="content-area-ucot" id="panel-contenido">
        
        <header class="d-flex justify-content-between align-items-center mb-4">
             <div class="logo-seccion d-flex align-items-center">
            <img src="<?= base_url('assets/images/UCOT-Original.svg') ?>" alt="UCOT" style="height: 40px; margin-right: 10px;">
                <span class="separador">|</span>
                <span class="modulo-texto">Gestión de Citas Académicas</span>
            </div>
            
            
        </header>

        <div class="container-fluid">
            <?= $this->renderSection('content') ?>
        </div>

        <?php include(APPPATH . 'Views/Template/footer.php'); ?>
    </main>

</body>
</html>