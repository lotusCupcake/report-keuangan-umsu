<?php

namespace App\Controllers;

use App\Models\PembayaranBniModel;


class PembayaranBni extends BaseController
{
    protected $PembayaranBniModel;
    public function __construct()
    {
        $this->PembayaranBniModel = new PembayaranBniModel();
    }



    public function index()
    {
        $data = [
            'title' => "Pembayaran Per Prodi (BNI)",
            'appName' => "UMSU",
            'breadcrumb' => ['Home', 'Pembayaran Per Prodi (BNI)'],
            'tunggakan' => [],
            'validation' => \Config\Services::validation(),
        ];
        // dd($data);

        return view('pages/pembayaranBni', $data);
    }
}
