<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Data Usulan</h4>
                    <div class="page-title-right">
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
          <div class="col-xl-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row g-6">
                        <div class="col-lg-6 col-md-12 col-sm-12">
                            <div style="display: table-row;">
                                <div style="display: table-cell; padding-right: 0.5rem;">
                                    <h6>Jenis Layanan</h6>
                                </div>
                                <div style="display: table-cell; padding-right: 0.5rem;">:</div>
                                <div style="display: table-cell; padding-right: 0.5rem;"><?= layanan($surat->jenis_surat)?></div>
                            </div>
                            <div style="display: table-row;">
                                <div style="display: table-cell; padding-right: 0.5rem;">
                                    <h6>Nomor Surat</h6>
                                </div>
                                <div style="display: table-cell; padding-right: 0.5rem;">:</div>
                                <div style="display: table-cell; padding-right: 0.5rem;"><?= $surat->no_surat?></div>
                            </div>
                            <div style="display: table-row;">
                                <div style="display: table-cell; padding-right: 0.5rem;">
                                    <h6>Perihal</h6>
                                </div>
                                <div style="display: table-cell; padding-right: 0.5rem;">:</div>
                                <div style="display: table-cell; padding-right: 0.5rem;"><?= $surat->perihal?></div>
                            </div>
                            <div style="display: table-row;">
                                <div style="display: table-cell; padding-right: 0.5rem;">
                                    <h6>Tanggal Surat</h6>
                                </div>
                                <div style="display: table-cell; padding-right: 0.5rem;">:</div>
                                <div style="display: table-cell; padding-right: 0.5rem;"><?= $surat->tanggal_surat?></div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12">
                            <div style="display: table-row;">
                                <div style="display: table-cell; padding-right: 0.5rem;">
                                    <h6>Penanda Tangan</h6>
                                </div>
                                <div style="display: table-cell; padding-right: 0.5rem;">:</div>
                                <div style="display: table-cell; padding-right: 0.5rem;"><?= $surat->penandatangan?></div>
                            </div>
                            <div style="display: table-row;">
                                <div style="display: table-cell; padding-right: 0.5rem;">
                                    <h6>Dokumen</h6>
                                </div>
                                <div style="display: table-cell; padding-right: 0.5rem;">:</div>
                                <div style="display: table-cell; padding-right: 0.5rem;"><a href="https://ropeg.kemenag.go.id:9000/pengadaan/pppk/<?= $surat->lampiran?>" class="btn btn-primary btn-sm" target="_blank">Dokumen</a></div>
                            </div>
                            <div style="display: table-row;">
                                <div style="display: table-cell; padding-right: 0.5rem;">
                                    <h6>Status</h6>
                                </div>
                                <div style="display: table-cell; padding-right: 0.5rem;">:</div>
                                <div style="display: table-cell; padding-right: 0.5rem;"><?= surat_status($surat->status)?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table align-middle table-striped-columns mb-0 datatable">
                    <thead>
                      <tr>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($paruhwaktu as $row) {?>
                      <tr>
                        <td><?= $row->nik?></td>
                        <td><?= $row->nama?></td>
                        <td><?= $row->jabatan?></td>
                        <td>
                            <?php if($surat->status == 0):?>
                          <a href="<?= site_url('surat/inputdelete/'.encrypt($row->id))?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
                            <?php endif;?>
                        </td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <?php if($surat->status == 0):?>
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Input Peserta</h5>
                </div>
                <div class="card-body">
                    <p>Tidak perlu mengisi peserta</p>
                    <!-- <form action="<?= site_url('surat/savedefault')?>" method="POST" enctype="multipart/form-data">
                        <div class="row mb-4">
                            <label for="nik" class="col-sm-3 col-form-label">NIK</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                <input type="number" class="form-control" name="nik" id="nik" required>
                                <button class="btn btn-outline-success" type="button" id="cari">Cari</button>
                                </div>
                                <input type="hidden" name="surat_id" id="surat_id" value="<?= $surat->id?>">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label for="xnama" class="col-sm-3 col-form-label">Nama</label>
                            <div class="col-sm-9">
                                <input type="text" name="xnama" class="form-control" id="xnama" disabled>
                                <input type="hidden" name="nama" id="nama">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label for="xjabatan" class="col-sm-3 col-form-label">Jabatan</label>
                            <div class="col-sm-9">
                                <input type="text" name="xjabatan" class="form-control" id="xjabatan" disabled>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label for="penempatan" class="col-sm-3 col-form-label">Keterangan</label>
                            <div class="col-sm-9">
                                <textarea name="keterangan" id="keterangan" class="form-control" rows="3" placeholder="Isi keterangan dengan perubahan, contoh: perubahan pendidikan, perubahan lokasi atau keduanya"></textarea>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label for="penempatan" class="col-sm-3 col-form-label"></label>
                            <div class="col-sm-9">
                                <input type="submit" name="submit" id="submit" class="btn btn-primary" value="Simpan">
                            </div>
                        </div>
                    </form> -->
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <a href="<?= site_url('surat/submit/'.encrypt($surat->id))?>" class="btn btn-success" onclick="return confirm('Apakah Anda yakin ingin mengirim surat usulan ini?')">Kirim Surat Usulan</a>
                </div>
            </div>
            <?php endif;?>

    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  $(document).ready(function() {

    $('#cari').on('click', function(event) {
    $nik = $('#nik').val();

    if($nik == ''){
      alert('NIK tidak boleh kosong');
    }else{
      axios.get('<?= site_url()?>surat/search/'+$nik)
      .then(function (response) {
        console.log(response.data);
        if(response.data.status == 'error'){
            alert(response.data.message);
        }else{
            $('#xnama').val(response.data.nama_peserta);
            $('#nama').val(response.data.nama_peserta);
            $('#xjabatan').val(response.data.jabatan_melamar);
        }
      });
    }

  });

  });

  

</script>
<?= $this->endSection() ?>
