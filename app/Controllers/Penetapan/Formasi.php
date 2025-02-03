<?php

namespace App\Controllers\Penetapan;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\FormasiModel;
use \Hermawan\DataTables\DataTable;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Formasi extends BaseController
{
    public function index()
    {        
        if (session()->get('is_admin') == '1') {
            // $lokasi = session('lokasi');
            // $db = db_connect();
            // $data['formasi'] = $db->query("SELECT a.id,
            //                                 a.jabatan_sscasn,
            //                                 a.lokasi,
            //                                 a.jumlah,
            //                                 (SELECT COUNT(1) FROM peserta b
            //                                     WHERE b.penempatan_id = a.id) as terisi
            //                             FROM formasi a")->getResult();
            return view('penetapan/formasiadmin');
        } else {
            $model = new FormasiModel;
            $data['formasi'] = $model->where(['kode_satker'=>session('lokasi')])->findAll();
            $lokasi = session('lokasi');
            $db = db_connect();
            $data['formasi'] = $db->query("SELECT a.id,
                                            a.jabatan_sscasn,
                                            a.lokasi,
                                            a.jumlah,
                                            (SELECT COUNT(1) FROM peserta b
                                                WHERE b.kode_satker = '$lokasi' AND b.penempatan_id = a.id) as terisi
                                        FROM formasi a
                                        WHERE a.kode_satker = '$lokasi'")->getResult();
            return view('penetapan/formasi', $data);
        }        
    }

    public function getdata() {
        $db = \Config\Database::connect('default', false);
        $lokasi = session('lokasi');
        if (session()->get('is_admin') == '1') {
            $builder = $db->table('formasi a')
                        ->select('a.id, a.jabatan_sscasn, a.lokasi, a.jumlah, (SELECT COUNT(1) FROM `peserta` `b` WHERE `b`.`penempatan_id` = `a`.`id`) as terisi');
        } else {
            $builder = $db->table('formasi a')
                        ->select('a.id, a.jabatan_sscasn, a.lokasi, a.jumlah, 
                                    (SELECT COUNT(1) FROM `peserta` `b` WHERE `b`.`penempatan_id` = `a`.`id`) AS terisi')
                        ->where('a.kode_satker', $lokasi);
        }
        // Debugging: Log the last executed query
        //dd($builder->getCompiledSelect());
        //$query = $builder->get();
        //log_message('debug', $db->getLastQuery());
        return DataTable::of($builder)->toJson(true);
    }

    public function rekapitulasi()
    {
        $db = db_connect();
        $lokasi = session('lokasi');
        if (session()->get('is_admin') == '1') {
            $data['rekapitulasi'] = $db->query("SELECT 
                                        a.formasi, 
                                        COUNT(*) AS jumlah,
                                        (SELECT SUM(b.jumlah) 
                                        FROM formasi b 
                                        WHERE b.jabatan_sscasn = a.formasi) AS jumlah_formasi,
                                        (SELECT COUNT(1) 
                                        FROM peserta b 
                                        WHERE a.formasi = b.formasi
                                        AND (b.status_akhir = 'P/L' OR b.status_akhir = 'P/L-E2' OR b.status_akhir = 'P/L-U1')
                                        AND b.penempatan_id IS NOT NULL) AS penempatan
                                    FROM peserta a 
                                    WHERE (a.status_akhir = 'P/L' OR a.status_akhir = 'P/L-E2' OR a.status_akhir = 'P/L-U1') 
                                    GROUP BY a.formasi")->getResult();
        } else {
            $data['rekapitulasi'] = $db->query("SELECT 
                                        a.formasi, 
                                        COUNT(*) AS jumlah,
                                        (SELECT SUM(b.jumlah) 
                                        FROM formasi b 
                                        WHERE b.jabatan_sscasn = a.formasi 
                                        AND b.kode_satker = '$lokasi') AS jumlah_formasi,
                                        (SELECT COUNT(1) 
                                        FROM peserta b 
                                        WHERE a.formasi = b.formasi
                                        AND (b.status_akhir = 'P/L' OR b.status_akhir = 'P/L-E2' OR b.status_akhir = 'P/L-U1')
                                        AND b.penempatan_id IS NOT NULL) AS penempatan
                                    FROM peserta a 
                                    WHERE a.kode_satker = '$lokasi' 
                                    AND (a.status_akhir = 'P/L' OR a.status_akhir = 'P/L-E2' OR a.status_akhir = 'P/L-U1') 
                                    GROUP BY a.formasi")->getResult();
        }        
        return view('penetapan/rekapitulasi', $data);
    }

    public function export() {        
        $lokasi = session('lokasi');
        if (session()->get('is_admin') == '1') {
            $db = db_connect();
            $data = $db->query("SELECT a.id,
                                            a.jabatan_sscasn,
                                            a.lokasi,
                                            a.jumlah,
                                            (SELECT COUNT(1) FROM peserta b
                                                WHERE b.penempatan_id = a.id) as terisi
                                        FROM formasi a")->getResult();
        } else {
            $db = db_connect();
            $data = $db->query("SELECT a.id,
                                            a.jabatan_sscasn,
                                            a.lokasi,
                                            a.jumlah,
                                            (SELECT COUNT(1) FROM peserta b
                                                WHERE b.kode_satker = '$lokasi' AND b.penempatan_id = a.id) as terisi
                                        FROM formasi a
                                        WHERE a.kode_satker = '$lokasi'")->getResult();
        }    
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'JABATAN');
        $sheet->setCellValue('B1', 'LOKASI');
        $sheet->setCellValue('C1', 'JUMLAH');
        $sheet->setCellValue('D1', 'TERISI');

        $i = 2;
        foreach ($data as $row) {
            $sheet->setCellValue('A'.$i, $row->jabatan_sscasn);
            $sheet->setCellValue('B'.$i, $row->lokasi);
            $sheet->setCellValue('C'.$i, $row->jumlah);
            $sheet->setCellValue('D'.$i, $row->terisi);
            $i++;
        }

        $tanggal = date('YmdHis');
        $writer = new Xlsx($spreadsheet);
        ob_clean();
        ob_start();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="Data_Peserta_'.$tanggal.'.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        ob_end_flush();
        exit;
    }
}
