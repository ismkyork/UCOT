<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string {
        $info['footer']=view('Template/footer');
        $info['header']=view('Template/header');
        $info['menu']=view('Template/menu');
        return view('vistas/inicio', $info); 
    }
}
