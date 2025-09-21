<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\SuratModel;
use App\Models\SuratparuhwaktuModel;
use Aws\S3\S3Client;

class Surat extends BaseController
{
    public function index()
    {
        $model = new SuratModel;
        $data['surat'] = $model->where(['kode_satker' => session('lokasi')])->findAll();
        return view('surat/index', $data);
    }

    function input($id) {
        $id = decrypt($id);
        $model = new SuratModel;
        $data['surat'] = $model->find($id);

        if($data['surat']->jenis_surat == 'd5ba481b59fd483d95d42fc0d311390b') {
            $pwm = new SuratparuhwaktuModel;
            $data['paruhwaktu'] = $pwm->where(['surat_id'=>$id])->findAll();

            return view('surat/form_paruhwaktu', $data);
        }
    }

    function saveinput() {
        $model = new SuratparuhwaktuModel;
        // insert data
        $data = [
            'surat_id' => $this->request->getVar('surat_id'),
            'nik' => $this->request->getVar('nik'),
            'nama' => $this->request->getVar('nama'),
            'pendidikan' => $this->request->getVar('pendidikan'),
            'jabatan' => $this->request->getVar('jabatan'),
            'lokasi_id' => $this->request->getVar('unor'),
            'lokasi' => $this->request->getVar('siasnname'),
            'keterangan' => $this->request->getVar('keterangan'),
        ];

        $model->insert($data);
        return redirect()->back()->with('message', 'Data berhasil disimpan');
    }

    function inputdelete($id) {
        $id = decrypt($id);
        $model = new SuratparuhwaktuModel;
        $model->delete($id);
        return redirect()->back()->with('message', 'Data berhasil dihapus');
    }

    function save() {
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
            return redirect()->back()->with('message', $this->validator->getErrors()['dokumen']);
  		}

      $model = new SuratModel();

      $file_name = $_FILES['dokumen']['name'];
      $ext = pathinfo($file_name, PATHINFO_EXTENSION);

      $kodesatker = session('lokasi');
      $jenis = $this->request->getVar('jenis');

      $file_name = 'surat_'.random_int(1000, 9999).'_'.$jenis.'.'.$kodesatker.'.'.$ext;
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
        $data = [
                'jenis_surat' => $jenis,
                'no_surat' => $this->request->getVar('no_surat'),
                'perihal' => $this->request->getVar('perihal'),
                'penandatangan' => $this->request->getVar('penandatangan'),
                'tanggal_surat' => $this->request->getVar('tanggal_surat'),
                'kode_satker' => session('lokasi'),
                'satker' => session('satker4'),
                'status' => 0,
                'lampiran' => $file_name
        ];

          $model->insert($data);

        return redirect()->back()->with('message', 'Surat telah dibuat');
      }else{
        return redirect()->back()->with('message', 'Surat gagal dibuat');
    }
}

    function submit($id) {
        $id = decrypt($id);
        
        $model = new SuratModel;
        $update = $model->update($id,['status'=>1]);
        return redirect()->back()->with('message', 'Surat usul berhasil dikiirm.');
    }
}
