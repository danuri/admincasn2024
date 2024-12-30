<?php

namespace App\Controllers\Skb;

use App\Controllers\BaseController;
use App\Models\UploadModel;
use App\Models\DokumenModel;
use Aws\S3\S3Client;
use Config\Minio;
use Aws\Exception\AwsException;

class Dokumen extends BaseController
{
    public function index()
    {
        $model = new DokumenModel;
        $data['dokumen'] = $model->findAll();
        return view('skb/dokumen', $data);
    }

    public function save()
    {
        $satker = session('lokasi');
        // Load validasi service
        $validation = \Config\Services::validation();

        // Define validation rules
        $rules = [
            'kategori' => 'required'
        ];

        if ($this->validate($rules)) {
            $minio = new Minio();
            $s3Client = new S3Client([
                'version' => 'latest',
                'region'  => 'us-east-1',
                'endpoint' => $minio->endpoint,
                'credentials' => [
                    'key'    => $minio->accessKey,
                    'secret' => $minio->secretKey,
                ],
                'use_path_style_endpoint' => true,
            ]);
            // File upload configuration
            $file = $this->request->getFile('lampiran');
            $sysdate = date('YmdHis');
            $newFileName = $satker . '_' . $this->request->getPost('kategori') . '_' . $sysdate . '.pdf' ;
            $nip = session('nip');
            
            if ($file->isValid() && !$file->hasMoved()) {
                //$file->move('./downloads/', $newFileName . '.pdf', true);
                $result = $s3Client->putObject([
                    'Bucket' => $minio->bucket,
                    'Key'    => 'dokumen/'.$newFileName,
                    'SourceFile' => $file->getTempName(),
                    'ACL'    => 'public-read', // Adjust as needed
                    'ContentType' => 'application/pdf'
                ]);
            } else {
                session()->setFlashdata('message', $file->getErrorString());
                return redirect()->to('skb/dokumen');
            }

            // Jika validasi berhasil, proses data di sini
            $uploadModel = new UploadModel();

            $param = [
                'id_dokumen' => $this->request->getPost('kategori'),
                'kode_lokasi' => $satker,
                'attachment' => $newFileName,
                'created_by' => $nip,
                'created_at' => date('Y-m-d H:i:s')
            ];

            $uploadModel->insert($param);
            session()->setFlashdata('message', 'Dokumen telah ditambahkan');
            return redirect()->to('skb/dokumen');
        } else {
            $data['validation'] = $validation;
            $model = new DokumenModel;
            $data['dokumen'] = $model->findAll();
            return view('skb/dokumen', $data);
        }
    }

    public function unggahan($id)
    {
        $kodesatker = session('lokasi');
        $model = new DokumenModel;
        $data['dokumen'] = $model->find($id);
        $data['unggahan'] = $model->unggahan($id, $kodesatker);
        //dd($data['unggahan']);
        return view('skb/unggahan', $data);
    }

    public function saveunggahan()
    {
        $satker = session('lokasi');
        // Load validasi service
        $validation = \Config\Services::validation();

        // Define validation rules
        $rules = [
            'kategori' => 'required'
        ];

        if ($this->validate($rules)) {
            $minio = new Minio();
            $s3Client = new S3Client([
                'version' => 'latest',
                'region'  => 'us-east-1',
                'endpoint' => $minio->endpoint,
                'credentials' => [
                    'key'    => $minio->accessKey,
                    'secret' => $minio->secretKey,
                ],
                'use_path_style_endpoint' => true,
            ]);
            // File upload configuration
            $file = $this->request->getFile('lampiran');
            $sysdate = date('YmdHis');
            $newFileName = $satker . '_' . $this->request->getPost('kategori') . '_' . $sysdate . '.pdf' ;
            $nip = session('nip');
            
            if ($file->isValid() && !$file->hasMoved()) {
                //$file->move('./downloads/', $newFileName . '.pdf', true);
                $result = $s3Client->putObject([
                    'Bucket' => $minio->bucket,
                    'Key'    => 'dokumen/'.$newFileName,
                    'SourceFile' => $file->getTempName(),
                    'ACL'    => 'public-read', // Adjust as needed
                    'ContentType' => 'application/pdf'
                ]);
            } else {
                session()->setFlashdata('message', $file->getErrorString());
                return redirect()->to('skb/dokumen/unggahan/'.$this->request->getPost('kategori'));
            }

            // Jika validasi berhasil, proses data di sini
            $uploadModel = new UploadModel();

            $param = [
                'id_dokumen' => $this->request->getPost('kategori'),
                'kode_lokasi' => $satker,
                'attachment' => $newFileName,
                'created_by' => $nip,
                'created_at' => date('Y-m-d H:i:s')
            ];

            $uploadModel->insert($param);
            session()->setFlashdata('message', 'Dokumen telah ditambahkan');
            return redirect()->to('skb/dokumen/unggahan/'.$this->request->getPost('kategori'));
        } else {
            $data['validation'] = $validation;
            return view('skb/dokumen/unggahan/'.$this->request->getPost('kategori'));
        }  
    }

    public function deleteunggahan($id)
    {
      $model = new UploadModel;
      $fileName = $model->find($id);
      //dd($fileName['attachment']);
      $model->delete($id);

      try {
        $minio = new Minio();
        $s3Client = new S3Client([
            'version' => 'latest',
            'region'  => 'us-east-1',
            'endpoint' => $minio->endpoint,
            'credentials' => [
                'key'    => $minio->accessKey,
                'secret' => $minio->secretKey,
            ],
            'use_path_style_endpoint' => true,
        ]);
        $result = $s3Client->deleteObject([
            'Bucket' => $minio->bucket,
            'Key'    => 'dokumen/'.$fileName['attachment'],
        ]);
      } catch (AwsException $e) {
      }

      return redirect()->back()->with('message', 'Unggahan telah dihapus');
    }
}