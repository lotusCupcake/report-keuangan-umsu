<?php

namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class TunggakanPerMahasiswa extends BaseController
{
    protected $curl;
    public function __construct()
    {
        $this->curl = service('curlrequest');
    }



    public function index()
    {
        $data = [
            'title' => "Tunggakan Per Mahasiswa",
            'appName' => "UMSU",
            'breadcrumb' => ['Home', 'Laporan Tunggakan', 'Tunggakan Per Mahasiswa'],
            'tunggakan' => [],
            'filter' => null,
            'student' => null,
            'validation' => \Config\Services::validation(),
        ];
        // dd($data);

        return view('pages/tunggakanPerMahasiswa', $data);
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

    public function prosesTunggakanPerMahasiswa()
    {
        if (!$this->validate([
            'filter' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama / NPM Mahasiswa Harus Diisi !',
                ]
            ],
        ])) {
            return redirect()->to('tunggakanPerMahasiswa')->withInput();
        }

        // dd($_POST);
        $filter = $this->request->getPost('filter');

        $response = $this->curl->request("POST", "https://api.umsu.ac.id/Laporankeu/getBillStudent", [
            "headers" => [
                "Accept" => "application/json"
            ],
            "form_params" => [
                "filter" => $filter,
            ]
        ]);


        // dd(json_decode($response->getBody()));

        $data = [
            'title' => "Tunggakan Per Mahasiswa",
            'appName' => "UMSU FM",
            'breadcrumb' => ['Home', 'Laporan Tunggakan', 'Tunggakan Per Mahasiswa'],
            'filter' => $filter,
            'student' => json_decode($response->getBody())->student,
            'tunggakan' => json_decode($response->getBody())->data,
            'validation' => \Config\Services::validation(),
        ];

        session()->setFlashdata('success', 'Berhasil Memuat Data Tunggakan, Klik Export Untuk Download !');
        return view('pages/tunggakanPerMahasiswa', $data);
    }

    public function cetakTunggakanPerMahasiswa()
    {
        $filter = $this->request->getPost('filter');

        $response = $this->curl->request("POST", "https://api.umsu.ac.id/Laporankeu/getBillStudent", [
            "headers" => [
                "Accept" => "application/json"
            ],
            "form_params" => [
                "filter" => $filter,
            ]
        ]);

        $spreadsheet = new Spreadsheet();

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Data Total Tunggakan Mahasiswa';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xlsx');
        header('Cache-Control: max-age=0');

        // session()->setFlashdata('success', 'Berhasil Export Data Tunggakan !');
        $writer->save('php://output');
        // return $this->index('tunggakan');
    }
}
