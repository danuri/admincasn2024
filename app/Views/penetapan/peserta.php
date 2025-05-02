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
              <?php if (session()->get('is_skb') == '1') { ?>  
                <!-- <table class="table table-bordered table-striped" id="pesertaskb"> -->
                <table class="table table-bordered table-striped table-hover datacpns dt-responsive">
                    <thead>
                        <tr>
                            <th>Nomor Peserta</th>
                            <th>Nama</th>
                            <th>Pendidikan</th>
                            <th>Jabatan</th>
                            <th>Jenis</th>
                            <th>Penempatan</th>
                            <th>Penempatan SIASN</th>
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
                            <td><?php echo $row->pendidikan; ?></td>
                            <td><?php echo $row->formasi; ?></td>
                            <td><?php echo $row->jenis; ?></td>
                            <td>
                                <?php if ($row->penempatan == null || $row->penempatan == '') { 
                                    echo '-';
                                } else {
                                    echo $row->penempatan;
                                } ?>
                            </td>
                            <td>
                            <?= ($row->lokasi_siasn_nama)?$row->lokasi_siasn_nama.' <a href="javascript:;" onclick="copyToClipboard(\''.$row->lokasi_siasn_nama.'\');" class="text-success"><i class="ri-survey-line"></i></a>':'-';?>
                            </td>
                            <td>
                                <?php if ($row->penempatan_id != null && $row->penempatan_id != '') {
                                        if ($row->doc_sprp != null && $row->doc_sprp != '') {
                                            if ($tanggal >= $tanggal_download_sprp) { 
                                ?>
                                                <a href="javascript:;" onclick="download_sprp('<?php echo $row->doc_sprp; ?>')" class="btn btn-sm btn-success">Download-SPRP</a>
                                <?php       } else { ?>
                                                <label>Menunggu TTE</label> 
                                <?php } 
                                        } else { ?>
                                            <a href="<?php echo site_url('penetapan/peserta/reset/'.$row->nopeserta); ?>" class="btn btn-sm btn-danger">Reset</a>
                                            <?php if ($row->no_sprp != null && $row->no_sprp != '')  {?>
                                                <a href="<?php echo site_url('penetapan/peserta/sprp/'.$row->nopeserta); ?>" class="btn btn-sm btn-success" onclick="return confirm('Apakah Anda yakin SPRP akan dikirimkan untuk TTE?')">Kirim TTE</a>
                                            <?php } ?>
                                <?php   } 
                                      } else { ?>
                                            <a href="javascript:;" onclick="penempatan('<?php echo $row->nopeserta; ?>')" class="btn btn-sm btn-success">Penempatan</a>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>                
              <?php } else { ?>
                <table class="table table-bordered table-striped" id="pesertaadmin">
                    <thead>
                        <tr>
                            <th>Nomor Peserta</th>
                            <th>Nama</th>
                            <th>Pendidikan</th>
                            <th>Jabatan</th>
                            <th>Jenis</th>
                            <th>Penempatan</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table> 
              <?php } ?>
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
<script type="text/javascript">
    // $(document).ready(function() {
    //     var table = $('#pesertaskb').DataTable({
    //         processing: true,
    //         serverSide: true,
    //         ajax: {
    //         url: '<?= site_url('penetapan/peserta/getdataskb')?>'
    //         },
    //         columns: [
    //             {data: 'nopeserta'},
    //             {data: 'nama'},
    //             {data: 'formasi'},
    //             {data: 'jenis'},
    //             {data: 'penempatan'},
    //             {data: 'aksi'}
    //         ]
    //     });
    // });
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

        //$('#formasix').select2();
        //$('.select2').select2();
    });
    $(document).ready(function() {
        var table = $('#pesertaadmin').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
            url: '<?= site_url('penetapan/peserta/getdataadmin')?>'
            },
            columns: [
                {data: 'nopeserta'},
                {data: 'nama'},
                {data: 'pendidikan'},
                {data: 'formasi'},
                {data: 'jenis'},
                {data: 'penempatan'}
            ]
        });        
        //$('#formasix').select2();
    });
    function penempatan(nopeserta) {
        // $('#bodypenembatan').load('<?php echo site_url('penetapan/peserta/get_detail');?>/'+nopeserta);
	    // $('#editpenempatan').modal('show');
        // $('#formasix').select2();
        $('#bodypenembatan').load('<?php echo site_url('penetapan/peserta/get_detail');?>/' + nopeserta, function() {
            $('#formasix').select2({
                placeholder: 'Pilih lokasi penempatan',
                dropdownParent: $('#editpenempatan'),
                allowClear: true,
            }); 
        });        
        //$.fn.modal.Constructor.prototype._enforceFocus = function() {};
        $('#editpenempatan').modal('show');
    }

    // $('#editpenempatan').on('shown.bs.modal', function () {
    //     $('#formasix').select2('open');
    // });

    function download_sprp(doc_sprp) {
        window.open(doc_sprp);
    }

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text)
            .then(() => alert("Text copied to clipboard!"))
            .catch(err => console.error("Failed to copy: ", err));
    }
</script>
<?= $this->endSection() ?>
