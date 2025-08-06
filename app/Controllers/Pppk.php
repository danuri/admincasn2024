<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Pppkt2Model;
use App\Models\UserModel;
use Aws\S3\S3Client;

class Pppk extends BaseController
{
    public function index()
    {
        //
    }

    function peserta() {
        $model = new Pppkt2Model;
        $data['peserta'] = $model->where(['kode_satker_asal'=>session('lokasi')])->findAll();
        return view('pppk/peserta', $data);
    }

    function pesertal2in() {
        $model = new Pppkt2Model;
        $data['peserta'] = $model->where(['penempatan_kode_satker'=>session('lokasi')])->like('status', 'L-2', 'before')->findAll();
        return view('pppk/peserta_l2in', $data);
    }

    function pesertal2out() {
        $model = new Pppkt2Model;
        $data['peserta'] = $model->where(['kode_satker_asal'=>session('lokasi')])->like('status', 'L-2', 'before')->findAll();
        return view('pppk/peserta_l2out', $data);
    }

    function usuloptimalisasi() {
        $model = new Pppkt2Model;
        $umodel = new UserModel;
        $data['user'] = $umodel->where(['kode_satker'=>session('lokasi')])->first();
        $data['peserta'] = $model->where(['kode_satker_asal'=>session('lokasi')])->like('status', 'L-2', 'before')->findAll();
        if($data['user']->pppk_submit == 1){
          return view('pppk/usuloptimalisasi_view', $data);
        }else{
          return view('pppk/usuloptimalisasi', $data);
        }
    }

    public function uploadsk()
    {
      if (!$this->validate([
			'dokumen' => [
				'rules' => 'uploaded[dokumen]|ext_in[dokumen,pdf,PDF]|max_size[dokumen,2048]',
				'errors' => [
					'uploaded' => 'Harus Ada File yang diupload',
					'mime_in' => 'File Extention Harus Berupa pdf',
					'max_size' => 'Ukuran File Maksimal 2 MB'
				]
			]
  		])) {
  			// session()->setFlashdata('error', $this->validator->listErrors());
  			// return redirect()->back()->withInput();
        return $this->response->setJSON(['status'=>'error','message'=>$this->validator->getErrors()['dokumen']]);
  		}

      $model = new Pppkt2Model();

      $file_name = $_FILES['dokumen']['name'];
      $ext = pathinfo($file_name, PATHINFO_EXTENSION);

      $nopeserta = $this->request->getVar('nopeserta');
      $nopeserta = decrypt($nopeserta);

      $file_name = 'sk.'.$nopeserta.'.'.$ext;
      $temp_file_location = $_FILES['dokumen']['tmp_name'];

      $s3 = new S3Client([
        'region'  => 'us-east-1',
        'endpoint' => 'https://ropeg.kemenag.go.id:9000/',
        'use_path_style_endpoint' => true,
        'version' => 'latest',
        'credentials' => [
          'key'    => "PkzyP2GIEBe8z29xmahI",
          'secret' => "voNVqTilX2iux6u7pWnaqJUFG1414v0KTaFYA0Uz",
        ],
        'http'    => [
            'verify' => false
        ]
      ]);

      $result = $s3->putObject([
        'Bucket' => 'pengadaan',
        'Key'    => 'pppk/'.$file_name,
        'SourceFile' => $temp_file_location,
        'ContentType' => 'application/pdf'
      ]);

      $url = $result->get('ObjectURL');


      if($url){

        $model
            ->where(['nopeserta' => $nopeserta])
            ->set(['surat_keterangan' => $file_name])
            ->update();
        return $this->response->setJSON(['status'=>'success','message'=>'Dokumen telah diunggah','info'=>'<a href="https://ropeg.kemenag.go.id:9000/pengadaan/pppk/'.$file_name.'" target="_blank">Lihat</a>']);
      }else{
        return $this->response->setJSON(['status'=>'error','message'=>'Dokumen gagal diunggah']);
      }
    }

    function uploaddok($jenis) {
        if (!$this->validate([
			'dokumen' => [
				'rules' => 'uploaded[dokumen]|ext_in[dokumen,pdf,PDF]|max_size[dokumen,2048]',
				'errors' => [
					'uploaded' => 'Harus Ada File yang diupload',
					'mime_in' => 'File Extention Harus Berupa pdf',
					'max_size' => 'Ukuran File Maksimal 2 MB'
				]
			]
  		])) {
  			// session()->setFlashdata('error', $this->validator->listErrors());
  			// return redirect()->back()->withInput();
        return $this->response->setJSON(['status'=>'error','message'=>$this->validator->getErrors()['dokumen']]);
  		}

      $model = new UserModel();

      $file_name = $_FILES['dokumen']['name'];
      $ext = pathinfo($file_name, PATHINFO_EXTENSION);

      $kodesatker = session('lokasi');

      $file_name = $jenis.'.'.$kodesatker.'.'.$ext;
      $temp_file_location = $_FILES['dokumen']['tmp_name'];

      $s3 = new S3Client([
        'region'  => 'us-east-1',
        'endpoint' => 'https://ropeg.kemenag.go.id:9000/',
        'use_path_style_endpoint' => true,
        'version' => 'latest',
        'credentials' => [
          'key'    => "PkzyP2GIEBe8z29xmahI",
          'secret' => "voNVqTilX2iux6u7pWnaqJUFG1414v0KTaFYA0Uz",
        ],
        'http'    => [
            'verify' => false
        ]
      ]);

      $result = $s3->putObject([
        'Bucket' => 'pengadaan',
        'Key'    => 'pppk/'.$file_name,
        'SourceFile' => $temp_file_location,
        'ContentType' => 'application/pdf'
      ]);

      $url = $result->get('ObjectURL');


      if($url){

        if($jenis == 'surat'){
          $model
              ->where(['kode_satker' => $kodesatker])
              ->set(['pppk_surat' => $file_name])
              ->update();
        }else{
          $model
              ->where(['kode_satker' => $kodesatker])
              ->set(['pppk_sptjm' => $file_name])
              ->update();
              
        }
        return redirect()->back()->with('message', 'Dokumen telah diunggah');
      }else{
        return redirect()->back()->with('message', 'Dokumen gagal diunggah');
      }
    }

    function submit() {
        $model = new UserModel;

        $update = $model->where(['kode_satker'=>session('lokasi')])->set(['pppk_submit'=>1])->update();
        return redirect()->back()->with('message', 'Usulan telah disampaikan');
    }
}
