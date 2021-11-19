<?php

namespace App\Controllers;

use App\Models\PenyiarModel;
use App\Models\AcaraModel;
use App\Models\EndorsementModel;
use App\Models\SettingModel;
use \Myth\Auth\Models\UserModel;


class Home extends BaseController
{
    protected $settingModel;
    protected $penyiarModel;
    protected $acaraModel;
    protected $endorsementModel;
    protected $users;
    public function __construct()
    {
        $this->settingModel = new SettingModel();
        $this->penyiarModel = new PenyiarModel();
        $this->acaraModel = new AcaraModel();
        $this->endorsementModel = new EndorsementModel();
    }

    public function index()
    {
        $data = [
            'title' => "Home",
            'appName' => "UMSU",
            'breadcrumb' => ['Home', 'Dashboard'],
            'jumlah_penyiar' => $this->penyiarModel,
            'acara' => $this->acaraModel,
            'jumlah_endors' => $this->endorsementModel,
            'setting' => $this->settingModel,
            'validation' => \Config\Services::validation(),
        ];
        return view('pages/home', $data);
    }

    public function streamEdit($id)
    {
        if (!$this->validate([
            'streamAdd' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Stream Add Harus Diisi',
                ]
            ],
        ])) {
            return redirect()->to('home')->withInput();
        }

        $data = array(
            'configValue' => $this->request->getPost('streamAdd'),
        );

        if ($this->settingModel->update($id, $data)) {
            session()->setFlashdata('success', 'Berhasil Diubah !');
            return redirect()->to('home');
        }
    }

    public function livechatEdit($id)
    {
        if (!$this->validate([
            'livechatAdd' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Live chat Add Harus Diisi',
                ]
            ],
        ])) {
            return redirect()->to('home')->withInput();
        }

        $data = array(
            'configValue' => $this->request->getPost('livechatAdd'),
        );

        if ($this->settingModel->update($id, $data)) {
            session()->setFlashdata('success', 'Berhasil Diubah !');
            return redirect()->to('home');
        }
    }

    public function whatsappEdit($id)
    {
        if (!$this->validate([
            'whatsappAdd' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Live chat Add Harus Diisi',
                ]
            ],
        ])) {
            return redirect()->to('home')->withInput();
        }

        $data = array(
            'configValue' => $this->request->getPost('whatsappAdd'),
        );

        if ($this->settingModel->update($id, $data)) {
            session()->setFlashdata('success', 'Berhasil Diubah !');
            return redirect()->to('home');
        }
    }

    public function logoAppEdit($id)
    {
        if (!$this->validate([
            'settingLogoApp' => [
                'rules' => 'max_size[settingLogoApp,5120]|mime_in[settingLogoApp,image/png]|is_image[settingLogoApp]',
                'errors' => [
                    'max_size' => 'Ukuran gambar terlalu besar',
                    'mime_in' => 'Yang anda Pilih bukan gambar',
                    'is_image' => 'Yang anda Pilih bukan gambar'
                ]
            ],
        ])) {
            return redirect()->to('home')->withInput();
        }

        $fileFlayer = $this->request->getFile('settingLogoApp');
        $width = getimagesize($fileFlayer)[0];
        $height = getimagesize($fileFlayer)[1];

        if ($width != 512 && $height != 512) {
            session()->setFlashdata('error', 'Dimensi gambar salah, gunakan 512px X 512px');
            return redirect()->back();
        }

        if ($fileFlayer->getError() == 4) {
            $namaFile = $this->request->getPost('logoAppLama');
        } else {
            $namaFile = $fileFlayer->getRandomName();
            $fileFlayer->move('settings', $namaFile);
            unlink('settings/' . $this->request->getPost('logoAppLama'));
        }

        $data = array(
            'configValue' => base_url('settings/' . $namaFile),
        );

        if ($this->settingModel->update($id, $data)) {
            session()->setFlashdata('success', 'Berhasil Diubah !');
            return redirect()->to('home');
        }
    }

    public function logoRuangdengarEdit($id)
    {
        if (!$this->validate([
            'settingLogoRuangdengar' => [
                'rules' => 'max_size[settingLogoRuangdengar,5120]|mime_in[settingLogoRuangdengar,image/png]|is_image[settingLogoRuangdengar]',
                'errors' => [
                    'max_size' => 'Ukuran gambar terlalu besar',
                    'mime_in' => 'Yang anda Pilih bukan gambar',
                    'is_image' => 'Yang anda Pilih bukan gambar'
                ]
            ],
        ])) {
            return redirect()->to('home')->withInput();
        }

        $fileFlayer = $this->request->getFile('settingLogoRuangdengar');
        $width = getimagesize($fileFlayer)[0];
        $height = getimagesize($fileFlayer)[1];

        if ($width != 512 && $height != 512) {
            session()->setFlashdata('error', 'Dimensi gambar salah, gunakan 512px X 512px');
            return redirect()->back();
        }

        if ($fileFlayer->getError() == 4) {
            $namaFile = $this->request->getPost('logoRuangdengarLama');
        } else {
            $namaFile = $fileFlayer->getRandomName();
            $fileFlayer->move('settings', $namaFile);
            unlink('settings/' . $this->request->getPost('logoRuangdengarLama'));
        }

        $data = array(
            'configValue' => base_url('settings/' . $namaFile),
        );

        if ($this->settingModel->update($id, $data)) {
            session()->setFlashdata('success', 'Berhasil Diubah !');
            return redirect()->to('home');
        }
    }

    public function logoHomescreenEdit($id)
    {
        if (!$this->validate([
            'settingLogoHomescreen' => [
                'rules' => 'max_size[settingLogoHomescreen,5120]|mime_in[settingLogoHomescreen,image/png]|is_image[settingLogoHomescreen]',
                'errors' => [
                    'max_size' => 'Ukuran gambar terlalu besar',
                    'mime_in' => 'Yang anda Pilih bukan gambar',
                    'is_image' => 'Yang anda Pilih bukan gambar'
                ]
            ],
        ])) {
            return redirect()->to('home')->withInput();
        }

        $fileFlayer = $this->request->getFile('settingLogoHomescreen');
        $width = getimagesize($fileFlayer)[0];
        $height = getimagesize($fileFlayer)[1];

        if ($width != 512 && $height != 512) {
            session()->setFlashdata('error', 'Dimensi gambar salah, gunakan 512px X 512px');
            return redirect()->back();
        }

        if ($fileFlayer->getError() == 4) {
            $namaFile = $this->request->getPost('logoHomescreenLama');
        } else {
            $namaFile = $fileFlayer->getRandomName();
            $fileFlayer->move('settings', $namaFile);
            unlink('settings/' . $this->request->getPost('logoHomescreenLama'));
        }

        $data = array(
            'configValue' => base_url('settings/' . $namaFile),
        );

        if ($this->settingModel->update($id, $data)) {
            session()->setFlashdata('success', 'Berhasil Diubah !');
            return redirect()->to('home');
        }
    }

    public function flayerDefaultsiaranEdit($id)
    {
        if (!$this->validate([
            'settingFlayerDefaultsiaran' => [
                'rules' => 'max_size[settingFlayerDefaultsiaran,5120]|mime_in[settingFlayerDefaultsiaran,image/png]|is_image[settingFlayerDefaultsiaran]',
                'errors' => [
                    'max_size' => 'Ukuran gambar terlalu besar',
                    'mime_in' => 'Yang anda Pilih bukan gambar',
                    'is_image' => 'Yang anda Pilih bukan gambar'
                ]
            ],
        ])) {
            return redirect()->to('home')->withInput();
        }

        $fileFlayer = $this->request->getFile('settingFlayerDefaultsiaran');

        if ($fileFlayer->getError() == 4) {
            $namaFile = $this->request->getPost('FlayerDefaultsiaranLama');
        } else {
            $namaFile = $fileFlayer->getRandomName();
            $fileFlayer->move('settings', $namaFile);
            unlink('settings/' . $this->request->getPost('FlayerDefaultsiaranLama'));
        }

        $data = array(
            'configValue' => base_url('settings/' . $namaFile),
        );

        if ($this->settingModel->update($id, $data)) {
            session()->setFlashdata('success', 'Berhasil Diubah !');
            return redirect()->to('home');
        }
    }
}
