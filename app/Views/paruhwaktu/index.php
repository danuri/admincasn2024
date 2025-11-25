<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Peserta PPPK Paruh Waktu</h4>
                    <div class="page-title-right">
                      <a href="<?= base_url('pengaturan') ?>" class="btn btn-primary">Pengaturan TTE</a>
                      <a href="<?= base_url('paruhwaktu/exportpw') ?>" class="btn btn-primary">Download Data</a>
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
                            <a class="text-success" href="<?= $row->kontrak_file ?>" target="_blank">Download Kontrak</a><br>
                            <?php } ?>
                            <a class="text-primary" href="javascript:;" onclick="previewKontrak('<?= $row->nik ?>')">Buat Kontrak</a>
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

<!-- Modal Preview Data -->
<div class="modal fade" id="kontrakPreview" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Preview Data Peserta untuk Kontrak Kerja</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Pastikan Data sudah sesuai</p>
        <input type="hidden" name="nik" id="kontrakNik">
        <table class="table table-bordered">
          <tr>
            <th>NIP</th>
            <td id="kontrakNip"></td>
          </tr>
          <tr>
            <th>Nama</th>
            <td id="kontrakNama"></td>
          </tr>
          <tr>
            <th>Tempat Lahir</th>
            <td id="kontrakTempatLahir"></td>
          </tr>
          <tr>
            <th>Tanggal Lahir</th>
            <td id="kontrakTanggalLahir"></td>
          </tr>
          <tr>
            <th>Jabatan</th>
            <td id="kontrakJabatan"></td>
          </tr>
          <tr>
            <th>Pendidikan</th>
            <td id="kontrakPendidikan"></td>
          </tr>
          <tr>
            <th>Penempatan</th>
            <td id="kontrakPenempatan"></td>
          </tr>
          <tr>
            <th>No Kontrak</th>
            <td><input type="text" name="kontrak_no" id="kontrakNo" class="form-control"></td>
          </tr>
          <tr>
            <th>Upah</th>
            <td><input type="number" name="kontrak_upah" id="kontrakUpah" class="form-control"></td>
          </tr>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="btnConfirmKontrak">Simpan dan Kirim TTE</button>
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
          $('#kontrakNip').text(response.usul_nip);
          $('#kontrakPendidikan').text(response.pendidikan_baru);
          $('#kontrakNik').val(response.nik);
          $('#kontrakNo').val(response.kontrak_no);
          $('#kontrakNama').text(response.nama_peserta);
          $('#kontrakNip').val(response.usul_nip);
          $('#kontrakTempatLahir').text(response.tempat_lahir);
          $('#kontrakTanggalLahir').text(response.tgl_lahir);
          $('#kontrakJabatan').text(response.usul_jabatan);
          $('#kontrakPendidikan').val(response.usul_pendidikan);
          $('#kontrakPenempatan').text(response.lokasi_baru);
          $('#kontrakUpah').val(response.kontrak_upah);
          
          $('#kontrakPreview').modal('show');
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
        }else{
          location.reload();
          alert('Data gagal dikirim ke TTE');
        }
      }
    });
    // window.location.href = "<?= base_url('paruhwaktu/sprp/') ?>"+nik;
  });

  $('#btnConfirmKontrak').on('click', function(){
    var nik = $('#kontrakNik').val();
    var no = $('#kontrakNo').val();
    var upah = $('#kontrakUpah').val();
    // disable button
    $(this).prop('disabled', true);
    // loading alert
    Toastify({text: "Sedang mengirim...",duration: -1}).showToast();

    // post data
    $.ajax({
      type: "POST",
      url: "<?= base_url('paruhwaktu/kontrak') ?>",
      data: {nik:nik, kontrak_no:no, kontrak_upah:upah},
      dataType: "json",
      success: function (response) {
        if(response.file_url){
          alert('Data berhasil dikirim ke TTE');
          // close modal
          // reload page
          console.log(response);
          location.reload();
          // $('#modalPreview').modal('hide');
        }else{
          console.log(response);
          alert('Data gagal dikirim ke TTE');
        }
      }
    });
    // window.location.href = "<?= base_url('paruhwaktu/sprp/') ?>"+nik;
  });
</script>
<?= $this->endSection() ?>
