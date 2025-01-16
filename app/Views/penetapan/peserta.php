<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Data Peserta Lulus CPNS 2024</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li><a href="<?= site_url('penetapan/peserta/export')?>" class="btn btn-primary">Export</a></li>
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
                            <th>Nomor Peserta</th>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th>Jenis</th>
                            <th>Penempatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($peserta as $row){?>
                        <tr>
                            <td><?= $row->nopeserta?></td>
                            <td><?= $row->nama?></td>
                            <td><?= $row->formasi?></td>
                            <td><?= $row->jenis?></td>
                            <td>-</td>
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
