<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Tte extends BaseController
{
    public function index()
    {
        //
    }

    public function signstore()
    {
      $client = \Config\Services::curlrequest();

      $nik = $this->request->getVar('nik');
      $title = $this->request->getVar('title');
      $jenis = $this->request->getVar('jenis');
      $layanan = $this->request->getVar('id_layanan');
      $from = $this->request->getVar('store_from');
      $verified_by = $this->request->getVar('verified_by');

      $file = $this->request->getFile('lampiran');
      $tempfile = $file->getTempName();
      $filename = $file->getName();
      $type = $file->getClientMimeType();

      $cfile = curl_file_create($tempfile,$type,$filename);

      try {
        $response = $client->request('POST', $_ENV['TTE_ENDPOINT'].'/document/store', [
          'auth' => [$_ENV['TTE_USER'], $_ENV['TTE_PASS']],
          'verify' => false,
          'headers' => [
              'Accept'  => 'application/json',
              'Cookie'  => 'cookiesession1=678B29C26ACB35425B22A620BB38D8F3'
          ],
          'multipart' => [
              'nik' => $nik,
              'regarding' => $title,
              'type' => $jenis,
              'anchor' => '^',
              'paper_type' => 'P',
              'file'=> $cfile
          ],
          'debug' => true
        ]);
      } catch (\Exception $e) {
          return $this->response->setJSON(['status'=>'error','message'=>$e->getMessage()]);
      }

      $body = json_decode($response->getBody());
      // print_r($body);
      if($body->data){
        // $verify = $crud->getRow('tr_pengelola',['role'=>2]);
        
        // if isset passphrase for bypass
        $passphrase = $this->request->getVar('passphrase');
        if($passphrase){
            $this->dsbp($nik,$body->data->id,$passphrase);
        }
        
        $show = $this->getsignshow($body->data->id);
        return $this->response->setJSON($show);
      }else{
        return $this->response->setJSON(['status'=>'error','message'=>$body->data]);
      }
    }

    public function getsignshow($id)
    {
      $client = \Config\Services::curlrequest();

      $response = $client->request('GET', $_ENV['TTE_ENDPOINT'].'/document/'.$id.'/show', [
        'auth' => [$_ENV['TTE_USER'], $_ENV['TTE_PASS']],
        'verify' => false
      ]);

      $data = json_decode($response->getBody());
      return $data->data;
    }

    public function dsbp($nik,$docid,$passphrase)
    {
      $client = \Config\Services::curlrequest();

      // $passphrase = 'Jayaropeg123*';
      $response = $client->request('POST', $_ENV['TTE_ENDPOINT'].'/document/process/signature', [
        'auth' => [$_ENV['TTE_USER'], $_ENV['TTE_PASS']],
        'verify' => false,
        'headers' => [
            'Accept'  => 'application/json',
            'Cookie'  => 'cookiesession1=678B29C26ACB35425B22A620BB38D8F3'
        ],
        'form_params' => [
            'nik' => $nik,
            'passphrase' => $passphrase,
            'document_id' => $docid,
            'anchor' => '^',
            'is_final' => '1',
        ],
      ]);

      if (strpos($response->header('content-type'), 'application/json') !== false) {
          $body = json_decode($response->getBody());
          return $this->response->setJSON($body);
      }else{
        $show = $this->getsignshow($docid);
        return $this->response->setJSON($show);
      }
    }
}
