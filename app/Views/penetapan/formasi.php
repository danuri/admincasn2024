<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Data Formasi</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?= site_url('penetapan/formasi/export');?>" target="_blank" class="btn btn-success"><i class="icon-arrow-left-circle"></i> Download</a></li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>

        <div class="row">
          <div class="col-xl-12">
            <div class="card">
              <div class="card-body">
                <table class="table table-bordered table-striped table-hover dataformasi dt-responsive">
                    <thead>
                        <tr>
                            <th>Jabatan</th>
                            <th>Lokasi</th>
                            <th>Jumlah</th>
                            <th>Terisi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($formasi as $row){?>
                        <tr>
                            <td><?= $row->jabatan_sscasn?></td>
                            <td><?= $row->lokasi?></td>
                            <td><?= $row->jumlah?></td>
                            <td><?= $row->terisi?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
              </div>
            </div>
            
          </div>
        </div>

    </div>
    <!-- container-fluid -->
</div>
<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script src="<?= base_url();?>assets/vendors/datatable/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url();?>assets/vendors/datatable/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.colVis.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.0.1/js/buttons.print.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.dataformasi').DataTable({
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
    });
</script>
<?= $this->endSection() ?>
