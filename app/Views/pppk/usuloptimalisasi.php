<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Usul Penyesuaian Hasil Optimalisasi PPPK Tahap II</h4>
                    <div class="page-title-right">
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
          <div class="col-xl-12">

            <div class="card">
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table align-middle table-striped-columns mb-0 datatable">
                    <thead>
                      <tr>
                        <th>Nomor Peserta</th>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>Satker Asal</th>
                        <th>Satker Optimalisasi</th>
                        <th>Dokumen</th>
                        <th>Unggah</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($peserta as $row) {?>
                      <tr>
                        <td><?= $row->nopeserta?></td>
                        <td><?= $row->nama?></td>
                        <td><?= $row->jabatan?></td>
                        <td><?= $row->lokasi_asal?></td>
                        <td><?= $row->penempatan?></td>
                        <td id="output<?= $row->nopeserta?>"><?= ($row->surat_keterangan)?'<a href="https://ropeg.kemenag.go.id:9000/pengadaan/pppk/'.$row->surat_keterangan.'" target="_blank">Lihat</a>':'Belum Upload'; ?></td>
                        <td>
                            <button type="button" class="btn btn-soft-danger waves-effect waves-light btn-sm" onclick="$('#file<?= $row->nopeserta?>').click()"><i class="ri-file-upload-line"></i></button>
                            <form method="POST" action="<?= site_url('pppk/uploadsk')?>" style="display: none;" id="form<?= $row->nopeserta?>" enctype="multipart/form-data">
                                <input type="hidden" name="nopeserta" value="<?= encrypt($row->nopeserta)?>">
                                <input type="file" name="dokumen" id="file<?= $row->nopeserta?>" onchange="uploadfile('<?= $row->nopeserta?>')">
                            </form>
                        </td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="card">
              <div class="card-body">
                <form action="<?= site_url('pppk/uploaddok/surat')?>" method="POST" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <div class="col-lg-3">
                            <label for="nameInput" class="form-label">Surat Usulan</label>
                        </div>
                        <div class="col-lg-9">
                            <div class="input-group">
                                <input type="file" class="form-control" name="dokumen" id="dokumensurat" aria-describedby="dokumensuratAdd" aria-label="Upload">
                                <?php
                                if($user->pppk_surat){
                                  echo '<a href="https://ropeg.kemenag.go.id:9000/pengadaan/pppk/'.$user->pppk_surat.'" class="btn btn-primary" id="dokumensuratAdd" target="_blank">Lihat Surat</a>';
                                }?>
                                <button class="btn btn-outline-success" type="submit" id="dokumensuratAdd">Upload</button>
                            </div>
                        </div>
                    </div>
                </form>
                <form action="<?= site_url('pppk/uploaddok/sptjm')?>" method="POST" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <div class="col-lg-3">
                            <label for="websiteUrl" class="form-label">SPTJM</label>
                        </div>
                        <div class="col-lg-9">
                            <div class="input-group">
                                <input type="file" class="form-control" name="dokumen" id="dokumensptjm" aria-describedby="dokumensptjmAdd" aria-label="Upload">
                                <?php
                                if($user->pppk_sptjm){
                                  echo '<a href="https://ropeg.kemenag.go.id:9000/pengadaan/pppk/'.$user->pppk_sptjm.'" class="btn btn-primary" id="dokumensptjmAdd" target="_blank">Lihat Surat</a>';
                                }?>
                                <button class="btn btn-outline-success" type="submit" id="dokumensptjmAdd">Upload</button>
                            </div>
                        </div>
                    </div>
                </form>    
              </div>
            </div>
            <div class="card">
              <div class="card-body">
                <div class="text-center">
                    <div class="mb-4">
                        <img src="<?= base_url()?>assets/images/check-box.svg" alt="">
                    </div>
                    <h5>Submit Usulan !</h5>
                    <p class="text-muted">Harap diperhatikan, sebelum submit usulan pastikan dokumen persyaratan telah diunggah.</p>
                    <p class="text-muted">Usulan yang telah disubmit, tidak dapat diubah kembali.</p>
                    <?php
                    if($user->pppk_surat && $user->pppk_sptjm){
                    ?>
                    <a href="<?= site_url('pppk/submit')?>" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin submit usulan?')">Submit</a>
                    <?php
                    }else{
                      ?>
                      <a href="#" class="btn btn-danger" onclick="return alert('Silahkan upload SPTJM dan Surat Usul?')">Submit</a>
                    <?php
                    }
                    ?>
                </div>
              </div>
            </div>
          </div>
        </div>

    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script src="https://malsup.github.io/jquery.form.js" charset="utf-8"></script>
<script>

    function uploadfile(id) {
        $('#form' + id).ajaxSubmit({
            // target: '#output'+id,
            beforeSubmit: function(a, f, o) {
                alert('Mengunggah');
            },
                success: function(data) {
                    if (data.status == 'error') {
                        alert(data.message);
                    } else {
                        alert(data.message);
                        
                        $('#output' + id).html(data.info);
                        
                        if ($("table:contains('Belum Diunggah')").length == 0) {
                            $('#reviewbutton').removeAttr('disabled');
                        }
                    }
                }
            });
        }
</script>
<?= $this->endSection() ?>
