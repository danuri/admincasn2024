<?php

namespace App\Controllers;

class Supervise extends BaseController
{

  public function index($num)
  {
    $request = \Config\Services::request();
    $client = \Config\Services::curlrequest();

    $apiurl = 'https://api-sscasn.bkn.go.id/verif2024/api/verifikasi/supervisor/filter';

    $response = $client->request('POST', $apiurl, [
      'json' => [
        'byJenisPengadaanId' => 2,
        'paginatedRequest' => ['tipeResult'=>'SHUFFLE','size'=>100,'page'=>$num]
      ],
      'headers' => [
          'Accept'        => 'application/json',
          'Content-Type' => 'application/json',
          'Authorization' => 'Bearer eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiIxOTc5MDIyODIwMDYwNDEwMTEiLCJpYXQiOjE3MjY1NDEwNjUsImV4cCI6MTcyNjU0NDY2NSwiaWQiOiI4YTAyODQ4NzkxOWNkYjMxMDE5MWExOTAyZDkzMDRjMSIsIm5hbWEiOiJNIFJFWkEgUEFITEVWSSIsInVzZXJuYW1lIjoiMTk3OTAyMjgyMDA2MDQxMDExIiwiaW5zdGFuc2lJZCI6IkE1RUIwM0UyM0JGQkY2QTBFMDQwNjQwQTA0MDI1MkFEIiwiaW5zdGFuc2lOYW1hIjoiS2VtZW50ZXJpYW4gQWdhbWEiLCJqZW5pc1BlbmdhZGFhbnMiOlt7InJvbGUiOiJTVVBFUlZJU09SX0NQTlMiLCJuYW1hIjoiQ1BOUyIsImxva2FzaXMiOlsiOGEwMzg0NTA5MTljZGIzYzAxOTFhMTA5M2E2YzAzNDgiXSwiaWQiOiIyIiwicGFyYW1ldGVySW5zdGFuc2lJZCI6ImYxNDc2MjFhM2Y1MTExZWZhNTRkMDA1MDU2OGZlZDBmIiwibG9rYXNpUHJvdklkcyI6W119XSwiYXV0aG9yaXRpZXMiOlt7ImF1dGhvcml0eSI6IlJPTEVfU1VQRVJWSVNPUl9DUE5TIn1dLCJpc0NwbnMiOnRydWUsImlzUHBwayI6ZmFsc2UsImlzUHBwa0d1cnUiOmZhbHNlLCJpc1BwcGtOYWtlcyI6ZmFsc2UsImlzUHBwa0Rvc2VuIjpmYWxzZX0.xkcPVR_MrfTHT-ocFixg5V_U3pFrGnKSbSrxcla-9n9Av9LKFra7TDlp9I-omOMmBTmRElPfMqyh6qiEsyXkIg'
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
          'Authorization' => 'Bearer eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiIxOTc5MDIyODIwMDYwNDEwMTEiLCJpYXQiOjE3MjY1NDEwNjUsImV4cCI6MTcyNjU0NDY2NSwiaWQiOiI4YTAyODQ4NzkxOWNkYjMxMDE5MWExOTAyZDkzMDRjMSIsIm5hbWEiOiJNIFJFWkEgUEFITEVWSSIsInVzZXJuYW1lIjoiMTk3OTAyMjgyMDA2MDQxMDExIiwiaW5zdGFuc2lJZCI6IkE1RUIwM0UyM0JGQkY2QTBFMDQwNjQwQTA0MDI1MkFEIiwiaW5zdGFuc2lOYW1hIjoiS2VtZW50ZXJpYW4gQWdhbWEiLCJqZW5pc1BlbmdhZGFhbnMiOlt7InJvbGUiOiJTVVBFUlZJU09SX0NQTlMiLCJuYW1hIjoiQ1BOUyIsImxva2FzaXMiOlsiOGEwMzg0NTA5MTljZGIzYzAxOTFhMTA5M2E2YzAzNDgiXSwiaWQiOiIyIiwicGFyYW1ldGVySW5zdGFuc2lJZCI6ImYxNDc2MjFhM2Y1MTExZWZhNTRkMDA1MDU2OGZlZDBmIiwibG9rYXNpUHJvdklkcyI6W119XSwiYXV0aG9yaXRpZXMiOlt7ImF1dGhvcml0eSI6IlJPTEVfU1VQRVJWSVNPUl9DUE5TIn1dLCJpc0NwbnMiOnRydWUsImlzUHBwayI6ZmFsc2UsImlzUHBwa0d1cnUiOmZhbHNlLCJpc1BwcGtOYWtlcyI6ZmFsc2UsImlzUHBwa0Rvc2VuIjpmYWxzZX0.xkcPVR_MrfTHT-ocFixg5V_U3pFrGnKSbSrxcla-9n9Av9LKFra7TDlp9I-omOMmBTmRElPfMqyh6qiEsyXkIg'
      ],
      'verify' => false,
      'debug' => true
    ]);

    $data = json_decode($response->getBody());
    return $data;
    // return $this->response->setJSON($data);
  }

}
