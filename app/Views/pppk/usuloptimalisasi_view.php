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
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($peserta as $row) {?>
                      <tr>
                        <td><?= $row->nopeserta?></td>
                        <td><?= $row->nama?></td>
                        <td><?= $row->jabatan?></td>
                        <td><?= $row->satker_asal?></td>
                        <td><?= $row->penempatan_satker?></td>
                        <td id="output<?= $row->nopeserta?>"><?= ($row->surat_keterangan)?'<a href="https://ropeg.kemenag.go.id:9000/pengadaan/pppk/'.$row->surat_keterangan.'" target="_blank">Lihat</a>':'Tidak Diusulkan'; ?></td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="card">
              <div class="card-body">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <div class="col-lg-3">
                            <label for="nameInput" class="form-label">Surat Usulan</label>
                        </div>
                        <div class="col-lg-9">
                            <div class="input-group">
                                <?php
                                if($user->pppk_surat){
                                  echo '<a href="https://ropeg.kemenag.go.id:9000/pengadaan/pppk/'.$user->pppk_surat.'" class="btn btn-primary" id="dokumensuratAdd" target="_blank">Lihat Surat</a>';
                                }?>
                            </div>
                        </div>
                    </div>
                </form>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <div class="col-lg-3">
                            <label for="websiteUrl" class="form-label">SPTJM</label>
                        </div>
                        <div class="col-lg-9">
                            <div class="input-group">
                                <?php
                                if($user->pppk_sptjm){
                                  echo '<a href="https://ropeg.kemenag.go.id:9000/pengadaan/pppk/'.$user->pppk_sptjm.'" class="btn btn-primary" id="dokumensptjmAdd" target="_blank">Lihat Surat</a>';
                                }?>
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
                    <h5>Usulan Telah Dikirimkan !</h5>
                    <p class="text-muted">Anda tidak dapat merubah usulan.</p>
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
</script>
<?= $this->endSection() ?>
