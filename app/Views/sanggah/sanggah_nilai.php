<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Data Sanggah Peserta CPNS 2024</h4>

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
                            <th>Nik Peserta</th>
                            <th>Nomor Peserta</th>
                            <th>Nama Peserta</th>
                            <th>Jabatan</th>
                            <th>Jenis</th>
                            <th>Tanggal Sanggah</th>
                            <th>Alasan Sanggah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($peserta as $row){?>
                        <tr>
                            <td><?= $row->nik?></td>
                            <td><?= $row->no_peserta?></td>                        
                            <td><?= $row->nama_ktp?></td>
                            <td><?= $row->jabatan_nama?></td>
                            <td><?= $row->jenis_formasi?></td>
                            <td><?= $row->tgl_sanggah?></td>
                            <td><?= $row->alasan_sanggah_nilai?></td>
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
