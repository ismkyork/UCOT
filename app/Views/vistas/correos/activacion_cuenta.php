<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #1e293b;
            margin: 0;
            padding: 0;
            background-color: #f8fafc;
        }
        
        .container {
            max-width: 560px;
            margin: 30px auto;
            background: white;
            border-radius: 32px;
            box-shadow: 0 20px 40px -10px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(145deg, #0d6efd, #0b5ed7);
            padding: 36px 32px 30px;
            text-align: center;
        }
        
        .logo-icon {
            font-size: 48px;
            display: inline-block;
            background: rgba(255,255,255,0.15);
            width: 80px;
            height: 80px;
            line-height: 80px;
            border-radius: 30px;
            margin-bottom: 16px;
            backdrop-filter: blur(4px);
        }
        
        .header h1 {
            color: white;
            margin: 0;
            font-weight: 700;
            font-size: 28px;
            letter-spacing: -0.5px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .header p {
            color: rgba(255,255,255,0.9);
            margin: 8px 0 0;
            font-size: 16px;
            font-weight: 400;
        }
        
        .content {
            padding: 40px 32px;
            background: white;
        }
        
        .welcome-message {
            font-size: 18px;
            font-weight: 500;
            color: #0d6efd;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .greeting {
            font-size: 24px;
            font-weight: 700;
            color: #0a0f1c;
            margin-bottom: 20px;
            line-height: 1.3;
        }
        
        .highlight {
            background: linear-gradient(120deg, #fef9e7 0%, #fef9e7 40%, #fff 80%);
            padding: 24px;
            border-radius: 24px;
            margin: 24px 0;
            border-left: 5px solid #0d6efd;
        }
        
        .btn {
            display: inline-block;
            background: linear-gradient(145deg, #0d6efd, #0a58ca);
            color: white !important;
            font-weight: 600;
            padding: 16px 36px;
            border-radius: 50px;
            text-decoration: none;
            font-size: 17px;
            letter-spacing: 0.3px;
            margin: 16px 0 8px;
            box-shadow: 0 8px 20px rgba(13,110,253,0.25);
            transition: all 0.2s ease;
            border: 1px solid rgba(255,255,255,0.1);
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(13,110,253,0.35);
        }
        
        .features {
            display: flex;
            justify-content: space-between;
            margin: 40px 0 20px;
            padding-top: 20px;
            border-top: 2px solid #f1f5f9;
        }
        
        .feature-item {
            text-align: center;
            flex: 1;
        }
        
        .feature-emoji {
            font-size: 28px;
            margin-bottom: 8px;
            display: block;
        }
        
        .feature-text {
            font-size: 13px;
            font-weight: 600;
            color: #475569;
        }
        
        .footer {
            background: #f8fafc;
            padding: 32px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        
        .footer p {
            margin: 6px 0;
            color: #64748b;
            font-size: 14px;
        }
        
        .small-note {
            background: white;
            padding: 16px 20px;
            border-radius: 16px;
            font-size: 13px;
            color: #64748b;
            margin-top: 24px;
            border: 1px dashed #cbd5e1;
        }
        
        .emoji-big {
            font-size: 22px;
            vertical-align: middle;
            margin-right: 6px;
        }
        
        @media only screen and (max-width: 600px) {
            .container { margin: 16px; border-radius: 24px; }
            .content { padding: 28px 20px; }
            .features { flex-direction: column; gap: 16px; }
        }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <div class='logo-icon'>🎓✨</div>
            <h1>¡Bienvenido a UCOT!</h1>
            <p>Donde el conocimiento encuentra su rumbo</p>
        </div>
        
        <div class='content'>
            <div class='welcome-message'>
                <span class='emoji-big'>🤝</span> Nos alegra tenerte aquí
            </div>
            
            <div class='greeting'>
                Hola, <span style='color: #0d6efd; border-bottom: 3px solid #ffd166; padding-bottom: 2px;'>futuro profesional</span> ✨
            </div>
            
            <p style='font-size: 16px; color: #334155; margin-bottom: 24px;'>
                Estás a solo un clic de formar parte de la comunidad educativa más vibrante de Venezuela. Prepara tus conocimientos y conecta con estudiantes apasionados.
            </p>
            
            <div class='highlight'>
                <div style='display: flex; align-items: center; gap: 12px; margin-bottom: 16px;'>
                    <span style='font-size: 32px;'>🔐</span>
                    <span style='font-weight: 700; font-size: 18px; color: #0a0f1c;'>Activa tu cuenta en segundos</span>
                </div>
                
                <p style='margin-bottom: 20px; color: #334155;'>
                    Haz clic en el botón de abajo para verificar tu correo y comenzar tu viaje como parte de UCOT.
                </p>
                
                <div style='text-align: center;'>
                    <a href='<?= $url_activacion ?>' class='btn'>
                        ✅ ACTIVAR MI CUENTA AHORA
                    </a>
                    <p style='font-size: 13px; color: #5d6d7e; margin-top: 12px; letter-spacing: 0.3px;'>
                        ⚡ El enlace expirará en 24 horas por seguridad
                    </p>
                </div>
            </div>
            
            <div class='features'>
                <div class='feature-item'>
                    <span class='feature-emoji'>📚</span>
                    <span class='feature-text'>Clases personalizadas</span>
                </div>
                <div class='feature-item'>
                    <span class='feature-emoji'>💰</span>
                    <span class='feature-text'>Alta calidad y perfección</span>
                </div>
                <div class='feature-item'>
                    <span class='feature-emoji'>⏱️</span>
                    <span class='feature-text'>Horarios flexibles</span>
                </div>
            </div>
            
            <div style='background: #fef2f2; padding: 20px 24px; border-radius: 20px; margin: 20px 0 10px;'>
                <p style='margin: 0; font-style: italic; color: #4b5563;'>
                    <span style='font-size: 20px; color: #ef4444;'>❤️</span> 
                    “La educación es el arma más poderosa para cambiar el mundo. <span style='color: #0d6efd; font-weight: 600;'>UCOT</span> es tu plataforma para hacerlo realidad.”
                </p>
                <p style='margin: 16px 0 0; font-weight: 600; color: #1e293b;'>
                    — Equipo UCOT
                </p>
            </div>
            
            <div class='small-note'>
                <span style='font-size: 18px; margin-right: 8px;'>🛡️</span> 
                <strong>¿No solicitaste este registro?</strong> Si no creaste una cuenta en UCOT, simplemente ignora este correo. Tu correo está seguro con nosotros.
            </div>
        </div>
        
        <div class='footer'>
            <p style='font-weight: 600; color: #1e293b;'>UCOT · Un Cambio Oportuno en Ti</p>
            <p>📍 Caracas, Venezuela · 📧 ucot2025@gmail.com</p>
            <p style='margin-top: 16px; font-size: 12px; opacity: 0.7;'>
                © 2025 UCOT. Todos los derechos reservados.