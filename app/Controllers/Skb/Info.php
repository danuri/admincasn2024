<?php

namespace App\Controllers\Skb;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\SatkerModel;
use App\Models\CrudModel;

class Info extends BaseController
{
    public function index() {
        $satker = session('lokasi');
        $model = new CrudModel;
        $data['satker'] = $model->getRow('satker', ['kode_satker' => $satker]);
        return view('skb/info', $data);
    }

    public function update()
    {
        $satker = session('lokasi');
        // Load validasi service
        $validation = \Config\Services::validation();

        // Define validation rules
        $rules = [
            'kontak' => 'required',
            'informasi' => 'required'
        ];

        if ($this->validate($rules))
        {
            // Jika validasi berhasil, proses data di sini
            $satkerModel = new SatkerModel();

            $data = array(
                'kontak' => $this->request->getPost('kontak'),
                'informasi' => $this->request->getPost('informasi')
            );

            $where = array (
                'kode_satker' => $satker,
            );
            $satkerModel->set($data)->where($where)->update();
            session()->setFlashdata('message', 'Info Satker telah diubah');
            return redirect()->to('skb/info');
        } else {
            //session()->setFlashdata('message', $validation->getErrors());
            // Jika validasi gagal, ambil error dan tampilkan di view
            $data['validation'] = $validation;
            $model = new CrudModel;
            $data['satker'] = $model->getRow('satker', ['kode_satker' => $satker]);
            return view('skb/info', $data);
        }
    }
}
