<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DokumenModel;
use App\Models\UploadModel;
use App\Models\CrudModel;
use App\Models\Pppkt2Model;
use App\Models\ParuhwaktuModel;

class Publish extends BaseController
{
    public function document($id)
    {
        $model = new DokumenModel;
        $data['dokumen'] = $model->find($id);
        $data['unggahan'] = $model->unggahan($id,false);

        return view('public/unggahan', $data);
    }

    function optimalisasi() {
        $model = new CrudModel;
        $data['satker'] = $model->getResult('users',['pppk_sptjm !='=> NULL]);

        return view('public/optimalisasi', $data);
    }

    function paruhwaktu() {
        $model = new CrudModel;
        $data['monitoring'] = $model->monitoringParuhwaktu();
        $data['sudah'] = $model->jumlahMapping()->jumlah;
        $data['belum'] = (7110 - $data['sudah']);

        return view('public/paruhwaktu', $data);
    }

    function monitoring() {
        $model = new CrudModel;
        $data['monitoring'] = $model->monitoringtahap2();
        $data['satker'] = $model->getResult('users');

        return view('public/tahap2', $data);
    }

    function sinkron() {
      $model = new Pppkt2Model;
    //   $data= $model->where(['kode_satker_asal'=>$lokasi])->findAll();
      $data= $model->where(['usul_nip'=>NULL])->findAll();

        foreach($data as $row){
            $this->monitoringusulnip($row->nopeserta,2024,'02','0208',1,0);
            $update = $model->where(['nopeserta'=>$row->nopeserta])->set(['tag'=>2])->update();
        }

      return redirect()->back()->with('message', 'Berhasil sinkron');
    }

    function monitoringusulnip($no_peserta,$tahun,$jenis,$jenis_formasi_id,$limit,$offset) {
      $client = service('curlrequest');
      $cache = service('cache');

      // $token = $cache->get('siasn_token');
    //   $token = 'eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJBUWNPM0V3MVBmQV9MQ0FtY2J6YnRLUEhtcWhLS1dRbnZ1VDl0RUs3akc4In0.eyJleHAiOjE3NTY3MzkwMTcsImlhdCI6MTc1NjY5NjA0OSwiYXV0aF90aW1lIjoxNzU2Njk1ODE3LCJqdGkiOiI0ODllNGExMi0xMGRlLTQxODctOGQ1YS1hMDI2ZGQ2M2YxNTAiLCJpc3MiOiJodHRwczovL3Nzby1zaWFzbi5ia24uZ28uaWQvYXV0aC9yZWFsbXMvcHVibGljLXNpYXNuIiwiYXVkIjoiYWNjb3VudCIsInN1YiI6ImVjNzEwYmU2LWY5MjctNDkyZC04M2JjLWY4YTdmZWI3ZGMzYyIsInR5cCI6IkJlYXJlciIsImF6cCI6InNpYXNuLWluc3RhbnNpIiwibm9uY2UiOiJmNmE3YmE0Ny0zZDA0LTQwZTQtOWFkMi0xYTU0N2QwOGQ5N2EiLCJzZXNzaW9uX3N0YXRlIjoiYzU4NmZhNTItM2Q3Yi00NmI2LThhNjEtZjUxYjdmMGZiN2RkIiwiYWNyIjoiMCIsImFsbG93ZWQtb3JpZ2lucyI6WyJodHRwczovL3NpYXNuLWluc3RhbnNpLmJrbi5nby5pZCIsImh0dHA6Ly9zaWFzbi1pbnN0YW5zaS5ia24uZ28uaWQiLCJodHRwOi8vbG9jYWxob3N0OjMwMDAiXSwicmVhbG1fYWNjZXNzIjp7InJvbGVzIjpbInJvbGU6aW11dC1pbnN0YW5zaTptb25pdG9yaW5nIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwZXJlbmNhbmFhbjppbnN0YW5zaS1vcGVyYXRvci1zb3RrIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwZW1iZXJoZW50aWFuOmFwcHJvdmFsX2l6aW5fcHBwayIsInJvbGU6c2lhc24taW5zdGFuc2k6cGVyZW5jYW5hYW46dXN1bC1yaW5jaWFuLWZvcm1hc2kiLCJvZmZsaW5lX2FjY2VzcyIsInVtYV9hdXRob3JpemF0aW9uIiwicm9sZTppbXV0LWluc3RhbnNpOm9wZXJhdG9yIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwZW5nYWRhYW46b3BlcmF0b3IiLCJyb2xlOnNpYXNuLWluc3RhbnNpOnByb2ZpbGFzbjp2aWV3cHJvZmlsIiwicm9sZTpzaWFzbi1pbnN0YW5zaTpwZW5nYWRhYW46YXBwcm92YWwiXX0sInJlc291cmNlX2FjY2VzcyI6eyJhY2NvdW50Ijp7InJvbGVzIjpbIm1hbmFnZS1hY2NvdW50IiwibWFuYWdlLWFjY291bnQtbGlua3MiLCJ2aWV3LXByb2ZpbGUiXX19LCJzY29wZSI6Im9wZW5pZCBlbWFpbCBwcm9maWxlIiwiZW1haWxfdmVyaWZpZWQiOmZhbHNlLCJuYW1lIjoiTVVIQU1NQUQgQkFTV09STyIsInByZWZlcnJlZF91c2VybmFtZSI6IjE5ODUwMjA1MjAwOTEyMTAwNSIsImdpdmVuX25hbWUiOiJNVUhBTU1BRCIsImZhbWlseV9uYW1lIjoiQkFTV09STyIsImVtYWlsIjoia2Vtb2QxODJAZ21haWwuY29tIn0.KEIhf-Q2ghksyYMUeqh9VqQQAyllLfnRfmH15yblW2spcp_UvzT3_XXYtSQZbgj9IbKlfWD5uMm_PrXAcCQNKKnF0U2MXtoo0Uub1WbZI3pZQDkDC0H1jjSuOMDVHPErrfK4tUi5MYOvb7oDHD8uQ8EOI-DInyE-A1-rKCjGAcCbF_VULbW3p6FkoVC5-5cfILMMXuTn_TQ36DzQ8JXFGjJ6OhQZ8PE8kMwa6UJi2DE-Fc7UPdf3aYLpqmgZjjn-eYqZaBqlyzOyBEEqtKz5GWNGfodVxLJWHBBhJFCUy3qvD6hNOzyKD2h-lIu50CvlwX6xBKPcdaZJBXCkQadsgg';
        $token = $cache->get('zxc');
    //   $response = $client->request('GET', 'https://api-siasn.bkn.go.id/siasn-instansi/pengadaan/usulan/monitoring?jenis_pengadaan_id='.$jenis.'&status_usulan=&periode='.$tahun.'&limit='.$limit.'&offset='.$offset, [
      $response = $client->request('GET', 'https://api-siasn.bkn.go.id/siasn-instansi/pengadaan/usulan/monitoring?no_peserta='.$no_peserta.'&jenis_pengadaan_id='.$jenis.'&jenis_formasi_id='.$jenis_formasi_id.'&periode='.$tahun.'&limit='.$limit.'&offset='.$offset, [
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

          $model = new Pppkt2Model;
          $model->set('usul_id', $id);
          @$model->set('usul_unor_id', $row->usulan_data->data->unor_id);
          @$model->set('usul_unor_nama', $row->usulan_data->data->unor_nama);
          @$model->set('usul_unor_induk_nama', $row->usulan_data->data->unor_induk_nama);
          $model->set('usul_status', $status);
          $model->set('usul_alasan_tolak', $alasan);
          $model->set('usul_path_ttd_pertek', $pathpertek);
          $model->set('usul_no_pertek', $nopertek);
          $model->set('usul_path_ttd_sk', $row->path_ttd_sk);
          $model->set('usul_nip', $nip);
          $model->where('nopeserta', $nopeserta);
          $model->update();

        //   echo '<pre>'.$no_peserta.'</pre>';
      }
  }

  function sinkronpw() {
      $model = new ParuhwaktuModel;
      $data= $model->findAll();

        foreach($data as $row){
            $this->monitoringusulpw($row->no_peserta,2025,'02','0210',1,0);
        }

      // return redirect()->back()->with('message', 'Berhasil sinkron');
    }

    function monitoringusulpw($no_peserta,$tahun,$jenis,$jenis_formasi_id,$limit,$offset) {
      $client = service('curlrequest');
      $cache = service('cache');

      $token = $cache->get('zxc');
      $response = $client->request('GET', 'https://api-siasn.bkn.go.id/siasn-instansi/pengadaan/usulan/monitoring?no_peserta='.$no_peserta.'&jenis_pengadaan_id='.$jenis.'&jenis_formasi_id='.$jenis_formasi_id.'&periode='.$tahun.'&limit='.$limit.'&offset='.$offset, [
          'headers' => [
              'Authorization'     => 'Bearer '.$token,
          ],
          'verify' => false,
          'debug' => true,
      ]);

      // return $this->response->setJSON( $response->getBody() );
      $data = json_decode($response->getBody());

      /*
      {
  "id": "89ea9596-fe10-4cdf-81b8-1e71b50c6570",
  "orang_id": "0d8cb534-f81d-473f-81c3-afb848f29d7a",
  "usulan_data": {
    "data": {
      "formasi_isi": "",
      "formasi_jumlah": "",
      "formasi_lebih": "",
      "formasi_sisa": "",
      "gaji_pokok": "150000",
      "glr_belakang": "S.Pd",
      "glr_depan": "",
      "golongan_id": "00",
      "golongan_nama": "",
      "instansi_induk_id": "A5EB03E23BFBF6A0E040640A040252AD",
      "instansi_induk_nama": "Kementerian Agama",
      "instansi_kerja_id": "A5EB03E23BFBF6A0E040640A040252AD",
      "instansi_kerja_nama": "Kementerian Agama",
      "instansi_masa_kerja": "",
      "jabatan_fungsional_id": "",
      "jabatan_fungsional_nama": "",
      "jabatan_fungsional_umum_id": "F426F8A44B17A8BDE050640AF2083B83",
      "jabatan_fungsional_umum_nama": "PENATA LAYANAN OPERASIONAL",
      "jabatan_struktural_nama": "",
      "jenis_jabatan_id": 4,
      "jenis_jabatan_nama": "Jabatan Fungsional Umum",
      "jenis_masa_kerja": "",
      "kanreg_id": "00",
      "kanreg_nama": "Badan Kepegawaian Negara",
      "ket_bebas_narkoba_nomor": "",
      "ket_bebas_narkoba_pejabat": "0001-01-01",
      "ket_bebas_narkoba_tanggal": "0001-01-01",
      "ket_kelakuanbaik_nomor": "SKCK/YANMAS/6644/IX/2025",
      "ket_kelakuanbaik_pejabat": "ISWANDI",
      "ket_kelakuanbaik_tanggal": "2025-09-19",
      "ket_sehat_dokter": "DIAN PUTRI MADANISTI",
      "ket_sehat_nomor": "NO:400.7.22.1/UPT-SPK/SKD/IX/2025/739",
      "ket_sehat_tanggal": "2025-09-20",
      "kpkn_id": "",
      "kpkn_nama": "",
      "lokasi_id": "A5EB03E21F99F6A0E040640A040252AD",
      "lokasi_nama": "BENGKALIS",
      "masa_kerja_bulan": "0",
      "masa_kerja_bulan_pmk": "",
      "masa_kerja_tahun": "0",
      "masa_kerja_tahun_pmk": "",
      "nama": "RENITA DIRAYATI",
      "nama_sek": "Universitas Riau",
      "no_peserta": "PW24301230820037051",
      "nomor_ijazah": "872032020000209",
      "orang_id": "0d8cb534-f81d-473f-81c3-afb848f29d7a",
      "pendidikan_pertama_id": "A5EB03E21A6EF6A0E040640A040252AD",
      "pendidikan_pertama_nama": "S-1 PENDIDIKAN EKONOMI",
      "satuan_kerja_id": "A5EB03E240E3F6A0E040640A040252AD",
      "satuan_kerja_induk_id": "A5EB03E240E3F6A0E040640A040252AD",
      "satuan_kerja_induk_nama": "Kementerian Agama",
      "satuan_kerja_nama": "Kementerian Agama",
      "sub_jabatan_fungsional_id": "",
      "sub_jabatan_fungsional_nama": "",
      "tahun_gaji": "2024",
      "tahun_lulus": "2020",
      "tahun_masa_kerja": "",
      "tempat_lahir": "BENGKALIS",
      "tgl_akhir_masa_kerja": "",
      "tgl_awal_masa_kerja": "",
      "tgl_kontrak_akhir": "2026-09-30",
      "tgl_kontrak_mulai": "2025-10-01",
      "tgl_lahir": "1997-01-20",
      "tgl_tahun_lulus": "2020-03-02",
      "tk_pendidikan_id": "40",
      "tmt_cpns": "2025-10-01",
      "unor_id": "39384222040C6702E050640A29027F86",
      "unor_induk": "39384222040B6702E050640A29027F86",
      "unor_induk_nama": "MTsN 3 Bengkalis",
      "unor_nama": "Urusan Tata Usaha - MTsN 3 Bengkalis"
    }
  },
  "status_usulan": 30,
  "dokumen_usulan": {
    "2248": {
      "object": "pengadaan/usulan/679b9620-9463-5b78-80b4-aa34d41f4fda.pdf",
      "slug": "2248",
      "status": "0"
    },
    "2249": {
      "object": "pengadaan/usulan/e466e403-910d-5b84-b65b-a8965d598d81.pdf",
      "slug": "2249",
      "status": "0"
    },
    "2251": {
      "object": "pengadaan/usulan/68d97437-1125-5e20-bab2-d356b81f13e6.pdf",
      "slug": "2251",
      "status": ""
    },
    "2253": {
      "object": "pengadaan/usulan/e934b840-3b82-5130-b258-47b2b2eb5a5a.jpg",
      "slug": "2253",
      "status": "0"
    },
    "2255": {
      "object": "pengadaan/usulan/7a93f426-fcb5-5a82-b3be-12f22b26bbe5.pdf",
      "slug": "2255",
      "status": "0"
    },
    "2256": {
      "object": "pengadaan/usulan/b26cd578-f676-5e4c-ba2c-a87176ce66a2.pdf",
      "slug": "2256",
      "status": "0"
    },
    "2257": {
      "object": "pengadaan/usulan/22b46aef-89da-5d2c-ab95-90c9098656dc.pdf",
      "slug": "2257",
      "status": "0"
    }
  },
  "tgl_usulan": "2025-09-26T00:00:00Z",
  "tgl_pengiriman_kelayanan": "2025-11-27T00:00:00Z",
  "tgl_update_layanan": "2025-11-27T00:00:00Z",
  "instansi_id": "A5EB03E23BFBF6A0E040640A040252AD",
  "keterangan": "TTE sukses",
  "status_aktif": 1,
  "no_surat_usulan": "",
  "status_paraf_pertek": 0,
  "status_ttd_paraf_pertek": 1,
  "provinsi_nama": "",
  "nip": "199701202025212130",
  "nama": "RENITA DIRAYATI",
  "instansi_nama": "Kementerian Agama",
  "jenis_layanan_nama": "Pengadaan",
  "no_surat_keluar": "",
  "tgl_surat_usulan": null,
  "path_pertek": "pertek/BKN.PT.01.04/no-sign/b6af53e2ced213eb27ec54b4c1e01c8d70a3a82a9b82f85c80245466-2025-11-27T05:28:47.686174/b6af53e2ced213eb27ec54b4c1e01c8d70a3a82a9b82f85c80245466-2025-11-27T05:28:47.686174.pdf",
  "path_ttd_pertek": "pertek/BKN.PT.01.04/sign-final/b6af53e2ced213eb27ec54b4c1e01c8d70a3a82a9b82f85c80245466-2025-11-27T05:28:47.686174/b6af53e2ced213eb27ec54b4c1e01c8d70a3a82a9b82f85c80245466-2025-11-27T05:28:47.686174.pdf",
  "path_surat_usulan": "",
  "pejabat_paraf_id": "",
  "pejabat_ttd_id": "",
  "tgl_surat_keluar": null,
  "uraian_perbaikan": "",
  "uraian_pembatalan": "",
  "nama_tabel_riwayat": "",
  "id_riwayat_update": "",
  "no_sk": "3575/Kw.04.1/3/Kp.00.3/09/SK/2025",
  "path_paraf_sk": "",
  "pejabat_paraf_sk": "",
  "status_paraf_sk": "",
  "tgl_paraf_sk": null,
  "path_ttd_sk": "sk-instansi-A5EB03E23BFBF6A0E040640A040252AD/INS.SK.01.04/sign-final/82821a5be0dd11294f5d2fc382af617141bc851fa98ec184f9a73964-2025-11-27T09-09-49.203991/82821a5be0dd11294f5d2fc382af617141bc851fa98ec184f9a73964-2025-11-27T09-09-49.203991.pdf",
  "pejabat_ttd_sk": "196910011997031004",
  "tgl_ttd_sk": null,
  "status_ttd_sk": "1",
  "no_pertek": "AAA-12018016824",
  "referensi_instansi": 0,
  "tgl_sk": "2025-09-29T00:00:00Z",
  "tgl_pertek": "2025-09-27T00:00:00Z",
  "alasan_tolak_id": 0,
  "alasan_tolak_tambahan": "",
  "generated_nomor": false,
  "periode": "2025",
  "jenis_formasi_id": "0210",
  "jenis_formasi_nama": "PPPK PARUH WAKTU",
  "jenis_pegawai_id": "01",
  "satuan_kerja_induk_id": "A5EB03E240E3F6A0E040640A040252AD",
  "satuan_kerja_induk_nama": "Kementerian Agama",
  "instansi_induk_id": "A5EB03E23BFBF6A0E040640A040252AD",
  "instansi_induk_nama": "Kementerian Agama",
  "tgl_kontrak_mulai": "",
  "tgl_kontrak_akhir": "",
  "no_urut": 0,
  "nip_approval": "198512102006041008",
  "path_dokumen_pembatalan": "",
  "tahun_formasi": 0,
  "flag_otomatisasi": 0,
  "dokumen_baru": "",
  "dokumen_lama": "",
  "flag_perbaikan_dokumen": "",
  "pendidikan_ijazah_nama": ""
}
      */

      foreach ($data->data as $row) {
          $nopeserta = $row->usulan_data->data->no_peserta;
          $id = $row->id;
          $status = siasn_usul_status($row->status_usulan);
          $alasan = $row->alasan_tolak_tambahan;
          $pathpertek = $row->path_ttd_pertek; 
          $nopertek = $row->no_pertek;
          $nip = $row->nip;

          $model = new ParuhwaktuModel;
          $model->set('usul_id', $id);
          $model->set('usul_status', $status);
          $model->set('usul_alasan_tolak', $alasan);
          $model->set('usul_path_ttd_pertek', $pathpertek);
          $model->set('usul_no_pertek', $nopertek);
          $model->set('usul_nip', $nip);
          $model->set('usul_pendidikan', $row->usulan_data->data->pendidikan_pertama_nama);
          @$model->set('unor_id', $row->usulan_data->data->unor_id);
          @$model->set('unor_induk', $row->usulan_data->data->unor_induk);
          @$model->set('unor_induk_nama', $row->usulan_data->data->unor_induk_nama);
          @$model->set('unor_nama', $row->usulan_data->data->unor_nama);
          $model->set('usul_pendidikan_tahun', $row->usulan_data->data->tahun_lulus);
          $model->set('usul_jabatan_id', $row->usulan_data->data->jabatan_fungsional_umum_id);
          $model->set('usul_jabatan', $row->usulan_data->data->jabatan_fungsional_umum_nama);
          $model->set('tmt_cpns', $row->usulan_data->data->tmt_cpns);
          $model->set('usul_sk_nomor', $row->no_sk);
          $model->set('usul_sk_file', $row->path_ttd_sk);
          $model->where('no_peserta', $nopeserta);
          $model->update();

        //   echo '<pre>'.$no_peserta.'</pre>';
      }
  }
}
