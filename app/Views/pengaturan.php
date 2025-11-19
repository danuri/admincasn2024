<?= $this->extend('template') ?>

<?= $this->section('style') ?>
<link rel="stylesheet" href="<?= base_url()?>assets/libs/filepond/filepond.min.css" type="text/css" />
<link rel="stylesheet" href="<?= base_url()?>assets/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.css">
<?= $this->endSection() ?>

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
                    <h5 class="card-title mb-0">Pengaturan Penandatangan</h5>
                </div>
                <div class="card-body">
                <form action="<?= site_url('pengaturan/save') ?>" method="POST" id="settingform">
          <div class="row mb-3">
              <div class="col-lg-3">
                  <label for="nik" class="form-label">PLT</label>
              </div>
              <div class="col-lg-9">
                <select name="isplt" id="isplt" class="form-select">
                  <option value="0" <?= $user->is_sdm == 0 ? 'selected' : '' ?>>Tidak</option>
                  <option value="1" <?= $user->is_sdm == 1 ? 'selected' : '' ?>>Ya</option>
                </select>
                <p>Jika PLT, tidak perlu mengisi form di bawah.</p>
              </div>
          </div>
          <div class="row mb-3">
              <div class="col-lg-3">
                  <label for="nik" class="form-label">NIK</label>
              </div>
              <div class="col-lg-9">
                  <input type="text" class="form-control" name="ttenik" id="ttenik" placeholder="Enter NIK" value="<?= $user->tte_nik ?>">
              </div>
          </div>
          <div class="row mb-3">
              <div class="col-lg-3">
                  <label for="nip" class="form-label">NIP</label>
              </div>
              <div class="col-lg-9">
                  <input type="text" class="form-control" name="ttenip" id="ttenip" placeholder="Enter NIP" value="<?= $user->tte_nip ?>">
              </div>
          </div>
          <div class="row mb-3">
              <div class="col-lg-3">
                  <label for="nama" class="form-label">Nama</label>
              </div>
              <div class="col-lg-9">
                  <input type="text" class="form-control" name="ttenama" id="ttenama" placeholder="Enter Nama" value="<?= $user->tte_nama ?>">
              </div>
          </div>
          <div class="row mb-3">
              <div class="col-lg-3">
                  <label for="jabatan" class="form-label">Jabatan</label>
              </div>
              <div class="col-lg-9">
                  <input type="text" class="form-control" name="ttejabatan" id="ttejabatan" placeholder="Enter Jabatan" value="<?= $user->tte_jabatan ?>">
              </div>
          </div>
          <div class="row mb-3">
              <div class="col-lg-3">
                  <label for="jabatan" class="form-label">Kop Surat</label>
              </div>
              <div class="col-lg-9">
                  <input type="file" class="filepond filepond-input" name="filepond" data-allow-reorder="true" data-max-file-size="3MB">
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
<?= $this->section('script') ?>
<script src="<?= base_url()?>assets/libs/filepond/filepond.min.js"></script>
<script src="<?= base_url()?>assets/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.js"></script>

<script>
    FilePond.registerPlugin(
        FilePondPluginImagePreview,
        FilePondPluginImageExifOrientation,
        FilePondPluginFileValidateSize,
        FilePondPluginImageEdit
    );

    FilePond.create(
        document.querySelector(".filepond-input"),
        {
            labelIdle: `Drag & Drop your picture or <span class="filepond--label-action">Browse</span>`,
            imageCropAspectRatio: '1:1',
            stylePanelLayout: 'compact circle',
            styleLoadIndicatorPosition: 'center bottom',
            styleProgressIndicatorPosition: 'right bottom',
            styleButtonRemoveItemPosition: 'left bottom',
            styleButtonProcessItemPosition: 'right bottom',
        }
    );
</script>
<?= $this->endSection() ?>
