<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
</head>
<body style='margin: 0; padding: 0; background-color: #f8fafc;'>
    <div style='font-family: Arial, sans-serif; max-width: 500px; margin: 20px auto; background: white; border-radius: 20px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); overflow: hidden;'>

        <div style='background: #2563eb; padding: 25px 20px; text-align: center;'>
            <div style='font-size: 45px; margin-bottom: 5px;'>🔐🛡️</div>
            <h2 style='color: white; margin: 0; font-size: 22px;'>¿Olvidaste tu contraseña?</h2>
            <p style='color: rgba(255,255,255,0.9); margin: 5px 0 0; font-size: 14px;'>Tranquilo, te ayudamos</p>
        </div>
        
        <div style='padding: 30px 25px; background: white;'>
            <p style='font-size: 16px; color: #1e293b; margin-top: 0;'>
                <span style='font-size: 20px;'>👋</span> Hola, recibimos tu solicitud para restablecer la contraseña.
            </p>
            
            <div style='background: #f0f9ff; padding: 25px; border-radius: 16px; text-align: center; margin: 20px 0;'>
                <a href='<?= $url_reset ?>' 
                   style='background: #2563eb; color: white; padding: 14px 30px; border-radius: 50px; text-decoration: none; font-weight: bold; display: inline-block; font-size: 16px;'>
                    🔄 Crear nueva contraseña
                </a>
                
                <div style='background: #fff4e6; border-radius: 12px; padding: 12px; margin-top: 20px; font-size: 13px;'>
                    <span style='font-size: 18px;'>⏳</span> 
                    <span style='color: #9a3412; font-weight: bold;'>Vence en 1 hora</span> — por tu seguridad
                </div>
            </div>
            
            <div style='background: #f8fafc; border-left: 4px solid #2563eb; padding: 15px; margin: 20px 0; font-size: 13px;'>
                <p style='margin: 0 0 8px; font-weight: bold; color: #0a0f1c;'>🔒 Contraseña segura:</p>
                <p style='margin: 5px 0; color: #475569;'>✓ 8+ caracteres, mezcla letras, números y símbolos</p>
                <p style='margin: 5px 0; color: #475569;'>✗ Evita fechas, nombres o '123456'</p>
            </div>
            
            <div style='background: #ecfdf5; border-radius: 12px; padding: 15px; margin: 20px 0 10px;'>
                <p style='margin: 0; color: #065f46; font-style: italic;'>
                    <span style='font-size: 22px;'>🌿</span> 
                    <span>¿No solicitaste esto? Ignora el mensaje, tu cuenta sigue segura.</span>
                </p>
            </div>
            
            <div style='background: #f1f5f9; padding: 12px; border-radius: 10px; margin-top: 20px; font-size: 11px; word-break: break-all;'>
                <span style='color: #475569;'>🔗 Enlace directo si el botón no funciona:</span><br>
                <span style='color: #2563eb;'><?= $url_reset ?></span>
            </div>
        </div>
        
        <div style='background: #f8fafc; padding: 20px; text-align: center; border-top: 1px solid #e2e8f0;'>
            <p style='margin: 0; color: #64748b; font-size: 12px;'>UCOT · Ayuda: ucot2025@gmail.com</p>
            <p style='margin: 8px 0 0; color: #94a3b8; font-size: 11px;'>© 2025 · Seguridad y confianza</p>
        </div>
    </div>
</body>
</html>