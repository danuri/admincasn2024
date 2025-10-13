<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Peserta PPPK Tahap II</h4>
                    <div class="page-title-right">
                      <a href="<?= site_url('pppk/sinkron') ?>" class="btn btn-primary">Sinkron</a>
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
                        <th>Status</th>
                        <th>USUL NI</th>
                        <th>#</th>
                        <th>Dosen</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($peserta as $row) {?>
                      <tr>
                        <td><?= $row->nopeserta?></td>
                        <td><?= $row->nama?></td>
                        <td><?= $row->jabatan?></td>
                        <td><?= $row->status?></td>
                        <td><?= $row->usul_status?></td>
                        <td>
                          <div class="dropdown card-header-dropdown">
                              <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  <span class="text-muted fs-16"><i class="mdi mdi-dots-vertical align-middle"></i></span>
                              </a>
                              <div class="dropdown-menu dropdown-menu-end">
                                  <a class="dropdown-item" href="https://ropeg.kemenag.go.id:9000/sscasn/2024/eformasi/lamaran/LAMARAN_<?= $row->nik?>.pdf" target="_blank">Surat Lamaran</a>
                                  <?php if(!empty($row->usul_path_ttd_pertek)): ?><a class="dropdown-item" href="https://birosdm.kemenag.go.id/apimws/upload/download?fname=PERTEK_<?= $row->nopeserta.'_'.str_replace(' ','_',$row->nama)?>.pdf&path=<?= encrypt($row->usul_path_ttd_pertek)?>" target="_blank">Pertek</a><?php endif; ?>
                                  <?php if(!empty($row->usul_path_ttd_sk)): ?><a class="dropdown-item" href="https://birosdm.kemenag.go.id/apimws/upload/download?fname=SK_<?= $row->nopeserta.'_'.str_replace(' ',',',$row->nama)?>.pdf&path=<?= encrypt($row->usul_path_ttd_sk)?>" target="_blank">SK</a><?php endif; ?>
                              </div>
                          </div>  
                        </td>
                        <td>
                          <!-- checkbox is dosen -->
                          <div class="form-check">
                            <input type="checkbox" class="form-check-input formcheck" id="<?= $row->nopeserta;?>" <?= ($row->nopeserta == 1)?'checked':'';?> value="1">
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
<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script>
  $('.formcheck').change(function(event) {
    if(this.checked) {
        $.get('<?= site_url('pppk/peserta/cekdosen');?>/'+this.id+'/1', function() {
          alert('Data ditandai sebagai dosen');
        });
      }else{
        $.get('<?= site_url('pppk/peserta/cekdosen');?>/'+this.id+'/0', function() {
          alert('Data ditandai sebagai bukan dosen');
        });
      }
      cekVerifikasi();
    });
</script>
<?= $this->endSection() ?>
