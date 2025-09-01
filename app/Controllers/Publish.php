<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DokumenModel;
use App\Models\UploadModel;
use App\Models\CrudModel;

class Publish extends BaseController
{
    public function document($id)
    {
        $model = new DokumenModel;
        $data['dokumen'] = $model->find($id);
        $data['unggahan'] = $model->unggahan($id,false);

        return view('public/unggahan', $data);
    }

    function paruhwaktu() {
        $model = new CrudModel;
        $data['monitoring'] = $model->monitoringParuhwaktu();
        $data['sudah'] = $model->jumlahMapping()->jumlah;
        $data['belum'] = (7110 - $data['sudah']);

        return view('public/paruhwaktu', $data);
    }

    function monitoring() {
        $model = new CrudModel;
        $data['monitoring'] = $model->monitoringtahap2();

        return view('public/tahap2', $data);
    }
}
