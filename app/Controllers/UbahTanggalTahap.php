<?php

namespace App\Controllers;

use App\Models\UbahTanggalTahapModel;


class UbahTanggalTahap extends BaseController
{
    protected $UbahTanggalTahapModel;
    protected $curl;

    public function __construct()
    {
        $this->UbahTanggalTahapModel = new UbahTanggalTahapModel();
        $this->curl = service('curlrequest');
    }



    public function index()
    {
        $data = [
            'title' => "Ubah Tanggal Tahap",
            'appName' => "UMSU",
            'breadcrumb' => ['Home', 'Ubah Tanggal Tahap'],
            'termYear' => null,
            'entryYear' => null,
            'paymentOrder' => null,
            'tunggakan' => [],
            'icon' => 'https://assets10.lottiefiles.com/packages/lf20_s6bvy00o.json',
            'listTermYear' => $this->getTermYear(),
            'validation' => \Config\Services::validation(),
        ];
        // dd($data);

        return view('pages/ubahTanggalTahap', $data);
    }

    public function getTermYear()
    {
        $response = $this->curl->request("GET", "https://api.umsu.ac.id/Laporankeu/getTermYear", [
            "headers" => [
                "Accept" => "application/json"
            ],

        ]);

        return json_decode($response->getBody())->data;
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
            'tahunAngkatan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tahun Angkatan Harus Diisi !',
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

            $term_year_id = $this->request->getPost('tahunAjar');
            $entry_year_id = $this->request->getPost('tahunAngkatan');
            $payment_order = $this->request->getPost('tahap');
            $startDate = $this->request->getPost('tahapTanggalAwal').' 00:00:00.000';
            $endDate = $this->request->getPost('tahapTanggalAkhir').' 23:59:00.000';
        
        $response = $this->curl->request("POST", "https://api.umsu.ac.id/Laporankeu/updTanggalTahap", [
            "headers" => [
                "Accept" => "application/json"
            ],
            "form_params" => [
                "entryYearId" => $entry_year_id,
                "termYearId" => $term_year_id,
                "tahap" => $payment_order,
                "startDate"=> $startDate,
                "endDate" => $endDate
            ]
        ]);

        $data = [
            'title' => "Ubah Tanggal Tahap",
            'appName' => "UMSU",
            'breadcrumb' => ['Home', 'Ubah Tanggal Tahap'],
            'termYear' => null,
            'paymentOrder' => null,
            'dataUbah' => json_decode($response->getBody())->data,
            'listTermYear' => $this->getTermYear(),
            'icon' => (json_decode($response->getBody())->status) ? 'https://assets1.lottiefiles.com/packages/lf20_y2hxPc.json': 'https://assets10.lottiefiles.com/packages/lf20_gO48yV.json',
            'validation' => \Config\Services::validation(),
        ];

        session()->setFlashdata('success', 'Berhasil Mengubah Tanggal Tahap !');
        return view('pages/ubahTanggalTahap', $data);
    }
}
