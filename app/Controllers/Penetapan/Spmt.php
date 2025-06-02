<?php

namespace App\Controllers\Penetapan;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\CrudModel;
use App\Models\PesertaModel;
class Spmt extends BaseController
{
    public function index()
    {
        
        $model = new CrudModel;
        $data['peserta'] = $model->getResult('peserta', ['kode_satker' => session('lokasi'),'usul_nip !=' => '']);
        return view('penetapan/spmt', $data);
    }

    function upload() {
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
        return $this->response->setJSON(['status'=>'error','message'=>$this->validator->getErrors()['dokumen']]);
       }

      $file_name = $_FILES['dokumen']['name'];
      $ext = pathinfo($file_name, PATHINFO_EXTENSION);
    $nopeserta = $this->request->getVar('nopeserta');
      $temp_file_location = $_FILES['dokumen']['tmp_name'];

      $result = $this->sendtte($temp_file_location, $nopeserta);

      $response = json_decode($result['response'], true);
      if (isset($response['message']['file_url'])) {
            $doc_spmt = $response['message']['file_url'];
            $pesertaModel = new PesertaModel();
            $data = array (
                'doc_spmt' => $doc_spmt
            );
            $where = array (
                'nopeserta' => $nopeserta
            );
            $pesertaModel->set($data)->where($where)->update();
            
            return $this->response->setJSON(['status'=>'success','message'=>'<a href="'.$doc_spmt.'" target="_blank">Download SPMT</a>']);
        } else {
            session()->setFlashdata('error', 'File URL TTE tidak ada');
            return $this->response->setJSON(['status'=>'error','message'=>'Gagal mengirim dokumen ke TTE. Pastikan koneksi internet stabil dan coba lagi.']);
        }
    }

    function baupload() {
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
        return $this->response->setJSON(['status'=>'error','message'=>$this->validator->getErrors()['dokumen']]);
       }

      $file_name = $_FILES['dokumen']['name'];
      $ext = pathinfo($file_name, PATHINFO_EXTENSION);
    $nopeserta = $this->request->getVar('nopeserta');
      $temp_file_location = $_FILES['dokumen']['tmp_name'];

      $result = $this->sendtte($temp_file_location, $nopeserta);

      $response = json_decode($result['response'], true);
      if (isset($response['message']['file_url'])) {
            $doc_ba = $response['message']['file_url'];
            $pesertaModel = new PesertaModel();
            $data = array (
                'doc_ba' => $doc_ba
            );
            $where = array (
                'nopeserta' => $nopeserta
            );
            $pesertaModel->set($data)->where($where)->update();
            
            return $this->response->setJSON(['status'=>'success','message'=>'<a href="'.$doc_ba.'" target="_blank">Download BA</a>']);
        } else {
            session()->setFlashdata('error', 'File URL TTE tidak ada');
            return $this->response->setJSON(['status'=>'error','message'=>'Gagal mengirim dokumen ke TTE. Pastikan koneksi internet stabil dan coba lagi.']);
        }
    }

    private function sendtte($filepath, $peserta) {
        $apiUrl = getenv('TTE_URL'); 
        $gateKey = getenv('TTE_KEY');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data', 'gate-key: '.$gateKey));
        
        // Prepare file and parameters
        $postFields = [
            'nip' => '197706022005011005',
            'title' => 'SPMT a.n '.$peserta,
            'jenis' => 'SPRP',
            'id_layanan' => '0',
            'lampiran' => new \CURLFile($filepath, 'application/pdf', 'spmt.pdf'),
            'store_from' => 'admincasn'
        ];
        
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        
        // Execute cURL
        $response = curl_exec($ch);
        
        // Check for errors
        if (curl_errno($ch)) {
            curl_close($ch);
            $response = curl_error($ch);
            return array('status' => 'error', 'response' => $response);
        } else {
            curl_close($ch);
            return array('status' => 'success', 'response' => $response); 
        }
    }
}
