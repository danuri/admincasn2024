<?php

namespace App\Controllers;
use App\Models\ParuhwaktuModel;

class Supervise extends BaseController
{

  public function index($num)
  {
    $request = \Config\Services::request();
    $client = \Config\Services::curlrequest();

    $apiurl = 'https://api-sscasn.bkn.go.id/verif2024/api/verifikasi/supervisor/filter';

    $response = $client->request('POST', $apiurl, [
      'json' => [
        'byIsSudahSupervisi' => false,
        'byJenisPengadaanId' => 3,
        'paginatedRequest' => ['tipeResult'=>'SHUFFLE','size'=>100,'page'=>$num]
      ],
      'headers' => [
          'Accept'        => 'application/json',
          'Content-Type' => 'application/json',
          'Authorization' => 'Bearer eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiIxOTgzMTAwNjIwMDkwMTIwMDkiLCJpYXQiOjE3MzkwMjI4ODgsImV4cCI6MTczOTAyNjQ4OCwiaWQiOiI4YTAyODQ5ZDkxYWI5MjlkMDE5MWFiYmRkOTU0MDAxYiIsIm5hbWEiOiJUUkkgUkFIQVlVIiwidXNlcm5hbWUiOiIxOTgzMTAwNjIwMDkwMTIwMDkiLCJpbnN0YW5zaUlkIjoiQTVFQjAzRTIzQkZCRjZBMEUwNDA2NDBBMDQwMjUyQUQiLCJpbnN0YW5zaU5hbWEiOiJLZW1lbnRlcmlhbiBBZ2FtYSIsImplbmlzUGVuZ2FkYWFucyI6W3sicm9sZSI6IlNVUEVSVklTT1JfUFBQS19HVVJVIiwibmFtYSI6IlBQUEsgR3VydSIsImlkIjoiMSIsInBhcmFtZXRlckluc3RhbnNpSWQiOm51bGwsImxva2FzaVByb3ZJZHMiOm51bGx9LHsicm9sZSI6IlNVUEVSVklTT1JfUFBQSyIsIm5hbWEiOiJQUFBLIFRla25pcyIsImlkIjoiMyIsInBhcmFtZXRlckluc3RhbnNpSWQiOiJmMTQ3ZjM5MDNmNTExMWVmYTU0ZDAwNTA1NjhmZWQwZiIsImxva2FzaVByb3ZJZHMiOltdfSx7InJvbGUiOiJTVVBFUlZJU09SX0NQTlMiLCJuYW1hIjoiQ1BOUyIsImlkIjoiMiIsInBhcmFtZXRlckluc3RhbnNpSWQiOiJmMTQ3NjIxYTNmNTExMWVmYTU0ZDAwNTA1NjhmZWQwZiIsImxva2FzaVByb3ZJZHMiOltdfSx7InJvbGUiOiJTVVBFUlZJU09SX1BQUEtfTkFLRVMiLCJuYW1hIjoiUFBQSyBUZW5hZ2EgS2VzZWhhdGFuIiwiaWQiOiI0IiwicGFyYW1ldGVySW5zdGFuc2lJZCI6ImYxNDg3NTFlM2Y1MTExZWZhNTRkMDA1MDU2OGZlZDBmIiwibG9rYXNpUHJvdklkcyI6W119XSwiYXV0aG9yaXRpZXMiOlt7ImF1dGhvcml0eSI6IlJPTEVfU1VQRVJWSVNPUl9QUFBLIn0seyJhdXRob3JpdHkiOiJST0xFX1NVUEVSVklTT1JfUFBQS19OQUtFUyJ9LHsiYXV0aG9yaXR5IjoiUk9MRV9TVVBFUlZJU09SX0NQTlMifSx7ImF1dGhvcml0eSI6IlJPTEVfU1VQRVJWSVNPUl9QUFBLX0dVUlUifV0sImlzQ3BucyI6dHJ1ZSwiaXNQcHBrIjp0cnVlLCJpc1BwcGtHdXJ1Ijp0cnVlLCJpc1BwcGtOYWtlcyI6dHJ1ZSwiaXNQcHBrRG9zZW4iOmZhbHNlLCJrYW5yZWdJZCI6IjAwIn0.CwUDFp70ObO6pzXxC0wDucx7UbEXNhPoZsrzS704P1tdvOe3XqvoPaqwIX2HdYNJrL8yoNhsHHOEw1-rnnrfSg'
      ],
      'verify' => false
    ]);

    $data = json_decode($response->getBody());

    foreach ($data->content as $row) {
      echo $row->id;
      echo '<br>';
      $approve = $this->app($row->id);
      // return $this->response->setJSON($approve);
      echo $approve;
      echo '<br>';
    }
  }

  public function app($pid)
  {
    $request = \Config\Services::request();
    $client = \Config\Services::curlrequest();

    $apiurl = 'https://api-sscasn.bkn.go.id/verif2024/api/verifikasi/supervise';

    $response = $client->request('POST', $apiurl, [
      'json' => [
        'keterangan' => null,
        'isApprove' => true,
        'pendaftaranId' => $pid
      ],
      'headers' => [
          'Accept'        => 'application/json',
          'Authorization' => 'Bearer eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiIxOTgzMTAwNjIwMDkwMTIwMDkiLCJpYXQiOjE3MzkwMjI4ODgsImV4cCI6MTczOTAyNjQ4OCwiaWQiOiI4YTAyODQ5ZDkxYWI5MjlkMDE5MWFiYmRkOTU0MDAxYiIsIm5hbWEiOiJUUkkgUkFIQVlVIiwidXNlcm5hbWUiOiIxOTgzMTAwNjIwMDkwMTIwMDkiLCJpbnN0YW5zaUlkIjoiQTVFQjAzRTIzQkZCRjZBMEUwNDA2NDBBMDQwMjUyQUQiLCJpbnN0YW5zaU5hbWEiOiJLZW1lbnRlcmlhbiBBZ2FtYSIsImplbmlzUGVuZ2FkYWFucyI6W3sicm9sZSI6IlNVUEVSVklTT1JfUFBQS19HVVJVIiwibmFtYSI6IlBQUEsgR3VydSIsImlkIjoiMSIsInBhcmFtZXRlckluc3RhbnNpSWQiOm51bGwsImxva2FzaVByb3ZJZHMiOm51bGx9LHsicm9sZSI6IlNVUEVSVklTT1JfUFBQSyIsIm5hbWEiOiJQUFBLIFRla25pcyIsImlkIjoiMyIsInBhcmFtZXRlckluc3RhbnNpSWQiOiJmMTQ3ZjM5MDNmNTExMWVmYTU0ZDAwNTA1NjhmZWQwZiIsImxva2FzaVByb3ZJZHMiOltdfSx7InJvbGUiOiJTVVBFUlZJU09SX0NQTlMiLCJuYW1hIjoiQ1BOUyIsImlkIjoiMiIsInBhcmFtZXRlckluc3RhbnNpSWQiOiJmMTQ3NjIxYTNmNTExMWVmYTU0ZDAwNTA1NjhmZWQwZiIsImxva2FzaVByb3ZJZHMiOltdfSx7InJvbGUiOiJTVVBFUlZJU09SX1BQUEtfTkFLRVMiLCJuYW1hIjoiUFBQSyBUZW5hZ2EgS2VzZWhhdGFuIiwiaWQiOiI0IiwicGFyYW1ldGVySW5zdGFuc2lJZCI6ImYxNDg3NTFlM2Y1MTExZWZhNTRkMDA1MDU2OGZlZDBmIiwibG9rYXNpUHJvdklkcyI6W119XSwiYXV0aG9yaXRpZXMiOlt7ImF1dGhvcml0eSI6IlJPTEVfU1VQRVJWSVNPUl9QUFBLIn0seyJhdXRob3JpdHkiOiJST0xFX1NVUEVSVklTT1JfUFBQS19OQUtFUyJ9LHsiYXV0aG9yaXR5IjoiUk9MRV9TVVBFUlZJU09SX0NQTlMifSx7ImF1dGhvcml0eSI6IlJPTEVfU1VQRVJWSVNPUl9QUFBLX0dVUlUifV0sImlzQ3BucyI6dHJ1ZSwiaXNQcHBrIjp0cnVlLCJpc1BwcGtHdXJ1Ijp0cnVlLCJpc1BwcGtOYWtlcyI6dHJ1ZSwiaXNQcHBrRG9zZW4iOmZhbHNlLCJrYW5yZWdJZCI6IjAwIn0.CwUDFp70ObO6pzXxC0wDucx7UbEXNhPoZsrzS704P1tdvOe3XqvoPaqwIX2HdYNJrL8yoNhsHHOEw1-rnnrfSg'
      ],
      'verify' => false,
      'debug' => true
    ]);

    $data = json_decode($response->getBody());
    return $data;
    // return $this->response->setJSON($data);
  }

  function setparuhwaktu() {
        // POST https://api-siasn.bkn.go.id/perencanaan/formasi_paruh_waktu/update_paruh_waktu
        // status_usulan : 1
        // alasan_tidak_diusulkan : 
        // jenis_pengadaan :
        // teknis :
        // pendidikan_id : 0E7FA32614D38672E060640AF1083075
        // unor_id : 46e51456-62ec-43c3-8bf9-9318b043afff
        // user_id : af241811-1ae5-4759-970e-4e22690b3397
        // jabatan_id :
        // data_ids : 3674066501920004
        // usul_sotk_id : d9f13001-ad65-412e-a129-d744b40acba8
        // jenis : P

        $model = new ParuhwaktuModel;
        $peserta = $model->where(['sync_siasn'=>null])->whereIn('is_usul',['0','1'])->findAll(500,0);

        $client = service('curlrequest');

        // $form = $this->request->getVar();
        $token = 'eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJBUWNPM0V3MVBmQV9MQ0FtY2J6YnRLUEhtcWhLS1dRbnZ1VDl0RUs3akc4In0.eyJleHAiOjE3NTU3Mjg5MjYsImlhdCI6MTc1NTY4NTczMSwiYXV0aF90aW1lIjoxNzU1Njg1NzI2LCJqdGkiOiI3MTNkNTBjMC0xZDE4LTQ0ODQtYTgxNy1iNjNhYTUzYzk1MTQiLCJpc3MiOiJodHRwczovL3Nzby1zaWFzbi5ia24uZ28uaWQvYXV0aC9yZWFsbXMvcHVibGljLXNpYXNuIiwiYXVkIjoiYWNjb3VudCIsInN1YiI6ImFmMjQxODExLTFhZTUtNDc1OS05NzBlLTRlMjI2OTBiMzM5NyIsInR5cCI6IkJlYXJlciIsImF6cCI6ImJrbi1zaWFzbi1wZXJlbmNhbmFhbiIsIm5vbmNlIjoiNWViZTFjYjEtYzc0YS00NGE4LWJhNjMtOWIxYTlhMDM5MTIwIiwic2Vzc2lvbl9zdGF0ZSI6ImMzMTcxNzZmLTAxYTQtNDQzOC1iMDY0LWI4OTRmM2JlOWE5ZiIsImFjciI6IjAiLCJhbGxvd2VkLW9yaWdpbnMiOlsiaHR0cDovL3BlcmVuY2FuYWFuLXNpYXNuLmJrbi5nby5pZCIsImh0dHBzOi8vcGVyZW5jYW5hYW4tc2lhc24uYmtuLmdvLmlkIiwiaHR0cDovL2xvY2FsaG9zdDo0MjAwIiwiaHR0cDovL2xvY2FsaG9zdDo4MDAwIiwiaHR0cDovL2xvY2FsaG9zdDozMDAwIl0sInJlYWxtX2FjY2VzcyI6eyJyb2xlcyI6WyJyb2xlOnNpYXNuLWluc3RhbnNpOnBlcmVtYWphYW46b3BlcmF0b3IiLCJyb2xlOmltdXQtaW5zdGFuc2k6bW9uaXRvcmluZyIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVyZW5jYW5hYW46dXN1bC1wZW5nYWxpaGFuLWZvcm1hc2kiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnBlcmVuY2FuYWFuOmluc3RhbnNpLW9wZXJhdG9yLWluZm9qYWIiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnBlcmVtYWphYW46VFRFIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwZXJlbmNhbmFhbjppbnN0YW5zaS1tb25pdG9yLXBlcmVuY2FuYWFuLWtlcGVnYXdhaWFuIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwZW5nYWRhYW46YXBwcm92YWwiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnBlbmdhZGFhbjpvcGVyYXRvci1za3BucyIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVyZW1hamFhbjpyZWtvbiIsInJvbGU6ZGlzcGFrYXRpOmluc3RhbnNpOnR0ZSIsInJvbGU6c2lhc24taW5zdGFuc2k6a3A6b3BlcmF0b3IiLCJyb2xlOmltdXQtaW5zdGFuc2k6YWRtaW4iLCJyb2xlOm1hbmFqZW1lbi13czpkZXZlbG9wZXIiLCJvZmZsaW5lX2FjY2VzcyIsInJvbGU6c2lhc24taW5zdGFuc2k6cGk6cGVuZ2FsaWhhbi1rbDpvcGVyYXRvciIsInJvbGU6c2lhc24taW5zdGFuc2k6YmF0YWxuaXA6b3BlcmF0b3IiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnBlcmVuY2FuYWFuOmluc3RhbnNpLW9wZXJhdG9yLXBlbWVudWhhbi1rZWItcGVnYXdhaSIsInVtYV9hdXRob3JpemF0aW9uIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwZXJlbmNhbmFhbjppbnN0YW5zaS1vcGVyYXRvci1ldmFqYWIiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnBlbmdhZGFhbjpwYXJhZiIsInJvbGU6c2lhc24taW5zdGFuc2k6c2trOm9wZXJhdG9yIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwZXJlbWFqYWFuOmFwcHJvdmFsIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwZXJlbmNhbmFhbjppbnN0YW5zaS1vcGVyYXRvci1zb3RrIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwZXJlbmNhbmFhbjp1c3VsLXJpbmNpYW4tZm9ybWFzaSIsInJvbGU6ZGlzcGFrYXRpOmluc3RhbnNpOm9wZXJhdG9yIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwZW5nYWRhYW46b3BlcmF0b3IiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnBpOnBlbmdhbGloYW4ta2w6YXBwcm92YWwiLCJyb2xlOnNpYXNuLWluc3RhbnNpOmJhdGFsbmlwOmFwcHJvdmFsIiwicm9sZTpzaWFzbi1pbnN0YW5zaTphZG1pbi10ZW1wbGF0ZSIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVyZW5jYW5hYW46aW5zdGFuc2ktb3BlcmF0b3Itc3RhbmRhci1rb21wLWphYiIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVyZW5jYW5hYW46aW5zdGFuc2ktcGVuZXRhcGFuLXNvdGsiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnBlcmVtYWphYW46cGFyYWYiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnByb2ZpbGFzbjp2aWV3cHJvZmlsIiwicm9sZTpzaWFzbi1pbnN0YW5zaTphZG1pbjphZG1pbiIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVyZW5jYW5hYW46aW5zdGFuc2ktdmFsaWRhdG9yLXN0YW5kYXIta29tcC1qYWIiXX0sInJlc291cmNlX2FjY2VzcyI6eyJhY2NvdW50Ijp7InJvbGVzIjpbIm1hbmFnZS1hY2NvdW50IiwibWFuYWdlLWFjY291bnQtbGlua3MiLCJ2aWV3LXByb2ZpbGUiXX19LCJzY29wZSI6Im9wZW5pZCBlbWFpbCBwcm9maWxlIiwiZW1haWxfdmVyaWZpZWQiOmZhbHNlLCJuYW1lIjoiREFOVVJJIERBTlVSSSIsInByZWZlcnJlZF91c2VybmFtZSI6IjE5ODcwNzIyMjAxOTAzMTAwNSIsImdpdmVuX25hbWUiOiJEQU5VUkkiLCJmYW1pbHlfbmFtZSI6IkRBTlVSSSIsImVtYWlsIjoiZGFudWFsYmFudGFuaUBnbWFpbC5jb20ifQ.Uz9R0F1UzM2JzIQZ1FG2Hl1E4D74_JPfm6NNZL1__C2Ws58u4PlunkTspwLn8H7rjnOTPtiZh-t2ZH3srICBIPukQCgPQpJcHTspJCyFk36MULLW8iSY-OQFVGbN4YLN5zrzO4goVK0v-R8c7cy9gqWaDtnojVEImKKQ-I06n7qCPqQr0hUDr4_dJLQoYd9gzv1ojxx4vP92Zko_87DamPARkF7MQ5LL-LKzZr4wG7CLFpPNKyqWj7sk14egTKDPDrU8Boq9h2DcNHYRM6R9KQsBr4wMwLImXJgErPGW9FWPdajQhUL2dAvDTy9jOtYJ7fISy9oHobanoyummb3bTw';

        foreach($peserta as $row) {

          try {
             if(in_array($row->rincian_tk_pendidikan, ['0E7FA32616438672E060640AF1083075','39095cf0e4c147a4924da21ad7c0bdf6'])) {
            $jenis = 'dosen';
          }else{
            $jenis = 'teknis';
          }

          $response = $client->request('POST', 'https://api-siasn.bkn.go.id/perencanaan/formasi_paruh_waktu/update_paruh_waktu', [
              'headers' => [
                  'Origin' => null,
                  'referer' => 'https://perencanaan-siasn.bkn.go.id/pengelolaan/verval-perbaikan-update-rincian-formasi-menpan/d9f13001-ad65-412e-a129-d744b40acba8/3a6b38a7-ec6c-4faf-ad0c-7498208d72fb',
                  'Cookie'     => '_ga=GA1.1.1409040940.1726985318; _ga_5GQ07M1DL1=GS1.1.1729584198.3.1.1729584220.0.0.0; _ga_NVRKS9CPBG=GS1.1.1729597976.14.0.1729597976.0.0.0; BIGipServerpool_prod_sscasn2024_kube=3154600970.47873.0000; 9760101acce488cfa3a2041bbb8bb77f=ac978f6657646bddd4c30510616f0898; JSESSIONID=58DFA0AA1F244DBD78450618BF158E12; OAuth_Token_Request_State=7b1d214e-89ab-4e1c-9245-c9e7618d642b; _ga_THXT2YWNHR=GS1.1.1730096260.14.0.1730096262.0.0.0',
                  'Authorization'     => 'Bearer '.$token,
              ],
              'form_params' => [
                  'status_usulan' => $row->is_usul,
                  'alasan_tidak_diusulkan' => $row->alasan_tolak,
                  'jenis_pengadaan' => $jenis,
                  'pendidikan_id' => $row->rincian_tk_pendidikan,
                  'unor_id' => $row->unit_penempatan_id,
                  'user_id' => 'af241811-1ae5-4759-970e-4e22690b3397',
                  'jabatan_id' => '',
                  'data_ids' => $row->nik,
                  'usul_sotk_id' => 'd9f13001-ad65-412e-a129-d744b40acba8',
                  'jenis' => 'P'
              ],
              'debug' => true,
              'verify' => false
          ]);
  
          $response = json_decode($response->getBody());

          if(isset($response->respon_status) && $response->respon_status->status == 'SUCCESS') {
            $model->update($row->nik, ['sync_siasn' => '1']);
          }
        } catch (\Exception $e) {
            $model->update($row->nik, ['sync_siasn' => '2']);
          }

          
        }

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
