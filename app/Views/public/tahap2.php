
<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

    <head>

        <meta charset="utf-8" />
        <title>Monitoring Penetapan NI PPPK Tahap 2</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Themesbrand" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="<?= base_url()?>assets/images/favicon.ico">

        <!--Swiper slider css-->
        <link href="<?= base_url()?>assets/libs/swiper/swiper-bundle.min.css" rel="stylesheet" type="text/css" />

        <!-- Layout config Js -->
        <script src="<?= base_url()?>assets/js/layout.js"></script>
        <!-- Bootstrap Css -->
        <link href="<?= base_url()?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="<?= base_url()?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="<?= base_url()?>assets/css/app.min.css" rel="stylesheet" type="text/css" />
        <!-- custom Css-->
        <link href="<?= base_url()?>assets/css/custom.min.css" rel="stylesheet" type="text/css" />

    </head>

    <body data-bs-spy="scroll" data-bs-target="#navbar-example">

        <!-- Begin page -->
        <div class="layout-wrapper landing">

            <!-- start hero section -->
            

            <section class="" id="process">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="text-center mb-5">
                                <img src="<?= base_url()?>assets/images/hutri.png" width="300px" alt="">
                                <h1 class="mb-3 ff-secondary fw-semibold lh-base">Monitoring Progres Usul Penetapan NI PPPK Tahap 2</h1>
                                <p class="text-muted">Keadilan Sosial Bagi Seluruh Rakyat Indonesia.</p>
                                <div class="row pt-3">
                                <div class="col-4">
                                    <div class="text-center text-primary">
                                        <h4>16.013</h4>
                                        <p>Jumlah Kelulusan</p>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                        <!-- end col -->
                    </div>
                    <!--end row-->
                    <div class="row">
                        <div class="col-lg-12 col-md-6">
                            <div class="card shadow-lg">
                                <div class="card-body p-4">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Status Usul</th>
                                                <th>Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($monitoring as $row) {?>
                                            <tr>
                                                <td><?= $row->usul_status ?></td>
                                                <td><?= $row->jumlah ?></td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end container-->
            </section>

            <!-- Start footer -->
            <footer class="custom-footer bg-dark py-5 position-relative">
                <div class="container">
                    <div class="row text-center text-sm-start align-items-center mt-5">
                        <div class="col-sm-6">
                            <div>
                                <p class="copy-rights mb-0">
                                    <script> document.write(new Date().getFullYear()) </script> © Tim Pengadaan
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="text-sm-end mt-3 mt-sm-0">
                                <ul class="list-inline mb-0 footer-list gap-4 fs-13">
                                    <li class="list-inline-item">
                                        <a href="pages-privacy-policy.html">Privacy Policy</a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="pages-term-conditions.html">Terms & Conditions</a>
                                    </li>
                                    <li class="list-inline-item">
                                        <a href="pages-privacy-policy.html">Security</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- end footer -->

            <!--start back-to-top-->
            <button onclick="topFunction()" class="btn btn-info btn-icon landing-back-top" id="back-to-top">
                <i class="ri-arrow-up-line"></i>
            </button>
            <!--end back-to-top-->

        </div>
        <!-- end layout wrapper -->


        <!-- JAVASCRIPT -->
        <script src="<?= base_url()?>assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="<?= base_url()?>assets/libs/simplebar/simplebar.min.js"></script>
        <script src="<?= base_url()?>assets/libs/node-waves/waves.min.js"></script>
        <script src="<?= base_url()?>assets/libs/feather-icons/feather.min.js"></script>
        <script src="<?= base_url()?>assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
        <script src="<?= base_url()?>assets/js/plugins.js"></script>

        <!--Swiper slider js-->
        <script src="<?= base_url()?>assets/libs/swiper/swiper-bundle.min.js"></script>

        <!--job landing init -->
        <script src="<?= base_url()?>assets/js/pages/job-lading.init.js"></script>
    </body>

</html>