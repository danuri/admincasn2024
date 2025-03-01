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

}
