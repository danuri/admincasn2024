<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Selamat Datang Admin Retjeh</h4>

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
          <div class="col-xl-6">
            <!-- <div class="card">
              <div class="card-body">
                <h4>Informasi Verifikasi</h4>
                <ul>
                  <li>Login ke <a href="https://verifikasi-sscasn.bkn.go.id" target="_blank">https://verifikasi-sscasn.bkn.go.id</a></li>
                  <li>Verifikator: NIP, Password: @Vrf2023</li>
                  <li>Supervisor: NIP, Password: @Spv2023</li>
                  <li>Data Pelamar bisa diunduh melalui menu <a href="https://ropeg.kemenag.go.id/admincasn/downloads">Download Data</a></li>
                  <li>Proses Verifikasi Seleksi Administrasi s.d Tanggal 12 Oktober 2023 Pukul 23:59 WIB</li>
                </ul>
                <p>Note:<br>Bagi yang tidak bisa login, silahkan menghubungi Aa Wasis</p>
              </div>
            </div> -->
            <?php if(session('is_skb') == 1){ ?>
            <div class="card">
              <div class="card-body">
                <h4>Informasi Pelaksanaan SKB</h4>
                <ul>
                  <li>Login ke <a href="https://skbcpns.kemenag.go.id" target="_blank">https://skbcpns.kemenag.go.id</a></li>
                  <li>Username: tilok_<?= session('lokasi')?>, Password: <?= session('lokasi')?></li>
                </ul>
              </div>
            </div>
            <?php } ?>
          </div>
        </div>

    </div>
    <!-- container-fluid -->
</div>
<?= $this->endSection() ?>
