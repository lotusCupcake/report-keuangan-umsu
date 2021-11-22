<?php

namespace App\Controllers;

use App\Models\PembayaranBsmModel;


class PembayaranBsm extends BaseController
{
    protected $PembayaranBsmModel;
    public function __construct()
    {
        $this->PembayaranBsmModel = new PembayaranBsmModel();
    }



    public function index()
    {
        $data = [
            'title' => "Pembayaran Per Prodi (BSM)",
            'appName' => "UMSU",
            'breadcrumb' => ['Home', 'Pembayaran Per Prodi (BSM)'],
            'tunggakan' => [],
            'validation' => \Config\Services::validation(),
        ];
        // dd($data);

        return view('pages/pembayaranBsm', $data);
    }
}
