<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PendidikanModel;
use App\Models\UnorModel;

class Ajax extends BaseController
{
    public function index()
    {
        //
    }

    public function searchunor()
    {
      $model = new UnorModel;
      $search = $this->request->getVar('search');

      $data = $model->like('unor_nama', $search, 'both')->findAll();
      return $this->response->setJSON($data);
    }
}
