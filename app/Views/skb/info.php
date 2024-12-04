<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="page-content">
    <div class="container-fluid">
        <div class="row ">
            <div class="col-12  align-self-center">
                <div class="sub-header mt-3 py-3 align-self-center d-sm-flex w-100 rounded">
                    <div class="w-sm-100 mr-auto"><h4 class="mb-0">Info Satuan Kerja</h4></div>
                    <div class="breadcrumb bg-transparent align-self-center m-0 p-0">
                    <!-- <a href="<?= site_url('admin/peserta/export/cpns');?>" target="_blank" class="btn btn-info"><i class="icon-arrow-left-circle"></i> Download XLS</a> -->
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-lg-12 mt-3">
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
                <div class="card">
                    <div class="card-body">
                        <form class="" action="<?php echo site_url('skb/info/update');?>" method="post">
                            <div class="form-group row">
                                <label for="kontak" class="col-sm-4 col-form-label">Kontak Satuan Kerja</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="kontak" name="kontak" value="<?= $satker->kontak;?>" placeholder="Call Center Untuk Peserta">
                                </div>
                            </div>
                            <br>
                            <div class="form-group row">
                                <label for="informasi" class="col-sm-4 col-form-label">Informasi Untuk Peserta</label>
                                <div class="col-sm-8">
                                    <textarea class="ckeditor-classic" name="informasi" id="informasi" cols="5"><?= $satker->informasi;?></textarea>
                                </div>
                            </div>
                            <div class="form-group row mt-5">
                            <label for="informasi" class="col-sm-4 col-form-label"></label>
                                <div class="col-sm-8">
                                    <input type="submit" name="submit" value="Simpan" class="btn btn-primary">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('script') ?>
<!-- <link rel="stylesheet" href="<?= base_url();?>assets/vendors/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?= base_url();?>assets/vendors/jquery-ui/jquery-ui.min.css">
        <link rel="stylesheet" href="<?= base_url();?>assets/vendors/jquery-ui/jquery-ui.theme.min.css">
        <link rel="stylesheet" href="<?= base_url();?>assets/vendors/simple-line-icons/css/simple-line-icons.css">
        <link rel="stylesheet" href="<?= base_url();?>assets/vendors/flags-icon/css/flag-icon.min.css">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {
        $('#editor').summernote({
        height: 200
        });
    });
    </script> -->
    <!-- JAVASCRIPT -->
    <script src="<?= base_url();?>xassets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url();?>xassets/libs/simplebar/simplebar.min.js"></script>
    <script src="<?= base_url();?>xassets/libs/node-waves/waves.min.js"></script>
    <script src="<?= base_url();?>xassets/libs/feather-icons/feather.min.js"></script>
    <script src="<?= base_url();?>xassets/js/pages/plugins/lord-icon-2.1.0.js"></script>
    <!-- <script src="<?= base_url();?>assets/js/plugins.js"></script> -->

    <!-- ckeditor -->
    <script src="<?= base_url();?>xassets/libs/ckeditor/ckeditor5-build-classic/build/ckeditor.js"></script>

    <!-- quill js -->
    <script src="<?= base_url();?>xassets/libs/quill/quill.min.js"></script>

    <!-- init js -->
    <script src="<?= base_url();?>xassets/js/pages/form-editor.init.js"></script>

    <script src="<?= base_url();?>xassets/js/app.js"></script>
<?= $this->endSection() ?>