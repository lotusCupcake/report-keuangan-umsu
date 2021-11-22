<?php

namespace App\Controllers;

use App\Models\UbahTanggalTahapModel;


class UbahTanggalTahap extends BaseController
{
    protected $UbahTanggalTahapModel;
    public function __construct()
    {
        $this->UbahTanggalTahapModel = new UbahTanggalTahapModel();
    }



    public function index()
    {
        $data = [
            'title' => "Ubah Tanggal Tahap",
            'appName' => "UMSU",
            'breadcrumb' => ['Home', 'Ubah Tanggal Tahap'],
            'tunggakan' => [],
            'validation' => \Config\Services::validation(),
        ];
        // dd($data);

        return view('pages/ubahTanggalTahap', $data);
    }

    public function proses()
    {
        if (!$this->validate([
            'tahap' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Pembayaran Tahap Harus Diisi !',
                ]
            ],
            'tahunAjar' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tahun Ajar Harus Diisi !',
                ]
            ],
            'tahapTanggalAwal' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tanggal Awal Harus Diisi !',
                ]
            ],
            'tahapTanggalAkhir' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tanggal Akhir Harus Diisi !',
                ]
            ],
        ])) {
            return redirect()->to('ubahTanggalTahap')->withInput();
        }
    }
}
