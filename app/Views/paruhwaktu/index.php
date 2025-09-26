<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Peserta PPPK Paruh Waktu</h4>
                    <div class="page-title-right">
                      <!-- <a href="<?= base_url('paruhwaktu/export') ?>" class="btn btn-primary">Download</a> -->
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
                        <th>NIK</th>
                        <th>No Peserta</th>
                        <th>Nama</th>
                        <th>Penempatan</th>
                        <th>Jabatan</th>
                        <th>Status DRH</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($peserta as $row) {?>
                      <tr>
                        <td><?= $row->nik?></td>
                        <td><?= $row->no_peserta?></td>
                        <td><?= $row->nama_peserta?></td>
                        <td><?= $row->instansi_paruh_waktu?></td>
                        <td><?= $row->jabatan_melamar?></td>
                        <td>
                          <?php
                            if($row->status_mengundurkan_diri == 'YA'){
                              echo 'Mengundurkan Diri';
                            }else{
                              echo $row->status_drh;
                            }
                          ?>
                        </td>
                        <td>
                          <div class="dropdown card-header-dropdown">
                            <?php if($row->status_drh == 'SUDAH'){ ?>
                                <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="text-muted fs-16"><i class="mdi mdi-dots-vertical align-middle"></i></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="javascript:;" onclick="previewData('<?= $row->nik ?>')">Preview SPRP</a>
                                    <?php if($row->doc_sprp != null){ ?>
                                    <a class="dropdown-item" href="<?= base_url('paruhwaktu/download_sprp/'.encrypt($row->nik)) ?>">Download SPRP</a>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                            </div> 
                        </td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>

    </div>
</div>

<!-- Modal Preview Data -->
<div class="modal fade" id="modalPreview" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Preview Data Peserta untuk SPRP</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Pastikan Data sudah sesuai</p>
        <table class="table table-bordered">
          <tr>
            <th>Nama Peserta</th>
            <td id="previewNamaPeserta"></td>
          </tr>
          <tr>
            <th>Tempat Lahir</th>
            <td id="previewTempatLahir"></td>
          </tr>
          <tr>
            <th>Tanggal Lahir</th>
            <td id="previewTanggalLahir"></td>
          </tr>
          <tr>
            <th>Jabatan</th>
            <td id="previewJabatan"></td>
          </tr>
          <tr>
            <th>Pendidikan</th>
            <td id="previewPendidikan"></td>
          </tr>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="btnConfirmPreview">Kirim TTE</button>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  function previewData(nik){
    $.ajax({
      type: "POST",
      url: "<?= base_url('paruhwaktu/getpeserta') ?>",
      data: {nik:nik},
      dataType: "json",
      success: function (response) {
        if(response.data != null){
          $('#previewNamaPeserta').text(response.data.nama_peserta);
          $('#previewTempatLahir').text(response.data.tempat_lahir);
          $('#previewTanggalLahir').text(response.data.tgl_lahir);
          $('#previewJabatanMelamar').text(response.data.jabatan_baru);
          $('#previewPendidikan').text(response.data.pendidikan_baru);
          
          $('#modalPreview').modal('show');
        }else{
          alert('Data tidak ditemukan');
        }
      }
    });
  }

  function confirmPreview(nik){
    $('#btnConfirmPreview').on('click', function(){
      window.location.href = "<?= base_url('paruhwaktu/sprp/') ?>"+btoa(nik);
    });
  }
</script>
<?= $this->endSection() ?>
