<?php

namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class TunggakanTotal extends BaseController
{
    protected $curl;
    public function __construct()
    {
        $this->curl = service('curlrequest');
    }



    public function index()
    {
        $data = [
            'title' => "Total Tunggakan",
            'appName' => "UMSU",
            'breadcrumb' => ['Home', 'Laporan Total Tunggakan'],
            'tunggakan' => [],
            'termYear' => null,
            'paymentOrder' => null,
            'listTermYear' => $this->getTermYear(),
            'prodi' => [],
            'fakultas' => [],
            'angkatan'=> [],
            'validation' => \Config\Services::validation(),
        ];
        // dd($data);

        return view('pages/tunggakanTotal', $data);
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

    public function prosesTunggakanTotal()
    {
        if (!$this->validate([
            'tahunAjar' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tahun Ajar Harus Diisi !',
                ]
            ],
            'tahap' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tunggakan Tahap Harus Diisi !',
                ]
            ],
        ])) {
            return redirect()->to('tunggakanTotal')->withInput();
        }

        $term_year_id = $this->request->getPost('tahunAjar');
        $payment_order = $this->request->getPost('tahap');
        
        $response = $this->curl->request("POST", "https://api.umsu.ac.id/Laporankeu/getTotalTunggakan", [
        "headers" => [
            "Accept" => "application/json"
        ],
        "form_params" => [
            "termYearId" => $term_year_id,
            "tahap" => $payment_order
            ]
        ]);

        $fakultas = [];
        foreach (json_decode($response->getBody())->data as $f) {
            if (!in_array($f->FAKULTAS, $fakultas)) {
                array_push($fakultas, $f->FAKULTAS);
            }
        }

        $prodi =[];
        foreach (json_decode($response->getBody())->data as $k) {
            array_push($prodi,[
                "fakultas" => $k->FAKULTAS,
                "prodi" => $k->NAMA_PRODI
            ]);
        }

        $angkatan = [];
        foreach (json_decode($response->getBody())->data as $a) {
            if (!in_array($a->ANGKATAN, $angkatan)) {
                array_push($angkatan, $a->ANGKATAN);
            }
        }
        

        $data = [
            'title' => "Total Tunggakan",
            'appName' => "UMSU FM",
            'breadcrumb' => ['Home', 'Laporan Total Tunggakan'],
            'tunggakan' => json_decode($response->getBody())->data,
            'termYear' => $term_year_id,
            'paymentOrder' => $payment_order,
            'listTermYear' => $this->getTermYear(),
            'prodi' => array_unique($prodi,SORT_REGULAR),
            'fakultas'=>$fakultas,
            'angkatan'=>$angkatan,
            'validation' => \Config\Services::validation(),
        ];

        session()->setFlashdata('success', 'Berhasil Memuat Data Tunggakan, Klik Export Untuk Download !');
        return view('pages/tunggakanTotal', $data);
    }

    public function cetakTunggakanTotal()
    {
        $term_year_id = $this->request->getPost('tahunAjar');
        $payment_order = $this->request->getPost('tahap');

        $response = $this->curl->request("POST", "https://api.umsu.ac.id/Laporankeu/getTotalTunggakan", [
            "headers" => [
                "Accept" => "application/json"
            ],
            "form_params" => [
                "termYearId" => $term_year_id,
                "tahap" => $payment_order
            ]
        ]);

        $fakultas = [];
        foreach (json_decode($response->getBody())->data as $f) {
            if (!in_array($f->FAKULTAS, $fakultas)) {
                array_push($fakultas, $f->FAKULTAS);
            }
        }

        $prodi =[];
        foreach (json_decode($response->getBody())->data as $k) {
            array_push($prodi,[
                "fakultas" => $k->FAKULTAS,
                "prodi" => $k->NAMA_PRODI
            ]);
        }

        $angkatan = [];
        foreach (json_decode($response->getBody())->data as $a) {
            if (!in_array($a->ANGKATAN, $angkatan)) {
                array_push($angkatan, $a->ANGKATAN);
            }
        }


        $spreadsheet = new Spreadsheet();

        $default = 1;
        $konten = 0;
        foreach ($prodi as $prd) {
            $konten = $default + $konten;
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $konten, $prd)->mergeCells("A" . $konten . ":" . "I" . $konten)->getStyle("A" . $konten . ":" . "I" . $konten)->getFont()->setBold(true);
            $konten = $konten + 1;
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $konten, 'No Register')
                ->setCellValue('B' . $konten, 'No Register')
                ->setCellValue('C' . $konten, 'NPM')
                ->setCellValue('D' . $konten, 'Nama Lengkap')
                ->setCellValue('E' . $konten, 'Nama Prodi')
                ->setCellValue('F' . $konten, 'Angkatan')
                ->setCellValue('G' . $konten, 'Nama Biaya')
                ->setCellValue('H' . $konten, 'Tahap')
                ->setCellValue('I' . $konten, 'Nominal')->getStyle("A" . $konten . ":" . "H" . $konten)->getFont()->setBold(true);

            $konten = $konten + 1;
            $total = 0;
            $no = 1;
            foreach (json_decode($response->getBody())->data as $data) {
                if ($prd == $data->NAMA_PRODI) {
                    $total = $total + $data->NOMINAL;
                    $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue('A' . $konten, $no++)
                        ->setCellValue('B' . $konten, $data->NO_REGISTER)
                        ->setCellValue('C' . $konten, $data->Npm)
                        ->setCellValue('D' . $konten, $data->NAMA_LENGKAP)
                        ->setCellValue('E' . $konten, $data->NAMA_PRODI)
                        ->setCellValue('F' . $konten, $data->ANGKATAN)
                        ->setCellValue('G' . $konten, $data->NAMA_BIAYA)
                        ->setCellValue('H' . $konten, $data->TAHAP)
                        ->setCellValue('I' . $konten, number_to_currency($data->NOMINAL, 'IDR'))->getStyle("A" . $konten . ":" . "H" . $konten);
                    $konten++;
                }
            }
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $konten, 'Total Amount')->mergeCells("A" . $konten . ":" . "H" . $konten)->getStyle("A" . $konten . ":" . "H" . $konten)->getFont()->setBold(true);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('I' . $konten, number_to_currency($total, 'IDR'))->getStyle('I' . $konten)->getFont()->setBold(true);
            $konten = $konten + 1;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Data Tunggakan Mahasiswa';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xlsx');
        header('Cache-Control: max-age=0');

        // session()->setFlashdata('success', 'Berhasil Export Data Tunggakan !');
        $writer->save('php://output');
        return $this->index('tunggakan');
    }

}
