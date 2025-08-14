<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Pppkt2Model;
use App\Models\UserModel;

class Pppk extends BaseController
{
    public function index()
    {
        //
    }

    function optimalisasi() {
        $model = new Pppkt2Model;
        $data['satker'] = $model->getSatker();
        return view('admin/pppk/optimalisasi', $data);
    }

    function optimalisasidetail($kode_satker) {
        $model = new Pppkt2Model;
        $umodel = new UserModel;

        $data['peserta'] = $model->where(['kode_satker_asal' => $kode_satker])->findAll();
        $data['user'] = $umodel->where(['kode_lokasi' => $kode_satker])->first();
        return view('admin/pppk/optimalisasi_detail', $data);
    }
}
