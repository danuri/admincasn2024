<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PendidikanModel;
use App\Models\UnorModel;
use App\Models\PesertaModel;

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

      $data = $model->like('unor_lengkap', $search, 'both')->findAll();
      return $this->response->setJSON($data);
    }

    function monitoringusulnip($tahun,$jenis,$limit,$offset) {
      $client = service('curlrequest');
      $cache = service('cache');

      // $token = $cache->get('siasn_token');
      $token = 'eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJBUWNPM0V3MVBmQV9MQ0FtY2J6YnRLUEhtcWhLS1dRbnZ1VDl0RUs3akc4In0.eyJleHAiOjE3NDYzMDYxOTIsImlhdCI6MTc0NjI2Mjk5OSwiYXV0aF90aW1lIjoxNzQ2MjYyOTkyLCJqdGkiOiIzNzI2MTczMS0zNmJjLTRlMzMtYWI0ZS1hOWU2NTY3MzIzOGYiLCJpc3MiOiJodHRwczovL3Nzby1zaWFzbi5ia24uZ28uaWQvYXV0aC9yZWFsbXMvcHVibGljLXNpYXNuIiwiYXVkIjoiYWNjb3VudCIsInN1YiI6ImFmMjQxODExLTFhZTUtNDc1OS05NzBlLTRlMjI2OTBiMzM5NyIsInR5cCI6IkJlYXJlciIsImF6cCI6InNpYXNuLWluc3RhbnNpIiwibm9uY2UiOiIyNmU3NjczMy1kMDk0LTRlYmQtOGM0ZC05OTBlY2Q5MTMxNGIiLCJzZXNzaW9uX3N0YXRlIjoiOTkwNjAwZDUtM2ZhZC00NmEyLWIwZWYtNDRlZmM0OWUzNjczIiwiYWNyIjoiMCIsImFsbG93ZWQtb3JpZ2lucyI6WyJodHRwczovL3NpYXNuLWluc3RhbnNpLmJrbi5nby5pZCIsImh0dHA6Ly9zaWFzbi1pbnN0YW5zaS5ia24uZ28uaWQiLCJodHRwOi8vbG9jYWxob3N0OjMwMDAiXSwicmVhbG1fYWNjZXNzIjp7InJvbGVzIjpbInJvbGU6c2lhc24taW5zdGFuc2k6cGVyZW1hamFhbjpvcGVyYXRvciIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVyZW5jYW5hYW46dXN1bC1wZW5nYWxpaGFuLWZvcm1hc2kiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnBlcmVuY2FuYWFuOmluc3RhbnNpLW9wZXJhdG9yLWluZm9qYWIiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnBlcmVtYWphYW46VFRFIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwZXJlbmNhbmFhbjppbnN0YW5zaS1tb25pdG9yLXBlcmVuY2FuYWFuLWtlcGVnYXdhaWFuIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwZW5nYWRhYW46YXBwcm92YWwiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnBlbmdhZGFhbjpvcGVyYXRvci1za3BucyIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVyZW1hamFhbjpyZWtvbiIsInJvbGU6bWFuYWplbWVuLXdzOmRldmVsb3BlciIsIm9mZmxpbmVfYWNjZXNzIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwaTpwZW5nYWxpaGFuLWtsOm9wZXJhdG9yIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpiYXRhbG5pcDpvcGVyYXRvciIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVyZW5jYW5hYW46aW5zdGFuc2ktb3BlcmF0b3ItcGVtZW51aGFuLWtlYi1wZWdhd2FpIiwidW1hX2F1dGhvcml6YXRpb24iLCJyb2xlOnNpYXNuLWluc3RhbnNpOnBlcmVuY2FuYWFuOmluc3RhbnNpLW9wZXJhdG9yLWV2YWphYiIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVuZ2FkYWFuOnBhcmFmIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwZXJlbWFqYWFuOmFwcHJvdmFsIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwZXJlbmNhbmFhbjppbnN0YW5zaS1vcGVyYXRvci1zb3RrIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwZXJlbmNhbmFhbjp1c3VsLXJpbmNpYW4tZm9ybWFzaSIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVuZ2FkYWFuOm9wZXJhdG9yIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpiYXRhbG5pcDphcHByb3ZhbCIsInJvbGU6c2lhc24taW5zdGFuc2k6YWRtaW4tdGVtcGxhdGUiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnBlcmVuY2FuYWFuOmluc3RhbnNpLW9wZXJhdG9yLXN0YW5kYXIta29tcC1qYWIiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnBlcmVuY2FuYWFuOmluc3RhbnNpLXBlbmV0YXBhbi1zb3RrIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwZXJlbWFqYWFuOnBhcmFmIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwcm9maWxhc246dmlld3Byb2ZpbCIsInJvbGU6c2lhc24taW5zdGFuc2k6YWRtaW46YWRtaW4iLCJyb2xlOnNpYXNuLWluc3RhbnNpOnBlcmVuY2FuYWFuOmluc3RhbnNpLXZhbGlkYXRvci1zdGFuZGFyLWtvbXAtamFiIl19LCJyZXNvdXJjZV9hY2Nlc3MiOnsiYWNjb3VudCI6eyJyb2xlcyI6WyJtYW5hZ2UtYWNjb3VudCIsIm1hbmFnZS1hY2NvdW50LWxpbmtzIiwidmlldy1wcm9maWxlIl19fSwic2NvcGUiOiJvcGVuaWQgZW1haWwgcHJvZmlsZSIsImVtYWlsX3ZlcmlmaWVkIjpmYWxzZSwibmFtZSI6IkRBTlVSSSBEQU5VUkkiLCJwcmVmZXJyZWRfdXNlcm5hbWUiOiIxOTg3MDcyMjIwMTkwMzEwMDUiLCJnaXZlbl9uYW1lIjoiREFOVVJJIiwiZmFtaWx5X25hbWUiOiJEQU5VUkkiLCJlbWFpbCI6ImRhbnVhbGJhbnRhbmlAZ21haWwuY29tIn0.bp3J7VejQewHNk_80euOrTKNupj_SHHqLsr5OUppnS-48XQFu3xaE_xJtyp_1JgbnnuIeYkC4scSdgTs0a5Gr8DvDdTh3yXwga8dd0SwgYnzNfdFm6Egb1KLKMrhhHztfaGoVs-oyihZZk1bQ0f58kRjUgjFV9EFBb8QyAAfeA0hDkVRIR0vi-Oe7XvxrcMADlZt3h74yToEP417U1g-6g8freSMfmozUX9zS1f781Hhx3gA3p8WWAA3HPJQW1qkxieOv1lfiS2IjnsAwyhiHd2ijwBSW7c-BNdmfCJANA1YV2IAYC4wUvFXj8HB0FoovwOGH50QvTQsTe_xRCefDg';

      $response = $client->request('GET', 'https://api-siasn.bkn.go.id/siasn-instansi/pengadaan/usulan/monitoring?jenis_pengadaan_id='.$jenis.'&status_usulan=&periode='.$tahun.'&limit='.$limit.'&offset='.$offset, [
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

          $model = new PesertaModel;
          $model->set('usul_status', $status);
          $model->set('usul_alasan_tolak', $alasan);
          $model->set('usul_path_ttd_pertek', $pathpertek);
          $model->set('usul_no_pertek', $nopertek);
          $model->set('usul_nip', $nip);
          $model->where('nopeserta', $nopeserta);
          $model->update();
      }
  }
}
