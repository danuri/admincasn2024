<?php

namespace App\Controllers\Skb;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\CrudModel;
use App\Models\PengujiModel;

class Penguji extends BaseController
{
    public function index()
    {
        $satker = session('lokasi');
        $model = new CrudModel;
        $data['penguji'] = $model->getResult('penguji', ['kode_satker' => $satker]);

        return view('skb/penguji', $data);
    }

    public function add()
    {
        $satker = session('lokasi');

        // Load validasi service
        $validation = \Config\Services::validation();

        // Define validation rules
        $rules = [
            'nip' => 'required',
            'nama' => 'required',
            'type' => 'required',
            'jabatan' => 'required'
        ];

        //if($validation->run() === TRUE)
        if ($this->validate($rules))
        {
            // File upload configuration
            $file = $this->request->getFile('lampiran');
            $newFileName = $satker . '-' . $this->request->getPost('nip');
            
            if ($file->isValid() && !$file->hasMoved()) {
                $file->move('./downloads/', $newFileName . '.pdf', true);
            } else {
                session()->setFlashdata('message', $file->getErrorString());
                return redirect()->to('skb/penguji');
            }

            // Jika validasi berhasil, proses data di sini
            $pengujiModel = new PengujiModel();

            $param = [
                'nip' => $this->request->getPost('nip'),
                'nip_lama' => $this->request->getPost('nip_lama'),
                'nama' => $this->request->getPost('nama'),
                'tpt_lahir' => $this->request->getPost('tpt_lahir'),
                'tgl_lahir' => $this->request->getPost('tgl_lahir'),
                'agama' => $this->request->getPost('agama'),
                'jk' => $this->request->getPost('jk'),
                'jabatan' => $this->request->getPost('jabatan'),
                'pendidikan' => $this->request->getPost('pendidikan'),
                'satker' => $this->request->getPost('satker'),
                'type' => $this->request->getPost('type'),
                'kode_satker' => $satker
            ];

            $pengujiModel->insert($param);
            session()->setFlashdata('message', 'Penguji telah ditambahkan');
            return redirect()->to('skb/penguji');
        } else {
            //session()->setFlashdata('message', $validation->getErrors());
            // Jika validasi gagal, ambil error dan tampilkan di view
            $data['validation'] = $validation;
            $model = new CrudModel;
            $data['penguji'] = $model->getResult('penguji', ['kode_satker' => $satker]);
            return view('skb/penguji', $data);
        }
    }

    public function getpegawai($nip)
    {
        $db = \Config\Database::connect('simpeg', false);
        $pegawai = $db->query("SELECT * FROM TEMP_PEGAWAI WHERE NIP_BARU='$nip'")->getRow();
        //$pegawai = $this->simpeg->get_pegawaiby_nip($nip);

        $pegawai = (array)$pegawai;
        unset($pegawai['1']);
        $pegawai = (object)$pegawai;

        return $this->response->setJSON($pegawai);
    }

    public function addfile()
    {
        $satker = session('lokasi');
        $file = $this->request->getFile('lampiran');
        $newFileName = $satker . '-' . $this->request->getPost('nip');
            
        if ($file->isValid() && !$file->hasMoved()) {
            $file->move('./downloads/', $newFileName . '.pdf', true);
            session()->setFlashdata('message', 'Dokumen telah diupload');  
        } else {
            session()->setFlashdata('message', $file->getErrorString());            
        }

        return redirect()->to('skb/penguji');
    }

    public function get_detail($id)
    {
        //$satker = session('lokasi');
        $model = new CrudModel;
        $row = $model->getRow('penguji', 'id='.$id);
        ?>
        <form class="" action="<?php echo site_url('skb/penguji/update');?>" method="post" id="updatepenguji" enctype="multipart/form-data">
            <label for="">NIP</label>
            <div class="input-group input-group-sm">
                <input type="text" class="form-control" name="nip" id="nipx" value="<?php echo $row->nip;?>">
                <input type="hidden" name="id" id="idx" value="<?php echo $row->id;?>">
                <input type="hidden" name="nip_lama" id="nip_lamax" value="<?php echo $row->id;?>">
                <span class="input-group-btn">
                    <button type="button" class="btn btn-info btn-flat" onclick="getpegawaix();">Ambil Ulang Data</button>
                </span>
            </div>
            <div class="form-group">
                <img src="https://ropeg.kemenag.go.id/webview/avatar/image/<?php echo $row->nip_lama;?>" id="fotox">
                Jika tidak ada foto, harap update di simpeg
            </div>
            <div class="form-group">
                <label for="">Nama</label>
                <input type="text" class="form-control" name="nama" id="namax" value="<?php echo $row->nama;?>">
            </div>
            <div class="form-group">
                <label for="">Tempat Lahir</label>
                <input type="text" class="form-control" name="tpt_lahir" id="tpt_lahirx" value="<?php echo $row->tpt_lahir;?>">
            </div>
            <div class="form-group">
                <label for="">Tanggal Lahir</label>
                <input type="text" class="form-control" name="tgl_lahir" id="tgl_lahirx" value="<?php echo $row->tgl_lahir;?>">
            </div>
            <div class="form-group">
                <label for="">Jenis Kelamin</label>
                <input type="text" class="form-control" name="jk" id="jkx" value="<?php echo $row->jk;?>">
            </div>
            <div class="form-group">
                <label for="">Agama</label>
                <input type="text" class="form-control" name="agama" id="agamax" value="<?php echo $row->agama;?>">
            </div>
            <div class="form-group">
                <label for="">Satuan Kerja</label>
                <input type="text" class="form-control" name="satker" id="satkerx" value="<?php echo $row->satker;?>">
            </div>
            <div class="form-group">
                <label for="">Pendidikan</label>
                <input type="text" class="form-control" name="pendidikan" id="pendidikanx" value="<?php echo $row->pendidikan;?>">
            </div>
            <div class="form-group">
                <label for="">Jabatan</label>
                <input type="text" class="form-control" name="jabatan" id="jabatanx" value="<?php echo $row->jabatan;?>">
            </div>
            <div class="form-group">
                <label for="">Jenis Ujian</label>
                <select class="form-control" name="type" id="typex">
                    <option value="Praktik Kerja" <?php echo ($row->type == 'Praktik Kerja')?'selected':'';?>>Praktik Kerja</option>
                    <option value="Wawancara" <?php echo ($row->type == 'Wawancara')?'selected':'';?>>Wawancara</option>
                </select>
            </div>
        </form>
        <script>
            function getpegawaix()
            {
                $('#infoget').html('Loading...');
                var nip = $('#nipx').val();
                $.get('<?php echo site_url('skb/penguji/getpegawai');?>/'+nip, function(result) {

                if(!result.NAMA_LENGKAP){
                    $('#infoget').html('Data tidak ditemukan...');
                    return false;
                }
                $('#infoget').html('');
                // var obj = jQuery.parseJSON( result );

                $('#namax').val(result.NAMA_LENGKAP);
                $('#nipx').val(result.NIP_BARU);
                $('#nip_lamax').val(result.NIP);
                $('#pendidikanx').val(result.PENDIDIKAN);
                $('#jabatanx').val(result.KETERANGAN);
                $('#agamax').val(result.AGAMA);
                $('#alamatx').val(result.ALAMAT_1+' '+result.ALAMAT_2);
                $('#satkerx').val(result.SATKER_1+' '+result.SATKER_2+' '+result.SATKER_3+' '+result.SATKER_4);
                $('#jkx').val(setjk(result.JENIS_KELAMIN));
                $('#tgl_lahirx').val(result.TANGGAL_LAHIR);
                $('#tpt_lahirx').val(result.TEMPAT_LAHIR);
                $('#fotox').attr('src','https://ropeg.kemenag.go.id/webview/avatar/image/'+result.NIP);

                });
            }
        </script>
        <?php
    }

    public function update()
    {
        $satker = session('lokasi');

        // Load validasi service
        $validation = \Config\Services::validation();

        // Define validation rules
        $rules = [
            'nip' => 'required',
            'nama' => 'required',
            'type' => 'required',
            'jabatan' => 'required'
        ];

        //if($validation->run() === TRUE)
        if ($this->validate($rules))
        {
            // Jika validasi berhasil, proses data di sini
            $pengujiModel = new PengujiModel();

            $data = array (
                'nip' => $this->request->getPost('nip'),
                'nip_lama' => $this->request->getPost('nip_lama'),
                'nama' => $this->request->getPost('nama'),
                'tpt_lahir' => $this->request->getPost('tpt_lahir'),
                'tgl_lahir' => $this->request->getPost('tgl_lahir'),
                'agama' => $this->request->getPost('agama'),
                'jk' => $this->request->getPost('jk'),
                'jabatan' => $this->request->getPost('jabatan'),
                'pendidikan' => $this->request->getPost('pendidikan'),
                'satker' => $this->request->getPost('satker'),
                'type' => $this->request->getPost('type'),
                'kode_satker' => $satker
            );

            $where = array (
                'id' => $this->request->getPost('id'),
            );
            $pengujiModel->set($data)->where($where)->update();
            session()->setFlashdata('message', 'Penguji telah diubah');
            return redirect()->to('skb/penguji');
        } else {
            //session()->setFlashdata('message', $validation->getErrors());
            // Jika validasi gagal, ambil error dan tampilkan di view
            $data['validation'] = $validation;
            $model = new CrudModel;
            $data['penguji'] = $model->getResult('penguji', ['kode_satker' => $satker]);
            return view('skb/penguji', $data);
        }
    }

    public function delete($id)
    {
        $pengujiModel = new PengujiModel();
        $pengujiModel->where('id', $id)->delete();
        session()->setFlashdata('message', 'Penguji telah dihapus');
        return redirect()->to('skb/penguji');
    }
}
