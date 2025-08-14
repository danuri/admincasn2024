<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ParuhwaktuModel;

class Paruhwaktu extends BaseController
{
    public function index()
    {
        $model = new ParuhwaktuModel;

        $data['peserta'] = $model->where(['owner'=>session('kodesatker4')])->findAll();

        return view('paruhwaktu/index', $data);
    }

    function setusul($id,$status) {
        $model = new ParuhwaktuModel;

        // update field is_usul
        if($status == 1){
            $model->update($id, ['is_usul' => 1]);
            return $this->response->setJSON(['status'=>'success','message'=>'Peserta ditandai untuk diusulkan']);
        }else{
            $model->update($id, ['is_usul' => 0]);
            return $this->response->setJSON(['status'=>'success','message'=>'Peserta ditandai tidak diusulkan']);
        }
    }
}
