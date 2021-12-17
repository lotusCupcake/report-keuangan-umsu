<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $data = [
            'title' => "Home",
            'appName' => "UMSU",
            'breadcrumb' => ['Home', 'Dashboard'],
            'validation' => \Config\Services::validation(),
            'menu'=>'fikri kantui'
        ];
        return view('pages/home', $data);
    }

    public function fetchMenu()
    {
        # code...
    }

    public function fetchSubMenu()
    {
        # code...
    }
}
