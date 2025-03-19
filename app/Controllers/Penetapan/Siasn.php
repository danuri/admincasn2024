<?php

namespace App\Controllers\Penetapan;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Siasn extends BaseController
{
    public function index()
    {
        //
    }

    function getFormasi() {
        // https://api-siasn.bkn.go.id/siasn-instansi/pengadaan/layananFormasi/cekFormasi/detail/70c1265c-f027-11ef-b301-005056b44d2b?page=1&per_page=10&jenis_jabatan_id=4
        $request = \Config\Services::request();
    $client = \Config\Services::curlrequest();

    $apiurl = 'https://api-sscasn.bkn.go.id/verif2024/api/verifikasi/supervise';

    $response = $client->request('GET', $apiurl,
        [
        'headers' => [
          'Accept'        => 'application/json',
          'Authorization' => 'Bearer eyJhbGciOiJIUzUxMiJ9.eyJzdWIiOiIxOTgzMTAwNjIwMDkwMTIwMDkiLCJpYXQiOjE3MzkwMjI4ODgsImV4cCI6MTczOTAyNjQ4OCwiaWQiOiI4YTAyODQ5ZDkxYWI5MjlkMDE5MWFiYmRkOTU0MDAxYiIsIm5hbWEiOiJUUkkgUkFIQVlVIiwidXNlcm5hbWUiOiIxOTgzMTAwNjIwMDkwMTIwMDkiLCJpbnN0YW5zaUlkIjoiQTVFQjAzRTIzQkZCRjZBMEUwNDA2NDBBMDQwMjUyQUQiLCJpbnN0YW5zaU5hbWEiOiJLZW1lbnRlcmlhbiBBZ2FtYSIsImplbmlzUGVuZ2FkYWFucyI6W3sicm9sZSI6IlNVUEVSVklTT1JfUFBQS19HVVJVIiwibmFtYSI6IlBQUEsgR3VydSIsImlkIjoiMSIsInBhcmFtZXRlckluc3RhbnNpSWQiOm51bGwsImxva2FzaVByb3ZJZHMiOm51bGx9LHsicm9sZSI6IlNVUEVSVklTT1JfUFBQSyIsIm5hbWEiOiJQUFBLIFRla25pcyIsImlkIjoiMyIsInBhcmFtZXRlckluc3RhbnNpSWQiOiJmMTQ3ZjM5MDNmNTExMWVmYTU0ZDAwNTA1NjhmZWQwZiIsImxva2FzaVByb3ZJZHMiOltdfSx7InJvbGUiOiJTVVBFUlZJU09SX0NQTlMiLCJuYW1hIjoiQ1BOUyIsImlkIjoiMiIsInBhcmFtZXRlckluc3RhbnNpSWQiOiJmMTQ3NjIxYTNmNTExMWVmYTU0ZDAwNTA1NjhmZWQwZiIsImxva2FzaVByb3ZJZHMiOltdfSx7InJvbGUiOiJTVVBFUlZJU09SX1BQUEtfTkFLRVMiLCJuYW1hIjoiUFBQSyBUZW5hZ2EgS2VzZWhhdGFuIiwiaWQiOiI0IiwicGFyYW1ldGVySW5zdGFuc2lJZCI6ImYxNDg3NTFlM2Y1MTExZWZhNTRkMDA1MDU2OGZlZDBmIiwibG9rYXNpUHJvdklkcyI6W119XSwiYXV0aG9yaXRpZXMiOlt7ImF1dGhvcml0eSI6IlJPTEVfU1VQRVJWSVNPUl9QUFBLIn0seyJhdXRob3JpdHkiOiJST0xFX1NVUEVSVklTT1JfUFBQS19OQUtFUyJ9LHsiYXV0aG9yaXR5IjoiUk9MRV9TVVBFUlZJU09SX0NQTlMifSx7ImF1dGhvcml0eSI6IlJPTEVfU1VQRVJWSVNPUl9QUFBLX0dVUlUifV0sImlzQ3BucyI6dHJ1ZSwiaXNQcHBrIjp0cnVlLCJpc1BwcGtHdXJ1Ijp0cnVlLCJpc1BwcGtOYWtlcyI6dHJ1ZSwiaXNQcHBrRG9zZW4iOmZhbHNlLCJrYW5yZWdJZCI6IjAwIn0.CwUDFp70ObO6pzXxC0wDucx7UbEXNhPoZsrzS704P1tdvOe3XqvoPaqwIX2HdYNJrL8yoNhsHHOEw1-rnnrfSg'
      ],
      'verify' => false,
      'debug' => true
    ]);

    $data = json_decode($response->getBody());
    return $data;
    }
}
