<?php

namespace App\Controllers\Skb;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Jadwal extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect('default', false);
        $satker = session('lokasi');
        $data['peserta'] = $db->query("SELECT
                                            peserta.*,
                                            zooms.*
                                            FROM
                                            peserta
                                            INNER JOIN zooms ON peserta.nopeserta = zooms.nopeserta
                                            WHERE peserta.kode_satker='$satker'")->getResult();
        $data['accounts'] = $db->query("SELECT DISTINCT(email_praktik) FROM zooms
                                            WHERE kode_satker='$satker'")->getResult();
        return view('skb/jadwal', $data);
    }

    public function importjadwal_praktik() {
        
    }
}
