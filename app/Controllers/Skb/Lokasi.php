<?php

namespace App\Controllers\Skb;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\CrudModel;
use App\Models\TilokModel;
use App\Models\LokasiModel;

class Lokasi extends BaseController
{
    public function index($lokasi=null)
    {
        $satker = session('lokasi');
        $model = new CrudModel;
        $data['lokasi'] = $model->getLokasiSatker($satker);
        $data['lokasiAll'] = $model->getLokasiBySatker($satker);

        if ($lokasi !== null) {
            $data['peserta'] = $model->getResult('peserta', ['lokasi_kode' => $lokasi]);
            $data['lok'] = $model->getRow('lokasi', ['kode_tilok' => $lokasi]);
        }
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
            session()->setFlashdata('message', 'Titik Lokasi telah ditambahkan');
            return redirect()->to('skb/lokasi');
        } else {
            //session()->setFlashdata('message', $validation->getErrors());
            // Jika validasi gagal, ambil error dan tampilkan di view
            $data['validation'] = $validation;
            $model = new CrudModel;
            $data['lokasi'] = $model->getLokasiSatker($satker);
            $data['lokasiAll'] = $model->getLokasiBySatker($satker);
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
            <select class="form-control" name="lokasi_kode">
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
            return view('skb/lokasi', $data);
        }
    }

    public function delete($id)
    {
        $satker = session('lokasi');
        $tilokModel = new TilokModel();
        $tilokModel->delete($id);

        //$delete = $this->crud->delete('lokasi_titik',array('lokasi_kode'=>$id,'kode_satker'=>$satker));
        //$this->session->set_flashdata('message','Titik Lokasi telah dihapus');
        session()->setFlashdata('message', 'Titik Lokasi telah dihapus');
        return redirect()->to('skb/lokasi');
    }

    public function setjadwal($lokasi)
    {
        $satker = session('lokasi');
        $model = new CrudModel;
        $data['lokasi'] = $model->getLokasiSatker($satker);
        $data['lokasiAll'] = $model->getLokasiBySatker($satker);
        $data['peserta'] = $model->getResult('peserta', ['lokasi_kode' => $lokasi]);
        $data['lok'] = $model->getRow('lokasi', ['kode_tilok' => $lokasi]);
        return view('skb/lokasi', $data);
    }

}
