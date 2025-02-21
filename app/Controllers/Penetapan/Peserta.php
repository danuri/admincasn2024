<?php

namespace App\Controllers\Penetapan;

use App\Controllers\BaseController;
use App\Models\CrudModel;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PesertaModel;
use Dompdf\Dompdf;
use Dompdf\Options;
use Aws\S3\S3Client;
use Config\Minio;
use Aws\Exception\AwsException;
use CURLFile;
use \Hermawan\DataTables\DataTable;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Peserta extends BaseController
{
    public function index()
    {        
        // $db = db_connect();
        $lokasi = session('lokasi');
        if (session()->get('is_admin') == '1') {
            // $data['peserta'] = $db->query("SELECT * FROM peserta WHERE (`status_akhir` = 'P/L' OR `status_akhir` = 'P/L-E2' OR `status_akhir` = 'P/L-U1')")->getResult();
            return view('penetapan/peserta');
        } else {
            $db = db_connect();
            $data['peserta'] = $db->query("SELECT * FROM peserta WHERE kode_satker = '$lokasi' AND (`status_akhir` = 'P/L' OR `status_akhir` = 'P/L-E2' OR `status_akhir` = 'P/L-U1')")->getResult();
            $data['tanggal'] = date('Ymd'); 
            $data['tanggal_download_sprp'] = getenv('TANGGAL_MULAI_DOWNLOAD_SPRP');
            //dd($data);
            return view('penetapan/peserta', $data);
        }           
    }

    public function getdataskb() {
        $db = \Config\Database::connect('default', false);
        $lokasi = session('lokasi');
        if (session()->get('is_admin') == '1') {            
            return null;
        } else {           
            $builder = $db->table('peserta')
                        ->select('nopeserta, nama, formasi, jenis, penempatan, penempatan_id, doc_sprp')
                        ->where("kode_satker = ". $db->escape($lokasi) ." AND (`status_akhir` = 'P/L' OR `status_akhir` = 'P/L-E2' OR `status_akhir` = 'P/L-U1')");
            return DataTable::of($builder)
            ->edit('penempatan', function ($row) {
                return $row->penempatan != null ? $row->penempatan : "-";
            })
            ->add('aksi', function ($row) {
                $html = '';
                $tanggal = date('Ymd'); 
                $tanggal_download_sprp = getenv('TANGGAL_MULAI_DOWNLOAD_SPRP');

                if ($row->penempatan_id != null && $row->penempatan_id != '') {
                    if ($row->doc_sprp != null && $row->doc_sprp != '') {
                        if ($tanggal >= $tanggal_download_sprp) {
                            $html .= '<a href="javascript:;" onclick="download_sprp(\'' . $row->doc_sprp . '\')" class="btn btn-sm btn-success">Download-SPRP</a>';
                        } else {
                            $html .= '<label>Menunggu TTE</label>';
                        }
                    } else {
                        $html .= '<a href="' . site_url('penetapan/peserta/reset/' . $row->nopeserta) . '" class="btn btn-sm btn-danger">Reset</a> ';
                        $html .= '<a href="' . site_url('penetapan/peserta/sprp/' . $row->nopeserta) . '" class="btn btn-sm btn-success" onclick="return confirm(\'Apakah Anda yakin SPRP akan dikirimkan untuk TTE?\')">Kirim TTE</a>';
                    }                    
                } else {
                    $html .= '<a href="javascript:;" onclick="penempatan(\'' . $row->nopeserta . '\')" class="btn btn-sm btn-success">Penempatan</a> ';
                }
                return $html;
            })
            ->toJson(true);
        }
    }

    public function getdataadmin() {
        $db = \Config\Database::connect('default', false);
        if (session()->get('is_admin') == '1') {
            $builder = $db->table('peserta')
                        ->select('nopeserta, nama, pendidikan, formasi, jenis, penempatan, doc_sprp')
                        ->where("(`status_akhir` = 'P/L' OR `status_akhir` = 'P/L-E2' OR `status_akhir` = 'P/L-U1')");
            return DataTable::of($builder)->toJson(true);
        } else {            
            return null;
        }
    }

    public function get_detail($nopeserta)
    {
        $satker = session('lokasi');
        $model = new CrudModel;
        $row = $model->getRow('peserta', ['nopeserta'=>$nopeserta]);   
        //$formasi = $model->getResult('formasi', ['kode_satker'=>$satker, 'jabatan_sscasn'=>$row->formasi]);
        $db = \Config\Database::connect('default', false);
        $formasi = $db->table('formasi a')
                        ->select('a.id, a.jabatan_sscasn, a.lokasi, a.jumlah, 
                                    (SELECT COUNT(1) FROM peserta b 
                                    WHERE b.kode_satker = '. $db->escape($satker) .' AND b.penempatan_id = a.id) as terisi')
                        ->where('a.kode_satker', $satker)
                        ->where('a.jabatan_sscasn', $row->formasi)
                        ->get()
                        ->getResult();
        ?>
        <form class="" action="<?php echo site_url('penetapan/peserta/save');?>" method="post" id="savepenempatan" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nopeserta" style="margin-top:.5rem;margin-bottom:.0rem;">No Peserta</label>
                <input type="text" class="form-control" name="nopeserta" id="nopesertax" value="<?php echo $row->nopeserta;?>" readonly>
            </div>
            <div class="form-group">
                <label for="nama" style="margin-top:.5rem;margin-bottom:.0rem;">Nama Peserta</label>
                <input type="text" class="form-control" name="nama" id="namax" value="<?php echo $row->nama;?>" readonly>
            </div>
            <div class="form-group">
                <label for="nama" style="margin-top:.5rem;margin-bottom:.0rem;">Pendidikan</label>
                <input type="text" class="form-control" name="nama" id="namax" value="<?php echo $row->pendidikan;?>" readonly>
            </div>
            <div class="form-group">
                <label for="jabatan" style="margin-top:.5rem;margin-bottom:.0rem;">Jabatan</label>
                <input type="text" class="form-control" name="jabatan" id="jabatanx" value="<?php echo $row->formasi;?>" readonly>
            </div>
            <div class="form-group">
                <label for="jenis" style="margin-top:.5rem;margin-bottom:.0rem;">Jenis</label>
                <input type="text" class="form-control" name="jenis" id="jenisx" value="<?php echo $row->jenis;?>" readonly>
            </div>
            <div class="form-group">
                <label for="formasi" style="margin-top:.5rem;margin-bottom:.0rem;">Penempatan</label>
                <select class="form-control" name="formasi" id="formasix">
                    <?php for ($i = 0; $i < count($formasi); $i++) {?>
                        <option value="<?php echo $formasi[$i]->id;?>" ><?php echo $formasi[$i]->lokasi.' ('.$formasi[$i]->terisi.' dari '.$formasi[$i]->jumlah.')';?></option>
                    <?php } ?>
                </select>
            </div>
        </form>
        <?php
    }

    public function save() {
        // $satker = session('lokasi');
        // Load validasi service
        $validation = \Config\Services::validation();

        // Define validation rules
        $rules = [
            'formasi' => 'required',
            'nopeserta' => 'required'
        ];

        if ($this->validate($rules)) {
            $pesertaModel = new PesertaModel();
            $model = new CrudModel;
            $lokasi_formasi = $model->getRow('formasi', ['id'=>$this->request->getPost('formasi')]);
            $terisi = $model->getCountV2('peserta', 'penempatan_id', $this->request->getPost('formasi'));

            if ($terisi >= $lokasi_formasi->jumlah) {
                session()->setFlashdata('error', 'Penempatan sudah penuh');
                return redirect()->to('penetapan/peserta');
            } else {
                $data = array (
                    'penempatan' => $lokasi_formasi->lokasi,
                    'penempatan_id' => $this->request->getPost('formasi')
                );
                $where = array (
                    'nopeserta' => $this->request->getPost('nopeserta')
                );            
                //dd($where);
                $pesertaModel->set($data)->where($where)->update();
                session()->setFlashdata('message', 'Penempatan Peserta berhasil dilakukan');
                return redirect()->to('penetapan/peserta');
            }            
        } else {
            session()->setFlashdata('message', $validation->getErrors());
            $db = db_connect();
            $lokasi = session('lokasi');
            $data['peserta'] = $db->query("SELECT * FROM peserta WHERE kode_satker = '$lokasi' AND (`status_akhir` = 'P/L' OR `status_akhir` = 'P/L-E2' OR `status_akhir` = 'P/L-U1')")->getResult();
            $data['resume'] = $db->query("SELECT formasi, COUNT(*) as jumlah FROM peserta WHERE kode_satker = '30130000' AND (`status_akhir` = 'P/L' OR `status_akhir` = 'P/L-E2' OR `status_akhir` = 'P/L-U1') GROUP BY formasi")->getResult();
            return view('penetapan/peserta', $data);
        }
    }

    public function reset($nopeserta) {
        $pesertaModel = new PesertaModel();
        $data = array (
            'penempatan' => '',
            'penempatan_id' => null
        );
        $where = array (
            'nopeserta' => $nopeserta
        );
        $pesertaModel->set($data)->where($where)->update();
        session()->setFlashdata('message', 'Reset Peserta berhasil');
        return redirect()->to('penetapan/peserta');
    }

    public function cetak_sprp($nopeserta) {  
        $data['nama_satker'] = session('lokasi_nama');
        $model = new CrudModel;
        $data['peserta'] = $model->getRow('peserta', ['nopeserta'=>$nopeserta]); 
        
        $timestamp = strtotime($data['peserta']->tanggal_lahir);
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
        if ($data['peserta']->penempatan_id != null) {
            $penempatan = array_map('trim', explode('|', $data['peserta']->penempatan));
            $length = count($penempatan);
            $data['penempatan'] = $penempatan[$length - 1];
        }

        $dateRequest = date('Ymd');
        if ($dateRequest < '20250222') {
            $data['sysdate'] = '22 Februari 2025';
            $dateRequest = '20250222';
        } else {
            $sysdate = date('d F Y');
            $data['sysdate'] = str_replace(array_keys($indonesianMonths), $indonesianMonths, $sysdate);
        }        

        //dd($data);
        $html = view('penetapan/sprp',$data);

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
        $result = $this->sendtte($tempFilePath, $data['peserta'], $dateRequest);

        if ($result['status'] == 'error') {
            session()->setFlashdata('error', $result['response']);
            return redirect()->to('penetapan/peserta');
        } else {
            if ($result['status'] != 'success') {
                session()->setFlashdata('error', 'TTE Error');
                return redirect()->to('penetapan/peserta');
            }
        }
        $response = json_decode($result['response'], true);
        
        if (isset($response['message']['file_url'])) {
            $doc_sprp = $response['message']['file_url'];
            $pesertaModel = new PesertaModel();
            $data = array (
                'doc_sprp' => $doc_sprp
            );
            $where = array (
                'nopeserta' => $nopeserta
            );
            $pesertaModel->set($data)->where($where)->update();
            session()->setFlashdata('message', 'Dokumen SPRP Berhasil Dikirim');
            return redirect()->to('penetapan/peserta');
        } else {
            session()->setFlashdata('error', 'File URL TTE tidak ada');
            return redirect()->to('penetapan/peserta');
        }
    }

    private function sendtte($filepath, $peserta, $dateRequest) {
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
            'title' => 'SPRP a.n '.$peserta->nama.' tgl '.$dateRequest,
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

    private function uploadToMinio($filePath, $file) {
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

        $result = $s3Client->putObject([
            'Bucket' => $minio->bucket,
            'Key'    => 'sprp/'.$filePath,
            'SourceFile' => $file->getTempName(),
            'ACL'    => 'public-read', // Adjust as needed
            'ContentType' => 'application/pdf'
        ]);

        return $result;
    }

    public function export() {
        $lokasi = session('lokasi');
        if (session()->get('is_admin') == '1') {
            $db = db_connect();
            $data = $db->query("SELECT * 
                                FROM peserta 
                                WHERE (`status_akhir` = 'P/L' OR `status_akhir` = 'P/L-E2' OR `status_akhir` = 'P/L-U1')")
                            ->getResult();
        } else {
            $db = db_connect();
            $data = $db->query("SELECT * 
                                FROM peserta 
                                WHERE kode_satker = '$lokasi' 
                                AND (`status_akhir` = 'P/L' OR `status_akhir` = 'P/L-E2' OR `status_akhir` = 'P/L-U1')")
                            ->getResult();
        }    
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'NIK');
        $sheet->setCellValue('B1', 'NOMOR PESERTA');
        $sheet->setCellValue('C1', 'NAMA PESERTA');
        $sheet->setCellValue('D1', 'JENIS_KELAMIN');
        $sheet->setCellValue('E1', 'NO_HP');
        $sheet->setCellValue('F1', 'AGAMA');
        $sheet->setCellValue('G1', 'DISABILITAS');
        $sheet->setCellValue('H1', 'ALAMAT_DOMISILI');
        $sheet->setCellValue('I1', 'KAB_DOMISILI');
        $sheet->setCellValue('J1', 'PROV_DOMISILI');
        $sheet->setCellValue('K1', 'STATUS_KAWIN');
        $sheet->setCellValue('L1', 'STATUS_DRH');
        $sheet->setCellValue('M1', 'PENDIDIKAN');
        $sheet->setCellValue('N1', 'JABATAN');
        $sheet->setCellValue('O1', 'JENIS');
        $sheet->setCellValue('P1', 'PENEMPATAN');

        $i = 2;
        foreach ($data as $row) {
            $sheet->getCell('A'.$i)->setValueExplicit($row->nik,\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->getCell('B'.$i)->setValueExplicit($row->nopeserta,\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('C'.$i, $row->nama);
            $sheet->setCellValue('D'.$i, $row->jenis_kelamin);
            $sheet->setCellValue('E'.$i, $row->no_hp);
            $sheet->setCellValue('F'.$i, $row->agama);
            $sheet->setCellValue('G'.$i, $row->jenis_disabilitas);
            $sheet->setCellValue('H'.$i, $row->alamat_domisili);
            $sheet->setCellValue('I'.$i, $row->kabkota_domisili);
            $sheet->setCellValue('J'.$i, $row->provinsi_domisili);
            $sheet->setCellValue('K'.$i, $row->status_kawin);
            $sheet->setCellValue('L'.$i, $row->status_drh);
            $sheet->setCellValue('M'.$i, $row->pendidikan);
            $sheet->setCellValue('N'.$i, $row->formasi);
            $sheet->setCellValue('O'.$i, $row->kelompok);
            $sheet->setCellValue('P'.$i, $row->penempatan);
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
