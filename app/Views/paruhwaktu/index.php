<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Peserta Prioritas Paruh Waktu</h4>
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
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Pendidikan</th>
                        <th>Unor</th>
                        <th>Status</th>
                        <th>Tampungan</th>
                        <th>Instansi SSCASN</th>
                        <th>Usulkan</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($peserta as $row) {?>
                      <tr>
                        <td><?= $row->nik?></td>
                        <td><?= $row->nama?></td>
                        <td><?= $row->pendidikan_nama_nonasn?></td>
                        <td><?= $row->unor_nama_nonasn.' | '.$row->unor_nama_atasan_nonasn?></td>
                        <td><?= $row->status_prioritas?></td>
                        <td><?= ($row->is_tampungan_sscn)?'Ya':'Tidak';?></td>
                        <td><?= $row->instansi_sscn?></td>
                        <td>
                          <input type="checkbox" class="form-check-input formcheck" id="<?= $row->nik;?>" <?= ($row->is_usul == 1)?'checked':'';?> value="1" onclick="usul(this)">
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
<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script>
  function usul(check) {
        if (check.checked) {
            $.get('<?= site_url('paruhwaktu/setusul'); ?>/' + check.id + '/1', function(e) {
                console.log(e);
                alert('Peserta diusulkan');
            });
        } else {
            $.get('<?= site_url('paruhwaktu/setusul'); ?>/' + check.id + '/0', function() {
                alert('Peserta tidak diusulkan');
            });
        }
        // cekVerifikasi();
    }
</script>
<?= $this->endSection() ?>
