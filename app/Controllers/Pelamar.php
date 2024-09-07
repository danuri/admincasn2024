<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CrudModel;

class Pelamar extends BaseController
{
    public function index(): string
    {
        $model  = new CrudModel();
        $data['stat']   = $model->getResult('statistik_pelamar',['lok_formasi_nm'=>session('lokasi_nama')]);
        $data['jpendaftar'] = $model->getCount('statistik_pelamar','jml_pendaftar',['lok_formasi_nm'=>session('lokasi_nama')]);
        $data['jsubmit'] = $model->getCount('statistik_pelamar','jml_submit',['lok_formasi_nm'=>session('lokasi_nama')]);
        $data['jms'] = $model->getCount('statistik_pelamar','jml_ms',['lok_formasi_nm'=>session('lokasi_nama')]);
        $data['jtms'] = $model->getCount('statistik_pelamar','jml_tms',['lok_formasi_nm'=>session('lokasi_nama')]);
        return view('pelamar',$data);
    }
}
