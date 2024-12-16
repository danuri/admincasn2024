<?php

namespace App\Controllers\Skb;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\CrudModel;
use App\Models\PesertaModel;
use \Hermawan\DataTables\DataTable;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Peserta extends BaseController
{
    public function index()
    {
        $kodesatker = session('lokasi');
        $crud = new CrudModel();
        $db = \Config\Database::connect('default', false);
        // $data['peserta'] = $crud->getResult('peserta', array('kode_satker'=>$kodesatker));
        $data['lokasi'] = $db->query("SELECT a.lokasi_titik, a.lokasi_kabupaten, a.lokasi_provinsi, b.tilok, b.alamat, b.maps, b.kontak_panitia, COUNT(a.nik) AS jumlah FROM peserta a
                                            LEFT JOIN lokasi_titik b ON b.lokasi_kode=a.lokasi_kode
                                            WHERE a.kode_satker='$kodesatker'
                                            GROUP BY a.lokasi_titik,a.lokasi_kabupaten, a.lokasi_provinsi")->getResult();

        // $data['jabatans'] = $this->crud->preport_jabatan($satker->kode_satker_bkn_new);
        //$this->load->tpl('skb/peserta', $data);
        return view('skb/peserta', $data);
    }

    public function getdata()
    {
      $db = \Config\Database::connect('default', false);
      $kodesatker = session('lokasi');
      $builder = $db->table('peserta')
                    ->select('nik,nopeserta,nama,agama,no_hp,formasi,jenis,kelompok,skb_jadwal_hari,skb_jadwal_tanggal,lokasi_provinsi,lokasi_kabupaten,jadwal_praktik,jadwal_wawancara')
                    ->where('kode_satker',$kodesatker);

      return DataTable::of($builder)
      ->edit('nik', function($row, $meta){
        return '<a href="javascript:;" onclick="detail(\''.(string) $row->nik.'\')" class="text-danger">'.$row->nik.'</a>';
        })
      ->toJson(true);
    }

    public function drh($nik) {
        return redirect()->to('http://localhost:8001/drh/peserta/'+$nik);
    }

    public function detail($id)
    {
        $satker = session('lokasi');
        $model = new CrudModel;

        $peserta = $model->getRow('peserta',array('nik'=>$id,'kode_satker'=>$satker));

        if(!$peserta){
            echo 'Data peserta tidak ditemukan!';
            exit;
        }
        // $pendidikan = $model->getResult('pendidikan',array('nik'=>$id),array('field'=>'tahun','by'=>'ASC'));
        // $pekerjaan = $model->getResult('pekerjaan',array('nik'=>$id),array('field'=>'mulai_tahun','by'=>'ASC'));
        // $organisasi = $model->getResult('organisasi',array('nik'=>$id),array('field'=>'mulai_tahun','by'=>'ASC'));
        $db = \Config\Database::connect('default', false);
        $pendidikan = $db->query("SELECT * FROM pendidikan WHERE nik='$id' ORDER BY tahun DESC")->getResult();
        // dd($pendidikan);
        $pekerjaan = $db->query("SELECT * FROM pekerjaan WHERE nik='$id' ORDER BY mulai_tahun DESC")->getResult();
        $organisasi = $db->query("SELECT * FROM organisasi WHERE nik='$id' ORDER BY mulai_tahun DESC")->getResult();
        $dokumen = $model->getResult('dokumen_peserta',array('nik'=>$id));
        ?>
            <table class="table table-striped">
            <tbody>
                <tr>
                <th></th>
                <td><img src="https://casn.kemenag.go.id/peserta/uploads/foto/<?php echo $peserta->nopeserta.'.jpg';?>" width="200px"></td>
                </tr>
                    <tr>
                <th>NIK</th>
                <td><?php echo $peserta->nik;?></td>
                </tr>
                <tr>
                <th>Nomor Peserta</th>
                <td><?php echo $peserta->nopeserta;?></td>
                </tr>
                <tr>
                <th>Nama</th>
                <td><?php echo $peserta->nama;?></td>
                </tr>
                <tr>
                <th>Formasi</th>
                <td><?php echo $peserta->formasi;?></td>
                </tr>
                <tr>
                <th>Jenis</th>
                <td><?php echo $peserta->jenis;?></td>
                </tr>
                <tr>
                <th>Jenis Disabilitas</th>
                <td><?php echo $peserta->disabilitas;?></td>
                </tr>
                <tr>
                <th>Email</th>
                <td><?php echo $peserta->email;?></td>
                </tr>
                <tr>
                <th>No HP</th>
                <td><?php echo $peserta->no_hp;?></td>
                </tr>
                <tr>
                <th>Agama</th>
                <td><?php echo $peserta->agama;?></td>
                </tr>
                <?php if(substr($peserta->kode_formasi,0, 6) == 'JFGURU'){?>
                <tr>
                <th>Sertifikat Pendidik</th>
                <td><?php echo ($peserta->serdik == '')?'(Tidak mengunggah)':'<a href="https://casn.kemenag.go.id/peserta/uploads/serdik/'.$peserta->serdik.'" target="_blank">Lihat</a>';?></td>
                </tr>
                <tr>
                <th>Linier</th>
                <td><?php echo ($peserta->serdik_linier == 1)?'Ya':'Tidak';?></td>
                </tr>
                <?php }?>
                <tr>
                <th>Facebook</th>
                <td><a href="https://www.facebook.com/search/top?q=<?php echo urlencode($peserta->facebook);?>" target="_blank"><?php echo $peserta->facebook;?></td>
                </tr>
                <tr>
                <th>Instagram</th>
                <td><a href="https://www.instagram.com/<?php echo $peserta->instagram;?>" target="_blank"><?php echo $peserta->instagram;?></a></td>
                </tr>
                <tr>
                <th>Twitter</th>
                <td><a href="https://www.twitter.com/<?php echo $peserta->twitter;?>" target="_blank"><?php echo $peserta->twitter;?></a></td>
                </tr>
            </tbody>
            </table>

            <h4>Riwayat Pendidikan</h4>
            <table class="table table-bordered table-striped">
            <thead>
                <tr>
                <th>Tahun</th>
                <th>Jenjang</th>
                <th>Nama Sekolah/PT</th>
                <th>Jurusan</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pendidikan as $row) {?>
                <tr>
                    <td><?php echo $row->tahun;?></td>
                    <td><?php echo $row->jenjang;?></td>
                    <td><?php echo $row->nama;?></td>
                    <td><?php echo $row->jurusan;?></td>
                </tr>
                <?php } ?>
            </tbody>
            </table>
            <h4>Riwayat Pekerjaan</h4>
            <table class="table table-bordered table-striped">
            <thead>
                <tr>
                <th>Tahun</th>
                <th>Perusahaan/Instansi</th>
                <th>Jabatan</th>
                <th>Lampiran</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pekerjaan as $row) {?>
                <tr>
                    <td><?php echo $row->mulai_tahun.' - '.$row->sampai_tahun;?></td>
                    <td><?php echo $row->nama;?></td>
                    <td><?php echo $row->jabatan;?></td>
                    <td><?php echo ($row->lampiran == '')?'(Tidak mengunggah)':'<a href="https://casn.kemenag.go.id/peserta/uploads/pekerjaan/'.$row->lampiran.'" target="_blank">Lampiran</a>';?></td>
                </tr>
                <?php } ?>
            </tbody>
            </table>
            <h4>Riwayat Organisasi</h4>
            <table class="table table-bordered table-striped">
            <thead>
                <tr>
                <th>Tahun</th>
                <th>Organisasi</th>
                <th>Jabatan</th>
                <th>Lokasi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($organisasi as $row) {?>
                <tr>
                    <td><?php echo $row->mulai_tahun.' - '.$row->sampai_tahun;?></td>
                    <td><?php echo $row->nama;?></td>
                    <td><?php echo $row->jabatan;?></td>
                    <td><?php echo $row->lokasi;?></td>
                </tr>
                <?php } ?>
            </tbody>
            </table>
            <h4>Riwayat Dokumen</h4>
            <table class="table table-bordered table-striped">
            <thead>
                <tr>
                <th>Dokumen</th>
                <th>Keterangan</th>
                <th>Tahun</th>
                <th>Lampiran</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dokumen as $row) {?>
                <tr>
                    <td><?php echo $row->nama;?></td>
                    <td><?php echo $row->keterangan;?></td>
                    <td><?php echo $row->tahun;?></td>
                    <td><a href="<?php echo 'https://casn.kemenag.go.id/peserta/uploads/pekerjaan/'.$row->lampiran;?>" target="_blank">Lampiran</a></td>
                </tr>
                <?php } ?>
            </tbody>
            </table>
        <?php
    }

    public function export()
    {
      $kodesatker = session('lokasi');
      $crud = new CrudModel();
      $data = $crud->getResult('peserta', array('kode_satker'=>$kodesatker));

      $spreadsheet = new Spreadsheet();
      $sheet = $spreadsheet->getActiveSheet();

      $sheet->setCellValue('A1', 'NIK');
      $sheet->setCellValue('B1', 'NO PESERTA');
      $sheet->setCellValue('C1', 'NAMA');
      $sheet->setCellValue('D1', 'AGAMA');
      $sheet->setCellValue('E1', 'TANGGAL LAHIR');
      $sheet->setCellValue('F1', 'EMAIL');
      $sheet->setCellValue('G1', 'NO. HP');
      $sheet->setCellValue('H1', 'FORMASI');
      $sheet->setCellValue('I1', 'PENDIDIKAN');
      $sheet->setCellValue('J1', 'JENIS');
      $sheet->setCellValue('K1', 'KELOMPOK');
      $sheet->setCellValue('L1', 'LOKASI PROVINSI');
      $sheet->setCellValue('M1', 'LOKASI KOTA/KABUPATEN');
      $sheet->setCellValue('N1', 'SKB LOKASI');
      $sheet->setCellValue('O1', 'SKB JADWAL TANGGAL');
      $sheet->setCellValue('P1', 'SKB JADWAL HARI');
      $sheet->setCellValue('Q1', 'PRAKTIK KERJA (WIB)');
      $sheet->setCellValue('R1', 'WAWANCARA (WIB)');
      $sheet->setCellValue('S1', 'INSTAGRAM');
      $sheet->setCellValue('T1', 'FACEBOOK');
      $sheet->setCellValue('U1', 'TWITTER');

      $i = 2;
      foreach ($data as $row) {
        $sheet->getCell('A'.$i)->setValueExplicit($row->nik,\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->getCell('B'.$i)->setValueExplicit($row->nopeserta,\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('C'.$i, $row->nama);
        $sheet->setCellValue('D'.$i, $row->agama);
        $sheet->setCellValue('E'.$i, $row->tanggal_lahir);
        $sheet->setCellValue('F'.$i, $row->email);
        $sheet->setCellValue('G'.$i, $row->no_hp);
        $sheet->setCellValue('H'.$i, $row->formasi);
        $sheet->setCellValue('I'.$i, $row->pendidikan);
        $sheet->setCellValue('J'.$i, $row->jenis);
        $sheet->setCellValue('K'.$i, $row->kelompok);
        $sheet->setCellValue('L'.$i, $row->lokasi_provinsi);
        $sheet->setCellValue('M'.$i, $row->lokasi_kabupaten);
        $sheet->setCellValue('N'.$i, $row->skb_lokasi);
        $sheet->setCellValue('O'.$i, $row->skb_jadwal_tanggal);
        $sheet->setCellValue('P'.$i, $row->skb_jadwal_hari);
        $sheet->setCellValue('Q'.$i, $row->jadwal_praktik);
        $sheet->setCellValue('R'.$i, $row->jadwal_wawancara);
        $sheet->setCellValue('S'.$i, $row->instagram);
        $sheet->setCellValue('T'.$i, $row->facebook);
        $sheet->setCellValue('U'.$i, $row->twitter);
        $i++;
      }

      $tanggal = date('YmdHis');
      $writer = new Xlsx($spreadsheet);
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment; filename="Data_Peserta_'.$tanggal.'.xlsx"');
      $writer->save('php://output');
    }
}
