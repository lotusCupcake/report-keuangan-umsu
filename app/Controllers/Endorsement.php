<?php

namespace App\Controllers;

use App\Models\EndorsementModel;

class Endorsement extends BaseController
{
    protected $endorsementModel;
    public function __construct()
    {
        $this->endorsementModel = new EndorsementModel();
    }

    public function index()
    {
        $data = [
            'title' => "Endorsement",
            'appName' => "UMSU FM",
            'breadcrumb' => ['Home', 'Endorsement'],
            'endorse' => $this->endorsementModel,
            'validation' => \Config\Services::validation()
        ];
        return view('pages/endorsement', $data);
    }

    public function delete($id)
    {
        $endorsement = $this->endorsementModel->find($id);
        if (basename($endorsement->endorsmentFlayer) != "endorsement.png") {
            unlink('endorsements/' . basename($endorsement->endorsmentFlayer));
        }
        if ($this->endorsementModel->delete($id)) {
            session()->setFlashdata('success', 'Data Endorsement Berhasil Dihapus !');
        };
        return redirect()->to('endorsement');
    }

    public function add()
    {
        if (!$this->validate([
            'endorsmentFlayer' => [
                'rules' => 'max_size[endorsmentFlayer,5120]|mime_in[endorsmentFlayer,image/png]|is_image[endorsmentFlayer]|max_dims[endorsmentFlayer,960,1280]',
                'errors' => [
                    'max_size' => 'Ukuran gambar terlalu besar',
                    'mime_in' => 'Yang anda Pilih bukan gambar',
                    'is_image' => 'Yang anda Pilih bukan gambar',
                    'max_dims' => 'Dimensi gambar salah, gunakan 960px X 1280px'
                ]
            ],
            'endorsmentNama' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama Endorsement Harus Diisi',
                ]
            ],
            'endorsmentDeskripsi' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Deskripsi Endorsement Harus Diisi',
                ]
            ],
        ])) {
            return redirect()->to('endorsement')->withInput();
        }

        $fileFlayer = $this->request->getFile('endorsmentFlayer');


        if ($fileFlayer->getError() == 4) {
            $namaFile = 'endorsement.png';
        } else {
            $width = getimagesize($fileFlayer)[0];
            $height = getimagesize($fileFlayer)[1];

            if ($width != 960 && $height != 1280) {
                session()->setFlashdata('error', 'Dimensi gambar salah, gunakan 960px X 1280px');
                return redirect()->back();
            }
            $namaFile = $fileFlayer->getRandomName();
            $fileFlayer->move('endorsements', $namaFile);
        }

        $data = array(
            'endorsmentFlayer' => base_url('endorsements/' . $namaFile),
            'endorsmentNama' => $this->request->getPost('endorsmentNama'),
            'endorsmentTanggaAwal' => strtotime($this->request->getPost('endorsmentTanggaAwal')),
            'endorsmentTanggaAkhir' => strtotime($this->request->getPost('endorsmentTanggaAkhir')),
            'endorsmentDeskripsi' => $this->request->getPost('endorsmentDeskripsi'),
        );
        if ($this->endorsementModel->insert($data)) {
            session()->setFlashdata('success', 'Data Acara Berhasil Ditambah !');
            return redirect()->to('endorsement');
        }
    }

    public function edit($id)
    {
        if (!$this->validate([
            'endorsmentFlayer' => [
                'rules' => 'max_size[endorsmentFlayer,5120]|mime_in[endorsmentFlayer,image/png]|is_image[endorsmentFlayer]',
                'errors' => [
                    'max_size' => 'Ukuran gambar terlalu besar',
                    'mime_in' => 'Yang anda Pilih bukan gambar',
                    'is_image' => 'Yang anda Pilih bukan gambar',
                ]
            ],
            'endorsmentNama' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama Endorsement Harus Diisi',
                ]
            ],
            'endorsmentDeskripsi' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Deskripsi Endorsement Harus Diisi',
                ]
            ],
        ])) {
            return redirect()->to('endorsement')->withInput();
        }

        $fileFlayer = $this->request->getFile('endorsmentFlayer');

        if ($fileFlayer->getError() == 4) {
            $namaFile = $this->request->getPost('flayerLama');
        } else {

            $width = getimagesize($fileFlayer)[0];
            $height = getimagesize($fileFlayer)[1];

            if ($width != 960 && $height != 1280) {
                session()->setFlashdata('error', 'Dimensi gambar salah, gunakan 960px X 1280px');
                return redirect()->back();
            }
            $namaFile = $fileFlayer->getRandomName();
            $fileFlayer->move('endorsements', $namaFile);
            unlink('endorsements/' . $this->request->getPost('flayerLama'));
        }

        $data = array(
            'endorsmentFlayer' => base_url('endorsements/' . $namaFile),
            'endorsmentNama' => $this->request->getPost('endorsmentNama'),
            'endorsmentTanggaAwal' => strtotime($this->request->getPost('endorsmentTanggaAwal')),
            'endorsmentTanggaAkhir' => strtotime($this->request->getPost('endorsmentTanggaAkhir')),
            'endorsmentDeskripsi' => $this->request->getPost('endorsmentDeskripsi'),
        );

        if ($this->endorsementModel->update($id, $data)) {
            session()->setFlashdata('success', 'Data Acara Berhasil Diupdate !');
            return redirect()->to('endorsement');
        }
    }
}
