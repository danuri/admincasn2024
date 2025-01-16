<?php

namespace App\Controllers\Penetapan;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PesertaModel;
use App\Models\CrudModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Peserta extends BaseController
{
    public function index()
    {
        // $model = new PesertaModel;
        // $data['peserta'] = $model->where(['kode_satker'=>session('lokasi')])->where(['status_akhir'=>'P/L'])->orWhere(['status_akhir'=>'P/L-E2'])->orWhere(['status_akhir'=>'P/L-U1'])->findAll();
        $db = db_connect();
        $lokasi = session('lokasi');
        $data['peserta'] = $db->query("SELECT * FROM peserta WHERE kode_satker = '$lokasi' AND (`status_akhir` = 'P/L' OR `status_akhir` = 'P/L-E2' OR `status_akhir` = 'P/L-U1')")->getResult();
        $data['resume'] = $db->query("SELECT formasi, COUNT(*) as jumlah FROM peserta WHERE kode_satker = '30130000' AND (`status_akhir` = 'P/L' OR `status_akhir` = 'P/L-E2' OR `status_akhir` = 'P/L-U1') GROUP BY formasi")->getResult();
        return view('penetapan/peserta', $data);
    }

    public function export()
    {
      $lokasi = session('lokasi');
    //   $crud = new CrudModel();
    //   $data = $crud->getResult('peserta', ['kode_satker'=>$kodesatker,'']);
      $db = db_connect();
      $data = $db->query("SELECT * FROM peserta WHERE kode_satker = '$lokasi' AND (`status_akhir` = 'P/L' OR `status_akhir` = 'P/L-E2' OR `status_akhir` = 'P/L-U1')")->getResult();

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
      $sheet->setCellValue('L1', 'DISABILITAS');
      $sheet->setCellValue('M1', 'ALAMAT_DOMISILI');
      $sheet->setCellValue('N1', 'KABKOTA_DOMISILI');
      $sheet->setCellValue('O1', 'PROVINSI_DOMISILI');
      $sheet->setCellValue('P1', 'STATUS LULUS');

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
        $sheet->setCellValue('L'.$i, $row->jenis_disabilitas);
        $sheet->setCellValue('M'.$i, $row->alamat_domisili);
        $sheet->setCellValue('N'.$i, $row->kabkota_domisili);
        $sheet->setCellValue('O'.$i, $row->provinsi_domisili);
        $sheet->setCellValue('P'.$i, $row->status_akhir);
        $i++;
      }

      $tanggal = date('YmdHis');
      $writer = new Xlsx($spreadsheet);
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment; filename="Data_Peserta_L_'.$tanggal.'.xlsx"');
      $writer->save('php://output');
    }
}
