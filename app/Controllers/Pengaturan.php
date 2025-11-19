<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\UserModel;
class Pengaturan extends BaseController
{
    public function index(): string
    {
        $model = new UserModel();
        $data['user'] = $model->where('kode_satker', session('lokasi'))->first();
        return view('pengaturan', $data);
    }

    function save() {
        

        // Logic to save settings goes here
        $model = new UserModel();
        $data = [
            'is_sdm' => $this->request->getPost('isplt'),
            'tte_nip' => $this->request->getPost('ttenip'),
            'tte_nik' => $this->request->getPost('ttenik'),
            'tte_nama' => $this->request->getPost('ttenama'),
            'tte_jabatan' => $this->request->getPost('ttejabatan')
        ];

        // if passphrase is set
        $ttepass = $this->request->getPost('ttepass');
        if(!empty($ttepass)) {
            $data['tte_pass'] = setencrypt($ttepass);
        }

        // Upload kop surat
        $file = $this->request->getFile('filepond');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = 'kop_surat_'.session('lokasi').'.'.$file->getExtension();
            $file->move('./downloads/kop_surat/', $newName, true);

            $data['kop_surat'] = $newName;
        }

        $model->where('kode_satker', session('lokasi'))->set($data)->update();
        return redirect()->back()->with('message', 'Pengaturan berhasil disimpan');
    }

}