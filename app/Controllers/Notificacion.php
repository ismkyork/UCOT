<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\NotificacionModel;

class Notificacion extends BaseController
{
    public function leer($id)
    {
        $notifModel = new NotificacionModel();
        $notificacion = $notifModel->find($id);
        
        // Obtenemos el ID del usuario actual para verificar propiedad
        $id_auth_actual = session()->get('id_auth');

        // Verificamos que la notificación exista y sea del usuario logueado
        if ($notificacion && $notificacion['id_destinatario'] == $id_auth_actual) {
            
            // 1. Marcar como leída
            $notifModel->update($id, ['leido' => 1]);

            // 2. Obtener el ROL actual para saber a dónde redirigir
            $rol = session()->get('rol'); // 'estudiante', 'alumno', 'profesor', 'admin'

            // 3. Redirección inteligente
            switch ($notificacion['tipo']) {
                case 'cita':
                    // Si es alumno, va a SUS citas. Si es profe, a SU agenda.
                    if ($rol == 'Estudiante' || $rol == 'Estudiante') {
                        return redirect()->to(base_url('alumno/mis_citas')); 
                    } elseif ($rol == 'Profesor') {
                        return redirect()->to(base_url('profesor/dashboard')); // O donde veas tus clases
                    } else {
                        return redirect()->to(base_url('admin/pagos'));
                    }
                    break;

                case 'feedback':
                    // El feedback generalmente lo recibe el profesor
                    if ($rol == 'Profesor') {
                        return redirect()->to(base_url('profesor/opiniones'));
                    }
                    // Si un alumno recibe feedback (ej: respuesta), va a su historial
                    return redirect()->to(base_url('alumno/inicio_alumno')); 
                    break;
                
                default:
                    // Si es 'sistema' o desconocido, al inicio según rol
                    if ($rol == 'Profesor') return redirect()->to(base_url('profesor/dashboard'));
                    if ($rol == 'admin')    return redirect()->to(base_url('admin/pagos'));
                    return redirect()->to(base_url('alumno/inicio_alumno'));
            }
        }

        // Si falla la validación, volver atrás
        return redirect()->back();
    }
}