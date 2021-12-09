<?php

namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
            'title' => "Total Pembayaran Pokok",
            'appName' => "UMSU",
            'breadcrumb' => ['Home', 'Laporan Pembayaran', 'Total Pembayaran Pokok'],
            'pembayaran' => [],
            'termYear' => null,
            'entryYear' => null,
            'paymentOrder' => null,
            'bank' => null,
            'listTermYear' => $this->getTermYear(),
            'listBank' => $this->getBank(),
            'fakultas' => [],
            'prodi' => [],
            'icon' => 'https://assets2.lottiefiles.com/packages/lf20_yzoqyyqf.json',
            'angkatan' => [],
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

        $term_year_id = trim($this->request->getPost('tahunAjar'));
        $payment_order = trim($this->request->getPost('tahap'));
        $bank = trim($this->request->getPost('bank'));

        $response = $this->curl->request("POST", "https://api.umsu.ac.id/Laporankeu/getLaporanTotalPembayaran", [
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

        $fakultas = [];
        foreach (json_decode($response->getBody())->data as $f) {
            if (!in_array($f->FAKULTAS, $fakultas)) {
                array_push($fakultas, $f->FAKULTAS);
            }
        }

        $prodi = [];
        foreach (json_decode($response->getBody())->data as $k) {
            array_push($prodi, [
                "fakultas" => $k->FAKULTAS,
                "prodi" => $k->PRODI
            ]);
        }

        $angkatan = [];
        foreach (json_decode($response->getBody())->data as $a) {
            if (!in_array($a->ANGKATAN, $angkatan)) {
                array_push($angkatan, $a->ANGKATAN);
            }
        }

        $data = [
            'title' => "Total Pembayaran Pokok",
            'appName' => "UMSU",
            'breadcrumb' => ['Home', 'Laporan Pembayaran', 'Total Pembayaran Pokok'],
            'termYear' => $term_year_id,
            'paymentOrder' => $payment_order,
            'bank' => $bank,
            'pembayaran' => json_decode($response->getBody())->data,
            'listTermYear' => $this->getTermYear(),
            'listBank' => $this->getBank(),
            'fakultas' => $fakultas,
            'prodi' => array_unique($prodi, SORT_REGULAR),
            'angkatan' => $angkatan,
            'validation' => \Config\Services::validation(),
        ];

        // dd($data);

        session()->setFlashdata('success', 'Berhasil Memuat Data Pembayaran, Klik Export Untuk Download !');
        return view('pages/pembayaranTotal', $data);
    }

    public function cetakPembayaranTotal()
    {
        $term_year_id = trim($this->request->getPost('tahunAjar'));
        $payment_order = trim($this->request->getPost('tahap'));
        $bank = trim($this->request->getPost('bank'));

        $response = $this->curl->request("POST", "https://api.umsu.ac.id/Laporankeu/getLaporanTotalPembayaran", [
            "headers" => [
                "Accept" => "application/json"
            ],
            "form_params" => [
                "termYearId" => $term_year_id,
                "tahap" => $payment_order,
                "bank" => $bank
            ]
        ]);

        $fakultas = [];
        foreach (json_decode($response->getBody())->data as $f) {
            if (!in_array($f->FAKULTAS, $fakultas)) {
                array_push($fakultas, $f->FAKULTAS);
            }
        }

        $prodi = [];
        foreach (json_decode($response->getBody())->data as $k) {
            array_push($prodi, [
                "fakultas" => $k->FAKULTAS,
                "prodi" => $k->PRODI
            ]);
        }

        $angkatan = [];
        foreach (json_decode($response->getBody())->data as $a) {
            if (!in_array($a->ANGKATAN, $angkatan)) {
                array_push($angkatan, $a->ANGKATAN);
            }
        }

        $spreadsheet = new Spreadsheet();
        $col =   array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
        $row = 1;

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $row, "Rekap Pembayaran")->mergeCells("A" . $row . ":" . $col[2 + (count($angkatan) - 1)] . $row)->getStyle("A" . $row . ":" . $col[2 + (count($angkatan) - 1)] . $row)->getFont()->setBold(true);
        $row = $row + 1;
        $no = 0;
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A' . $row, 'No.')
            ->setCellValue('B' . $row, 'Fakultas / Prodi');

        foreach ($angkatan as $ang) {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue($col[2 + ($no)] . $row, $ang)->getStyle($col[2 + ($no)] . $row)->getFont()->setBold(true);
            $no++;
        }

        $row = $row + 1;

        foreach ($fakultas as $fak) {
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $row, '')
                ->setCellValue('B' . $row, $fak);
            $no = 0;
            foreach ($angkatan as $ang) {
                $spreadsheet->setActiveSheetIndex(0)->setCellValue($col[2 + ($no)] . $row, '')->getStyle($col[2 + ($no)] . $row)->getFont()->setBold(true);
                $no++;
            }
            $row++;

            $urut = 1;
            foreach (array_unique($prodi, SORT_REGULAR) as $prd) {
                if ($fak == $prd['fakultas']) {
                    $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue('A' . $row, $urut)
                        ->setCellValue('B' . $row, $prd['prodi']);

                    $no = 0;
                    foreach ($angkatan as $ang) {
                        $nilai = 0;
                        foreach (json_decode($response->getBody())->data as $pemb) {
                            ($ang == $pemb->ANGKATAN && $prd['prodi'] == $pemb->PRODI) ? $nilai = $pemb->NOMINAL : $nilai = $nilai;
                        }
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue($col[2 + ($no)] . $row, number_to_currency($nilai, 'IDR'))->getStyle($col[2 + ($no)] . $row)->getFont()->setBold(true);
                        $no++;
                    }
                    $urut++;
                    $row++;
                }
            }
        }


        $writer = new Xlsx($spreadsheet);
        $fileName = 'Data Total Pembayaran Pokok PokokMahasiswa';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xlsx');
        header('Cache-Control: max-age=0');

        // session()->setFlashdata('success', 'Berhasil Export Data Tunggakan !');
        $writer->save('php://output');
        // return $this->index('tunggakan');
    }
}
