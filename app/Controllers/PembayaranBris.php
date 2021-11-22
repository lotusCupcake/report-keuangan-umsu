<?php

namespace App\Controllers;

use App\Models\PembayaranBrisModel;


class PembayaranBris extends BaseController
{
    protected $PembayaranBrisModel;
    public function __construct()
    {
        $this->PembayaranBrisModel = new PembayaranBrisModel();
    }



    public function index()
    {
        $data = [
            'title' => "Pembayaran Per Prodi (BRIS)",
            'appName' => "UMSU",
            'breadcrumb' => ['Home', 'Pembayaran Per Prodi (BRIS)'],
            'tunggakan' => [],
            'validation' => \Config\Services::validation(),
        ];
        // dd($data);

        return view('pages/pembayaranBris', $data);
    }
}
