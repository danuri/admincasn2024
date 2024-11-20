<?php

namespace App\Controllers\Skb;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\CrudModel;

class Lokasi extends BaseController
{
    public function index()
    {
        $model = new CrudModel;
        $data['lokasi'] = $model->geLokasiSatker(session('lokasi'));
        return view('skb/lokasi', $data);
    }
}
