<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Lokasi Pelaksanaan SKB</h4>

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
    <div class="col-12 col-lg-12  mt-3">
      <div class="card">
        <div class="card-header">
            <h5>Lokasi Ujian</h5>
            <span class="text-muted">Panitia Lokasi Ujian diharuskan menyediakan ruangan sesuai dengan Jumlah Ruangan yang tertera di masing-masing titik lokasi.</span>
            <div class="card-header-right">
                <ul class="list-unstyled card-option">
                    <li><button type="button" class="btn btn-success float-right" onclick="addtilok()"><i class="zmdi zmdi-plus"></i>Tambah Titik Lokasi</button></li>
                </ul>
            </div>
        </div>
        <div class="card-body table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Lokasi</th>
                <th>Alamat</th>
                <th>Jumlah Peserta</th>
                <th>Jumlah Ruang</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($lokasi as $row) {?>
                <tr class="table-warning">
                  <td><?= $row->lokasi_titik;?></td>
                  <td></td>
                  <td><a href="<?= site_url('admin/skb/lokasi/index/'.$row->lokasi_kode);?>" class="text-danger"><?= $row->jumlah;?></a></td>
                  <td><?= $row->jumlah_ruangan;?></td>
                </tr>
                <?php
                $tiloks = $this->db->query("SELECT * FROM lokasi_titik WHERE lokasi_kode='$row->lokasi_kode'")->result();
                foreach ($tiloks as $tilok) {?>
                  <tr>
                    <td colspan="4">
                      <b><?php echo $tilok->tilok;?></b><br><br><b><?php echo $tilok->alamat;?></b><br><a href="<?php echo $tilok->maps;?>" target="_blank"><?php echo $tilok->maps;?></a><br><?php echo $tilok->kontak;?> | <?php echo $tilok->kontak_panitia;?>
                      <br><br><a href="javascript:;" onclick="detail('<?php echo $tilok->lokasi_kode;?>')">Edit</a> | <a href="<?php echo site_url('admin/skb/lokasi/delete/'.$tilok->lokasi_kode);?>" onclick="return confirm('Titik Lokasi akan dihapus?')" class="text-red">Delete</a>
                      </td>
                  </tr>
                <?php }
                if(count($tiloks) > 1){
                  ?>
                  <tr>
                    <td class="text-green"><a href="<?php echo site_url('admin/skb/lokasi/setjadwal/'.$row->lokasi_kode);?>" class="btn btn-danger">Tentukan Lokasi Peserta</a></td>
                    <td colspan="4"></td>
                  </tr>
                  <?php
                }
              } ?>
            </tbody>
          </table>
        </div>
      </div>
      <?php if(isset($peserta)){ ?>
        <div class="card mt-3">
          <div class="card-header  justify-content-between align-items-center">
            <h6 class="card-title">Data Peserta Lokasi</h6>
          </div>
          <div class="card-body table-responsive">
              <table class="table table-bordered table-striped table-hover datatable">
                <thead class="text-center">
                  <tr>
                    <th>Nomor Peserta</th>
                    <th>Nama</th>
                    <th>Formasi</th>
                    <th>No HP</th>
                    <th>Satuan Kerja</th>
                    <th>Praktik Kerja (WIB)</th>
                    <th>Ruangan</th>
                    <th>Wawancara (WIB)</th>
                    <th>Ruangan</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  foreach ($peserta as $row) {
                    ?>
                    <tr>
                      <td>'<?php echo $row->nopeserta;?></td>
                      <td><?php echo $row->nama;?></td>
                      <td><?php echo $row->formasi;?></td>
                      <td><?php echo $row->no_hp;?></td>
                      <td><?php echo $row->satker;?></td>
                      <td><?php echo $row->jadwal_praktik;?></td>
                      <td><?php echo $row->ruangan_praktik;?></td>
                      <td><?php echo $row->jadwal_wawancara;?></td>
                      <td><?php echo $row->ruangan_wawancara;?></td>
                    </tr>
                    <?php
                    }
                    ?>
                  </tbody>
                </table>
            </div>
          </div>
      <?php } ?>
    </div>
  </div>

    </div>
    <!-- container-fluid -->
</div>
<?= $this->endSection() ?>
