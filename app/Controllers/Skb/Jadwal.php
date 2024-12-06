<?php

namespace App\Controllers\Skb;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\CrudModel;
use App\Models\ZoomsModel;
use App\Models\PesertaModel;
use DateTime;

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
                    $namaw1 = $model->getRow('penguji', ['nip' => preg_replace('/[\x{200B}-\x{200D}]/u', '', $pengujiwaw1), 'type' => 'Wawancara']);
                    $namaw2 = $model->getRow('penguji', ['nip' => preg_replace('/[\x{200B}-\x{200D}]/u', '', $pengujiwaw2), 'type' => 'Wawancara']);
                    $namaw3 = $model->getRow('penguji', ['nip' => preg_replace('/[\x{200B}-\x{200D}]/u', '', $pengujiwaw3), 'type' => 'Wawancara']);
                    $namaprak1 = $model->getRow('penguji', ['nip' => preg_replace('/[\x{200B}-\x{200D}]/u', '', $pengujiprak1), 'type' => 'Praktik Kerja']);
                    $namaprak2 = $model->getRow('penguji', ['nip' => preg_replace('/[\x{200B}-\x{200D}]/u', '', $pengujiprak2), 'type' => 'Praktik Kerja']);
                    $namaprak3 = $model->getRow('penguji', ['nip' => preg_replace('/[\x{200B}-\x{200D}]/u', '', $pengujiprak3), 'type' => 'Praktik Kerja']);
                    $countpeserta = $model->getCountv2('zooms','nopeserta',$nopeserta);

                    // Safely access nama property for namaprak1
                    $nama_penguji1 = isset($namaprak1) ? $namaprak1->nama : null; // or set a default value

                    // Safely access nama property for namaprak2
                    $nama_penguji2 = isset($namaprak2) ? $namaprak2->nama : null; // or set a default value

                    // Safely access nama property for namaprak3
                    $nama_penguji3 = isset($namaprak3) ? $namaprak3->nama : null; // or set a default value

                    // Safely access nama property for namaw1
                    $nama_pewawancara1 = isset($namaw1) ? $namaw1->nama : null; // or set a default value

                    // Safely access nama property for namaw1
                    $nama_pewawancara2 = isset($namaw2) ? $namaw2->nama : null; // or set a default value

                    // Safely access nama property for namaw1
                    $nama_pewawancara3 = isset($namaw3) ? $namaw3->nama : null; // or set a default value
                                       
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

                    if ($countpeserta > 0) {
                        //$zooms = new ZoomsModel();
                        $data = [
                            'email_praktik' => $emailprak,
                            'id_praktik' => $zoompraktik,
                            'password_praktik' => $passpraktik,
                            'penguji1' => $pengujiprak1,
                            'nama_penguji1' => $nama_penguji1,
                            'penguji2' => $pengujiprak2,
                            'nama_penguji2' => $nama_penguji2,
                            'penguji3' => $pengujiprak3,
                            'nama_penguji3' => $nama_penguji3,
                            'email_wawancara' => $emailwaw,
                            'id_wawancara' => $zoomwawancara,
                            'password_wawancara' => $passwawancara,
                            'pewawancara1' => $pengujiwaw1,
                            'nama_pewawancara1' => $nama_pewawancara1,
                            'pewawancara2' => $pengujiwaw2,
                            'nama_pewawancara2' => $nama_pewawancara2,
                            'pewawancara3' => $pengujiwaw3,
                            'nama_pewawancara3' => $nama_pewawancara3,
                            'kode_satker' => $satker
                        ];
                        $where = array(
                            'nopeserta' => $nopeserta,
                        );
                        $zooms->set($data)->where($where)->update();

                        $sesipraktik = $model->getRow('sesi', ['sesi' => $idsesiprak]);
                        $sesiwawancara = $model->getRow('sesi', ['sesi ' => $idsesiwaw]);
                        
                        $tglpraktik = isset($sesipraktik->tanggal) ? $sesipraktik->tanggal : null;
                        $tglwawancara = isset($sesiwawancara->tanggal) ? $sesiwawancara->tanggal : null;
                        $pukulpraktik = isset($sesipraktik->pukul) ? $sesipraktik->pukul : null;
                        $pukulwawancara = isset($sesiwawancara->pukul) ? $sesiwawancara->pukul : null;
                        $data = [
                            'jadwal_praktik' => date('Y-m-d H:i:s', strtotime($tglpraktik . ' ' . $pukulpraktik .':00')),
                            'jadwal_wawancara' => date('Y-m-d H:i:s', strtotime($tglwawancara . ' ' . $pukulwawancara .':00'))
                        ];
                        
                        $peserta = new PesertaModel();  
                        $peserta ->set($data)->where($where)->update();
                    } else {
                        $param = [
                            'email_praktik' => $emailprak,
                            'id_praktik' => $zoompraktik,
                            'password_praktik' => $passpraktik,
                            'penguji1' => $pengujiprak1,
                            'nama_penguji1' => $nama_penguji1,
                            'penguji2' => $pengujiprak2,
                            'nama_penguji2' => $nama_penguji2,
                            'penguji3' => $pengujiprak3,
                            'nama_penguji3' => $nama_penguji3,
                            'email_wawancara' => $emailwaw,
                            'id_wawancara' => $zoomwawancara,
                            'password_wawancara' => $passwawancara,
                            'pewawancara1' => $pengujiwaw1,
                            'nama_pewawancara1' => $nama_pewawancara1,
                            'pewawancara2' => $pengujiwaw2,
                            'nama_pewawancara2' => $nama_pewawancara2,
                            'pewawancara3' => $pengujiwaw3,
                            'nama_pewawancara3' => $nama_pewawancara3,
                            'kode_satker' => $satker,
                            'nopeserta' => $nopeserta
                        ];
                        $zooms->insert($param);

                        $sesipraktik = $model->getRow('sesi', ['sesi' => $idsesiprak]);
                        $sesiwawancara = $model->getRow('sesi', ['sesi ' => $idsesiwaw]);
                        $tglpraktik = isset($sesipraktik->tanggal) ? $sesipraktik->tanggal : null;
                        $tglwawancara = isset($sesiwawancara->tanggal) ? $sesiwawancara->tanggal : null;
                        $pukulpraktik = isset($sesipraktik->pukul) ? $sesipraktik->pukul : null;
                        $pukulwawancara = isset($sesiwawancara->pukul) ? $sesiwawancara->pukul : null;
                        $data = [
                            'jadwal_praktik' => date('Y-m-d H:i:s', strtotime($tglpraktik . ' ' . $pukulpraktik .':00')),
                            'jadwal_wawancara' => date('Y-m-d H:i:s', strtotime( $tglwawancara . ' ' . $pukulwawancara .':00'))
                        ];
                        $where = array(
                            'nopeserta' => $nopeserta,
                        );
                        $peserta = new PesertaModel();  
                        $peserta ->set($data)->where($where)->update();
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
}
