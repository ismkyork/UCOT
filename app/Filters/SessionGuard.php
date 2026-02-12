<?php 

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class SessionGuard implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // 1. Si no está logueado, fuera.
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/')->with('msg', 'Debes iniciar sesión para acceder.');
        }

        // 2. Obtener la URL actual para saber a dónde intenta entrar
        $uri = $request->getUri()->getPath();
        $rol = session()->get('rol');

        // 3. Lógica de protección por carpeta
        
        // Si intenta entrar a rutas de ADMIN
        if (strpos($uri, 'admin') !== false) {
            if ($rol !== 'admin') {
                return redirect()->to('/')->with('msg', 'Acceso denegado: Se requieren permisos de Superusuario.');
            }
        }

        // Si intenta entrar a rutas de PROFESOR
        if (strpos($uri, 'profesor') !== false) {
            if ($rol !== 'Profesor' && $rol !== 'admin') { 
                // Nota: dejamos que el admin también pueda entrar a ver cosas de profes si quiere
                return redirect()->to('/')->with('msg', 'No tienes permisos de profesor.');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No se necesita nada aquí
    }
}