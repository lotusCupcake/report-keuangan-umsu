<?php

namespace App\Controllers;


class PembayaranTotal extends BaseController
{
    protected $curl;
    public function __construct()
    {
        $this->curl = service('curlrequest');
    }



    public function index()
    {
        $data = [
            'title' => "Total Pembayaran",
            'appName' => "UMSU",
            'breadcrumb' => ['Home', 'Laporan Pembayaran', 'Total Pembayaran'],
            'pembayaran' => [],
            'termYear' => null,
            'entryYear' => null,
            'paymentOrder' => null,
            'bank' => null,
            'listTermYear' => $this->getTermYear(),
            'listBank' => $this->getBank(),
            'prodi' => [],
            'icon' => 'https://assets2.lottiefiles.com/packages/lf20_yzoqyyqf.json',
            'validation' => \Config\Services::validation(),
        ];
        // dd($data);

        return view('pages/pembayaranTotal', $data);
    }

    public function getBank()
    {
        $response = $this->curl->request("GET", "https://api.umsu.ac.id/Laporankeu/getBank", [
            "headers" => [
                "Accept" => "application/json"
            ],

        ]);

        return json_decode($response->getBody())->data;
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

    public function prosesPembayaranTotal()
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
        ])) {
            return redirect()->to('pembayaranTotal')->withInput();
        }

        $term_year_id = $this->request->getPost('tahunAjar');
        $payment_order = $this->request->getPost('tahap');
        $bank = $this->request->getPost('bank');

        $response = $this->curl->request("POST", "https://api.umsu.ac.id/Laporankeu/getLaporanPembayaran", [
            "headers" => [
                "Accept" => "application/json"
            ],
            "form_params" => [
                "termYearId" => $term_year_id,
                // "termYearName" => $term_year_name,
                "tahap" => $payment_order,
                "bank" => $bank
            ]
        ]);

        $prodi = [];
        foreach (json_decode($response->getBody())->data as $k) {
            if (!in_array($k->PRODI, $prodi)) {
                array_push($prodi, $k->PRODI);
            }
        }

        $data = [
            'title' => "Total Pembayaran",
            'appName' => "UMSU",
            'breadcrumb' => ['Home', 'Laporan Pembayaran', 'Total Pembayaran'],
            'termYear' => $term_year_id,
            'paymentOrder' => $payment_order,
            'bank' => $bank,
            'pembayaran' => json_decode($response->getBody())->data,
            'listTermYear' => $this->getTermYear(),
            'listBank' => $this->getBank(),
            'prodi' => $prodi,
            'validation' => \Config\Services::validation(),
        ];

        session()->setFlashdata('success', 'Berhasil Memuat Data Pembayaran, Klik Export Untuk Download !');
        return view('pages/pembayaranTotal', $data);
    }
}
