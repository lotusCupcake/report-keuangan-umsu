<?php

namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class PembayaranLain extends BaseController
{
    protected $curl;
    public function __construct()
    {
        $this->curl = service('curlrequest');
    }



    public function index()
    {
        $data = [
            'title' => "Pembayaran Lain-Lain",
            'appName' => "UMSU",
            'breadcrumb' => ['Home', 'Laporan Pembayaran', 'Pembayaran Lain-Lain'],
            'pembayaran' => [],
            'termYear' => null,
            'listTermYear' => $this->getTermYear(),
            'prodi' => [],
            'icon' => 'https://assets2.lottiefiles.com/packages/lf20_yzoqyyqf.json',
            'validation' => \Config\Services::validation(),
        ];
        // dd($data);

        return view('pages/pembayaranLain', $data);
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

    public function prosesPembayaranLain()
    {
        if (!$this->validate([
            'jenis' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Jenis Pembayaran Harus Diisi !',
                ]
            ],
            'tahap' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Pembayaran Tahap Harus Diisi !',
                ]
            ],
        ])) {
            return redirect()->to('pembayaranLain')->withInput();
        }

        $term_year_id = trim($this->request->getPost('tahunAjar'));
        // dd($term_year_id, $entry_year_id, $payment_order, $bank);

        $response = $this->curl->request("POST", "https://api.umsu.ac.id/Laporankeu/getLaporanPembayaran", [
            "headers" => [
                "Accept" => "application/json"
            ],
            "form_params" => [
                "termYearId" => $term_year_id,
            ]
        ]);

        $prodi = [];
        foreach (json_decode($response->getBody())->data as $k) {
            if (!in_array($k->PRODI, $prodi)) {
                array_push($prodi, $k->PRODI);
            }
        }

        $data = [
            'title' => "Pembayaran Lain-Lain",
            'appName' => "UMSU",
            'breadcrumb' => ['Home', 'Laporan Pembayaran', 'Pembayaran Lain-Lain'],
            'termYear' => $term_year_id,
            'pembayaran' => json_decode($response->getBody())->data,
            'listTermYear' => $this->getTermYear(),
            'prodi' => $prodi,
            'validation' => \Config\Services::validation(),
        ];

        session()->setFlashdata('success', 'Berhasil Memuat Data Pembayaran, Klik Export Untuk Download !');
        return view('pages/pembayaranLain', $data);
    }

    public function cetakPembayaranLainProdi()
    {
        $term_year_id = trim($this->request->getPost('tahunAjar'));

        $response = $this->curl->request("POST", "https://api.umsu.ac.id/Laporankeu/getLaporanPembayaran", [
            "headers" => [
                "Accept" => "application/json"
            ],
            "form_params" => [
                "termYearId" => $term_year_id,
            ]
        ]);

        $prodi = [];
        foreach (json_decode($response->getBody())->data as $k) {
            if (!in_array($k->PRODI, $prodi)) {
                array_push($prodi, $k->PRODI);
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
                ->setCellValue('A' . $konten, 'No.')
                ->setCellValue('B' . $konten, 'No Register')
                ->setCellValue('C' . $konten, 'NPM')
                ->setCellValue('D' . $konten, 'Nama Lengkap')
                ->setCellValue('E' . $konten, 'Nama Prodi')
                ->setCellValue('F' . $konten, 'Angkatan')
                ->setCellValue('G' . $konten, 'Nama Biaya')
                ->setCellValue('H' . $konten, 'Bank')
                ->setCellValue('I' . $konten, 'Tahap')
                ->setCellValue('J' . $konten, 'Nominal')->getStyle("A" . $konten . ":" . "J" . $konten)->getFont()->setBold(true);

            $konten = $konten + 1;
            $total = 0;
            $no = 1;
            foreach (json_decode($response->getBody())->data as $data) {
                if ($prd == $data->PRODI) {
                    $total = $total + $data->NOMINAL;
                    $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue('A' . $konten, $no++)
                        ->setCellValue('B' . $konten, $data->NO_REGISTER)
                        ->setCellValue('C' . $konten, $data->Npm)
                        ->setCellValue('D' . $konten, $data->NAMA_LENGKAP)
                        ->setCellValue('E' . $konten, $data->PRODI)
                        ->setCellValue('F' . $konten, $data->ANGKATAN)
                        ->setCellValue('G' . $konten, $data->NAMA_BIAYA)
                        ->setCellValue('H' . $konten, $data->BANK_NAMA)
                        ->setCellValue('I' . $konten, ($data->TAHAP == 0) ? "Lunas" : "Tahap " . $data->TAHAP)
                        ->setCellValue('J' . $konten, number_to_currency($data->NOMINAL, 'IDR'))->getStyle("A" . $konten . ":" . "J" . $konten);
                    $konten++;
                }
            }
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $konten, 'Total Amount')->mergeCells("A" . $konten . ":" . "I" . $konten)->getStyle("A" . $konten . ":" . "I" . $konten)->getFont()->setBold(true);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('J' . $konten, number_to_currency($total, 'IDR'))->getStyle('J' . $konten)->getFont()->setBold(true);
            $konten = $konten + 1;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Data Pembayaran Lain-Lain Mahasiswa - Prodi';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xlsx');
        header('Cache-Control: max-age=0');

        // session()->setFlashdata('success', 'Berhasil Export Data Tunggakan !');
        $writer->save('php://output');
    }

    public function cetakPembayaranLainSeluruh()
    {
        $term_year_id = trim($this->request->getPost('tahunAjar'));

        $response = $this->curl->request("POST", "https://api.umsu.ac.id/Laporankeu/getLaporanPembayaran", [
            "headers" => [
                "Accept" => "application/json"
            ],
            "form_params" => [
                "termYearId" => $term_year_id,
            ]
        ]);

        $prodi = [];
        foreach (json_decode($response->getBody())->data as $k) {
            if (!in_array($k->PRODI, $prodi)) {
                array_push($prodi, $k->PRODI);
            }
        }

        $spreadsheet = new Spreadsheet();

        $konten = 0;
        $konten = $konten + 1;
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A' . $konten, 'No.')
            ->setCellValue('B' . $konten, 'No Register')
            ->setCellValue('C' . $konten, 'NPM')
            ->setCellValue('D' . $konten, 'Nama Lengkap')
            ->setCellValue('E' . $konten, 'Nama Prodi')
            ->setCellValue('F' . $konten, 'Angkatan')
            ->setCellValue('G' . $konten, 'Nama Biaya')
            ->setCellValue('H' . $konten, 'Bank')
            ->setCellValue('I' . $konten, 'Tahap')
            ->setCellValue('J' . $konten, 'Nominal')->getStyle("A" . $konten . ":" . "J" . $konten)->getFont()->setBold(true);

        $konten = $konten + 1;
        $total = 0;
        $no = 1;
        foreach (json_decode($response->getBody())->data as $data) {
            $total = $total + $data->NOMINAL;
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $konten, $no++)
                ->setCellValue('B' . $konten, $data->NO_REGISTER)
                ->setCellValue('C' . $konten, $data->Npm)
                ->setCellValue('D' . $konten, $data->NAMA_LENGKAP)
                ->setCellValue('E' . $konten, $data->PRODI)
                ->setCellValue('F' . $konten, $data->ANGKATAN)
                ->setCellValue('G' . $konten, $data->NAMA_BIAYA)
                ->setCellValue('H' . $konten, $data->BANK_NAMA)
                ->setCellValue('I' . $konten, ($data->TAHAP == 0) ? "Lunas" : "Tahap " . $data->TAHAP)
                ->setCellValue('J' . $konten, number_to_currency($data->NOMINAL, 'IDR'))->getStyle("A" . $konten . ":" . "J" . $konten);
            $konten++;
        }
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $konten, 'Total Amount')->mergeCells("A" . $konten . ":" . "I" . $konten)->getStyle("A" . $konten . ":" . "I" . $konten)->getFont()->setBold(true);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('J' . $konten, number_to_currency($total, 'IDR'))->getStyle('J' . $konten)->getFont()->setBold(true);
        $konten = $konten + 1;

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Data Pembayaran Lain-Lain Mahasiswa - Seluruh';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xlsx');
        header('Cache-Control: max-age=0');

        // session()->setFlashdata('success', 'Berhasil Export Data Tunggakan !');
        $writer->save('php://output');
    }
}
