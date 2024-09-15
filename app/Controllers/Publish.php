<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DokumenModel;
use App\Models\UploadModel;

class Publish extends BaseController
{
    public function document($id)
    {
        $model = new DokumenModel;
        $data['dokumen'] = $model->find($id);
        $data['unggahan'] = $model->unggahan($id);

        return view('public/unggahan', $data);
    }
}
