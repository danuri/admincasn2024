<?php

namespace App\Controllers\Skb;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\CrudModel;
use App\Models\TilokModel;
use App\Models\LokasiModel;
use App\Models\PesertaModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Lokasi extends BaseController
{
    public function index($lokasi=null)
    {
        $satker = session('lokasi');
        $model = new CrudModel;
        $data['lokasi'] = $model->getLokasiSatker($satker);
        $data['lokasiAll'] = $model->getLokasiBySatker($satker);
        $data['jumlah'] = $model->getJumlahLokasi($satker);

        if ($lokasi !== null) {
            $data['peserta'] = $model->getResult('peserta', ['lokasi_kode' => $lokasi]);
            $data['lok'] = $model->getRow('lokasi', ['kode_tilok' => $lokasi]);
            $data['lokasititik'] = $model->getResult('lokasi_titik', ['lokasi_kode' => $lokasi]);
            $data['lokasikode'] = $lokasi;
            $data['issetlokasi'] = false;
        }
        //dd($data);  
        return view('skb/lokasi', $data);
    }

    public function add()
    {
        $satker = session('lokasi');

        // Load validasi service
        $validation = \Config\Services::validation();

        // Define validation rules
        $rules = [
            'tilok' => 'required',
            'lokasi_kode' => 'required',
            'alamat' => 'required',
            'maps' => 'required',
            'kontak' => 'required',
            'kontak_panitia' => 'required'
        ];

        //if($validation->run() === TRUE)
        if ($this->validate($rules))
        {
            // Jika validasi berhasil, proses data di sini
            $tilokModel = new TilokModel();

            $param = [
                'tilok' => $this->request->getPost('tilok'),
                'lokasi_kode' => $this->request->getPost('lokasi_kode'),
                'alamat' => $this->request->getPost('alamat'),
                'maps' => $this->request->getPost('maps'),
                'kontak' => $this->request->getPost('kontak'),
                'kontak_panitia' => $this->request->getPost('kontak_panitia'),
                'kode_satker' => $satker
            ];

            $tilokModel->insert($param);
            // Mendapatkan ID yang baru di-generate
            // $newIdTilok = $tilokModel->insertID();

            // $peserta = new PesertaModel();
            // $data = [
            //     'id_tilok' => $newIdTilok,
            //     'tilok' => $this->request->getPost('tilok'),
            // ];
            // $peserta->set($data)->where('lokasi_kode', $this->request->getPost('lokasi_kode'))->update();
            session()->setFlashdata('message', 'Titik Lokasi telah ditambahkan');
            return redirect()->to('skb/lokasi');
        } else {
            //session()->setFlashdata('message', $validation->getErrors());
            // Jika validasi gagal, ambil error dan tampilkan di view
            $data['validation'] = $validation;
            $model = new CrudModel;
            $data['lokasi'] = $model->getLokasiSatker($satker);
            $data['lokasiAll'] = $model->getLokasiBySatker($satker);
            $data['jumlah'] = $model->getJumlahLokasi($satker);
            return view('skb/lokasi', $data);
        }
    }

    public function get_detail($kode)
    {
        $satker =session('lokasi');
        $model = new CrudModel;
        // $lokasis = $this->crud->get_array('lokasi',array('kode_satker'=>$satker));
        // $lokasi = $this->crud->get_row('lokasi_titik',array('lokasi_kode'=>$kode));
        //$lokasis = $model->getRow('lokasi','kode_satker='.$satker);
        $lokasis  = $model->getLokasiBySatker($satker);
        $lokasi = $model->getRow('lokasi_titik', 'id_tilok='.$kode);
        //  dd($lokasis);
        ?>
        <form class="" action="<?php echo site_url('skb/lokasi/update');?>" method="post" id="edittilok">
        <input type="hidden" name="id_tilok" value="<?php echo $kode;?>">
        <div class="form-group">
            <label for="">Lokasi Ujian</label>
            <select class="form-control" name="lokasi_kode" disabled>
            <?php
            foreach ($lokasis as $row) {
                $select = ($row->kode_tilok === $kode)?'selected':'';
                ?>
            <option value="<?php echo $row->kode_tilok;?>" <?php echo $select;?>><?php echo $row->lokasi_ujian;?></option>
            <?php }?>
            </select>
        </div>
        <div class="form-group">
            <label for="">Nama Titik Lokasi</label>
            <input type="text" class="form-control" name="tilok" value="<?php echo $lokasi->tilok;?>">
        </div>
        <div class="form-group">
            <label for="">Kontak Untuk Panitia</label>
            <input type="text" class="form-control" name="kontak_panitia" value="<?php echo $lokasi->kontak_panitia;?>">
        </div>
        <div class="form-group">
            <label for="">Kontak Untuk Peserta</label>
            <input type="text" class="form-control" name="kontak" value="<?php echo $lokasi->kontak;?>">
        </div>
        <div class="form-group">
            <label for="">Alamat</label>
            <textarea name="alamat" class="form-control" rows="3"><?php echo $lokasi->alamat;?></textarea>
        </div>
        <div class="form-group">
            <label for="">Link Google Maps (Link Share dari Google Maps)</label>
            <input type="text" class="form-control" name="maps" value="<?php echo $lokasi->maps;?>">
            <input type="hidden" class="form-control" name="lokasi_kode" value="<?php echo $lokasi->lokasi_kode;?>">
        </div>
        </form>
        <?php
    }

    public function update()
    {
        $satker = session('lokasi');

        // Load validasi service
        $validation = \Config\Services::validation();

        // Define validation rules
        $rules = [
            'tilok' => 'required',
            'lokasi_kode' => 'required',
            'alamat' => 'required',
            'maps' => 'required',
            'kontak' => 'required',
            'kontak_panitia' => 'required'
        ];

        //if($this->form_validation->run() === TRUE)
        if ($this->validate($rules))
        {
            // Jika validasi berhasil, proses data di sini
            $tilokModel = new TilokModel();

            $data = array(
                'tilok' => $this->request->getPost('tilok'),
                'lokasi_kode' => $this->request->getPost('lokasi_kode'),
                'alamat' => $this->request->getPost('alamat'),
                'maps' => $this->request->getPost('maps'),
                'kontak' => $this->request->getPost('kontak'),
                'kontak_panitia' => $this->request->getPost('kontak_panitia'),
                'kode_satker' => $satker
            );

            $where = array (
                'id_tilok' => $this->request->getPost('id_tilok'),
            );
            $tilokModel->set($data)->where($where)->update();
            session()->setFlashdata('message', 'Titik Lokasi telah diubah');
            return redirect()->to('skb/lokasi');
        }else{
            //$this->session->set_flashdata('message',validation_errors());
            // Jika validasi gagal, ambil error dan tampilkan di view
            $data['validation'] = $validation;
            $satker = session('lokasi');
            $model = new CrudModel;
            $data['lokasi'] = $model->getLokasiSatker($satker);
            $data['lokasiAll'] = $model->getLokasiBySatker($satker);
            $data['jumlah'] = $model->getJumlahLokasi($satker);
            return view('skb/lokasi', $data);
        }
    }

    public function delete($id)
    {
        //$satker = session('lokasi');
        $tilokModel = new TilokModel();
        //$model = new CrudModel;
        //$tilok = $model->getRow('tilok', ['id_tilok' => $id]);
        $data = [
            'tilok' => '',
            'id_tilok' => ''
        ];
        $peserta = new PesertaModel();
        $peserta->set($data)->where('id_tilok', $id)->update();
        $tilokModel->delete($id);

        //$delete = $this->crud->delete('lokasi_titik',array('lokasi_kode'=>$id,'kode_satker'=>$satker));
        //$this->session->set_flashdata('message','Titik Lokasi telah dihapus');
        session()->setFlashdata('message', 'Titik Lokasi telah dihapus');
        return redirect()->to('skb/lokasi');
    }

    public function setjadwal($lokasi)
    {
        //$satker = session('lokasi');
        $model = new CrudModel;
        $peserta = $model->getResult('peserta', ['lokasi_kode' => $lokasi]);
        $tilok = $model->getResult('lokasi_titik', ['lokasi_kode' => $lokasi]);
        //dd($tilok);
        // $data['lokasi'] = $model->getLokasiSatker($satker);
        // $data['lokasiAll'] = $model->getLokasiBySatker($satker);
        // $data['peserta'] = $model->getResult('peserta', ['lokasi_kode' => $lokasi]);
        // $data['lok'] = $model->getRow('lokasi', ['kode_tilok' => $lokasi]);
        // $data['jumlah'] = $model->getJumlahLokasi($satker);
        // return view('skb/lokasi', $data);
        ?>
        <form class="" action="<?php echo site_url('skb/lokasi/updatejadwal/'.$lokasi);?>" method="post" id="updatejadwal">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped table-hover datatablex">
              <thead class="text-center">
                <tr>
                  <th>Pilih</th>
                  <th>Nomor Peserta</th>
                  <th>Nama</th>
                  <th>Formasi</th>
                  <th>Lokasi</th>
                  <th>Praktik Kerja</th>
                  <th>Ruangan</th>
                  <th>Wawancara</th>
                  <th>Ruangan</th>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach ($peserta as $row) {
                  ?>
                  <tr>
                    <td><input type="checkbox" name="nik[]" value="<?php echo $row->nik;?>"></td>
                    <td><?php echo $row->nopeserta;?></td>
                    <td><?php echo $row->nama;?></td>
                    <td><?php echo $row->formasi;?></td>
                    <td><?php echo $row->tilok;?></td>
                    <td><?php echo $row->jadwal_praktik;?></td>
                    <td><?php echo $row->ruangan_praktik;?></td>
                    <td><?php echo $row->jadwal_wawancara;?></td>
                    <td><?php echo $row->ruangan_wawancara;?></td>
                  </tr>
                  <?php
                  // $no++;
                }
                  ?>
                </tbody>
              </table>
        </div>
        <br>
          <div class="card-body">
            <div class="col-md-2">
            Pindahkan ke
            </div>
            <div class="col-md-3">
              <select class="form-control" name="id_tilok">
                <?php foreach ($tilok as $row) {
                  echo '<option value="'.$row->id_tilok.'">'.$row->tilok.'</option>';
                } ?>
              </select>
            </div>
            <!-- <div class="col-md-3">
              <input type="submit" name="submit" value="submit" class="btn btn-primary">
            </div> -->
          </div>
          </form>        
        <?php
    }

    public function updatejadwal($kode)
    {
        $satker = session('lokasi');
        $model = new CrudModel;
        $niks = $this->request->getPost('nik'); // Use request to get POST data
        $idtilok = $this->request->getPost('id_tilok');
        if (!$niks) {
            session()->setFlashdata('message', 'Pilih Peserta');
            return redirect()->to('skb/lokasi/setlokasi/'.$kode);
        }
        //dd($idtilok);
        // Load the model
        //$lokasiModel = new LokasiModel();
        //$tilok = $lokasiModel->where(['id_tilok' => $idtilok, 'kode_satker' => $satker])->first();
        $tilok = $model->getRow('lokasi_titik', ['id_tilok' => $idtilok]);

        if ($tilok) {
            $jumlah = count($niks);
            if ($jumlah > 0) {
                $pesertaModel = new PesertaModel();
                for ($i = 0; $i < $jumlah; $i++) {
                    $nik = $niks[$i];

                    // Update peserta
                    $data = [
                        'id_tilok' => $idtilok,
                        'tilok' => $tilok->tilok
                    ];
                    //$tilokModel->set($data)->where($where)->update();
                    $pesertaModel->set($data)->where(['nik' => $nik])->update();
                }

                session()->setFlashdata('message', 'Lokasi telah disimpan');
            } else {
                session()->setFlashdata('message', 'Pilih Peserta');
            }
        }

        return redirect()->to('skb/lokasi/setlokasi/' . $kode);
    }

    public function setlokasi($lokasi) {
        $satker = session('lokasi');
        $model = new CrudModel;
        $data['lokasi'] = $model->getLokasiSatker($satker);
        $data['lokasiAll'] = $model->getLokasiBySatker($satker);
        $data['jumlah'] = $model->getJumlahLokasi($satker);

        if ($lokasi !== null) {
            $data['peserta'] = $model->getResult('peserta', ['lokasi_kode' => $lokasi]);
            $data['lok'] = $model->getRow('lokasi', ['kode_tilok' => $lokasi]);
            $data['lokasititik'] = $model->getResult('lokasi_titik', ['lokasi_kode' => $lokasi]);
            $data['lokasikode'] = $lokasi;
            $data['issetlokasi'] = true;
        }
        //dd($data);  
        return view('skb/lokasi', $data);
    }

    public function export()
    {
      $kodesatker = session('lokasi');
      $crud = new CrudModel();
      $data = $crud->getResult('lokasi_titik', array('kode_satker'=>$kodesatker));

      $spreadsheet = new Spreadsheet();
      $sheet = $spreadsheet->getActiveSheet();

      $sheet->setCellValue('A1', 'TITIK LOKASI');
      $sheet->setCellValue('B1', 'ALAMAT');
      $sheet->setCellValue('C1', 'MAPS');
      $sheet->setCellValue('D1', 'KONTAK UNTUK PESERTA');
      $sheet->setCellValue('E1', 'KONTAK UNTUK PANITIA');
      $sheet->setCellValue('F1', 'USERNAME SKBCPNS');
      $sheet->setCellValue('G1', 'PASSWORD SKBCPNS');

      $i = 2;
      foreach ($data as $row) {
        $sheet->setCellValue('A'.$i, $row->tilok);
        $sheet->setCellValue('B'.$i, $row->alamat);
        $sheet->setCellValue('C'.$i, $row->maps);
        $sheet->getCell('D'.$i)->setValueExplicit($row->kontak,\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->getCell('E'.$i)->setValueExplicit($row->kontak_panitia,\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
        $sheet->setCellValue('F'.$i, 'tilok_0'.$row->id_tilok);
        $sheet->setCellValue('G'.$i, 'tilok.skbcpns@2024');
        $i++;
      }

      $tanggal = date('YmdHis');
      $writer = new Xlsx($spreadsheet);
      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      header('Content-Disposition: attachment; filename="Data_Tilok_'.$tanggal.'.xlsx"');
      $writer->save('php://output');
    }

}
