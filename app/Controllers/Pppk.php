<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Pppkt2Model;

class Pppk extends BaseController
{
    public function index()
    {
        //
    }

    function peserta() {
        $model = new Pppkt2Model;
        $data['peserta'] = $model->where(['kode_satker_asal'=>session('lokasi')])->findAll();
        return view('pppk/peserta', $data);
    }

    function pesertal2in() {
        $model = new Pppkt2Model;
        $data['peserta'] = $model->where(['penempatan_kode_satker'=>session('lokasi')])->like('status', 'L-2', 'before')->findAll();
        return view('pppk/peserta_l2in', $data);
    }

    function pesertal2out() {
        $model = new Pppkt2Model;
        $data['peserta'] = $model->where(['kode_satker_asal'=>session('lokasi')])->like('status', 'L-2', 'before')->findAll();
        return view('pppk/peserta_l2out', $data);
    }

    function usuloptimalisasi() {
        return view('pppk/usuloptimasi');
    }
}
