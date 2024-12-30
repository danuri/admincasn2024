<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="container-fluid">
        <div class="row ">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">Unggahan Dokumen <?= $dokumen->dokumen?></h4></p>
                      <a href="javascript:;" class="btn btn-primary" onclick="addunggahan()">Tambah Dokumen <i class="fas fa-plus"></i></a>
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
                        <th>KODE LOKASI</th>
                        <th>LOKASI</th>
                        <th>LAMPIRAN</th>
                        <th>UPLOAD DATE</th>
                        <th>OPSI</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($unggahan as $row) {?>
                      <tr>
                        <td><?= $row->kode_satker?></td>
                        <td><?= $row->satker?></td>
                        <?php if($row->attachment){ ?>
                        <td><a href="https://dokupak.kemenag.go.id:9000/casn2024/dokumen/<?= $row->attachment;?>" target="_blank" class="btn btn-sm btn-primary">Lihat</a></td>
                        <td><?= $row->created_at?></td>
                        <td><a href="<?= site_url('skb/dokumen/deleteunggahan/'.$row->idattachment)?>" class="btn btn-sm btn-danger" onclick="return confirm('Dokumen akan dihapus?')">Delete</a></td>
                      <?php }else{ ?>
                        <td>Belum</td>
                        <td></td>
                        <td></td>
                      <?php } ?>
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

<div class="modal fade" id="addunggahan" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="title" id="defaultModalLabel">Upload Dokumen</h4>
          <div id="progress"></div>
        </div>
        <div class="modal-body" id="">
          <form class="" action="<?php echo site_url('skb/dokumen/saveunggahan');?>" method="post" id="tambahunggahan" enctype="multipart/form-data">
            <div class="form-group">
              <label for="">Kategori Dokumen</label>
              <input type="hidden" class="form-control" name="kategori" id="kategori" value="<?= $dokumen->id?>">
              <input type="text" class="form-control" value="<?= $dokumen->dokumen?>" readonly>
            </div>
            <br>
            <div class="form-group">
              <label for="">Lampiran (maksimal ukuran file 5MB)</label>
              <input type="file" class="form-control" name="lampiran" id="lampiran">
            </div>
          </form>
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary waves-effect" onclick="$('#tambahunggahan').submit()">SIMPAN</button>
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
        $('#addunggahan').modal('hide');
    });
  });
  function addunggahan() {
    $('#tambahunggahan').trigger('reset');
    $('#addunggahan').modal('show');
  }
  </script>
<?= $this->endSection() ?>