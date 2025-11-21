<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ParuhwaktuModel;
use App\Models\SuratparuhwaktuModel;
use App\Models\UserModel;
use App\Models\SuratModel;
use App\Models\CrudModel;
use Aws\S3\S3Client;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use CURLFile;
use Dompdf\Dompdf;
use Dompdf\Options;

class Paruhwaktu extends BaseController
{
    public function index()
    {
        $model = new ParuhwaktuModel;
        $umodel = new UserModel;
        $data['user'] = $umodel->where(['kode_satker'=>session('lokasi')])->first();

        $data['peserta'] = $model->where(['kode_lokasi'=>session('lokasi')])->findAll();

        return view('paruhwaktu/index', $data);
    }

    function getpeserta() {
        $nik = $this->request->getVar('nik');
        $model = new ParuhwaktuModel;
        $data = $model->where('nik', $nik)->first();
        return $this->response->setJSON($data);
    }

    function search($nik) {
        $spm = new SuratparuhwaktuModel;
        $cek = $spm->where('nik',$nik)->first();
        
        if($cek){
            $sm = new SuratModel;
            $surat = $sm->find($cek->surat_id);
            $info = ['status'=>'error','message'=>'Peserta telah dilakukan maping sebelumnya oleh satker: '.$surat->satker.'. Silahkan Admin terkait untuk berkoordinasi jika diperlukan.'];
            return $this->response->setJSON($info);
        }else{
            $model = new ParuhwaktuModel;
            $data = $model->where('nik',$nik)->first();

            if($data){
                return $this->response->setJSON($data);
            }else{
                return $this->response->setJSON(['status'=>'error','message'=>'Data tidak ditemukan']);
            }
        }
    }

    function setusul() {
        return redirect()->back()->with('message', 'Time Out');
        // validate
        $status = $this->request->getVar('status_usulan');

        if($status == 1){
            if (! $this->validate([
                'nik' => 'required',
                'status_usulan' => 'required|in_list[0,1]',
                'pendidikan_id' => 'required',
                'unor' => 'required'
            ])) {
                return redirect()->back()->with('message', 'Form harus lengkap');
            }
        }else{
            if (! $this->validate([
                'nik' => 'required',
                'alasan_tidak_diusulkan' => 'required',
            ])) {
                return $this->response->setJSON(['status'=>'error','message'=>'Form harus lengkap']);
            }
        }

        $nik = $this->request->getVar('nik');

        $model = new ParuhwaktuModel;

        // update field is_usul
        // $this->setparuhwaktu($status,$nik,$this->request->getVar('unor'),$this->request->getVar('pendidikan_id'),$this->request->getVar('alasan_tidak_diusulkan'));
        if($status == 1){
            $model->update($nik, [
                'is_usul' => 1,
                'rincian_tk_pendidikan' => $this->request->getVar('pendidikan_id'),
                'unit_penempatan_id' => $this->request->getVar('unor'),
                'unit_penempatan_nama' => $this->request->getVar('siasnname'),
                'sync_siasn' => NULL,
            ]);

            return redirect()->back()->with('message', 'Peserta ditandai untuk diusulkan');
        }else{
            $model->update($nik, ['is_usul' => 0,'alasan_tolak'=> $this->request->getVar('alasan_tidak_diusulkan')]);
            return redirect()->back()->with('message', 'Peserta tidak diusulkan');
        }
    }

    function setparuhwaktu($status,$nik,$unor_id,$pendidikan_id,$alasan) {
        // POST https://api-siasn.bkn.go.id/perencanaan/formasi_paruh_waktu/update_paruh_waktu
        // status_usulan : 1
        // alasan_tidak_diusulkan : jenis_pengadaan
        // teknis :
        // pendidikan_id : 0E7FA32614D38672E060640AF1083075
        // unor_id : 46e51456-62ec-43c3-8bf9-9318b043afff
        // user_id : af241811-1ae5-4759-970e-4e22690b3397
        // jabatan_id :
        // data_ids : 3674066501920004
        // usul_sotk_id : d9f13001-ad65-412e-a129-d744b40acba8
        // jenis : P

        $client = service('curlrequest');

        // $form = $this->request->getVar();
        $token = 'eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJBUWNPM0V3MVBmQV9MQ0FtY2J6YnRLUEhtcWhLS1dRbnZ1VDl0RUs3akc4In0.eyJleHAiOjE3NTUyODY1MzcsImlhdCI6MTc1NTI0MzQwMywiYXV0aF90aW1lIjoxNzU1MjQzMzM3LCJqdGkiOiJjODhiMzQ3Mi0wZGY4LTQ3MmUtOWY5Ni1kZDdlODY5NGVmY2MiLCJpc3MiOiJodHRwczovL3Nzby1zaWFzbi5ia24uZ28uaWQvYXV0aC9yZWFsbXMvcHVibGljLXNpYXNuIiwiYXVkIjoiYWNjb3VudCIsInN1YiI6ImFmMjQxODExLTFhZTUtNDc1OS05NzBlLTRlMjI2OTBiMzM5NyIsInR5cCI6IkJlYXJlciIsImF6cCI6ImJrbi1zaWFzbi1wZXJlbmNhbmFhbiIsIm5vbmNlIjoiMDgzZDNmOTMtZWJmZS00YmZmLTk2NGMtMjU1NTYwNTk1YzJlIiwic2Vzc2lvbl9zdGF0ZSI6IjYxMmNlNjZkLWZmNzAtNDhlYy1hMjM2LTkzYjc0YmI1NTZhYSIsImFjciI6IjAiLCJhbGxvd2VkLW9yaWdpbnMiOlsiaHR0cDovL3BlcmVuY2FuYWFuLXNpYXNuLmJrbi5nby5pZCIsImh0dHBzOi8vcGVyZW5jYW5hYW4tc2lhc24uYmtuLmdvLmlkIiwiaHR0cDovL2xvY2FsaG9zdDo0MjAwIiwiaHR0cDovL2xvY2FsaG9zdDo4MDAwIiwiaHR0cDovL2xvY2FsaG9zdDozMDAwIl0sInJlYWxtX2FjY2VzcyI6eyJyb2xlcyI6WyJyb2xlOnNpYXNuLWluc3RhbnNpOnBlcmVtYWphYW46b3BlcmF0b3IiLCJyb2xlOmltdXQtaW5zdGFuc2k6bW9uaXRvcmluZyIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVyZW5jYW5hYW46dXN1bC1wZW5nYWxpaGFuLWZvcm1hc2kiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnBlcmVuY2FuYWFuOmluc3RhbnNpLW9wZXJhdG9yLWluZm9qYWIiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnBlcmVtYWphYW46VFRFIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwZXJlbmNhbmFhbjppbnN0YW5zaS1tb25pdG9yLXBlcmVuY2FuYWFuLWtlcGVnYXdhaWFuIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwZW5nYWRhYW46YXBwcm92YWwiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnBlbmdhZGFhbjpvcGVyYXRvci1za3BucyIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVyZW1hamFhbjpyZWtvbiIsInJvbGU6ZGlzcGFrYXRpOmluc3RhbnNpOnR0ZSIsInJvbGU6c2lhc24taW5zdGFuc2k6a3A6b3BlcmF0b3IiLCJyb2xlOmltdXQtaW5zdGFuc2k6YWRtaW4iLCJyb2xlOm1hbmFqZW1lbi13czpkZXZlbG9wZXIiLCJvZmZsaW5lX2FjY2VzcyIsInJvbGU6c2lhc24taW5zdGFuc2k6cGk6cGVuZ2FsaWhhbi1rbDpvcGVyYXRvciIsInJvbGU6c2lhc24taW5zdGFuc2k6YmF0YWxuaXA6b3BlcmF0b3IiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnBlcmVuY2FuYWFuOmluc3RhbnNpLW9wZXJhdG9yLXBlbWVudWhhbi1rZWItcGVnYXdhaSIsInVtYV9hdXRob3JpemF0aW9uIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwZXJlbmNhbmFhbjppbnN0YW5zaS1vcGVyYXRvci1ldmFqYWIiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnBlbmdhZGFhbjpwYXJhZiIsInJvbGU6c2lhc24taW5zdGFuc2k6c2trOm9wZXJhdG9yIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwZXJlbWFqYWFuOmFwcHJvdmFsIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwZXJlbmNhbmFhbjppbnN0YW5zaS1vcGVyYXRvci1zb3RrIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwZXJlbmNhbmFhbjp1c3VsLXJpbmNpYW4tZm9ybWFzaSIsInJvbGU6ZGlzcGFrYXRpOmluc3RhbnNpOm9wZXJhdG9yIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwZW5nYWRhYW46b3BlcmF0b3IiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnBpOnBlbmdhbGloYW4ta2w6YXBwcm92YWwiLCJyb2xlOnNpYXNuLWluc3RhbnNpOmJhdGFsbmlwOmFwcHJvdmFsIiwicm9sZTpzaWFzbi1pbnN0YW5zaTphZG1pbi10ZW1wbGF0ZSIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVyZW5jYW5hYW46aW5zdGFuc2ktb3BlcmF0b3Itc3RhbmRhci1rb21wLWphYiIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVyZW5jYW5hYW46aW5zdGFuc2ktcGVuZXRhcGFuLXNvdGsiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnBlcmVtYWphYW46cGFyYWYiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnByb2ZpbGFzbjp2aWV3cHJvZmlsIiwicm9sZTpzaWFzbi1pbnN0YW5zaTphZG1pbjphZG1pbiIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVyZW5jYW5hYW46aW5zdGFuc2ktdmFsaWRhdG9yLXN0YW5kYXIta29tcC1qYWIiXX0sInJlc291cmNlX2FjY2VzcyI6eyJhY2NvdW50Ijp7InJvbGVzIjpbIm1hbmFnZS1hY2NvdW50IiwibWFuYWdlLWFjY291bnQtbGlua3MiLCJ2aWV3LXByb2ZpbGUiXX19LCJzY29wZSI6Im9wZW5pZCBlbWFpbCBwcm9maWxlIiwiZW1haWxfdmVyaWZpZWQiOmZhbHNlLCJuYW1lIjoiREFOVVJJIERBTlVSSSIsInByZWZlcnJlZF91c2VybmFtZSI6IjE5ODcwNzIyMjAxOTAzMTAwNSIsImdpdmVuX25hbWUiOiJEQU5VUkkiLCJmYW1pbHlfbmFtZSI6IkRBTlVSSSIsImVtYWlsIjoiZGFudWFsYmFudGFuaUBnbWFpbC5jb20ifQ.bvLwx4wPvafd4D0mjEEuch-TZpOZnEwcqdVem-K9WJ3PlZC2z6qoTI09otdHNCP5OXpyMc6w_j51P62jW9hrTQXHYuM1a2icrE1DfzdsDl7skKqodw-CPCSKmN4azF6leH3MyX4bB3t98prtGZrP9qA8lhdrLxo_ZkHdAz0o9ByLIZM5dv4qt_6pjxRlKGz7sVpNP5d0URHa1Bku1_eK2cJ1H0OdVi7V--w0WsP1YGyTEtEaAKIKYPS7MpwqR-p7gRkMY2SpoMKPfKd2qA8BHXziT4_8Ir1eVhMKcr132Pfuwye4ChnVdJmttuFK8bwL5P4sYy1wl1KiaW0JW4TiTQ';

        $response = $client->request('POST', 'https://api-siasn.bkn.go.id/perencanaan/formasi_paruh_waktu/update_paruh_waktu', [
            'headers' => [
                'Origin' => null,
                'referer' => 'https://perencanaan-siasn.bkn.go.id/pengelolaan/verval-perbaikan-update-rincian-formasi-menpan/d9f13001-ad65-412e-a129-d744b40acba8/3a6b38a7-ec6c-4faf-ad0c-7498208d72fb',
                'Cookie'     => '_ga=GA1.1.1409040940.1726985318; _ga_5GQ07M1DL1=GS1.1.1729584198.3.1.1729584220.0.0.0; _ga_NVRKS9CPBG=GS1.1.1729597976.14.0.1729597976.0.0.0; BIGipServerpool_prod_sscasn2024_kube=3154600970.47873.0000; 9760101acce488cfa3a2041bbb8bb77f=ac978f6657646bddd4c30510616f0898; JSESSIONID=58DFA0AA1F244DBD78450618BF158E12; OAuth_Token_Request_State=7b1d214e-89ab-4e1c-9245-c9e7618d642b; _ga_THXT2YWNHR=GS1.1.1730096260.14.0.1730096262.0.0.0',
                'Authorization'     => 'Bearer '.$token,
            ],
            'form_params' => [
                'status_usulan' => $status,
                'alasan_tidak_diusulkan' => $alasan,
                'pendidikan_id' => $pendidikan_id,
                'unor_id' => $unor_id,
                'user_id' => 'af241811-1ae5-4759-970e-4e22690b3397',
                'jabatan_id' => '',
                'data_ids' => $nik,
                'usul_sotk_id' => 'd9f13001-ad65-412e-a129-d744b40acba8',
                'jenis' => 'P'
            ],
            'debug' => true,
            'verify' => false
        ]);

        $response = json_decode($response->getBody());

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

    function uploaddok() {
        return redirect()->back()->with('message', 'Time Out');

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
            return redirect()->back()->with('message', $this->validator->getErrors()['dokumen']);
  		}

      $model = new UserModel();

      $file_name = $_FILES['dokumen']['name'];
      $ext = pathinfo($file_name, PATHINFO_EXTENSION);

      $kodesatker = session('lokasi');

      $file_name = 'paruhwaktu_sptjm'.'.'.$kodesatker.'.'.$ext;
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
              ->where(['kode_satker' => $kodesatker])
              ->set(['paruhwaktu_sptjm' => $file_name])
              ->update();

        return redirect()->back()->with('message', 'Dokumen telah diunggah');
      }else{
        return redirect()->back()->with('message', 'Dokumen gagal diunggah');
      }
    }

    public function export()
    {
      $kodesatker = session('kodesatker4');
      $model = new ParuhwaktuModel;
      $data = $model->where(['owner'=>$kodesatker])->findAll();

      $spreadsheet = new Spreadsheet();
      $sheet = $spreadsheet->getActiveSheet();
      $sheet->setCellValue('A1', 'nik');
      $sheet->setCellValue('B1', 'nama');
      $sheet->setCellValue('C1', 'jenis_kelamin');
      $sheet->setCellValue('D1', 'cepat_kode_sscn');
      $sheet->setCellValue('E1', 'instansi_sscn');
      $sheet->setCellValue('F1', 'jenis_pengadaan_id');
      $sheet->setCellValue('G1', 'jabatan_siasn_id');
      $sheet->setCellValue('H1', 'kode_jabatan_sscn');
      $sheet->setCellValue('I1', 'jabatan_sscn');
      $sheet->setCellValue('J1', 'kode_pendidikan_sscn');
      $sheet->setCellValue('K1', 'pendidikan_sscn');
      $sheet->setCellValue('L1', 'lokasi_formasi_sscn');
      $sheet->setCellValue('M1', 'usia_saat_daftar_sscn');
      $sheet->setCellValue('N1', 'tahap_sscn');
      $sheet->setCellValue('O1', 'is_tampungan_sscn');
      $sheet->setCellValue('P1', 'cepat_kode_nonasn');
      $sheet->setCellValue('Q1', 'instansi_non_asn');
      $sheet->setCellValue('R1', 'unor_id_nonasn');
      $sheet->setCellValue('S1', 'unor_nama_nonasn');
      $sheet->setCellValue('T1', 'unor_nama_atasan_nonasn');
      $sheet->setCellValue('U1', 'unor_exist');
      $sheet->setCellValue('V1', 'pendidikan_nama_nonasn');
      $sheet->setCellValue('W1', 'kode_jabatan_nonasn');
      $sheet->setCellValue('X1', 'jabatan_nonasn');
      $sheet->setCellValue('Y1', 'status_aktif_nonasn');
      $sheet->setCellValue('Z1', 'updated_at');
      $sheet->setCellValue('AA1', 'jabatan_rincian_id');
      $sheet->setCellValue('AB1', 'jabatan_rincian_nama');
      $sheet->setCellValue('AC1', 'sub_jabatan_rincian_id');
      $sheet->setCellValue('AD1', 'sub_jabatan_rincian_nama');
      $sheet->setCellValue('AE1', 'rincian_pendidikan_id');
      $sheet->setCellValue('AF1', 'rincian_pendidikan_nama');
      $sheet->setCellValue('AG1', 'rincian_tk_pendidikan');
      $sheet->setCellValue('AH1', 'unit_penempatan_id');
      $sheet->setCellValue('AI1', 'unit_penempatan_nama');
      $sheet->setCellValue('AJ1', 'is_terdata_nonasn');
      $sheet->setCellValue('AK1', 'status_prioritas');
      $sheet->setCellValue('AL1', 'is_usul');
      $sheet->setCellValue('AM1', 'alasan_tolak');
      $sheet->setCellValue('AN1', 'sync_siasn');

      $i = 2;
      foreach ($data as $row) {
        $sheet->getCell('A'.$i)->setValueExplicit($row->nik,\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('B'.$i, $row->nama);
        $sheet->setCellValue('C'.$i, $row->jenis_kelamin);
        $sheet->setCellValue('D'.$i, $row->cepat_kode_sscn);
        $sheet->setCellValue('E'.$i, $row->instansi_sscn);
        $sheet->setCellValue('F'.$i, $row->jenis_pengadaan_id);
        $sheet->setCellValue('G'.$i, $row->jabatan_siasn_id);
        $sheet->setCellValue('H'.$i, $row->kode_jabatan_sscn);
        $sheet->setCellValue('I'.$i, $row->jabatan_sscn);
        $sheet->setCellValue('J'.$i, $row->kode_pendidikan_sscn);
        $sheet->setCellValue('K'.$i, $row->pendidikan_sscn);
        $sheet->setCellValue('L'.$i, $row->lokasi_formasi_sscn);
        $sheet->setCellValue('M'.$i, $row->usia_saat_daftar_sscn);
        $sheet->setCellValue('N'.$i, $row->tahap_sscn);
        $sheet->setCellValue('O'.$i, $row->is_tampungan_sscn);
        $sheet->setCellValue('P'.$i, $row->cepat_kode_nonasn);
        $sheet->setCellValue('Q'.$i, $row->instansi_non_asn);
        $sheet->setCellValue('R'.$i, $row->unor_id_nonasn);
        $sheet->setCellValue('S'.$i, $row->unor_nama_nonasn);
        $sheet->setCellValue('T'.$i, $row->unor_nama_atasan_nonasn);
        $sheet->setCellValue('U'.$i, $row->unor_exist);
        $sheet->setCellValue('V'.$i, $row->pendidikan_nama_nonasn);
        $sheet->setCellValue('W'.$i, $row->kode_jabatan_nonasn);
        $sheet->setCellValue('X'.$i, $row->jabatan_nonasn);
        $sheet->setCellValue('Y'.$i, $row->status_aktif_nonasn);
        $sheet->setCellValue('Z'.$i, $row->updated_at);
        $sheet->setCellValue('AA'.$i, $row->jabatan_rincian_id);
        $sheet->setCellValue('AB'.$i, $row->jabatan_rincian_nama);
        $sheet->setCellValue('AC'.$i, $row->sub_jabatan_rincian_id);
        $sheet->setCellValue('AD'.$i, $row->sub_jabatan_rincian_nama);
        $sheet->setCellValue('AE'.$i, $row->rincian_pendidikan_id);
        $sheet->setCellValue('AF'.$i, $row->rincian_pendidikan_nama);
        $sheet->setCellValue('AG'.$i, $row->rincian_tk_pendidikan);
        $sheet->setCellValue('AH'.$i, $row->unit_penempatan_id);
        $sheet->setCellValue('AI'.$i, $row->unit_penempatan_nama);
        $sheet->setCellValue('AJ'.$i, $row->is_terdata_nonasn);
        $sheet->setCellValue('AK'.$i, $row->status_prioritas);
        $sheet->setCellValue('AL'.$i, $row->is_usul);
        $sheet->setCellValue('AM'.$i, $row->alasan_tolak);
        $sheet->setCellValue('AN'.$i, $row->sync_siasn);
        $i++;
      }

      $tanggal = date('YmdHis');
      $writer = new Xlsx($spreadsheet);
      ob_clean();
      ob_start();
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment; filename="Data_Potensi_Paruhwaktu_'.$tanggal.'.xlsx"');
      header('Cache-Control: max-age=0');
      $writer->save('php://output');
      ob_end_flush();
      exit;
    }

    public function cetak_sprp() { 
        // $nik = decrypt($nik); 
        $nik = $this->request->getVar('nik');
        $pendidikan = $this->request->getVar('pendidikan');

        $data['nama_satker'] = session('lokasi_nama');
        $model = new ParuhwaktuModel;
        $data['peserta'] = $model->find($nik);
        $data['pendidikan'] = $pendidikan;
        
        $timestamp = strtotime($data['peserta']->tgl_lahir);
        $formattedDate = date('d F Y', $timestamp);
        $indonesianMonths = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember'
        ];
        $data['peserta']->tanggal_lahir = str_replace(array_keys($indonesianMonths), $indonesianMonths, $formattedDate);
        if ($data['peserta']->lokasi_baru != null) {
            $penempatan = array_map('trim', explode('|', $data['peserta']->lokasi_baru));
            $length = count($penempatan);
            $data['penempatan'] = $penempatan[$length - 1];
            
            if (str_contains(strtolower($data['peserta']->lokasi_baru), 'kanwil')) {
                if($length == 4){
                    $data['penempatan'] = $penempatan[$length - 1].' '.$penempatan[$length - 2];
                }
                
                if($length == 5){
                    $data['penempatan'] = $penempatan[$length - 1].' '.$penempatan[$length - 2].' '.$penempatan[$length - 3];
                }
            }            
        }

        // $dateRequest = date('Ymd');
        // if ($dateRequest < '20250222') {
        //     $data['sysdate'] = '22 Februari 2025';
        //     $dateRequest = '20250222';
        // } else {
        //     $sysdate = date('d F Y');
        //     $data['sysdate'] = str_replace(array_keys($indonesianMonths), $indonesianMonths, $sysdate);
        // }        
        $data['sysdate'] = '26 September 2025';

        //dd($data);
        $html = view('penetapan/sprp_paruhwaktu',$data);

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);        

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        //$dompdf->stream('sprp.pdf', ['Attachment' => 0]); 
        //return view('penetapan/sprp');

        // Save PDF to a temporary file
        $output = $dompdf->output();
        $tempFilePath = tempnam(sys_get_temp_dir(), 'sprp_') . '.pdf';
        file_put_contents($tempFilePath, $output);
        $result = $this->sendtte($tempFilePath, $data['peserta']);

        if ($result['status'] == 'error') {
            session()->setFlashdata('error', $result['response']);
            return redirect()->to('paruhwaktu');
        } else {
            if ($result['status'] != 'success') {
                session()->setFlashdata('error', 'TTE Error');
                return redirect()->to('paruhwaktu');
            }
        }
        $response = json_decode($result['response'], true);
        
        if (isset($response['message']['file_url'])) {
            $doc_sprp = $response['message']['file_url'];
            $pesertaModel = new ParuhwaktuModel();
            $data = array (
                'doc_sprp' => $doc_sprp
            );
            $where = array (
                'nik' => $nik
            );
            $pesertaModel->set($data)->where($where)->update();
            // session()->setFlashdata('message', 'Dokumen SPRP Berhasil Dikirim');
            // return redirect()->to('paruhwaktu');

            // return json
            return $this->response->setJSON(['status'=>'success','message'=>'Dokumen SPRP Berhasil Dikirim','file_url'=>$doc_sprp]);
        } else {
            // session()->setFlashdata('error', 'File URL TTE tidak ada');
            // return redirect()->to('paruhwaktu');
            return $this->response->setJSON(['status'=>'error','message'=>'Dokumen gagal dikirim']);
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
            'title' => 'SPRP Paruh Waktu a.n '.$peserta->no_peserta,
            'jenis' => 'SPRP',
            'id_layanan' => '0',
            'lampiran' => new CURLFile($filepath, 'application/pdf', 'sprp.pdf'),
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

    function draftkontrak()
    {
      $pmodel = new ParuhwaktuModel();
      $id = $this->request->getPost('nik');
      $update = $pmodel->update($id,[
        'kontrak_no'=>$this->request->getPost('kontrak_no'),
        'kontrak_upah'=>$this->request->getPost('kontrak_upah'),
      ]);
      $ybs = $pmodel->find($id);

      $model = new UserModel();
      $user = $model->where('kode_satker', session('lokasi'))->first();

      $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('assets/template-kontrak-pw.docx');

      $predefinedMultilevel = array('listType' => \PhpOffice\PhpWord\Style\ListItem::TYPE_BULLET_EMPTY);

    //   $templateProcessor->setValue('companyLogo', $user->kop_surat);
      $templateProcessor->setValue('noKontrak', $ybs->kontrak_no);

    $templateProcessor->setValue('namaJabatanTtd', $user->tte_nama);
    $templateProcessor->setValue('JabatanTtd', $user->tte_jabatan);

    $templateProcessor->setValue('namaYbs', $ybs->nama_peserta);
    $templateProcessor->setValue('nipYbs', $ybs->usul_nip);
    $templateProcessor->setValue('jabatanYbs', $ybs->jabatan_baru);
    $templateProcessor->setValue('tempatLahirYbs', $ybs->tempat_lahir);
    $templateProcessor->setValue('tanggalLahirYbs', local_date($ybs->tgl_lahir));
    $templateProcessor->setValue('pendidikanYbs', $ybs->usul_pendidikan);
    $templateProcessor->setValue('tahunPendidikanYbs', $ybs->usul_pendidikan_tahun);
    $templateProcessor->setValue('alamatYbs', $ybs->alamat_domisili);
    $templateProcessor->setValue('unitKerjaYbs', $ybs->usul_unor);
    
    $templateProcessor->setValue('gajiYbs', rupiah($ybs->kontrak_upah));
    $templateProcessor->setValue('terbilangYbs', terbilang($ybs->kontrak_upah));

    // $templateProcessor->setImageValue('companyLogo', 'downloads/kop_surat/'.$user->kop_surat);
    $templateProcessor->setImageValue('companyLogo', array('path' => 'downloads/kop_surat/'.$user->kop_surat, 'width' => 600, 'height' => 100, 'ratio' => false));
      

    //   $templateProcessor->setValue('surat_tanggal', local_date($usul->rekomendasi_tanggal));

    $filename = 'draft_kontrak_'.$id.'.docx';
    $templateProcessor->saveAs('draft/'.$filename);

    $pdfPath = 'draft/draft_kontrak_' . $id . '.pdf';
    $fname = 'draft_kontrak_' . $id;

      // Convert DOCX to PDF
    $convRes = $this->convertDoctoPDF('draft/'.$filename);

    // print_r($convRes);
    if (!$convRes['status']) {
    //   return redirect()->back()->with('error', 'Gagal konversi PDF. Hubungi Administrator.');
        return $this->response->setJSON(['status'=>'error','message'=>'Gagal konversi PDF. Hubungi Administrator.']);
    }

    // Save the PDF file
    $cek = file_put_contents($pdfPath, $convRes['response']);
    
    if (!file_exists($pdfPath)) {
        // return redirect()->back()->with('error', 'Gagal menyimpan PDF. Hubungi Administrator.');
        return $this->response->setJSON(['status'=>'error','message'=>'Gagal menyimpan PDF. Hubungi Administrator.']);
    }

    $response = $this->sendttekontrak($pdfPath, $ybs);

    //   return redirect()->back()->with('message', 'Draft telah diupdate.');
    $response = json_decode($response['response'], true);

    $pmodel->update($id,['kontrak_file' => $response['file_url']]);

    return $this->response->setJSON($response);
  }

    function convertDoctoPDF($id)
  {
    $url        = 'https://ropegdev.kemenag.go.id/convert-doc2pdf/upload/file';
    $userapi    = 'RopegAdmin';
    $pwdapi     = 'B1R0kePeG4w4ai4n$1Khl4sB3r4MaL';

    $curl = curl_init();
    $postData = file_get_contents($id);

    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => $postData,
      CURLOPT_USERPWD => $userapi . ':' . $pwdapi,
      CURLOPT_SSL_VERIFYPEER => 0,
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'Cookie: cookiesession1=678B28EE4BF97D93467CF88E7A5F7C67'
      ),
    ));

    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $error    = curl_error($curl);

    curl_close($curl);
    // echo $response;
    if ($httpCode == 200 && !empty($response)) {
        return ['status' => true, 'message' => 'Conversion successful', 'response' => $response];
    } else {
        return ['status' => false, 'message' => 'Conversion failed: ' . $error, 'response' => $response];
    }
  }

  private function sendttekontrak($filepath, $peserta) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, site_url('tte/signstore'));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
        
        // Prepare file and parameters
        $umodel = new UserModel;
        $user = $umodel->where(['kode_satker'=>session('lokasi')])->first();
        $postFields = [
            'nik' => $user->tte_nik,
            'title' => 'Kontrak Paruh Waktu a.n '.$peserta->nik,
            'jenis' => 'Kontrak',
            'passphrase' => setdecrypt($user->tte_pass),
            'id_layanan' => '0',
            'lampiran' => new CURLFile($filepath, 'application/pdf', 'kontrak.pdf'),
            'store_from' => 'admincasn'
        ];
        
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        
        // Execute cURL
        $response = curl_exec($ch);
        
        // Check for errors
        if (curl_errno($ch)) {
            // curl_close($ch);
            $response = curl_error($ch);
            return array('status' => 'error', 'response' => $response);
        } else {
            // curl_close($ch);
            // $model = new ParuhwaktuModel();
            // $update = $model->update($peserta->nik,[
            //     'kontrak_file'=>$response,
            // ]);
            return ['status' => 'success', 'response' => $response];
        }
    }
}
