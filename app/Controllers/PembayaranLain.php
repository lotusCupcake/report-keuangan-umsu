<?php

namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class PembayaranLain extends BaseController
{
    protected $curl;
    protected $jenis, $json;

    public function __construct()
    {
        $this->curl = service('curlrequest');

        $this->jenis = array(
            array(
                "value" => "wisuda",
                "text" => "Wisuda"
            ),
            array(
                "value" => "kompri",
                "text" => "Kompri"
            ), array(
                "value" => "sidang",
                "text" => "Sidang"
            ),
            array(
                "value" => "seminar",
                "text" => "Seminar"
            ), array(
                "value" => "praktikum",
                "text" => "Praktikum"
            ),
            array(
                "value" => "remedial",
                "text" => "Remedial"
            ), array(
                "value" => "ujian_susulan",
                "text" => "Ujian Susulan"
            ),
            array(
                "value" => "kelas_malam",
                "text" => "Kelas Malam"
            ),
            array(
                "value" => "konversi",
                "text" => "Konversi Mata Kuliah"
            )
        );
        $this->json = json_encode(array_values($this->jenis));
    }



    public function index()
    {
        $data = [
            'title' => "Pembayaran Lain-Lain",
            'appName' => "UMSU",
            'breadcrumb' => ['Home', 'Laporan Pembayaran', 'Pembayaran Lain-Lain'],
            'pembayaran' => [],
            'prodi' => [],
            'tagihan' => null,
            'jenis' => json_decode($this->json),
            'icon' => 'https://assets2.lottiefiles.com/packages/lf20_yzoqyyqf.json',
            'validation' => \Config\Services::validation(),
        ];
        // dd($data);

        return view('pages/pembayaranLain', $data);
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
            'tanggalAwal' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tanggal Awal Harus Diisi !',
                ]
            ],
            'tanggalAkhir' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tanggal Akhir Harus Diisi !',
                ]
            ],
        ])) {
            return redirect()->to('pembayaranLain')->withInput();
        }

        $jenis = trim($this->request->getPost('jenis'));
        $startDate = trim($this->request->getPost('tanggalAwal')) . ' 00:00:00.000';
        $endDate = trim($this->request->getPost('tanggalAkhir')) . ' 23:59:00.000';

        $response = $this->curl->request("POST", "https://api.umsu.ac.id/Laporankeu/getPembayaranLain", [
            "headers" => [
                "Accept" => "application/json"
            ],
            "form_params" => [
                "jenis" => $jenis,
                "startDate"=> $startDate,
                "endDate"=>$endDate
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
            'tagihan' => $jenis,
            'pembayaran' => json_decode($response->getBody())->data,
            'prodi' => $prodi,
            'jenis' => json_decode($this->json),
            'validation' => \Config\Services::validation(),
        ];

        session()->setFlashdata('success', 'Berhasil Memuat Data Pembayaran, Klik Export Untuk Download !');
        return view('pages/pembayaranLain', $data);
    }

    public function cetakPembayaranLainProdi()
    {
        $jenis = trim($this->request->getPost('jenis'));
        $startDate = trim($this->request->getPost('tanggalAwal')) . ' 00:00:00.000';
        $endDate = trim($this->request->getPost('tanggalAkhir')) . ' 23:59:00.000';

        $response = $this->curl->request("POST", "https://api.umsu.ac.id/Laporankeu/getPembayaranLain", [
            "headers" => [
                "Accept" => "application/json"
            ],
            "form_params" => [
                "jenis" => $jenis,
                "startDate"=> $startDate,
                "endDate"=>$endDate
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
                ->setCellValue('I' . $konten, 'Nominal')->getStyle("A" . $konten . ":" . "I" . $konten)->getFont()->setBold(true);

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
                        ->setCellValue('I' . $konten, number_to_currency($data->NOMINAL, 'IDR'))->getStyle("A" . $konten . ":" . "I" . $konten);
                    $konten++;
                }
            }
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $konten, 'Total Amount')->mergeCells("A" . $konten . ":" . "H" . $konten)->getStyle("A" . $konten . ":" . "H" . $konten)->getFont()->setBold(true);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('I' . $konten, number_to_currency($total, 'IDR'))->getStyle('I' . $konten)->getFont()->setBold(true);
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
        $jenis = trim($this->request->getPost('jenis'));

        $response = $this->curl->request("POST", "https://api.umsu.ac.id/Laporankeu/getPembayaranLain", [
            "headers" => [
                "Accept" => "application/json"
            ],
            "form_params" => [
                "jenis" => $jenis,
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
            ->setCellValue('I' . $konten, 'Nominal')->getStyle("A" . $konten . ":" . "I" . $konten)->getFont()->setBold(true);

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
                ->setCellValue('I' . $konten, number_to_currency($data->NOMINAL, 'IDR'))->getStyle("A" . $konten . ":" . "I" . $konten);
            $konten++;
        }
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $konten, 'Total Amount')->mergeCells("A" . $konten . ":" . "H" . $konten)->getStyle("A" . $konten . ":" . "H" . $konten)->getFont()->setBold(true);
        $spreadsheet->setActiveSheetIndex(0)->setCellValue('I' . $konten, number_to_currency($total, 'IDR'))->getStyle('I' . $konten)->getFont()->setBold(true);
        $konten = $konten + 1;

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Data Pembayaran Lain-Lain Mahasiswa - Seluruh';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xlsx');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }
}
