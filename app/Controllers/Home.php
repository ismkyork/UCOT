<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string {
        $info['footer']=view('Template/footer');
        $info['header']=view('Template/header');
        $info['login']=view('vistas/auth/login');

        return view('vistas/inicio', $info); 
    }
}
