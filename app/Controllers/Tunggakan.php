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
            'paymentOrder' => null,
            'listTermYear' => $this->getTermYear(),
            'prodi'=> [],
            'validation' => \Config\Services::validation(),
        ];
        // dd($data);

        return view('pages/tunggakan', $data);
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

    public function prosesTunggakan()
    {
        $term_year_id = $this->request->getPost('tahunAjar');
        $entry_year_id = $this->request->getPost('tahunAngkatan');
        $payment_order = $this->request->getPost('tahap');

        $response = $this->curl->request("POST", "https://api.umsu.ac.id/Laporankeu", [
			"headers" => [
				"Accept" => "application/json"
            ],
            "form_params" =>[
                "entryYearId" => $entry_year_id,
                "termYearId" => $term_year_id,
                "tahap" => $payment_order
            ]
		]);

        $prodi=[];
        foreach(json_decode($response->getBody())->data as $k){
            if(!in_array($k->NAMA_PRODI,$prodi)){
                array_push($prodi,$k->NAMA_PRODI);
            }
        }
        
        $data = [
            'title' => "Tunggakan",
            'appName' => "UMSU FM",
            'breadcrumb' => ['Home', 'Tunggakan'],
            'tunggakan' => json_decode($response->getBody())->data,
            'termYear' => $term_year_id,
            'entryYear' => $entry_year_id,
            'paymentOrder' => $payment_order,
            'listTermYear' => $this->getTermYear(),
            'prodi'=> $prodi,
            'validation' => \Config\Services::validation(),
        ];

        return view('pages/tunggakan', $data);
    }

    public function cetakTunggakan()
    {
        $term_year_id = $this->request->getPost('tahunAjar');
        $entry_year_id = $this->request->getPost('tahunAngkatan');
        $payment_order = $this->request->getPost('tahap');

        $response = $this->curl->request("POST", "https://api.umsu.ac.id/Laporankeu", [
			"headers" => [
				"Accept" => "application/json"
            ],
            "form_params" =>[
                "entryYearId" => $entry_year_id,
                "termYearId" => $term_year_id,
                "tahap" => $payment_order
            ]
		]);

        $prodi=[];
        foreach(json_decode($response->getBody())->data as $k){
            if(!in_array($k->NAMA_PRODI,$prodi)){
                array_push($prodi,$k->NAMA_PRODI);
            }
        }

        $spreadsheet = new Spreadsheet();

        $default=1;
        $konten = 0;
        foreach ($prodi as $prd) {
            $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A'.$default+$konten, 'No Register')
                    ->setCellValue('B'.$default+$konten, 'NPM')
                    ->setCellValue('C'.$default+$konten, 'Nama Lengkap')
                    ->setCellValue('D'.$default+$konten, 'Nama Prodi')
                    ->setCellValue('E'.$default+$konten, 'Angkatan')
                    ->setCellValue('F'.$default+$konten, 'Nama Biaya')
                    ->setCellValue('G'.$default+$konten, 'Tahap')
                    ->setCellValue('H'.$default+$konten, 'Nominal')->getStyle("A1:H1")->getFont()->setBold( true );

            $konten = $konten+1;
            $total = 0;
            foreach(json_decode($response->getBody())->data as $data) {
                $total=$total+$data->NOMINAL;
                $spreadsheet->setActiveSheetIndex(0)
                            ->setCellValue('A' . $konten, $data->NO_REGISTER)
                            ->setCellValue('B' . $konten, $data->Npm)
                            ->setCellValue('C' . $konten, $data->NAMA_LENGKAP)
                            ->setCellValue('D' . $konten, $data->NAMA_PRODI)
                            ->setCellValue('E' . $konten, $data->ANGKATAN)
                            ->setCellValue('F' . $konten, $data->NAMA_BIAYA)
                            ->setCellValue('G' . $konten, $data->TAHAP)
                            ->setCellValue('H' . $konten, number_to_currency($data->NOMINAL, 'IDR'));
                $konten++;
                $konten++;
            }

            $spreadsheet->setActiveSheetIndex(0)->setCellValue('H' . $konten, number_to_currency($total, 'IDR'))->getStyle('H' . $konten)->getFont()->setBold( true );
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Data Tunggakan Mahasiswa';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename='.$fileName.'.xlsx');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }
}
