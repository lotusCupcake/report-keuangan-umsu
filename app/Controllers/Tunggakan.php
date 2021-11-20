<?php

namespace App\Controllers;

use App\Models\TunggakanModel;


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
            'validation' => \Config\Services::validation(),
        ];
        // dd($data);

        return view('pages/tunggakan', $data);
    }
}
