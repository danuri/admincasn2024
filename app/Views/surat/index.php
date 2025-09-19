<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Surat Usulan</h4>
                    <div class="page-title-right">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#suratModal">Buat Surat</button>
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
                        <th>Jenis</th>
                        <th>Nomor Surat</th>
                        <th>Tanggal Surat</th>
                        <th>Perihal</th>
                        <th>Dokumen</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($surat as $row) {?>
                      <tr>
                        <td><?= $row->jenis_surat?></td>
                        <td><?= $row->no_surat?></td>
                        <td><?= $row->tanggal_surat?></td>
                        <td><?= $row->perihal?></td>
                        <td><?= $row->lampiran?></td>
                        <td><a href="<?= site_url('surat/input/'.$row->id)?>" class="btn btn-success btn-sm">Input</a></td>
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

<div class="modal fade" id="suratModal" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="title" id="defaultModalLabel">Buat Surat</h4>
          <div id="progress"></div>
        </div>
        <div class="modal-body" id="">
          <form class="" action="<?= site_url('surat/save');?>" method="post" id="mapform" enctype="multipart/form-data">
            <div class="form-group">
                <label for="">Jenis Surat</label>
                <select class="form-control" name="jenis_surat" id="jenis_surat" required>
                  <option value="d5ba481b59fd483d95d42fc0d311390b">Permohonan Perubahan Detail Alokasi PPPK Paruh Waktu</option>
                </select>
              </div>
              <div class="form-group">
                <label for="">Nomor Surat</label>
                <input type="text" name="nomor_surat" id="nomor_surat" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="">Perihal</label>
                <input type="text" name="perihal" id="perihal" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="">Tanggal Surat</label>
                <input type="date" name="tanggal_surat" id="tanggal_surat" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="">Jabatan Penandatangan</label>
                <input type="text" name="penandatangan" id="penandatangan" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="">Upload Surat</label>
                <input type="file" name="dokumen" id="dokumen" class="form-control" required>
              </div>
          </form>
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary waves-effect" onclick="$('#mapform').submit()">Submit</button>
        <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">TUTUP</button>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  $(document).ready(function() {
  });

</script>
<?= $this->endSection() ?>
