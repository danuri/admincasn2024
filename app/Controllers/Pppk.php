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

    function cekdosen($id,$val) {
        $model = new Pppkt2Model;

        $update = $model->where(['nopeserta'=>$id])->set(['is_dosen'=>$val])->update();
        return $this->response->setJSON(['status'=>'success','message'=>'Data telah diubah']);
    }

    function sinkron() {
      $model = new Pppkt2Model;
      $data= $model->where(['kode_satker_asal'=>session('lokasi')])->findAll();

        foreach($data as $row){
            $this->monitoringusulnip($row->nopeserta,2024,'02','0208',1,0);
        }

      return redirect()->back()->with('message', 'Berhasil sinkron');
    }

    function monitoringusulnip($no_peserta,$tahun,$jenis,$jenis_formasi_id,$limit,$offset) {
      $client = service('curlrequest');
      $cache = service('cache');

      // $token = $cache->get('siasn_token');
    //   $token = 'eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJBUWNPM0V3MVBmQV9MQ0FtY2J6YnRLUEhtcWhLS1dRbnZ1VDl0RUs3akc4In0.eyJleHAiOjE3NTY3MzkwMTcsImlhdCI6MTc1NjY5NjA0OSwiYXV0aF90aW1lIjoxNzU2Njk1ODE3LCJqdGkiOiI0ODllNGExMi0xMGRlLTQxODctOGQ1YS1hMDI2ZGQ2M2YxNTAiLCJpc3MiOiJodHRwczovL3Nzby1zaWFzbi5ia24uZ28uaWQvYXV0aC9yZWFsbXMvcHVibGljLXNpYXNuIiwiYXVkIjoiYWNjb3VudCIsInN1YiI6ImVjNzEwYmU2LWY5MjctNDkyZC04M2JjLWY4YTdmZWI3ZGMzYyIsInR5cCI6IkJlYXJlciIsImF6cCI6InNpYXNuLWluc3RhbnNpIiwibm9uY2UiOiJmNmE3YmE0Ny0zZDA0LTQwZTQtOWFkMi0xYTU0N2QwOGQ5N2EiLCJzZXNzaW9uX3N0YXRlIjoiYzU4NmZhNTItM2Q3Yi00NmI2LThhNjEtZjUxYjdmMGZiN2RkIiwiYWNyIjoiMCIsImFsbG93ZWQtb3JpZ2lucyI6WyJodHRwczovL3NpYXNuLWluc3RhbnNpLmJrbi5nby5pZCIsImh0dHA6Ly9zaWFzbi1pbnN0YW5zaS5ia24uZ28uaWQiLCJodHRwOi8vbG9jYWxob3N0OjMwMDAiXSwicmVhbG1fYWNjZXNzIjp7InJvbGVzIjpbInJvbGU6aW11dC1pbnN0YW5zaTptb25pdG9yaW5nIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwZXJlbmNhbmFhbjppbnN0YW5zaS1vcGVyYXRvci1zb3RrIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwZW1iZXJoZW50aWFuOmFwcHJvdmFsX2l6aW5fcHBwayIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVyZW5jYW5hYW46dXN1bC1yaW5jaWFuLWZvcm1hc2kiLCJvZmZsaW5lX2FjY2VzcyIsInVtYV9hdXRob3JpemF0aW9uIiwicm9sZTppbXV0LWluc3RhbnNpOm9wZXJhdG9yIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwZW5nYWRhYW46b3BlcmF0b3IiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnByb2ZpbGFzbjp2aWV3cHJvZmlsIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwZW5nYWRhYW46YXBwcm92YWwiXX0sInJlc291cmNlX2FjY2VzcyI6eyJhY2NvdW50Ijp7InJvbGVzIjpbIm1hbmFnZS1hY2NvdW50IiwibWFuYWdlLWFjY291bnQtbGlua3MiLCJ2aWV3LXByb2ZpbGUiXX19LCJzY29wZSI6Im9wZW5pZCBlbWFpbCBwcm9maWxlIiwiZW1haWxfdmVyaWZpZWQiOmZhbHNlLCJuYW1lIjoiTVVIQU1NQUQgQkFTV09STyIsInByZWZlcnJlZF91c2VybmFtZSI6IjE5ODUwMjA1MjAwOTEyMTAwNSIsImdpdmVuX25hbWUiOiJNVUhBTU1BRCIsImZhbWlseV9uYW1lIjoiQkFTV09STyIsImVtYWlsIjoia2Vtb2QxODJAZ21haWwuY29tIn0.KEIhf-Q2ghksyYMUeqh9VqQQAyllLfnRfmH15yblW2spcp_UvzT3_XXYtSQZbgj9IbKlfWD5uMm_PrXAcCQNKKnF0U2MXtoo0Uub1WbZI3pZQDkDC0H1jjSuOMDVHPErrfK4tUi5MYOvb7oDHD8uQ8EOI-DInyE-A1-rKCjGAcCbF_VULbW3p6FkoVC5-5cfILMMXuTn_TQ36DzQ8JXFGjJ6OhQZ8PE8kMwa6UJi2DE-Fc7UPdf3aYLpqmgZjjn-eYqZaBqlyzOyBEEqtKz5GWNGfodVxLJWHBBhJFCUy3qvD6hNOzyKD2h-lIu50CvlwX6xBKPcdaZJBXCkQadsgg';
        $token = $cache->get('zxc');
    //   $response = $client->request('GET', 'https://api-siasn.bkn.go.id/siasn-instansi/pengadaan/usulan/monitoring?jenis_pengadaan_id='.$jenis.'&status_usulan=&periode='.$tahun.'&limit='.$limit.'&offset='.$offset, [
      $response = $client->request('GET', 'https://api-siasn.bkn.go.id/siasn-instansi/pengadaan/usulan/monitoring?no_peserta='.$no_peserta.'&jenis_pengadaan_id='.$jenis.'&jenis_formasi_id='.$jenis_formasi_id.'&periode='.$tahun.'&limit='.$limit.'&offset='.$offset, [
          'headers' => [
              'Authorization'     => 'Bearer '.$token,
          ],
          'verify' => false,
          'debug' => true,
      ]);

      // return $this->response->setJSON( $response->getBody() );
      $data = json_decode($response->getBody());

      foreach ($data->data as $row) {
          $nopeserta = $row->usulan_data->data->no_peserta;
          $id = $row->id;
          $status = siasn_usul_status($row->status_usulan);
          $alasan = $row->alasan_tolak_tambahan;
          $pathpertek = $row->path_ttd_pertek; 
          $nopertek = $row->no_pertek;
          $nip = $row->nip;

          $model = new Pppkt2Model;
          $model->set('usul_id', $id);
          $model->set('usul_status', $status);
          $model->set('usul_alasan_tolak', $alasan);
          $model->set('usul_path_ttd_pertek', $pathpertek);
          $model->set('usul_no_pertek', $nopertek);
          $model->set('usul_nip', $nip);
          $model->where('nopeserta', $nopeserta);
          $model->update();

        //   echo '<pre>'.$no_peserta.'</pre>';
      }
  }
}
