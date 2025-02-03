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
                <table class="table table-bordered table-striped" id="pesertaskb">
                    <thead>
                        <tr>
                            <th>Nomor Peserta</th>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th>Jenis</th>
                            <th>Penempatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>                
              <?php } else { ?>
                <table class="table table-bordered table-striped" id="pesertaadmin">
                    <thead>
                        <tr>
                            <th>Nomor Peserta</th>
                            <th>Nama</th>
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
<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#pesertaskb').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
            url: '<?= site_url('penetapan/peserta/getdataskb')?>'
            },
            columns: [
                {data: 'nopeserta'},
                {data: 'nama'},
                {data: 'formasi'},
                {data: 'jenis'},
                {data: 'penempatan'},
                {data: 'aksi'}
            ]
        });
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
                {data: 'formasi'},
                {data: 'jenis'},
                {data: 'penempatan'}
            ]
        });
    });
    function penempatan(nopeserta) {
        $('#bodypenembatan').load('<?php echo site_url('penetapan/peserta/get_detail');?>/'+nopeserta);
	    $('#editpenempatan').modal('show');
    }

    function download_sprp(doc_sprp) {
        window.open(doc_sprp);
    }
</script>
<?= $this->endSection() ?>
