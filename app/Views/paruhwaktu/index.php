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
                  <table class="table table-bordered align-middle table-striped-columns mb-0 datatable">
                    <thead>
                      <tr>
                        <th>Nomor Peserta</th>
                        <th>Nama</th>
                        <th>Penempatan</th>
                        <th>Jabatan</th>
                        <th>Pendidikan</th>
                        <th>SPRP</th>
                        <th>Kontrak</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($peserta as $row) {?>
                      <tr>
                        <td><?= $row->no_peserta?></td>
                        <td><?= $row->nama_peserta?></td>
                        <td><?= $row->lokasi_baru?></td>
                        <td><?= $row->jabatan_baru?></td>
                        <td><?= $row->pendidikan_baru?></td>
                        <td>
                          <?php if($row->doc_sprp != null){ ?>
                            <a class="text-success" href="<?= $row->doc_sprp ?>" target="_blank">Download SPRP</a>
                            <?php }else{ ?>
                            <a class="text-primary" href="javascript:;" onclick="previewData('<?= $row->nik ?>')">Preview SPRP</a>
                            <?php } ?>
                        </td>
                        <td>
                          <?php if($row->kontrak_file != null){ ?>
                            <a class="text-success" href="<?= $row->kontrak_file ?>" target="_blank">Download Kontrak</a>
                            <?php }else{ ?>
                            <a class="text-primary" href="javascript:;" onclick="previewKontrak('<?= $row->nik ?>')">Preview SPRP</a>
                            <?php } ?>
                        </td>
                        <td>
                          
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
        <input type="hidden" name="nik" id="previewNik">
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
            <td>
              <!-- input group -->
              <input type="text" class="form-control" id="previewPendidikan">
              <p>Jika ada perubahan pendidikan, silahkan disesuaikan.</p>
              <p>Contoh Penulisan: S-1 PENDIDIKAN BAHASA INGGRIS, S-2 PENDIDIKAN BAHASA INDONESIA</p>
            </td>
          </tr>
          <tr>
            <th>Penempatan</th>
            <td id="previewPenempatan"></td>
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

<!-- Modal Setting TTE -->
<div class="modal fade" id="modalSettingTTE" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Setting Penandatangan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="<?= site_url('paruhwaktu/settingsave') ?>" method="POST" id="settingform">
          <div class="row mb-3">
              <div class="col-lg-3">
                  <label for="nik" class="form-label">PLT</label>
              </div>
              <div class="col-lg-9">
                <select name="isplt" id="isplt">
                  <option value="0">Tidak</option>
                  <option value="1">Ya</option>
                </select>
                <p>Jika PLT, tidak perlu mengisi form di bawah.</p>
              </div>
          </div>
          <div class="row mb-3">
              <div class="col-lg-3">
                  <label for="nik" class="form-label">NIK</label>
              </div>
              <div class="col-lg-9">
                  <input type="text" class="form-control" name="ttenik" id="ttenik" placeholder="Enter NIK">
              </div>
          </div>
          <div class="row mb-3">
              <div class="col-lg-3">
                  <label for="nip" class="form-label">NIP</label>
              </div>
              <div class="col-lg-9">
                  <input type="url" class="form-control" name="ttenip" id="ttenip" placeholder="Enter NIP">
              </div>
          </div>
          <div class="row mb-3">
              <div class="col-lg-3">
                  <label for="nama" class="form-label">Nama</label>
              </div>
              <div class="col-lg-9">
                  <input type="text" class="form-control" name="ttenama" id="ttenama" placeholder="Enter Nama">
              </div>
          </div>
          <div class="row mb-3">
              <div class="col-lg-3">
                  <label for="jabatan" class="form-label">Jabatan</label>
              </div>
              <div class="col-lg-9">
                  <input type="text" class="form-control" name="ttejabatan" id="ttejabatan" placeholder="Enter Jabatan">
              </div>
          </div>
      </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-primary" id="btnConfirmSetting" onclick="$('#settingform').submit()">Simpan</button>
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
        if(response.no_peserta != null){
          $('#previewNamaPeserta').text(response.nama_peserta);
          $('#previewNik').val(response.nik);
          $('#previewTempatLahir').text(response.tempat_lahir);
          $('#previewTanggalLahir').text(response.tgl_lahir);
          $('#previewJabatan').text(response.jabatan_baru);
          $('#previewPendidikan').val(response.pendidikan_baru);
          $('#previewPenempatan').text(response.lokasi_baru);
          
          $('#modalPreview').modal('show');
        }else{
          alert('Data tidak ditemukan.');
        }
      }
    });
  }

  function previewKontrak(nik){
    $.ajax({
      type: "POST",
      url: "<?= base_url('paruhwaktu/getpeserta') ?>",
      data: {nik:nik},
      dataType: "json",
      success: function (response) {
        if(response.no_peserta != null){
          $('#previewNamaPeserta').text(response.nama_peserta);
          $('#previewNik').val(response.nik);
          $('#previewTempatLahir').text(response.tempat_lahir);
          $('#previewTanggalLahir').text(response.tgl_lahir);
          $('#previewJabatan').text(response.jabatan_baru);
          $('#previewPendidikan').val(response.pendidikan_baru);
          $('#previewPenempatan').text(response.lokasi_baru);
          
          $('#modalPreview').modal('show');
        }else{
          alert('Data tidak ditemukan.');
        }
      }
    });
  }

  $('#btnConfirmPreview').on('click', function(){
    var nik = $('#previewNik').val();
    var pendidikan = $('#previewPendidikan').val();
    // disable button
    $(this).prop('disabled', true);
    // loading alert
    Toastify({text: "Sedang mengirim...",duration: -1}).showToast();

    // post data
    $.ajax({
      type: "POST",
      url: "<?= base_url('paruhwaktu/sprp') ?>",
      data: {nik:nik, pendidikan:pendidikan},
      dataType: "json",
      success: function (response) {
        if(response.status == 'success'){
          alert('Data berhasil dikirim ke TTE');
          // close modal
          // reload page
          location.reload();
          // $('#modalPreview').modal('hide');
        }else{
          alert('Data gagal dikirim ke TTE');
        }
      }
    });
    // window.location.href = "<?= base_url('paruhwaktu/sprp/') ?>"+nik;
  });
</script>
<?= $this->endSection() ?>
