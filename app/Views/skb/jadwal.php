<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<style media="screen">
<?php $i=0; foreach ($accounts as $acc) {?>
  <?php echo '.'.strstr($acc->email_praktik, '@', true);?>{
    background-color: <?php echo colors($i);?> !important;
  }
<?php
$i++;
}

function colors($i)
{
  $array = array('#f44336','#009688','#ff9800','#673ab7','#3f51b5','#2196f3','#e91e63','#ffc107','#9c27b0','#795548','#9e9e9e','#607d8b','#00bcd4');
  return $array[$i];
}
?>
</style>

<div class="page-content">
<div class="container-fluid">
  <div class="row ">
    <div class="col-12  align-self-center">
      <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
        <div class="w-sm-100 mr-auto"><h4 class="mb-0">Jadwal Zoom SKB</h4></div>
        <div class="breadcrumb bg-transparent align-self-center m-0 p-0">
          <!-- <a href="<?= site_url('admin/peserta/export/cpns');?>" target="_blank" class="btn btn-info"><i class="icon-arrow-left-circle"></i> Download XLS</a> -->
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-12 col-lg-12 mt-3">
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
      <div class="card-body">
        <ul>
          <li>Download Template File: <a href="<?php echo base_url();?>downloads/template-zooms.xlsx" class="text-danger">Download</a></li>
          <li>Data list sesi mengacu pada file berikut: <a href="<?php echo base_url();?>downloads/list-sesi.xlsx" class="text-danger">Download</a></li>
          <li>Data yang dilampirkan bisa sebagaian (Dicicil) atau seluruhnya</li>
          <li>Jika ingin merubah, diupload ulang</li>
          <li>Pengisian Jadwal maksimal H-1 Jadwal Ujian</li>
        </ul>
        <ul class="list-unstyled card-option">
          <li><button type="button" class="btn btn-success float-right" onclick="$('#filejadwal').click()"><i class="zmdi zmdi-plus"></i>Import Jadwal</button></li>
        </ul>
          <form class="" action="<?php echo site_url('skb/jadwal/importjadwal');?>" method="post" enctype="multipart/form-data" id="importjadwal" style="display:none;">
            <input type="file" name="lampiran" id="filejadwal" class="form-control" onchange="$('#importjadwal').submit()" />
          </form>
      </div>
    </div>
    <div class="card mt-3">
      <div class="card-header  justify-content-between align-items-center">
        <h6 class="card-title">Jadwal Praktik Kerja</h6>
        <!-- <div class="card-header-right">
            <ul class="list-unstyled card-option">
                <li><button type="button" class="btn btn-success float-right" onclick="$('#filejadwal').click()"><i class="zmdi zmdi-plus"></i>Import Jadwal</button></li>
            </ul>
            <form class="" action="<?php echo site_url('skb/jadwal/importjadwal');?>" method="post" enctype="multipart/form-data" id="importjadwal" style="display:none;">
            <input type="file" name="lampiran" id="filejadwal" class="form-control" onchange="$('#importjadwal').submit()" />
            </form>
        </div> -->
      </div>
      <div class="card-body table-responsive">
        <table class="table table-bordered table-striped table-hover datatable">
          <thead class="text-center">
            <tr>
              <th>Jadwal (WIB)</th>
              <th>Nomor Peserta</th>
              <th>Nama</th>
              <th>Formasi</th>
              <th>Akun Praktik</th>
              <th>Praktik Kerja</th>
              <th>Penguji</th>
              <th>Opsi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($peserta as $row) {?>
              <tr>
                <td><?php echo $row->jadwal_praktik;?></td>
                <td>'<?php echo $row->nopeserta;?></td>
                <td><?php echo $row->nama;?></td>
                <td><?php echo $row->formasi;?></td>
                <td class="<?php echo strstr($row->email_praktik, '@', true); ?>"><?php echo $row->email_praktik;?></td>
                <td>
                  ID: <?php echo $row->id_praktik;?><br>
                  Passcode: <?php echo $row->password_praktik;?><br>
                </td>
                <td><?php echo $row->nama_penguji1;?><br><?php echo $row->nama_penguji2;?><br><?php echo $row->nama_penguji3;?></td>
                <td><a href="<?php echo site_url('skb/jadwal/delete/'.$row->id.'/'.$row->nopeserta); ?>">Delete</a></td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
        <hr>
        <h6 class="card-title">Jadwal Wawancara</h6>
        <!-- <div class="card-header-right">
            <ul class="list-unstyled card-option">
                <li><button type="button" class="btn btn-success float-right" onclick="$('#filejadwal').click()"><i class="zmdi zmdi-plus"></i>Import Jadwal</button></li>
            </ul>
            <form class="" action="<?php echo site_url('skb/jadwal/importjadwal');?>" method="post" enctype="multipart/form-data" id="importjadwal" style="display:none;">
              <input type="file" name="lampiran" id="filejadwal" class="form-control" onchange="$('#importjadwal').submit()" />
            </form>
        </div> -->
          <table class="table table-bordered table-striped table-hover datatable">
            <thead class="text-center">
              <tr>
                <th>Jadwal (WIB)</th>
                <th>Nomor Peserta</th>
                <th>Nama</th>
                <th>Formasi</th>
                <th>Agama</th>
                <th>Akun Wawancara</th>
                <th>Wawancara</th>
                <th>Penguji</th>
                <th>Opsi</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($peserta as $row) {?>
                <tr>
                  <td><?php echo $row->jadwal_wawancara;?></td>
                  <td>'<?php echo $row->nopeserta;?></td>
                  <td><?php echo $row->nama;?></td>
                  <td><?php echo $row->formasi;?></td>
                  <td><?php echo $row->agama;?></td>
                  <td class="<?php echo strstr($row->email_wawancara, '@', true); ?>"><?php echo $row->email_wawancara;?></td>
                  <td>
                    ID: <?php echo $row->id_wawancara;?><br>
                    Passcode: <?php echo $row->password_wawancara;?><br>
                  </td>
                  <td><?php echo $row->nama_pewawancara1;?><br><?php echo $row->nama_pewawancara2;?><br><?php echo $row->nama_pewawancara3;?></td>
                  <!-- <td><?php //echo $row->pewawancara1;?><br><?php echo $row->pewawancara2;?></td> -->
                  <td><a href="<?php echo site_url('skb/jadwal/delete/'.$row->id.'/'.$row->nopeserta); ?>">Delete</a></td>
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
<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script src="<?= base_url();?>assets/vendors/datatable/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url();?>assets/vendors/datatable/js/dataTables.bootstrap4.min.js"></script>
<!-- <script src="<?= base_url();?>assets/vendors/datatable/js/jquery.dataTables.min.js"></script> -->
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
// $(document).ready(function() {
//   $('.datatable').DataTable({
//   dom: 'Bfrtip',
//               buttons: [
//                   'copy', 'csv', 'excel', 'pdf', 'print'
//               ],
//    responsive: true
// });
// });
</script>
<?= $this->endSection() ?>