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

    function setusul() {
        // validate
        $status = $this->request->getVar('status_usulan');

        if($status == 1){
            if (! $this->validate([
                'nik' => 'required',
                'status_usulan' => 'required|in_list[0,1]',
                'pendidikan_id' => 'required',
                'unor' => 'required'
            ])) {
                return redirect()->back()->with('message', 'Form harus lengkap');
            }
        }else{
            if (! $this->validate([
                'nik' => 'required',
                'alasan_tidak_diusulkan' => 'required',
            ])) {
                return $this->response->setJSON(['status'=>'error','message'=>'Form harus lengkap']);
            }
        }

        $nik = $this->request->getVar('nik');

        $model = new ParuhwaktuModel;

        // update field is_usul
        if($status == 1){
            $model->update($nik, [
                'is_usul' => 1,
                'rincian_tk_pendidikan' => $this->request->getVar('pendidikan_id'),
                'unit_penempatan_id' => $this->request->getVar('unor'),
                'unit_penempatan_nama' => $this->request->getVar('siasnname'),
            ]);
            return redirect()->back()->with('message', 'Peserta ditandai untuk diusulkan');
        }else{
            $model->update($nik, ['is_usul' => 0,'alasan_tolak'=> $this->request->getVar('alasan_tidak_diusulkan')]);
            return redirect()->back()->with('message', 'Peserta tidak diusulkan');
        }
    }

    function setparuhwaktu(){
        // POST https://api-siasn.bkn.go.id/perencanaan/formasi_paruh_waktu/update_paruh_waktu
        // status_usulan : 1
        // alasan_tidak_diusulkan : jenis_pengadaan
        // teknis :
        // pendidikan_id : 0E7FA32614D38672E060640AF1083075
        // unor_id : 46e51456-62ec-43c3-8bf9-9318b043afff
        // user_id : af241811-1ae5-4759-970e-4e22690b3397
        // jabatan_id :
        // data_ids : 3674066501920004
        // usul_sotk_id : d9f13001-ad65-412e-a129-d744b40acba8
        // jenis : P

        $client = service('curlrequest');

        // $form = $this->request->getVar();

        $response = $client->request('POST', 'https://api-siasn.bkn.go.id/perencanaan/formasi_paruh_waktu/update_paruh_waktu', [
            'headers' => [
                'Origin' => null,
                'referer' => 'https://perencanaan-siasn.bkn.go.id/pengelolaan/verval-perbaikan-update-rincian-formasi-menpan/d9f13001-ad65-412e-a129-d744b40acba8/3a6b38a7-ec6c-4faf-ad0c-7498208d72fb',
                'Cookie'     => '_ga=GA1.1.1409040940.1726985318; _ga_5GQ07M1DL1=GS1.1.1729584198.3.1.1729584220.0.0.0; _ga_NVRKS9CPBG=GS1.1.1729597976.14.0.1729597976.0.0.0; BIGipServerpool_prod_sscasn2024_kube=3154600970.47873.0000; 9760101acce488cfa3a2041bbb8bb77f=ac978f6657646bddd4c30510616f0898; JSESSIONID=58DFA0AA1F244DBD78450618BF158E12; OAuth_Token_Request_State=7b1d214e-89ab-4e1c-9245-c9e7618d642b; _ga_THXT2YWNHR=GS1.1.1730096260.14.0.1730096262.0.0.0',
            ],
            'form_params' => [
                'status_usulan' => 1,
                'alasan_tidak_diusulkan' => 'jenis_pengadaan',
                'pendidikan_id' => '0E7FA32614D38672E060640AF1083075',
                'unor_id' => '46e51456-62ec-43c3-8bf9-9318b043afff',
                'user_id' => 'af241811-1ae5-4759-970e-4e22690b3397',
                'jabatan_id' => '',
                'data_ids' => '3674066501920004',
                'usul_sotk_id' => 'd9f13001-ad65-412e-a129-d744b40acba8',
                'jenis' => 'P'
            ],
            'debug' => true,
            'verify' => false
        ]);

        $response = json_decode($response->getBody());

        return $this->response->setJSON($response);

        // respon
        /*
        {
            "respon_status": {
                "status": "SUCCESS",
                "code": 200,
                "message": "Data berhasil diperbarui"
            }
        }
        */
    }
}
