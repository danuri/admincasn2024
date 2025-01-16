<?php

namespace App\Controllers;
use App\Models\FormasiModel;
use App\Models\FormasiRinciModel;
class Home extends BaseController
{
    public function index(): string
    {
        return view('index');
    }

    function generateformasi()
    {
        $rinci = new FormasiRinciModel;
        $model = new FormasiModel;
        $formasi = $model->findAll();

        foreach($formasi as $row){
            $param = [
                'jenis_jabatan' => $row->jenis_jabatan,
                'jabatan' => $row->jabatan,
                'sub_jabatan' => $row->sub_jabatan,
                'jabatan_sscasn' => $row->jabatan_sscasn,
                'lokasi' => $row->lokasi,
                'pendidikan' => $row->pendidikan,
                'jumlah' => 1,
                'satker' => $row->satker,
                'kode_satker' => $row->kode_satker,
            ];

            for ($i=0; $i < $row->jumlah; $i++) { 
                $rinci->insert($param);
            }
        }
    }
}
