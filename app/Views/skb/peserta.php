<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="page-content">
<div class="container-fluid">
  <div class="row">
      <div class="col-12">
          <div class="page-title-box d-sm-flex align-items-center justify-content-between">
              <h4 class="mb-sm-0">Data Peserta</h4>

              <div class="page-title-right">
                  <ol class="breadcrumb m-0">
                      <li class="breadcrumb-item"><a href="<?= site_url('skb/peserta/export');?>" target="_blank" class="btn btn-success"><i class="icon-arrow-left-circle"></i> Download</a></li>
                  </ol>
              </div>

          </div>
      </div>
  </div>
  <div class="row">
    <div class="col-12 col-lg-12  mt-3">
        <?php if (session()->getFlashdata('message')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('message'); ?>
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
      <div class="card">
        <div class="card-header  justify-content-between align-items-center">
          <h6 class="card-title">List Peserta per Lokasi</h6>
        </div>
        <div class="card-body table-responsive">
        <table class="table table-bordered table-striped" id="peserta">
          <thead>
            <tr>
              <th>NIK</th>
              <th>NO PESERTA</th>
              <th>NAMA</th>
              <th>AGAMA</th>
              <th>NO. HP</th>
              <th>FORMASI</th>
              <th>JENIS</th>
              <th>KELOMPOK</th>
              <th>SKB CAT</th>
              <th>LOKASI PROVINSI</th>
              <th>LOKASI TITIK</th>
              <th>PRAKTIK KERJA (WIB)</th>
              <th>WAWANCARA (WIB)</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>

      </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-12 col-lg-12  mt-3">
      <div class="card">
        <div class="card-header  justify-content-between align-items-center">
          <h6 class="card-title">Sebaran Peserta per Lokasi</h6>
        </div>
        <div class="card-body table-responsive">
          <table class="table table-bordered table-striped datatable">
            <thead>
              <tr>
                <th width="40%">LOKASI</th>
                <th>KONTAK</th>
                <th>JUMLAH</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($lokasi as $row) {?>
                <tr>
                  <td>
                    PROVINSI: <b><?= $row->lokasi_provinsi;?></b><br>
                    KABUPATEN: <b><?= $row->lokasi_kabupaten;?></b><br>
                    TITIK LOKASI: <b><?= $row->lokasi_titik;?></b>
                  </td>
                  <td>
                    <?= $row->tilok;?><br>
                    <?= $row->alamat;?><br>
                    <a href="<?= $row->maps;?>" target="_blank" class="badge badge-warning">Google Maps</a><br>
                    <?= $row->kontak_panitia;?><br>
                  </td>
                  <td><?= $row->jumlah;?></td>
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

<div class="modal fade bs-example-modal-lg" id="detailmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="myLargeModalLabel">Data Peserta</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        </button>
      </div>
      <div class="modal-body" id="detail">

      </div>
      <div class="modal-footer">
          <input type="hidden" name="idprint" id="idprint" value="">
          <button type="button" class="btn btn-danger" onclick="printit()">Print DRH</button>
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script src="<?= base_url();?>assets/vendors/datatable/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url();?>assets/vendors/datatable/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url();?>assets/vendors/datatable/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url();?>assets/vendors/datatable/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url();?>assets/vendors/datatable/jszip/jszip.min.js"></script>
<script src="<?= base_url();?>assets/vendors/datatable/pdfmake/pdfmake.min.js"></script>
<script src="<?= base_url();?>assets/vendors/datatable/pdfmake/vfs_fonts.js"></script>
<script src="<?= base_url();?>assets/vendors/datatable/buttons/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url();?>assets/vendors/datatable/buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?= base_url();?>assets/vendors/datatable/buttons/js/buttons.colVis.min.js"></script>
<script src="<?= base_url();?>assets/vendors/datatable/buttons/js/buttons.flash.min.js"></script>
<script src="<?= base_url();?>assets/vendors/datatable/buttons/js/buttons.html5.min.js"></script>
<script src="<?= base_url();?>assets/vendors/datatable/buttons/js/buttons.print.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    var table = $('#peserta').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: '<?= site_url('skb/peserta/getdata')?>'
        },
        columns: [
            {data: 'nik'},
            {data: 'nopeserta'},
            {data: 'nama'},
            {data: 'agama'},
            {data: 'no_hp'},
            {data: 'formasi'},
            {data: 'jenis'},
            {data: 'kelompok'},
            {data: 'skb_jadwal_tanggal'},
            {data: 'lokasi_provinsi'},
            {data: 'lokasi_kabupaten'},
            {data: 'jadwal_praktik'},
            {data: 'jadwal_wawancara'}
        ]
    });
  });

  function detail(nik) {
    //$('#detail').load('<?= site_url('https://casn.kemenag.go.id/drh/peserta');?>/'+nik.replace(/'/g, ""));
    //$('#detail').load('http://localhost:8001/drh/peserta/7602011011960006');
    //$('#idprint').val(nik);
    //$('#detailmodal').modal('show');
    window.open("https://casn.kemenag.go.id/drh/peserta/"+nik, "myWindow", 'width=800,height=600');
  }

  function printit()
  {
    window.open("https://casn.kemenag.go.id/admin/skb/peserta/printit/"+$('#idprint').val(), "myWindow", 'width=800,height=600');
  }

</script>
<?= $this->endSection() ?>