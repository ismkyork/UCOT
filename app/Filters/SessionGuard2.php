<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class SessionGuard2 implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
      
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/')->with('msg', 'Debes iniciar sesión para acceder.');
        }

        if (session()->get('rol') !== 'Estudiante') {
           
            return redirect()->to('/')->with('msg', 'No tienes permisos de estudiante.');
        }
        

    
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // do somethings
    }
}