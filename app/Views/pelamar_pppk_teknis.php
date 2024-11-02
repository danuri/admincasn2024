<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Statistik Pelamar</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item">Update : <?= $stat[0]->updated_at?></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
          <div class="col-xl-12">
            <div class="card crm-widget">
                <div class="card-body p-0">
                    <div class="row row-cols-xxl-4 row-cols-md-3 row-cols-1 g-0">
                        <div class="col">
                            <div class="py-4 px-3">
                                <h5 class="text-muted text-uppercase fs-13">Jumlah Pendaftar</h5>
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 ms-3">
                                        <h2 class="mb-0"><span class="counter-value" data-target="<?= $jpendaftar->jml_pendaftar?>">0</span></h2>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end col -->
                        <div class="col">
                            <div class="mt-3 mt-md-0 py-4 px-3">
                                <h5 class="text-muted text-uppercase fs-13">Jumlah Submit</h5>
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 ms-3">
                                        <h2 class="mb-0"><span class="counter-value" data-target="<?= $jsubmit->jml_submit?>">0</span></h2>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end col -->
                        <div class="col">
                            <div class="mt-3 mt-md-0 py-4 px-3">
                                <h5 class="text-muted text-uppercase fs-13">Memenuhi Syarat</h5>
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 ms-3">
                                        <h2 class="mb-0"><span class="counter-value" data-target="<?= $jms->jml_ms?>">0</span></h2>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end col -->
                        <div class="col">
                            <div class="mt-3 mt-lg-0 py-4 px-3">
                                <h5 class="text-muted text-uppercase fs-13">Tidak Memenuhi Syarat</h5>
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1 ms-3">
                                        <h2 class="mb-0"><span class="counter-value" data-target="<?= $jtms->jml_tms?>">0</span></h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table align-middle table-striped-columns mb-0 datatable">
                    <thead>
                      <tr>
                        <th>Jabatan</th>
                        <th>Jenis Formasi</th>
                        <th>Lokasi</th>
                        <th>Formasi</th>
                        <th>Pendaftar</th>
                        <th>Submit</th>
                        <th>MS</th>
                        <th>TMS</th>
                        <th>Belum Verif</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($stat as $row) {?>
                      <tr>
                        <td><?= $row->jab_nm?></td>
                        <td><?= $row->jenis_formasi_nm?></td>
                        <td><?= $row->lok_formasi_nm?></td>
                        <td><?= $row->jml_formasi?></td>
                        <td><?= $row->jml_pendaftar?></td>
                        <td><?= $row->jml_submit?></td>
                        <td><?= $row->jml_ms?></td>
                        <td><?= $row->jml_tms?></td>
                        <td><?= $row->jml_blm_verif?></td>
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
