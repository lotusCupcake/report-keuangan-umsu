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
        $response = $this->curl->request("get", "https://api.umsu.ac.id/home/slider", [
			"headers" => [
				"Accept" => "application/json"
			]
		]);
        
        $data = [
            'title' => "Tunggakan",
            'appName' => "UMSU FM",
            'breadcrumb' => ['Home', 'Tunggakan'],
            'tunggakan' => json_decode($response->getBody()),
            'validation' => \Config\Services::validation(),
        ];


        return view('pages/tunggakan', $data);
    }
}
