<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Sanggah extends BaseController
{
    public function index()
    {
        $db = db_connect();
        $lokasi = session('lokasi');
        $data['peserta'] = $db->query("select * from peserta_sanggah where lokasi_kode = '$lokasi'")
                            ->getResult();
        return view('sanggah/sanggah_nilai', $data);
    }
}
