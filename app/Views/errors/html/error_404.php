<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>404 - Página no encontrada | UCOT</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7fa;
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .error-container {
            text-align: center;
            max-width: 500px;
            padding: 50px 40px;
            background: white;
            border-radius: 30px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.05);
        }
        .logo-ucot {
            width: 150px; /* Ajusta el tamaño según tu logo */
            height: auto;
            margin-bottom: 25px;
        }
        h1 {
            font-weight: 800;
            color: #1e1e2f;
            font-size: 100px;
            margin: 0;
            line-height: 1;
        }
        h3 {
            font-weight: 700;
            color: #2e3748;
            margin-top: 10px;
        }
        p {
            color: #858796;
            font-size: 1.1rem;
            margin-bottom: 35px;
        }
        .btn-ucot {
            background-color: #3b82f6; /* El azul de tu layout.css */
            color: white;
            border-radius: 50px;
            padding: 14px 35px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            border: none;
        }
        .btn-ucot:hover {
            background-color: #2563eb;
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(59, 130, 246, 0.3);
            color: white;
        }
    </style>
</head>
<body>

<div class="error-container">
    <img src="<?= base_url('assets/images/UCOT-Original.svg') ?>" alt="UCOT Logo" class="logo-ucot">
    <h1>404</h1>
    <h3>¡Ups! Te perdiste</h3>
    <p>La página que intentas visitar no existe o el enlace ha expirado.</p>
    
    <a href="<?= base_url() ?>" class="btn-ucot">
        <i class="fas fa-home me-2"></i> Volver al Inicio
    </a>
</div>

</body>
</html>