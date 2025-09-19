<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Data Usulan</h4>
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
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Pendidikan</th>
                        <th>Jabatan</th>
                        <th>Lokasi</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($paruhwaktu as $row) {?>
                      <tr>
                        <td><?= $row->nik?></td>
                        <td><?= $row->nama?></td>
                        <td><?= $row->pendidikan?></td>
                        <td><?= $row->jabatan?></td>
                        <td><?= $row->lokasi?></td>
                        <td>
                          <a href="<?= site_url('surat/delete/'.encrypt($row->id))?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</a>
                        </td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <div class="card">
                        <td><?= $row->lampiran?></td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="<?= site_url('surat/create')?>" method="POST" enctype="multipart/form-data">
                        <div class="row mb-4">
                            <label for="nomor_usul" class="col-sm-3 col-form-label">NIK</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                <input type="number" class="form-control" name="nik" id="nik" required>
                                <button class="btn btn-outline-success" type="button" id="cari">Cari</button>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label for="nomor_usul" class="col-sm-3 col-form-label">Nama</label>
                            <div class="col-sm-9">
                                <input type="text" name="nama" class="form-control" id="nama" readonly>
                            </div>
                        </div>
                    </form>
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
