<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Data Rekapitulasi</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Pages</a></li>
                            <li class="breadcrumb-item active">Starter</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>

        <div class="row">
          <div class="col-xl-12">
            <div class="card">
              <div class="card-body">
                <table class="table datatable">
                    <thead>
                        <tr>
                            <th>Jabatan</th>
                            <th>Jumlah Formasi</th>
                            <th>Jumlah Lulus</th>
                            <th>Sudah Penempatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($rekapitulasi as $row){?>
                        <tr>
                            <td><?= $row->formasi?></td>
                            <td><?= $row->jumlah_formasi?></td>
                            <td><?= $row->jumlah?></td>
                            <td>0</td>
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
