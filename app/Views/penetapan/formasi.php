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
                            <th>Lokasi SSCASN</th>
                            <th>Lokasi SIASN</th>
                            <th>Jumlah</th>
                            <th>Terisi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($formasi as $row){?>
                        <tr>
                            <td><?= $row->jabatan_sscasn?></td>
                            <td><?= $row->lokasi?></td>
                            <td>
                                <?= ($row->lokasi_siasn_nama)?$row->lokasi_siasn_nama.' <a href="javascript:;" onclick="copyToClipboard(\''.$row->lokasi_siasn_nama.'\');" class="text-success"><i class="ri-survey-line"></i></a>':'';?>
                                <a href="javascript:;" onclick="updateunor(\"<?= $row->lokasi?>\")">Update</a>
                            </td>
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

<div class="modal fade" id="editunor" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="title" id="defaultModalLabel">Pemetaan Unor</h4>
          <div id="progress"></div>
        </div>
        <div class="modal-body">
        <form action="" method="POST" class="row g-3" id="saveunor">
            <div class="col-md-12">
                <label for="fullnameInput" class="form-label">Unor SSCASN</label>
                <textarea class="form-control" rows="3" name="sscasn" id="sscasn" readonly></textarea>
            </div>
            <div class="col-md-12">
                <label for="inputEmail4" class="form-label">Unor SIASN</label>
                <select class="form-select" id="searchunor" name="unor"></select>
                <input type="hidden" name="siasnname" id="siasnname">
            </div>
        </form>
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary waves-effect" onclick="$('#saveunor').submit()">SIMPAN</button>
        <button type="button" class="btn btn-danger waves-effect" data-bs-dismiss="modal">BATAL</button>
      </div>
    </div>
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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

        $('#searchunor').select2({
        dropdownParent: $('#editunor'),
        ajax: {
            url: '<?= site_url() ?>ajax/searchunor',
            data: function (params) {
            var query = {
                search: params.term,
                type: 'public'
            }

            return query;
            },
            processResults: function (data) {
            return {
                results: data
            };
            },
            processResults: (data, params) => {
                const results = data.map(item => {
                return {
                    id: item.id,
                    text: item.unor_nama+' - '+item.unor_induk_nama,
                };
                });
                return {
                results: results,
                }
            },
        },
        placeholder: 'Cari Unor',
        minimumInputLength: 5,
        });

        $('#searchunor').on('change', function() {
            var data = $('#searchunor').select2('data');
            $('#siasnname').val(data[0].text);
        });
    });
    
    function updateunor(sscasn) {
        $('#sscasn').html(sscasn);
        $('#editunor').modal('show');
    }

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text)
            .then(() => alert("Text copied to clipboard!"))
            .catch(err => console.error("Failed to copy: ", err));
    }
    
</script>
<?= $this->endSection() ?>
