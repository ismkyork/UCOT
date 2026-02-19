<!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: linear-gradient(145deg, #1299f3, #2287e6); color: white; padding: 20px; border-radius: 10px 10px 0 0; }
            .content { background: #f9f9f9; padding: 20px; border: 1px solid #ddd; }
            .footer { background: #f1f1f1; padding: 15px; text-align: center; font-size: 12px; color: #777; border-radius: 0 0 10px 10px; }
            .label { font-weight: bold; color: #555; }
            .data { margin-bottom: 15px; padding-left: 15px; }
            hr { border: 0; border-top: 1px solid #eee; margin: 20px 0; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2 style='margin:0;'>💰 Solicitud de Retiro de Fondos</h2>
                <p style='margin:5px 0 0; opacity:0.9;'>Profesor: {$data['nombre']} {$data['apellido']}</p>
            </div>
            <div class='content'>
                <p style='color: #2256e6; font-weight: bold;'>📅 Fecha de solicitud: {$fecha}</p>
                <hr>
                
                <h3 style='color: #2c3e50;'>👤 DATOS PERSONALES</h3>
                <div class='data'>
                    <p><span class='label'>ID Profesor:</span> {$data['id_profesor']}</p>
                    <p><span class='label'>Correo registrado:</span> {$data['correo']}</p>
                    <p><span class='label'>Nombre completo:</span> {$data['nombre']} {$data['apellido']}</p>
                    <p><span class='label'>Cédula:</span> {$data['tipo_cedula']}{$data['cedula']}</p>
                    <p><span class='label'>Teléfono:</span> {$data['telefono']}</p>
                </div>
                
                <hr>
                
                <h3 style='color: #2c3e50;'>🏦 DATOS BANCARIOS</h3>
                <div class='data'>
                    <p><span class='label'>Banco:</span> {$data['banco']}</p>
                    <p><span class='label'>Número de cuenta:</span> {$data['cuenta']}</p>
                </div>
                
                <hr>
                
                <h3 style='color: #2c3e50;'>💬 COMENTARIOS</h3>
                <div class='data'>
                    <p>{$data['comentarios']}</p>
                </div>
            </div>
            <div class='footer'>
                <p>Este es un mensaje automático generado desde el sistema UCOT.</p>
                <p style='margin:0; font-size:11px;'>Por favor procesar esta solicitud en el sistema administrativo.</p>
            </div>
        </div>
    </body>
    </html>