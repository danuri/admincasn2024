<?php

namespace App\Controllers\Penetapan;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\FormasiModel;

class Formasi extends BaseController
{
    public function index()
    {
        $model = new FormasiModel;
        $data['formasi'] = $model->where(['kode_satker'=>session('lokasi')])->findAll();

        return view('penetapan/formasi', $data);
    }

    public function rekapitulasi()
    {
        $db = db_connect();
        $lokasi = session('lokasi');
        $data['rekapitulasi'] = $db->query("SELECT formasi, COUNT(*) as jumlah,
                                    (SELECT SUM(jumlah) FROM formasi WHERE jabatan_sscasn = formasi AND kode_satker='$lokasi') as jumlah_formasi
                                    FROM peserta WHERE kode_satker = '$lokasi' AND (`status_akhir` = 'P/L' OR `status_akhir` = 'P/L-E2' OR `status_akhir` = 'P/L-U1') GROUP BY formasi")->getResult();
        return view('penetapan/rekapitulasi', $data);
    }
}
