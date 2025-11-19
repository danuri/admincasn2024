<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Pengaturan</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Pages</a></li>
                            <li class="breadcrumb-item active">Pengaturan</li>
                        </ol>
                    </div>

                </div>
            </div>
            <div class="col-12">
                <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Input Peserta</h5>
                </div>
                <div class="card-body">
                <form action="<?= site_url('pengaturan/save') ?>" method="POST" id="settingform">
          <div class="row mb-3">
              <div class="col-lg-3">
                  <label for="nik" class="form-label">PLT</label>
              </div>
              <div class="col-lg-9">
                <select name="isplt" id="isplt" class="form-select">
                  <option value="0">Tidak</option>
                  <option value="1">Ya</option>
                </select>
                <p>Jika PLT, tidak perlu mengisi form di bawah.</p>
              </div>
          </div>
          <div class="row mb-3">
              <div class="col-lg-3">
                  <label for="nik" class="form-label">NIK</label>
              </div>
              <div class="col-lg-9">
                  <input type="text" class="form-control" name="ttenik" id="ttenik" placeholder="Enter NIK">
              </div>
          </div>
          <div class="row mb-3">
              <div class="col-lg-3">
                  <label for="nip" class="form-label">NIP</label>
              </div>
              <div class="col-lg-9">
                  <input type="url" class="form-control" name="ttenip" id="ttenip" placeholder="Enter NIP">
              </div>
          </div>
          <div class="row mb-3">
              <div class="col-lg-3">
                  <label for="nama" class="form-label">Nama</label>
              </div>
              <div class="col-lg-9">
                  <input type="text" class="form-control" name="ttenama" id="ttenama" placeholder="Enter Nama">
              </div>
          </div>
          <div class="row mb-3">
              <div class="col-lg-3">
                  <label for="jabatan" class="form-label">Jabatan</label>
              </div>
              <div class="col-lg-9">
                  <input type="text" class="form-control" name="ttejabatan" id="ttejabatan" placeholder="Enter Jabatan">
              </div>
          </div>
          <div class="row mb-3">
              <div class="col-lg-3">
                  <label for="jabatan" class="form-label"></label>
              </div>
              <div class="col-lg-9">
                  <input type="submit" name="submit" id="submit" class="btn btn-primary" value="Simpan Pengaturan">
              </div>
          </div>
      </form>
            </div>
            </div>
        </div>

        

    </div>
    <!-- container-fluid -->
</div>
<?= $this->endSection() ?>
