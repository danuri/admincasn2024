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
                        <th>Saturan Kerja</th>
                        <th>Jumlah Usul</th>
                        <th>Status</th>
                        <th>Detail</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($satker as $row) {?>
                      <tr>
                        <td><?= $row->satker_asal?></td>
                        <td><?= $row->jumlah?></td>
                        <td>
                            -
                        </td>
                        <td>
                            <a href="<?= site_url('admin/pppk/optimalisasidetail/'.$row->kode_satker_asal)?>" type="button" class="btn btn-soft-info waves-effect waves-light btn-sm"><i class="ri-eye-line"></i></a>
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
