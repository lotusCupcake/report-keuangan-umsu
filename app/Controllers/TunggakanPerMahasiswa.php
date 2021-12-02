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

        $spreadsheet = new Spreadsheet();

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . 1, "Total Tunggakan Mahasiswa")->mergeCells("A" . 1 . ":" . "E" . 1)->getStyle("A" . 1 . ":" . "E" . 1)->getFont()->setBold(true);
        $konten = 1;
        $konten = $konten + 1;
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A' . $konten, 'No.')
            ->setCellValue('B' . $konten, 'Tahun')
            ->setCellValue('C' . $konten, 'Nama Tagihan')
            ->setCellValue('D' . $konten, 'Tahap')
            ->setCellValue('E' . $konten, 'Nominal')->getStyle("A" . $konten . ":" . "E" . $konten)->getFont()->setBold(true);

        $konten = $konten + 1;
        $total = 0;
        $no = 1;
        foreach (json_decode($response->getBody())->data as $data) {
            $total = $total + $data->Amount;
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $konten, $no++)
                ->setCellValue('B' . $konten, $data->Term_Year_Bill_id)
                ->setCellValue('C' . $konten, $data->Cost_Item_Name)
                ->setCellValue('D' . $konten, $data->Payment_Order)
                ->setCellValue('E' . $konten, number_to_currency($data->Amount, 'IDR'))->getStyle("A" . $konten . ":" . "E" . $konten);
            $konten++;
        }
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $konten, 'Total Amount')->mergeCells("A" . $konten . ":" . "D" . $konten)->getStyle("A" . $konten . ":" . "D" . $konten)->getFont()->setBold(true);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $konten, number_to_currency($total, 'IDR'))->getStyle('E' . $konten)->getFont()->setBold(true);


        $writer = new Xlsx($spreadsheet);
        $fileName = 'Data Tunggakan Mahasiswa';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xlsx');
        header('Cache-Control: max-age=0');

        // session()->setFlashdata('success', 'Berhasil Export Data Tunggakan !');
        $writer->save('php://output');
        // return $this->index('tunggakan');
    }
}
