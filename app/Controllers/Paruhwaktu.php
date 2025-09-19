<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ParuhwaktuModel;
use App\Models\SuratparuhwaktuModel;
use App\Models\UserModel;
use Aws\S3\S3Client;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Paruhwaktu extends BaseController
{
    public function index()
    {
        $model = new ParuhwaktuModel;
        $umodel = new UserModel;
        $data['user'] = $umodel->where(['kode_satker'=>session('lokasi')])->first();

        $data['peserta'] = $model->where(['kode_satker'=>session('kodesatker4')])->findAll();

        return view('paruhwaktu/index', $data);
    }

    function search($nik) {
        $spm = new SuratparuhwaktuModel;
        $cek = $spm->where('nik',$nik)->first();
        
        if($cek){
            $info = ['status'=>'error','message'=>'Peserta telah dilakukan maping sebelumnya oleh satker: '.$cek->satker.'. Silahkan Admin terkait untuk berkoordinasi jika diperlukan.'];
            return $this->response->setJSON($info);
        }else{
            $model = new ParuhwaktuModel;
            $data = $model->where('nik',$nik)->first();
            return $this->response->setJSON($data);
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
    /*   
    nik
nama
jenis_kelamin
cepat_kode_sscn
instansi_sscn
jenis_pengadaan_id
jabatan_siasn_id
kode_jabatan_sscn
jabatan_sscn
kode_pendidikan_sscn
pendidikan_sscn
lokasi_formasi_sscn
usia_saat_daftar_sscn
tahap_sscn
is_tampungan_sscn
cepat_kode_nonasn
instansi_non_asn
unor_id_nonasn
unor_nama_nonasn
unor_nama_atasan_nonasn
unor_exist
pendidikan_nama_nonasn
kode_jabatan_nonasn
jabatan_nonasn
status_aktif_nonasn
updated_at
jabatan_rincian_id
jabatan_rincian_nama
sub_jabatan_rincian_id
sub_jabatan_rincian_nama
rincian_pendidikan_id
rincian_pendidikan_nama
rincian_tk_pendidikan
unit_penempatan_id
unit_penempatan_nama
is_terdata_nonasn
status_prioritas
is_usul
alasan_tolak
sync_siasn
owner
owner_satker

*/

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
}
