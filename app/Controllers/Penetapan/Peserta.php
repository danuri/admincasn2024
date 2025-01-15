<?php

namespace App\Controllers\Penetapan;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PesertaModel;

class Peserta extends BaseController
{
    public function index()
    {
        // $model = new PesertaModel;
        // $data['peserta'] = $model->where(['kode_satker'=>session('lokasi')])->where(['status_akhir'=>'P/L'])->orWhere(['status_akhir'=>'P/L-E2'])->orWhere(['status_akhir'=>'P/L-U1'])->findAll();
        $db = db_connect();
        $lokasi = session('lokasi');
        $data['peserta'] = $db->query("SELECT * FROM peserta WHERE kode_satker = '$lokasi' AND (`status_akhir` = 'P/L' OR `status_akhir` = 'P/L-E2' OR `status_akhir` = 'P/L-U1')")->getResult();
        $data['resume'] = $db->query("SELECT formasi, COUNT(*) as jumlah FROM peserta WHERE kode_satker = '30130000' AND (`status_akhir` = 'P/L' OR `status_akhir` = 'P/L-E2' OR `status_akhir` = 'P/L-U1') GROUP BY formasi")->getResult();
        return view('penetapan/peserta', $data);
    }
}
