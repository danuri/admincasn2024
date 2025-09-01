<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PendidikanModel;
use App\Models\UnorModel;
use App\Models\PesertaModel;
use App\Models\Pppkt2Model;

class Ajax extends BaseController
{
    public function index()
    {
        //
    }

    public function searchunor()
    {
      $model = new UnorModel;
      $search = $this->request->getVar('search');

      $data = $model->like('unor_lengkap', $search, 'both')->orWhere('id_unor',$search)->findAll();
      return $this->response->setJSON($data);
    }

    function getmonitoring($limit, $offset) {
        $cache = service('cache');
        $token = $cache->save('zxc','xxxx');
        $model = new Pppkt2Model;
        $data= $model->orderBy('nopeserta', 'asc')->findAll($limit,$offset);

        foreach($data as $row){
            $this->monitoringusulnip($row->nopeserta,2024,'02','0208',1,0);
        }

        $no = $offset+$limit;

        echo '<a href="'.site_url('ajax/getmonitoring/'.$limit.'/'.$no).'">Next</a>';
    }

    function monitoringusulnip($no_peserta,$tahun,$jenis,$jenis_formasi_id,$limit,$offset) {
      $client = service('curlrequest');
      $cache = service('cache');

      // $token = $cache->get('siasn_token');
    //   $token = 'eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJBUWNPM0V3MVBmQV9MQ0FtY2J6YnRLUEhtcWhLS1dRbnZ1VDl0RUs3akc4In0.eyJleHAiOjE3NTY3MzkwMTcsImlhdCI6MTc1NjY5NjA0OSwiYXV0aF90aW1lIjoxNzU2Njk1ODE3LCJqdGkiOiI0ODllNGExMi0xMGRlLTQxODctOGQ1YS1hMDI2ZGQ2M2YxNTAiLCJpc3MiOiJodHRwczovL3Nzby1zaWFzbi5ia24uZ28uaWQvYXV0aC9yZWFsbXMvcHVibGljLXNpYXNuIiwiYXVkIjoiYWNjb3VudCIsInN1YiI6ImVjNzEwYmU2LWY5MjctNDkyZC04M2JjLWY4YTdmZWI3ZGMzYyIsInR5cCI6IkJlYXJlciIsImF6cCI6InNpYXNuLWluc3RhbnNpIiwibm9uY2UiOiJmNmE3YmE0Ny0zZDA0LTQwZTQtOWFkMi0xYTU0N2QwOGQ5N2EiLCJzZXNzaW9uX3N0YXRlIjoiYzU4NmZhNTItM2Q3Yi00NmI2LThhNjEtZjUxYjdmMGZiN2RkIiwiYWNyIjoiMCIsImFsbG93ZWQtb3JpZ2lucyI6WyJodHRwczovL3NpYXNuLWluc3RhbnNpLmJrbi5nby5pZCIsImh0dHA6Ly9zaWFzbi1pbnN0YW5zaS5ia24uZ28uaWQiLCJodHRwOi8vbG9jYWxob3N0OjMwMDAiXSwicmVhbG1fYWNjZXNzIjp7InJvbGVzIjpbInJvbGU6aW11dC1pbnN0YW5zaTptb25pdG9yaW5nIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwZXJlbmNhbmFhbjppbnN0YW5zaS1vcGVyYXRvci1zb3RrIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwZW1iZXJoZW50aWFuOmFwcHJvdmFsX2l6aW5fcHBwayIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVyZW5jYW5hYW46dXN1bC1yaW5jaWFuLWZvcm1hc2kiLCJvZmZsaW5lX2FjY2VzcyIsInVtYV9hdXRob3JpemF0aW9uIiwicm9sZTppbXV0LWluc3RhbnNpOm9wZXJhdG9yIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwZW5nYWRhYW46b3BlcmF0b3IiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnByb2ZpbGFzbjp2aWV3cHJvZmlsIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwZW5nYWRhYW46YXBwcm92YWwiXX0sInJlc291cmNlX2FjY2VzcyI6eyJhY2NvdW50Ijp7InJvbGVzIjpbIm1hbmFnZS1hY2NvdW50IiwibWFuYWdlLWFjY291bnQtbGlua3MiLCJ2aWV3LXByb2ZpbGUiXX19LCJzY29wZSI6Im9wZW5pZCBlbWFpbCBwcm9maWxlIiwiZW1haWxfdmVyaWZpZWQiOmZhbHNlLCJuYW1lIjoiTVVIQU1NQUQgQkFTV09STyIsInByZWZlcnJlZF91c2VybmFtZSI6IjE5ODUwMjA1MjAwOTEyMTAwNSIsImdpdmVuX25hbWUiOiJNVUhBTU1BRCIsImZhbWlseV9uYW1lIjoiQkFTV09STyIsImVtYWlsIjoia2Vtb2QxODJAZ21haWwuY29tIn0.KEIhf-Q2ghksyYMUeqh9VqQQAyllLfnRfmH15yblW2spcp_UvzT3_XXYtSQZbgj9IbKlfWD5uMm_PrXAcCQNKKnF0U2MXtoo0Uub1WbZI3pZQDkDC0H1jjSuOMDVHPErrfK4tUi5MYOvb7oDHD8uQ8EOI-DInyE-A1-rKCjGAcCbF_VULbW3p6FkoVC5-5cfILMMXuTn_TQ36DzQ8JXFGjJ6OhQZ8PE8kMwa6UJi2DE-Fc7UPdf3aYLpqmgZjjn-eYqZaBqlyzOyBEEqtKz5GWNGfodVxLJWHBBhJFCUy3qvD6hNOzyKD2h-lIu50CvlwX6xBKPcdaZJBXCkQadsgg';
        $token = $cache->get('siasn_token');
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
