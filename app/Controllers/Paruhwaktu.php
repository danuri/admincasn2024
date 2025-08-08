<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ParuhwaktuModel;

class Paruhwaktu extends BaseController
{
    public function index()
    {
        $model = new ParuhwaktuModel;

        $data['peserta'] = $model->where(['owner'=>session('kodesatker4')])->findAll();

        return view('paruhwaktu/index', $data);
    }
}
