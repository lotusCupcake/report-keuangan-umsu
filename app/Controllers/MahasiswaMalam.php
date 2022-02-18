<?php

namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class MahasiswaMalam extends BaseController
{
    protected $curl;
    public function __construct()
    {
        $this->curl = service('curlrequest');
    }



    public function index()
    {
        $data = [
            'title' => "Mahasiswa KRS Aktif (Malam)",
            'appName' => "UMSU",
            'breadcrumb' => ['Home', 'Laporan KRS Aktif', 'Mahasiswa KRS Aktif (Malam)'],
            'mahasiswaMalam' => [],
            'termYear' => null,
            'entryYear' => null,
            'paymentOrder' => null,
            'listTermYear' => $this->getTermYear(),
            'prodi' => [],
            'filter' => null,
            'fakultas' => [],
            'fakultasFilter' => $this->getFakultas(),
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu()
        ];
        // dd($data);

        return view('pages/mahasiswaMalam', $data);
    }

    public function getFakultas()
    {
        $response = $this->curl->request("GET", "https://api.umsu.ac.id/Laporankeu/getFakultas", [
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

    public function prosesMahasiswaMalam()
    {
        // dd($_POST);
        if (!$this->validate([
            'tahunAjar' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tahun Ajar Harus Diisi !',
                ]
            ],
        ])) {
            return redirect()->to('mahasiswaMalam')->withInput();
        }

        $term_year_id = trim($this->request->getPost('tahunAjar'));
        $filter = trim($this->request->getPost('fakultas') == '') ? 'Non Kedokteran' : trim($this->request->getPost('fakultas'));

        $response = $this->curl->request("POST", "https://api.umsu.ac.id/Laporankeu/getMahasiswaKelasMalam", [
            "headers" => [
                "Accept" => "application/json"
            ],
            "form_params" => [
                "tahunAjar" => $term_year_id,
                "filter" => $filter,

            ]
        ]);

        // dd(json_decode($response->getBody())->data);

        // $fakultas = [];
        // foreach (json_decode($response->getBody())->data as $f) {
        //     if (!in_array($f->FAKULTAS, $fakultas)) {
        //         array_push($fakultas, $f->FAKULTAS);
        //     }
        // }

        $prodi = [];
        foreach (json_decode($response->getBody())->data as $k) {
            array_push($prodi, [
                "prodi" => $k->PRODI
            ]);
        }


        // $angkatan = [];
        // foreach (json_decode($response->getBody())->data as $a) {
        //     if (!in_array($a->ANGKATAN, $angkatan)) {
        //         array_push($angkatan, $a->ANGKATAN);
        //     }
        // }

        $data = [
            'title' => "Mahasiswa KRS Aktif (Malam)",
            'appName' => "UMSU",
            'breadcrumb' => ['Home', 'Laporan KRS Aktif', 'Mahasiswa KRS Aktif (Malam)'],
            'mahasiswaMalam' => json_decode($response->getBody())->data,
            'termYear' => $term_year_id,
            'listTermYear' => $this->getTermYear(),
            'prodi' => array_unique($prodi, SORT_REGULAR),
            'filter' => $filter,
            // 'fakultas' => $fakultas,
            'fakultasFilter' => $this->getFakultas(),
            // 'angkatan' => $angkatan,
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu()
        ];

        session()->setFlashdata('success', 'Berhasil Memuat Data Mahasiswa KRS Aktif (Malam), Klik Export Untuk Download !');
        return view('pages/mahasiswaMalam', $data);
    }

    public function cetakMahasiswaMalam()
    {
        $term_year_id = trim($this->request->getPost('tahunAjar'));
        $filter = trim($this->request->getPost('fakultas') == '') ? 'Non Kedokteran' : trim($this->request->getPost('fakultas'));

        $response = $this->curl->request("POST", "https://api.umsu.ac.id/Laporankeu/getMahasiswaKelasMalam", [
            "headers" => [
                "Accept" => "application/json"
            ],
            "form_params" => [
                "tahunAjar" => $term_year_id,
                "filter" => $filter,

            ]
        ]);

        // $fakultas = [];
        // foreach (json_decode($response->getBody())->data as $f) {
        //     if (!in_array($f->FAKULTAS, $fakultas)) {
        //         array_push($fakultas, $f->FAKULTAS);
        //     }
        // }

        $prodi = [];
        foreach (json_decode($response->getBody())->data as $k) {
            array_push($prodi, [
                "prodi" => $k->PRODI
            ]);
        }


        // $angkatan = [];
        // foreach (json_decode($response->getBody())->data as $a) {
        //     if (!in_array($a->ANGKATAN, $angkatan)) {
        //         array_push($angkatan, $a->ANGKATAN);
        //     }
        // }

        $spreadsheet = new Spreadsheet();

        $konten = 0;
        $konten = $konten + 1;
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A' . $konten, 'No.')
            ->setCellValue('B' . $konten, 'NPM')
            ->setCellValue('C' . $konten, 'Nama Lengkap	')
            ->setCellValue('D' . $konten, 'Prodi')
            ->setCellValue('E' . $konten, 'Kelas')
            ->setCellValue('F' . $konten, 'Tahun Ajaran')->getStyle("A" . $konten . ":" . "F" . $konten)->getFont()->setBold(true);

        $konten = $konten + 1;
        // $total = 0;
        $no = 1;
        foreach (json_decode($response->getBody())->data as $krsAkt) {
            // $total = $total + $data->NOMINAL;
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $konten, $no++)
                ->setCellValue('B' . $konten, $krsAkt->NPM)
                ->setCellValue('C' . $konten, $krsAkt->NAMA_LENGKAP)
                ->setCellValue('D' . $konten, $krsAkt->PRODI)
                ->setCellValue('E' . $konten, $krsAkt->KELAS)
                ->setCellValue('F' . $konten, $krsAkt->TAHUN_SEMESTER)->getStyle("A" . $konten . ":" . "F" . $konten);
            $konten++;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Data Mahasiswa KRS Aktif (Malam)';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xlsx');
        header('Cache-Control: max-age=0');

        // session()->setFlashdata('success', 'Berhasil Export Data Tunggakan !');
        $writer->save('php://output');
        // return $this->index('tunggakan');
    }
}
