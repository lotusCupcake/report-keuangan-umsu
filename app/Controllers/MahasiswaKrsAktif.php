<?php

namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class MahasiswaKrsAktif extends BaseController
{
    protected $curl;
    public function __construct()
    {
        $this->curl = service('curlrequest');
    }



    public function index()
    {
        $data = [
            'title' => "Mahasiswa KRS Aktif",
            'appName' => "UMSU",
            'breadcrumb' => ['Home', 'Laporan KRS Aktif', 'Mahasiswa KRS Aktif'],
            'krsAktif' => [],
            'termYear' => null,
            'entryYear' => null,
            'paymentOrder' => null,
            'listTermYear' => $this->getTermYear(),
            'prodi' => [],
            'filter' => null,
            'fakultas' => [],
            'fakultasFilter' => $this->getFakultas(),
            'angkatan' => [],
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu()
        ];
        // dd($data);

        return view('pages/mahasiswaKrsAktif', $data);
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

    public function prosesMahasiswaKrsAktif()
    {
        // dd($_POST);
        if (!$this->validate([
            'tahunAjar' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tahun Ajar Harus Diisi !',
                ]
            ],
            'tahunAngkatan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tahun Angkatan Harus Diisi !',
                ]
            ],
        ])) {
            return redirect()->to('mahasiswaKrsAktif')->withInput();
        }

        $term_year_id = trim($this->request->getPost('tahunAjar'));
        $entry_year_id = trim($this->request->getPost('tahunAngkatan'));
        $filter = trim($this->request->getPost('fakultas') == '') ? 'Non Kedokteran' : trim($this->request->getPost('fakultas'));

        $response = $this->curl->request("POST", "https://api.umsu.ac.id/Laporankeu/getMhsKrsAktif", [
            "headers" => [
                "Accept" => "application/json"
            ],
            "form_params" => [
                "entryYearId" => $entry_year_id,
                "tahunAjar" => $term_year_id,
                "filter" => $filter,

            ]
        ]);

        // dd(json_decode($response->getBody())->data);

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
            'title' => "Mahasiswa KRS Aktif",
            'appName' => "UMSU",
            'breadcrumb' => ['Home', 'Laporan KRS Aktif', 'Mahasiswa KRS Aktif'],
            'krsAktif' => json_decode($response->getBody())->data,
            'termYear' => $term_year_id,
            'entryYear' => $entry_year_id,
            'listTermYear' => $this->getTermYear(),
            'prodi' => array_unique($prodi, SORT_REGULAR),
            'filter' => $filter,
            'fakultas' => $fakultas,
            'fakultasFilter' => $this->getFakultas(),
            'angkatan' => $angkatan,
            'validation' => \Config\Services::validation(),
            'menu' => $this->fetchMenu()
        ];

        session()->setFlashdata('success', 'Berhasil Memuat Data Mahasiswa KRS Aktif, Klik Export Untuk Download !');
        return view('pages/mahasiswaKrsAktif', $data);
    }

    public function cetakMahasiswaKrsAktif()
    {
        $term_year_id = trim($this->request->getPost('tahunAjar'));
        $entry_year_id = trim($this->request->getPost('tahunAngkatan'));
        $filter = trim($this->request->getPost('fakultas') == '') ? 'Non Kedokteran' : trim($this->request->getPost('fakultas'));

        $response = $this->curl->request("POST", "https://api.umsu.ac.id/Laporankeu/getMhsKrsAktif", [
            "headers" => [
                "Accept" => "application/json"
            ],
            "form_params" => [
                "entryYearId" => $entry_year_id,
                "tahunAjar" => $term_year_id,
                "filter" => $filter,

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

        $konten = 0;
        $konten = $konten + 1;
        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A' . $konten, 'No.')
            ->setCellValue('B' . $konten, 'NPM')
            ->setCellValue('C' . $konten, 'Nama Lengkap	')
            ->setCellValue('D' . $konten, 'Fakultas')
            ->setCellValue('E' . $konten, 'Prodi')
            ->setCellValue('F' . $konten, 'Status')->getStyle("A" . $konten . ":" . "F" . $konten)->getFont()->setBold(true);

        $konten = $konten + 1;
        // $total = 0;
        $no = 1;
        foreach (json_decode($response->getBody())->data as $krsAkt) {
            // $total = $total + $data->NOMINAL;
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A' . $konten, $no++)
                ->setCellValue('B' . $konten, $krsAkt->NPM)
                ->setCellValue('C' . $konten, $krsAkt->NAMA_LENGKAP)
                ->setCellValue('D' . $konten, $krsAkt->FAKULTAS)
                ->setCellValue('E' . $konten, $krsAkt->NAMA_PRODI)
                ->setCellValue('F' . $konten, $krsAkt->STATUS)->getStyle("A" . $konten . ":" . "F" . $konten);
            $konten++;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Data Mahasiswa KRS Aktif';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $fileName . '.xlsx');
        header('Cache-Control: max-age=0');

        // session()->setFlashdata('success', 'Berhasil Export Data Tunggakan !');
        $writer->save('php://output');
        // return $this->index('tunggakan');
    }
}
