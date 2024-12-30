<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="container-fluid">
        <div class="row ">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">Dokumen Unggahan</h4></p>
                      <!-- <a href="javascript:;" class="btn btn-primary" onclick="adddokumen()">Tambah Dokumen <i class="fas fa-plus"></i></a> -->
                    </div>
                </div>
            </div>
        </div>
        <div class="row">            
          <div class="col-xl-12">
            <div class="card">
              <div class="card-body">
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
                <div class="table-responsive">
                  <table id="buttons-datatables" class="display table table-bordered datatable" style="width:100%">
                    <thead>
                      <tr>
                        <th>DOKUMEN</th>
                        <th>KETERANGAN</th>
                        <!-- <th>DIBUAT TANGGAL</th> -->
                        <th>OPSI</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($dokumen as $row) {?>
                      <tr>
                        <td><?= $row->dokumen?></td>
                        <td><?= $row->keterangan?></td>
                        <!-- <td><?= $row->created_at?></td> -->
                        <td>
                          <a href="<?= site_url('skb/dokumen/unggahan/'.$row->id)?>" class="btn btn-sm btn-primary">Lihat Unggahan</a>
                          <!-- <a href="" class="btn btn-sm btn-success">Edit</a> -->
                          <!-- <a href="" class="btn btn-sm btn-danger" onclick="return confirm('Apa anda yakin ingin menghapus semua Dokumen <?= $row->dokumen?> ?')">Delete</a> -->
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

<div class="modal fade" id="adddokumen" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="title" id="defaultModalLabel">Upload Dokumen</h4>
          <div id="progress"></div>
        </div>
        <div class="modal-body" id="">
          <form class="" action="<?php echo site_url('skb/dokumen/save');?>" method="post" id="tambahdokumen" enctype="multipart/form-data">
            <div class="form-group">
              <label for="">Kategori Dokumen</label>
              <select class="form-control" name="kategori" required>
                <?php foreach ($dokumen as $dok): ?>
                  <option value="<?php echo $dok->id; ?>"><?php echo $dok->dokumen; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label for="">Lampiran (maksimal ukuran file 5MB)</label>
              <input type="file" class="form-control" name="lampiran" id="lampiran">
            </div>
          </form>
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary waves-effect" onclick="$('#tambahdokumen').submit()">SIMPAN</button>
        <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">BATAL</button>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script type="text/javascript">
  $(document).ready(function() {
    $('.btn-danger').on('click', function() {
        $('#adddokumen').modal('hide');
    });
  });
  function adddokumen() {
    $('#tambahdokumen').trigger('reset');
    $('#adddokumen').modal('show');
  }  
</script>
<?= $this->endSection() ?>
