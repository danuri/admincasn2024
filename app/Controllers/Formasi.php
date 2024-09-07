<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CrudModel;

class Formasi extends BaseController
{
  public function index(): string
  {
    $model  = new CrudModel();
    $data['formasi']   = $model->getResult('temp_formasi',['lokasi_formasi_kode'=>session('lokasi')]);
    return view('formasi',$data);
  }
}
