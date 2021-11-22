<?php

namespace App\Controllers;

use App\Models\PembayaranModel;


class Pembayaran extends BaseController
{
    protected $PembayaranModel;
    protected $curl;
    public function __construct()
    {
        $this->PembayaranBniModel = new PembayaranModel();
        $this->curl = service('curlrequest');
    }



    public function index()
    {
        $data = [
            'title' => "Pembayaran",
            'appName' => "UMSU",
            'breadcrumb' => ['Home', 'Pembayaran'],
            'pembayaran' => [],
            'termYear' => null,
            'entryYear' => null,
            'paymentOrder' => null,
            'listTermYear' => $this->getTermYear(),
            'prodi' => [],
            'validation' => \Config\Services::validation(),
        ];
        // dd($data);

        return view('pages/pembayaran', $data);
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

    public function prosesPembayaran()
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
            'bank' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Pilih Bank Harus Diisi !',
                ]
            ],
        ])) {
            return redirect()->to('pembayaran')->withInput();
        }
        $term_year_id = $this->request->getPost('tahunAjar');
        $entry_year_id = $this->request->getPost('tahunAngkatan');
        $payment_order = $this->request->getPost('tahap');

        $response = $this->curl->request("POST", "https://api.umsu.ac.id/Laporankeu/updTanggalTahap", [
            "headers" => [
                "Accept" => "application/json"
            ],
            "form_params" => [
                "entryYearId" => $entry_year_id,
                "termYearId" => $term_year_id,
                "tahap" => $payment_order
            ]
        ]);
        $data = [
            'title' => "Pembayaran",
            'appName' => "UMSU",
            'breadcrumb' => ['Home', 'Pembayaran'],
            'termYear' => null,
            'paymentOrder' => null,
            'dataUbah' => json_decode($response->getBody())->data,
            'listTermYear' => $this->getTermYear(),
            'validation' => \Config\Services::validation(),
        ];

        dd($data);
    }
}
