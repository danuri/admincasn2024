<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Data Peserta Lulus CPNS 2024</h4>

                    <!-- <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?= site_url('penetapan/peserta/export');?>" target="_blank" class="btn btn-success"><i class="icon-arrow-left-circle"></i> Download</a></li>
                        </ol>
                    </div> -->

                </div>
            </div>
            <div class="card mt-3">
                <div class="card-body">
                    <h5>Catatan!!!</h5>
                    <ul>
                        <li>Penempatan hanya bisa dilakukan sesuai jumlah formasi (Jabatan dan Lokasi)</li>
                        <li>Penempatan dapat diubah selama belum dikirimkan dokumen SPRP untuk TTE</li>
                        <li>Dokumen SPRP yang telah di-TTE, tidak lagi dapat diubah penempatannya</li> 
                        <li>Pastikan kolom Penempatan SIASN sudah terisi</li>      
                        <li>Pengisian Penempatan SIASN pada menu Penetapan NIP > Formasi (Segera update)</li>      
                    </ul>
                    <h5>Jadwal Penetapan NIP CPNS</h5>
                    <ul>
                        <li>Pemetaan penempatan: <strong>...</strong></li>
                        <li>Input SIASN: <strong>...</strong></li>
                    </ul>
                    <a href="<?= site_url('penetapan/peserta/export');?>" target="_blank" class="btn btn-success"><i class="icon-arrow-left-circle"></i> Download Peserta</a></li>
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
                            <th>Nomor Peserta</th>
                            <th>Nama</th>
                            <th>NIP</th>
                            <th>SPMT</th>
                            <th>BA</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($peserta as $row) {
                        ?>
                        <tr>
                            <td><?php echo $row->nopeserta; ?></td>
                            <td><?php echo $row->nama; ?></td>
                            <td><?php echo $row->usul_nip; ?></td>
                            <td id="output<?= $row->id ?>"><?= ($row->doc_spmt) ? '<a href="javascript:;" onclick="download_spmt(\'' . $row->doc_spmt . '\')">Download SPMT</a>' : 'Belum Diunggah'; ?></td>
                            <td id="outputba<?= $row->id ?>"><?= ($row->doc_ba) ? '<a href="javascript:;" onclick="download_spmt(\'' . $row->doc_ba . '\')">Download SPMT</a>' : 'Belum Diunggah'; ?></td>
                            <td>
                              <button type="button" class="btn btn-soft-danger waves-effect waves-light btn-sm" onclick="$('#file<?= $row->id ?>').click()"><i class="bx bx-upload align-middle"></i> SPMT</button>
                              <form method="POST" action="<?= site_url('penetapan/spmt/upload') ?>" style="display: none;" id="form<?= $row->id ?>" enctype="multipart/form-data">
                                <input type="hidden" name="nopeserta" value="<?= $row->nopeserta ?>">
                                <input type="hidden" name="layanan" value="spmt">
                                <input type="file" name="dokumen" id="file<?= $row->id ?>" onchange="uploadfile('<?= $row->id ?>')">
                              </form>
                              <button type="button" class="btn btn-soft-success waves-effect waves-light btn-sm" onclick="$('#fileba<?= $row->id ?>').click()"><i class="bx bx-upload align-middle"></i> BA</button>
                              <form method="POST" action="<?= site_url('penetapan/spmt/baupload') ?>" style="display: none;" id="formba<?= $row->id ?>" enctype="multipart/form-data">
                                <input type="hidden" name="nopeserta" value="<?= $row->nopeserta ?>">
                                <input type="hidden" name="layanan" value="spmt">
                                <input type="file" name="dokumen" id="fileba<?= $row->id ?>" onchange="uploadfileba('<?= $row->id ?>')">
                              </form>
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
    <!-- container-fluid -->
</div>
<div class="modal fade" id="editpenempatan" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="title" id="defaultModalLabel">Penempatan Peserta</h4>
          <div id="progress"></div>
        </div>
        <div class="modal-body" id="bodypenembatan">
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary waves-effect" onclick="$('#savepenempatan').submit()">SIMPAN</button>
        <button type="button" class="btn btn-danger waves-effect" data-bs-dismiss="modal">BATAL</button>
      </div>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://malsup.github.io/jquery.form.js" charset="utf-8"></script>
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

    });
    
    function download_spmt(doc_spmt) {
        window.open(doc_spmt, '_blank');
    }

    function uploadfile(id) {
    $('#form' + id).ajaxSubmit({
      // target: '#output'+id,
      beforeSubmit: function(a, f, o) {
        alert('Mengunggah');
      },
      success: function(responseText, statusText, xhr, $form) {

        if (responseText.status == 'error') {
          alert(responseText.message);
        } else {
          $('#output' + id).html(responseText.message);
          alert("SPMT telah diunggah");
        }
      }
    });
  }

    function uploadfileba(id) {
    $('#formba' + id).ajaxSubmit({
      // target: '#output'+id,
      beforeSubmit: function(a, f, o) {
        alert('Mengunggah');
      },
      success: function(responseText, statusText, xhr, $form) {

        if (responseText.status == 'error') {
          alert(responseText.message);
        } else {
          $('#outputba' + id).html(responseText.message);
          alert("BA telah diunggah");
        }
      }
    });
  }

</script>
<?= $this->endSection() ?>
