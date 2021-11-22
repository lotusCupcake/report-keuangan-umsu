<?php

namespace App\Controllers;

use App\Models\PembayaranSemuaModel;


class PembayaranSemua extends BaseController
{
    protected $PembayaranSemuaModel;
    public function __construct()
    {
        $this->PembayaranSemuaModel = new PembayaranSemuaModel();
    }



    public function index()
    {
        $data = [
            'title' => "Pembayaran Per Prodi (Semua)",
            'appName' => "UMSU",
            'breadcrumb' => ['Home', 'Pembayaran Per Prodi (Semua)'],
            'tunggakan' => [],
            'validation' => \Config\Services::validation(),
        ];
        // dd($data);

        return view('pages/pembayaranSemua', $data);
    }
}
