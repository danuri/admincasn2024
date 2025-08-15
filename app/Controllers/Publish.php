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
        $data['unggahan'] = $model->unggahan($id);

        return view('public/unggahan', $data);
    }

    function paruhwaktu() {
        $model = new CrudModel;
        $data['monitoring'] = $model->monitoringParuhwaktu();

        return view('public/paruhwaktu', $data);
    }
}
