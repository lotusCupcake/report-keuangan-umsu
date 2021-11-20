<?php

namespace App\Controllers;

use App\Models\TunggakanModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class Tunggakan extends BaseController
{
    protected $tunggakanModel;
    protected $curl;
    public function __construct()
    {
        $this->tunggakanModel = new TunggakanModel();
        $this->curl = service('curlrequest');
    }


    public function index()
    {
        $data = [
            'title' => "Tunggakan",
            'appName' => "UMSU FM",
            'breadcrumb' => ['Home', 'Tunggakan'],
            'tunggakan' => [],
            'termYear' => null,
            'entryYear' => null,
            'validation' => \Config\Services::validation(),
        ];


        return view('pages/tunggakan', $data);
    }

    public function prosesTunggakan()
    {
        $term_year_id = $this->request->getPost('tahunAjar');
        $entry_year_id = $this->request->getPost('tahunAngkatan');

        $response = $this->curl->request("POST", "https://api.umsu.ac.id/Laporankeu", [
			"headers" => [
				"Accept" => "application/json"
            ],
            "form_params" =>[
                "entryYearId" => $entry_year_id,
                "termYearId" => $term_year_id
            ]
		]);
        
        $data = [
            'title' => "Tunggakan",
            'appName' => "UMSU FM",
            'breadcrumb' => ['Home', 'Tunggakan'],
            'tunggakan' => json_decode($response->getBody())->data,
            'termYear' => $term_year_id,
            'entryYear' => $entry_year_id,
            'validation' => \Config\Services::validation(),
        ];

        return view('pages/tunggakan', $data);
    }

    public function cetakTunggakan()
    {
        $term_year_id = $this->request->getPost('tahunAjar');
        $entry_year_id = $this->request->getPost('tahunAngkatan');

        $response = $this->curl->request("POST", "https://api.umsu.ac.id/Laporankeu", [
			"headers" => [
				"Accept" => "application/json"
            ],
            "form_params" =>[
                "entryYearId" => $entry_year_id,
                "termYearId" => $term_year_id
            ]
		]);

        $spreadsheet = new Spreadsheet();

        $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1', 'No Register')
                ->setCellValue('B1', 'NPM')
                ->setCellValue('C1', 'Nama Lengkap')
                ->setCellValue('D1', 'Nama Prodi')
                ->setCellValue('E1', 'Angkatan')
                ->setCellValue('F1', 'Nama Biaya')
                ->setCellValue('G1', 'Tahap')
                ->setCellValue('H1', 'Nominal');

        $column = 2;
        $total =0;
        foreach(json_decode($response->getBody())->data as $data) {
            $total=$total+$data->NOMINAL;
            $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue('A' . $column, $data->NO_REGISTER)
                        ->setCellValue('B' . $column, $data->Npm)
                        ->setCellValue('C' . $column, $data->NAMA_LENGKAP)
                        ->setCellValue('D' . $column, $data->NAMA_PRODI)
                        ->setCellValue('E' . $column, $data->ANGKATAN)
                        ->setCellValue('F' . $column, $data->NAMA_BIAYA)
                        ->setCellValue('G' . $column, $data->TAHAP)
                        ->setCellValue('H' . $column, number_to_currency($data->NOMINAL, 'IDR'));
            $column++;
        }

        $spreadsheet->setActiveSheetIndex(0)->setCellValue('H' . $column, number_to_currency($total, 'IDR'))->getStyle('H' . $column)->getFont()->setBold( true );

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Data Tunggakan Mahasiswa';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename='.$fileName.'.xlsx');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }
}
