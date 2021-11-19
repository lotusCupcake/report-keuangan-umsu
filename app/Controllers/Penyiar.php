<?php

namespace App\Controllers;

use App\Models\PenyiarModel;


class Penyiar extends BaseController
{
    protected $penyiarModel;
    public function __construct()
    {
        $this->penyiarModel = new PenyiarModel();
    }


    public function index()
    {
        $data = [
            'title' => "Penyiar",
            'appName' => "UMSU FM",
            'breadcrumb' => ['Home', 'Penyiar'],
            'penyiar' => $this->penyiarModel->findAll(),
            'validation' => \Config\Services::validation(),
        ];

        return view('pages/penyiar', $data);
    }

    public function delete($id)
    {
        if ($this->penyiarModel->delete($id)) {
            session()->setFlashdata('success', 'Data Penyiar Berhasil Dihapus !');
        };
        return redirect()->to('penyiar');
    }

    public function add()
    {
        if (!$this->validate([
            'penyiarNama' => [
                'rules' => 'required|is_unique[penyiar.penyiarNama]',
                'errors' => [
                    'required' => 'Nama Penyiar Harus Diisi',
                    'is_unique' => 'Nama Penyiar Sudah terdaftar',
                ]
            ],
            'isHuman' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tipe Penyiar Harus Dipilih',
                ]
            ],
        ])) {
            return redirect()->to('penyiar')->withInput();
        }


        $data = array(
            'penyiarNama' => $this->request->getPost('penyiarNama'),
            'penyiarStatus' => $this->request->getPost('isHuman')
        );

        if ($this->penyiarModel->insert($data)) {
            session()->setFlashdata('success', 'Data Penyiar Berhasil Ditambah !');
            return redirect()->to('penyiar');
        }
    }

    public function edit($id)
    {
        //validation
        if (!$this->validate([
            'penyiarNama' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama Penyiar Harus Diisi',
                ]
            ],
        ])) {
            return redirect()->to('penyiar')->withInput();
        }
        $data = array(
            "penyiarNama" => $this->request->getPost('penyiarNama'),
            "penyiarStatus" => $this->request->getPost('isHuman')
        );

        if ($this->penyiarModel->update($id, $data)) {;
            session()->setFlashdata('success', 'Data Penyiar Berhasil Diupdate !');
            return redirect()->to('penyiar');
        }
    }
}
