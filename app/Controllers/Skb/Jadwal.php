<?php

namespace App\Controllers\Skb;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\CrudModel;
use App\Models\ZoomsModel;
use App\Models\PesertaModel;
use DateTime;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Jadwal extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect('default', false);
        $satker = session('lokasi');
        $data['peserta'] = $db->query("SELECT
                                            peserta.*,
                                            zooms.*
                                            FROM
                                            peserta
                                            INNER JOIN zooms ON peserta.nopeserta = zooms.nopeserta
                                            WHERE peserta.kode_satker='$satker'")->getResult();
        $data['accounts'] = $db->query("SELECT DISTINCT(email_praktik) FROM zooms
                                            WHERE kode_satker='$satker'")->getResult();
        return view('skb/jadwal', $data);
    }

    public function importjadwal() 
    {
        $fname = time();
        $file = $this->request->getFile('lampiran');
        $model = new CrudModel;

        // Check if the file is valid
        if ($file->isValid() && !$file->hasMoved()) {
            $uploadPath = './temp/';
            $file->move($uploadPath, $fname . '.xlsx');

            $inputFileType = 'Xlsx';
            $inputFileName = $uploadPath . $fname . '.xlsx';

            $reader = IOFactory::createReader($inputFileType);
            $spreadsheet = $reader->load($inputFileName);
            $sheetCol = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
            $sheetData = (object) $sheetCol;

            // if(count($sheetCol) < 15){
            //     return redirect()->back()->with('message', 'Template tidak sesuai. Gunakan template terbaru. '.count($sheetCol));
            // }

            $satker = session()->get('lokasi');

            $i = 0;
            foreach ($sheetData as $row) {
                if ($i > 0) {
                    $nopeserta = preg_replace('/[\x{200B}-\x{200D}]/u', '', $row['A']);
                    $nopeserta = str_replace("'", "", $nopeserta);
                    $idsesiprak = $row['B'];
                    $emailprak = $row['C'];
                    $zoompraktik = $row['D'];
                    $passpraktik = $row['E'];
                    $idsesiwaw = $row['F'];
                    $emailwaw = $row['G'];
                    $zoomwawancara = $row['H'];
                    $passwawancara = $row['I'];
                    $pengujiprak1 = $row['J'];
                    $pengujiprak2 = $row['K'];
                    $pengujiprak3 = $row['L'];
                    $pengujiwaw1 = $row['M'];
                    $pengujiwaw2 = $row['N'];
                    $pengujiwaw3 = $row['O'];

                    // Fetch names of the interviewers
                    
                    
                    $countpeserta = $model->getCountv2('zooms','nopeserta',$nopeserta);
                                       
                    $zooms = new ZoomsModel();
                    // $data = [
                    //     'email_praktik' => $emailprak,
                    //     'id_praktik' => $zoompraktik,
                    //     'password_praktik' => $passpraktik,
                    //     'penguji1' => $pengujiprak1,
                    //     'nama_penguji1' => $nama_penguji1,
                    //     'penguji2' => $pengujiprak2,
                    //     'nama_penguji2' => $nama_penguji2,
                    //     'penguji3' => $pengujiprak3,
                    //     'nama_penguji3' => $nama_penguji3,
                    //     'email_wawancara' => $emailwaw,
                    //     'id_wawancara' => $zoomwawancara,
                    //     'password_wawancara' => $passwawancara,
                    //     'pewawancara1' => $pengujiwaw1,
                    //     'nama_pewawancara1' => $nama_pewawancara1,
                    //     'pewawancara2' => $pengujiwaw2,
                    //     'nama_pewawancara2' => $nama_pewawancara2,
                    //     'pewawancara3' => $pengujiwaw3,
                    //     'nama_pewawancara3' => $nama_pewawancara3,
                    //     'kode_satker' => $satker
                    // ];
                    // $where = array(
                    //     'nopeserta' => $nopeserta,
                    // );
                    // $zooms->set($data)->where($where)->update();

                    // $sesipraktik = $model->getRow('sesi', ['sesi' => $idsesiprak]);
                    // $sesiwawancara = $model->getRow('sesi', ['sesi ' => $idsesiwaw]);

                    // $data = [
                    //     'jadwal_praktik' => date('Y-m-d H:i:s', strtotime($sesipraktik->tanggal . ' ' . $sesipraktik->pukul .':00')),
                    //     'jadwal_wawancara' => date('Y-m-d H:i:s', strtotime( $sesiwawancara->tanggal . ' ' . $sesiwawancara->pukul .':00'))
                    // ];
                    
                    // $peserta = new PesertaModel();  
                    // $peserta ->set($data)->where($where)->update();
                    
                    if ($countpeserta > 0 && $nopeserta <> '' && $nopeserta <> null) {                        
                                           
                        //dd($countpeserta);
                        //$zooms = new ZoomsModel();
                        // $data = [
                        //     'email_praktik' => $emailprak,
                        //     'id_praktik' => $zoompraktik,
                        //     'password_praktik' => $passpraktik,
                        //     'penguji1' => $pengujiprak1,
                        //     'nama_penguji1' => $nama_penguji1,
                        //     'penguji2' => $pengujiprak2,
                        //     'nama_penguji2' => $nama_penguji2,
                        //     'penguji3' => $pengujiprak3,
                        //     'nama_penguji3' => $nama_penguji3,
                        //     'email_wawancara' => $emailwaw,
                        //     'id_wawancara' => $zoomwawancara,
                        //     'password_wawancara' => $passwawancara,
                        //     'pewawancara1' => $pengujiwaw1,
                        //     'nama_pewawancara1' => $nama_pewawancara1,
                        //     'pewawancara2' => $pengujiwaw2,
                        //     'nama_pewawancara2' => $nama_pewawancara2,
                        //     'pewawancara3' => $pengujiwaw3,
                        //     'nama_pewawancara3' => $nama_pewawancara3,
                        //     'kode_satker' => $satker
                        // ];

                        $data = [];
                        $datapeserta = [];

                        if($idsesiprak){
                            $namaprak1 = $model->getRow('penguji', ['nip' => preg_replace('/[\x{200B}-\x{200D}]/u', '', $pengujiprak1), 'type' => 'Praktik Kerja']);
                            $namaprak2 = $model->getRow('penguji', ['nip' => preg_replace('/[\x{200B}-\x{200D}]/u', '', $pengujiprak2), 'type' => 'Praktik Kerja']);
                            $namaprak3 = $model->getRow('penguji', ['nip' => preg_replace('/[\x{200B}-\x{200D}]/u', '', $pengujiprak3), 'type' => 'Praktik Kerja']);

                            // Safely access nama property for namaprak1
                            $nama_penguji1 = isset($namaprak1) ? $namaprak1->nama : ''; // or set a default value

                            // Safely access nama property for namaprak2
                            $nama_penguji2 = isset($namaprak2) ? $namaprak2->nama : ''; // or set a default value

                            // Safely access nama property for namaprak3
                            $nama_penguji3 = isset($namaprak3) ? $namaprak3->nama : ''; // or set a default value

                            $data['email_praktik'] = $emailprak;
                            $data['id_praktik'] = $zoompraktik;
                            $data['password_praktik'] = $passpraktik;
                            $data['penguji1'] = $pengujiprak1;
                            $data['nama_penguji1'] = $nama_penguji1;
                            $data['penguji2'] = $pengujiprak2;
                            $data['nama_penguji2'] = $nama_penguji2;
                            $data['penguji3'] = $pengujiprak3;
                            $data['nama_penguji3'] = $nama_penguji3;

                            // Update Peserta
                            $sesipraktik = $model->getRow('sesi', ['sesi' => $idsesiprak]);
                            $tglpraktik = isset($sesipraktik->tanggal) ? $sesipraktik->tanggal : null;
                            $pukulpraktik = isset($sesipraktik->pukul) ? $sesipraktik->pukul : null;
                            $datapeserta['jadwal_praktik'] = date('Y-m-d H:i:s', strtotime($tglpraktik . ' ' . $pukulpraktik .':00'));
                        }

                        if($idsesiwaw){
                            $namaw1 = $model->getRow('penguji', ['nip' => preg_replace('/[\x{200B}-\x{200D}]/u', '', $pengujiwaw1), 'type' => 'Wawancara']);
                            $namaw2 = $model->getRow('penguji', ['nip' => preg_replace('/[\x{200B}-\x{200D}]/u', '', $pengujiwaw2), 'type' => 'Wawancara']);
                            $namaw3 = $model->getRow('penguji', ['nip' => preg_replace('/[\x{200B}-\x{200D}]/u', '', $pengujiwaw3), 'type' => 'Wawancara']);

                            // Safely access nama property for namaw1
                            $nama_pewawancara1 = isset($namaw1) ? $namaw1->nama : ''; // or set a default value

                            // Safely access nama property for namaw1
                            $nama_pewawancara2 = isset($namaw2) ? $namaw2->nama : ''; // or set a default value

                            // Safely access nama property for namaw1
                            $nama_pewawancara3 = isset($namaw3) ? $namaw3->nama : ''; // or set a default value

                            $data['email_wawancara'] = $emailwaw;
                            $data['id_wawancara'] = $zoomwawancara;
                            $data['password_wawancara'] = $passwawancara;
                            $data['pewawancara1'] = $pengujiwaw1;
                            $data['nama_pewawancara1'] = $nama_pewawancara1;
                            $data['pewawancara2'] = $pengujiwaw2;
                            $data['nama_pewawancara2'] = $nama_pewawancara2;
                            $data['pewawancara3'] = $pengujiwaw3;
                            $data['nama_pewawancara3'] = $nama_pewawancara3;

                            // Update Peserta
                            $sesiwawancara = $model->getRow('sesi', ['sesi ' => $idsesiwaw]);
                            $tglwawancara = isset($sesiwawancara->tanggal) ? $sesiwawancara->tanggal : null;
                            $pukulwawancara = isset($sesiwawancara->pukul) ? $sesiwawancara->pukul : null;
                            $datapeserta['jadwal_wawancara'] = date('Y-m-d H:i:s', strtotime($tglwawancara . ' ' . $pukulwawancara .':00'));
                        }
                        //dd($data);

                        $where = array(
                            'nopeserta' => $nopeserta,
                        );

                        $zooms->set($data)->where($where)->update();

                        // $sesipraktik = $model->getRow('sesi', ['sesi' => $idsesiprak]);
                        // $sesiwawancara = $model->getRow('sesi', ['sesi ' => $idsesiwaw]);
                        
                        // $tglpraktik = isset($sesipraktik->tanggal) ? $sesipraktik->tanggal : null;
                        // $tglwawancara = isset($sesiwawancara->tanggal) ? $sesiwawancara->tanggal : null;
                        // $pukulpraktik = isset($sesipraktik->pukul) ? $sesipraktik->pukul : null;
                        // $pukulwawancara = isset($sesiwawancara->pukul) ? $sesiwawancara->pukul : null;

                        // $data = [
                        //     'jadwal_praktik' => date('Y-m-d H:i:s', strtotime($tglpraktik . ' ' . $pukulpraktik .':00')),
                        //     'jadwal_wawancara' => date('Y-m-d H:i:s', strtotime($tglwawancara . ' ' . $pukulwawancara .':00'))
                        // ];
                        
                        $peserta = new PesertaModel();  
                        $peserta ->set($datapeserta)->where($where)->update();
                    } elseif ($countpeserta == 0 && $nopeserta <> '' && $nopeserta <> null) {
                        // $param = [
                        //     'email_praktik' => $emailprak,
                        //     'id_praktik' => $zoompraktik,
                        //     'password_praktik' => $passpraktik,
                        //     'penguji1' => $pengujiprak1,
                        //     'nama_penguji1' => $nama_penguji1,
                        //     'penguji2' => $pengujiprak2,
                        //     'nama_penguji2' => $nama_penguji2,
                        //     'penguji3' => $pengujiprak3,
                        //     'nama_penguji3' => $nama_penguji3,
                        //     'email_wawancara' => $emailwaw,
                        //     'id_wawancara' => $zoomwawancara,
                        //     'password_wawancara' => $passwawancara,
                        //     'pewawancara1' => $pengujiwaw1,
                        //     'nama_pewawancara1' => $nama_pewawancara1,
                        //     'pewawancara2' => $pengujiwaw2,
                        //     'nama_pewawancara2' => $nama_pewawancara2,
                        //     'pewawancara3' => $pengujiwaw3,
                        //     'nama_pewawancara3' => $nama_pewawancara3,
                        //     'kode_satker' => $satker,
                        //     'nopeserta' => $nopeserta
                        // ];
                        $data = [];
                        $datapeserta = [];
                        $data = [
                            'kode_satker' => $satker,
                            'nopeserta' => $nopeserta
                        ];

                        if($idsesiprak){
                            $namaprak1 = $model->getRow('penguji', ['nip' => preg_replace('/[\x{200B}-\x{200D}]/u', '', $pengujiprak1), 'type' => 'Praktik Kerja']);
                            $namaprak2 = $model->getRow('penguji', ['nip' => preg_replace('/[\x{200B}-\x{200D}]/u', '', $pengujiprak2), 'type' => 'Praktik Kerja']);
                            $namaprak3 = $model->getRow('penguji', ['nip' => preg_replace('/[\x{200B}-\x{200D}]/u', '', $pengujiprak3), 'type' => 'Praktik Kerja']);

                            // Safely access nama property for namaprak1
                            $nama_penguji1 = isset($namaprak1) ? $namaprak1->nama : ''; // or set a default value

                            // Safely access nama property for namaprak2
                            $nama_penguji2 = isset($namaprak2) ? $namaprak2->nama : ''; // or set a default value

                            // Safely access nama property for namaprak3
                            $nama_penguji3 = isset($namaprak3) ? $namaprak3->nama : ''; // or set a default value

                            $data['email_praktik'] = $emailprak;
                            $data['id_praktik'] = $zoompraktik;
                            $data['password_praktik'] = $passpraktik;
                            $data['penguji1'] = $pengujiprak1;
                            $data['nama_penguji1'] = $nama_penguji1;
                            $data['penguji2'] = $pengujiprak2;
                            $data['nama_penguji2'] = $nama_penguji2;
                            $data['penguji3'] = $pengujiprak3;
                            $data['nama_penguji3'] = $nama_penguji3;

                            // Update Peserta
                            $sesipraktik = $model->getRow('sesi', ['sesi' => $idsesiprak]);
                            $tglpraktik = isset($sesipraktik->tanggal) ? $sesipraktik->tanggal : null;
                            $pukulpraktik = isset($sesipraktik->pukul) ? $sesipraktik->pukul : null;
                            $datapeserta['jadwal_praktik'] = date('Y-m-d H:i:s', strtotime($tglpraktik . ' ' . $pukulpraktik .':00'));
                        }

                        if($idsesiwaw){
                            $namaw1 = $model->getRow('penguji', ['nip' => preg_replace('/[\x{200B}-\x{200D}]/u', '', $pengujiwaw1), 'type' => 'Wawancara']);
                            $namaw2 = $model->getRow('penguji', ['nip' => preg_replace('/[\x{200B}-\x{200D}]/u', '', $pengujiwaw2), 'type' => 'Wawancara']);
                            $namaw3 = $model->getRow('penguji', ['nip' => preg_replace('/[\x{200B}-\x{200D}]/u', '', $pengujiwaw3), 'type' => 'Wawancara']);

                            // Safely access nama property for namaw1
                            $nama_pewawancara1 = isset($namaw1) ? $namaw1->nama : ''; // or set a default value

                            // Safely access nama property for namaw1
                            $nama_pewawancara2 = isset($namaw2) ? $namaw2->nama : ''; // or set a default value

                            // Safely access nama property for namaw1
                            $nama_pewawancara3 = isset($namaw3) ? $namaw3->nama : ''; // or set a default value

                            $data['email_wawancara'] = $emailwaw;
                            $data['id_wawancara'] = $zoomwawancara;
                            $data['password_wawancara'] = $passwawancara;
                            $data['pewawancara1'] = $pengujiwaw1;
                            $data['nama_pewawancara1'] = $nama_pewawancara1;
                            $data['pewawancara2'] = $pengujiwaw2;
                            $data['nama_pewawancara2'] = $nama_pewawancara2;
                            $data['pewawancara3'] = $pengujiwaw3;
                            $data['nama_pewawancara3'] = $nama_pewawancara3;

                            // Update Peserta
                            $sesiwawancara = $model->getRow('sesi', ['sesi ' => $idsesiwaw]);
                            $tglwawancara = isset($sesiwawancara->tanggal) ? $sesiwawancara->tanggal : null;
                            $pukulwawancara = isset($sesiwawancara->pukul) ? $sesiwawancara->pukul : null;
                            $datapeserta['jadwal_wawancara'] = date('Y-m-d H:i:s', strtotime($tglwawancara . ' ' . $pukulwawancara .':00'));
                        }
                        //dd($peserta);
                        $zooms->insert($data);

                        // $sesipraktik = $model->getRow('sesi', ['sesi' => $idsesiprak]);
                        // $sesiwawancara = $model->getRow('sesi', ['sesi ' => $idsesiwaw]);
                        // $tglpraktik = isset($sesipraktik->tanggal) ? $sesipraktik->tanggal : null;
                        // $tglwawancara = isset($sesiwawancara->tanggal) ? $sesiwawancara->tanggal : null;
                        // $pukulpraktik = isset($sesipraktik->pukul) ? $sesipraktik->pukul : null;
                        // $pukulwawancara = isset($sesiwawancara->pukul) ? $sesiwawancara->pukul : null;
                        // $data = [
                        //     'jadwal_praktik' => date('Y-m-d H:i:s', strtotime($tglpraktik . ' ' . $pukulpraktik .':00')),
                        //     'jadwal_wawancara' => date('Y-m-d H:i:s', strtotime( $tglwawancara . ' ' . $pukulwawancara .':00'))
                        // ];
                        $where = array(
                            'nopeserta' => $nopeserta,
                        );
                        $peserta = new PesertaModel();  
                        $peserta ->set($datapeserta)->where($where)->update();
                    }
                }
                $i++;
            }

            session()->setFlashdata('message', 'Selesai');
        } else {
            session()->setFlashdata('message', $file->getErrorString());
        }

        return redirect()->to('skb/jadwal');        
    }

    public function delete($id, $idpeserta) {
        $zooms = new ZoomsModel();
        $peserta = new PesertaModel();
        $zooms->where('id', $id)->delete();
        $data = [
            'jadwal_praktik' => null,            
            'jadwal_wawancara' => null
        ];
        $peserta->set($data)->where('nopeserta', $idpeserta)->update();
        session()->setFlashdata('message', 'Jadwal telah dihapus');
        return redirect()->to('skb/jadwal');
    }

    public function export()
    {
      $kodesatker = session('lokasi');
      $crud = new CrudModel();
      $data = $crud->getResult('zooms', array('kode_satker'=>$kodesatker));

      $spreadsheet = new Spreadsheet();
      $sheet = $spreadsheet->getActiveSheet();

      $sheet->setCellValue('A1', 'NO PESERTA');
      $sheet->setCellValue('B1', 'EMAIL ZOOM PRAKTIK KERJA');
      $sheet->setCellValue('C1', 'ID ZOOM PRAKTIK KERJA');
      $sheet->setCellValue('D1', 'PASSWORD ZOOM PRAKTIK KERJA');
      $sheet->setCellValue('E1', 'EMAIL ZOOM WAWANCARA');
      $sheet->setCellValue('F1', 'ID ZOOM WAWANCARA');
      $sheet->setCellValue('G1', 'PASSWORD ZOOM WAWANCARA');
      $sheet->setCellValue('H1', 'NIP PENGUJI PRAKTIK KERJA 1');
      $sheet->setCellValue('I1', 'NAMA PENGUJI PRAKTIK KERJA 1');
      $sheet->setCellValue('J1', 'NIP PENGUJI PRAKTIK KERJA 2');
      $sheet->setCellValue('K1', 'NAMA PENGUJI PRAKTIK KERJA 2');
      $sheet->setCellValue('L1', 'NIP PENGUJI PRAKTIK KERJA 3');
      $sheet->setCellValue('M1', 'NAMA PENGUJI PRAKTIK KERJA 3');
      $sheet->setCellValue('N1', 'NIP PENGUJI WAWANCARA 1');
      $sheet->setCellValue('O1', 'NAMA PENGUJI WAWANCARA 1');
      $sheet->setCellValue('P1', 'NIP PENGUJI WAWANCARA 2');
      $sheet->setCellValue('Q1', 'NAMA PENGUJI WAWANCARA 2');
      $sheet->setCellValue('R1', 'NIP PENGUJI WAWANCARA 3');
      $sheet->setCellValue('S1', 'NAMA PENGUJI WAWANCARA 3');

      $i = 2;
      foreach ($data as $row) {
        $sheet->getCell('A'.$i)->setValueExplicit($row->nopeserta,\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('B'.$i, $row->email_praktik);
        $sheet->getCell('C'.$i)->setValueExplicit($row->id_praktik,\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('D'.$i, $row->password_praktik);
        $sheet->setCellValue('E'.$i, $row->email_wawancara);
        $sheet->getCell('F'.$i)->setValueExplicit($row->id_wawancara,\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('G'.$i, $row->password_wawancara);
        $sheet->getCell('H'.$i)->setValueExplicit($row->penguji1,\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('I'.$i, $row->nama_penguji1);
        $sheet->getCell('J'.$i)->setValueExplicit($row->penguji2,\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('K'.$i, $row->nama_penguji2);
        $sheet->getCell('L'.$i)->setValueExplicit($row->penguji3,\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('M'.$i, $row->nama_penguji3);
        $sheet->getCell('N'.$i)->setValueExplicit($row->pewawancara1,\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('O'.$i, $row->nama_pewawancara1);
        $sheet->getCell('P'.$i)->setValueExplicit($row->pewawancara2,\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('Q'.$i, $row->nama_pewawancara2);
        $sheet->getCell('R'.$i)->setValueExplicit($row->pewawancara3,\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('S'.$i, $row->nama_pewawancara3);
        $i++;
      }

      $tanggal = date('YmdHis');
      $writer = new Xlsx($spreadsheet);
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment; filename="Data_Jadwal_'.$tanggal.'.xlsx"');
      $writer->save('php://output');
    }
}
