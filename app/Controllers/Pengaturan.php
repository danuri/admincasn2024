<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\UserModel;
class Pengaturan extends BaseController
{
    public function index(): string
    {
        return view('pengaturan');
    }

    function save() {
        // Logic to save settings goes here
        $model = new UserModel();
        $data = [
            'tte_nip' => $this->request->getPost('nip'),
            'tte_nik' => $this->request->getPost('nik'),
            'tte_nama' => $this->request->getPost('nama'),
            'tte_jabatan' => $this->request->getPost('jabatan'),
        ];
        $model->where('kode_satker', session('lokasi'))->set($data)->update();
        redirect()->back()->with('message', 'Pengaturan berhasil disimpan');
    }

}
