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

    public function pppkteknis()
    {
      $model  = new CrudModel();
      $data['stat']   = $model->getResult('statistik_pelamar_pppk_teknis',['verifikator'=>session('nip')]);
      $data['jpendaftar'] = $model->getCount('statistik_pelamar_pppk_teknis','jml_pendaftar',['verifikator'=>session('nip')]);
      $data['jsubmit'] = $model->getCount('statistik_pelamar_pppk_teknis','jml_submit',['verifikator'=>session('nip')]);
      $data['jms'] = $model->getCount('statistik_pelamar_pppk_teknis','jml_ms',['verifikator'=>session('nip')]);
      $data['jtms'] = $model->getCount('statistik_pelamar_pppk_teknis','jml_tms',['verifikator'=>session('nip')]);
      return view('pelamar',$data);
    }
}
