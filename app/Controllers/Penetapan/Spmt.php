<?php

namespace App\Controllers\Penetapan;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\CrudModel;
class Spmt extends BaseController
{
    public function index()
    {
        
        $model = new CrudModel;
        $data['peserta'] = $model->getResult('peserta', ['kode_satker' => session('lokasi'),'usul_nip !=' => '']);
        return view('penetapan/spmt', $data);
    }
}
