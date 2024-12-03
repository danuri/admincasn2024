<?php

namespace App\Controllers\Skb;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\CrudModel;
use App\Models\ZoomsModel;

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
            $sheetData = (object) $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            $satker = session()->get('lokasi');

            $i = 0;
            foreach ($sheetData as $row) {
                if ($i > 0) {
                    $nopeserta = preg_replace('/[\x{200B}-\x{200D}]/u', '', $row['A']);
                    $nopeserta = str_replace("'", "", $nopeserta);
                    $emailprak = $row['B'];
                    $zoompraktik = $row['C'];
                    $passpraktik = $row['D'];
                    $emailwaw = $row['E'];
                    $zoomwawancara = $row['F'];
                    $passwawancara = $row['G'];
                    $pengujiprak1 = $row['H'];
                    $pengujiprak2 = $row['I'];
                    $pengujiwaw1 = $row['J'];
                    $pengujiwaw2 = $row['K'];

                    // Fetch names of the interviewers
                    $namaw1 = $model->getRow('penguji', ['nip' => preg_replace('/[\x{200B}-\x{200D}]/u', '', $pengujiwaw1), 'type' => 'Wawancara']);
                    $namaw2 = $model->getRow('penguji', ['nip' => preg_replace('/[\x{200B}-\x{200D}]/u', '', $pengujiwaw2), 'type' => 'Wawancara']);

                    $namaprak1 = $model->getRow('penguji', ['nip' => preg_replace('/[\x{200B}-\x{200D}]/u', '', $pengujiprak1), 'type' => 'Praktik Kerja']);
                    $namaprak2 = $model->getRow('penguji', ['nip' => preg_replace('/[\x{200B}-\x{200D}]/u', '', $pengujiprak2), 'type' => 'Praktik Kerja']);
                    
                    //$countpeserta = $model->getCountv2('zooms','nopeserta',$nopeserta);

                    // Safely access nama property for namaprak1
                    $nama_penguji1 = isset($namaprak1) ? $namaprak1->nama : null; // or set a default value

                    // Safely access nama property for namaprak2
                    $nama_penguji2 = isset($namaprak2) ? $namaprak2->nama : null; // or set a default value

                    // Safely access nama property for namaw1
                    $nama_pewawancara1 = isset($namaw1) ? $namaw1->nama : null; // or set a default value

                    // Safely access nama property for namaw1
                    $nama_pewawancara2 = isset($namaw2) ? $namaw2->nama : null; // or set a default value
                                       
                    $zooms = new ZoomsModel();
                    $data = [
                        'email_praktik' => $emailprak,
                        'id_praktik' => $zoompraktik,
                        'password_praktik' => $passpraktik,
                        'penguji1' => $pengujiprak1,
                        'nama_penguji1' => $nama_penguji1,
                        'penguji2' => $pengujiprak2,
                        'nama_penguji2' => $nama_penguji2,
                        'email_wawancara' => $emailwaw,
                        'id_wawancara' => $zoomwawancara,
                        'password_wawancara' => $passwawancara,
                        'pewawancara1' => $pengujiwaw1,
                        'nama_pewawancara1' => $nama_pewawancara1,
                        'pewawancara2' => $pengujiwaw2,
                        'nama_pewawancara2' => $nama_pewawancara2,
                        'kode_satker' => $satker
                    ];
                    $where = array(
                        'nopeserta' => $nopeserta,
                    );
                    $zooms->set($data)->where($where)->update();
                    // if ($countpeserta > 0) {
                    //     $data = [
                    //         'email_praktik' => $emailprak,
                    //         'id_praktik' => $zoompraktik,
                    //         'password_praktik' => $passpraktik,
                    //         'penguji1' => $pengujiprak1,
                    //         'nama_penguji1' => $nama_penguji1,
                    //         'penguji2' => $pengujiprak2,
                    //         'nama_penguji2' => $nama_penguji2,
                    //         'email_wawancara' => $emailwaw,
                    //         'id_wawancara' => $zoomwawancara,
                    //         'password_wawancara' => $passwawancara,
                    //         'pewawancara1' => $pengujiwaw1,
                    //         'nama_pewawancara1' => $nama_pewawancara1,
                    //         'pewawancara2' => $pengujiwaw2,
                    //         'nama_pewawancara2' => $nama_pewawancara2,
                    //         'kode_satker' => $satker
                    //     ];
                    //     $where = array(
                    //         'nopeserta' => $nopeserta,
                    //     );
                    //     $zooms->set($data)->where($where)->update();
                    // } else {
                    //     $param = [
                    //         'email_praktik' => $emailprak,
                    //         'id_praktik' => $zoompraktik,
                    //         'password_praktik' => $passpraktik,
                    //         'penguji1' => $pengujiprak1,
                    //         'nama_penguji1' => $nama_penguji1,
                    //         'penguji2' => $pengujiprak2,
                    //         'nama_penguji2' => $nama_penguji2,
                    //         'email_wawancara' => $emailwaw,
                    //         'id_wawancara' => $zoomwawancara,
                    //         'password_wawancara' => $passwawancara,
                    //         'pewawancara1' => $pengujiwaw1,
                    //         'nama_pewawancara1' => $nama_pewawancara1,
                    //         'pewawancara2' => $pengujiwaw2,
                    //         'nama_pewawancara2' => $nama_pewawancara2,
                    //         'kode_satker' => $satker,
                    //         'nopeserta' => $nopeserta
                    //     ];
                    //     $zooms->insert($param);
                    // }
                }
                $i++;
            }

            session()->setFlashdata('message', 'Selesai');
        } else {
            session()->setFlashdata('message', $file->getErrorString());
        }

        return redirect()->to('skb/jadwal');        
    }
}
