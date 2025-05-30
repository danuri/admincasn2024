<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Titik Lokasi SKTT PPPH Tahap 2</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
          <div class="col-xl-12">

            <div class="card">
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table align-middle table-striped-columns mb-0 datatable">
                    <thead>
                      <tr>
                        <th>Tilok</th>
                        <th>Username</th>
                        <th>Password</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($tilok as $row) {?>
                      <tr>
                        <td><?= $row->tilok?></td>
                        <td><?= $row->username?></td>
                        <td><?= $row->password?></td>
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
<?= $this->endSection() ?>
