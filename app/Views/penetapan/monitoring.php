<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Monitoring Usulan NIP CPNS 2024</h4>

                </div>
            </div>
        </div>
            <?php if (session()->getFlashdata('message')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('message'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('error'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($validation) && $validation->getErrors()): ?>
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <?php foreach ($validation->getErrors() as $error): ?>
                      <p><?= esc($error) ?></p>
                  <?php endforeach; ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            <?php endif; ?>

        <div class="row">
          <div class="col-xl-12">
            <div class="card">
              <div class="card-body">              
              <table class="table table-bordered table-striped table-hover datacpns dt-responsive">
                    <thead>
                        <tr>
                            <th>Status Usulan</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($rekap as $row) {
                        ?>
                        <tr>
                            <td><?php echo $row->usul_status; ?></td>
                            <td><?php echo $row->jumlah; ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>  
              </div>
            </div>
            
            <?php if (session()->get('is_admin') == '1') { ?>
            <div class="card">
              <div class="card-body">              
              <table class="table table-bordered table-striped table-hover datacpns dt-responsive">
                    <thead>
                        <tr>
                            <th>Nomor Peserta</th>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th>No HP</th>
                            <th>Status Usulan</th>
                            <th>NIP</th>
                            <th>PERTEK</th>
                            <th>SK</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($peserta as $row) {
                        ?>
                        <tr>
                            <td><?php echo $row->nopeserta; ?></td>
                            <td><?php echo $row->nama; ?></td>
                            <td><?php echo $row->formasi; ?></td>
                            <td><a href="https://wa.me/<?= hp($row->no_hp); ?>"><?= $row->no_hp; ?></a></td>
                            <td><b><?php echo $row->usul_status; ?></b><br><?php echo $row->usul_alasan_tolak; ?></td>
                            <td><?php echo $row->usul_nip; ?></td>
                            <td>
                                <?php if ($row->usul_path_ttd_pertek) { ?>
                                    <a href="https://ropeg.kemenag.go.id/apimws/upload/download?fname=PERTEK_<?= $row->nopeserta.'_'.str_replace(' ',',',$row->nama)?>.pdf&path=<?= encrypt($row->usul_path_ttd_pertek)?>" target="_blank" class="btn btn-primary btn-sm"><i class="ri-download-2-line"></i></a>
                                <?php } ?>
                            </td>
                            <td></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>  
              </div>
            </div>
            <?php } ?>
            
          </div>
        </div>

    </div>
    <!-- container-fluid -->
</div>
<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script type="text/javascript">
    $(document).ready(function() {
    // $('#datatable').DataTable();
        $('.datacpns').DataTable({
        dom: 'Bfrtip',
        lengthMenu: [
                [ 10, 25, 50, -1 ],
                [ '10 rows', '25 rows', '50 rows', 'Show all' ]
            ],
        buttons: [
            'pageLength','copy',
            {
                extend: 'excel',
                exportOptions: {
                    orthogonal: 'sort'
                },
                customizeData: function ( data ) {
                    for (var i=0; i<data.body.length; i++){
                        for (var j=0; j<data.body[i].length; j++ ){
                            data.body[i][j] = '\u200C' + data.body[i][j];
                        }
                    }
                }
                }
        ]
        });

    //$('.select2').select2();
    });
</script>
<?= $this->endSection() ?>
