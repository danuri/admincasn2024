<?php

namespace App\Controllers\Skb;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\CrudModel;

class Peserta extends BaseController
{
    public function index()
    {
        $kodesatker = session('lokasi');
        $crud = new CrudModel();
        $db = \Config\Database::connect('default', false);
        $data['peserta'] = $crud->getResult('peserta', array('kode_satker'=>$kodesatker));
        $data['lokasi'] = $db->query("SELECT a.lokasi_titik, a.lokasi_kabupaten, a.lokasi_provinsi, b.tilok, b.alamat, b.maps, b.kontak_panitia, COUNT(a.nik) AS jumlah FROM peserta a
                                            LEFT JOIN lokasi_titik b ON b.lokasi_kode=a.lokasi_kode
                                            WHERE a.kode_satker='$kodesatker'
                                            GROUP BY a.lokasi_titik,a.lokasi_kabupaten, a.lokasi_provinsi")->getResult();

        // $data['jabatans'] = $this->crud->preport_jabatan($satker->kode_satker_bkn_new);
        //$this->load->tpl('skb/peserta', $data);
        return view('skb/peserta', $data);
    }
}
