<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\CrudModel;

class Sktt extends BaseController
{
    public function index()
    {
        //
    }

    function tilok() {
        $model = new CrudModel;
        $data['tilok'] = $model->getResult('tilok_pppkt2',['kelola'=>session('lokasi')]);

        return view('sktt/tilok', $data);
    }
}
