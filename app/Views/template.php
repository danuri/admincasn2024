<!doctype html>
<html lang="en" data-layout="vertical" data-layout-style="detached" data-sidebar="dark" data-topbar="light" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>

    <meta charset="utf-8" />
    <title>Admin CASN 2024 - Kementerian Agama RI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Admin CASN Kementerian Agama RI TA 2024" name="description" />
    <meta content="Danunih" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="<?= base_url()?>assets/images/favicon.ico">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/css/dataTables.checkboxes.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Layout config Js -->
    <script src="<?= base_url()?>assets/js/layout.js?v=2"></script>
    <!-- Bootstrap Css -->
    <link href="<?= base_url()?>assets/css/bootstrap.min.css?v=2" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="<?= base_url()?>assets/css/icons.min.css?v=2" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="<?= base_url()?>assets/css/app.min.css?v=2" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="<?= base_url()?>assets/css/custom.min.css?v=2" rel="stylesheet" type="text/css" />

    <?= $this->renderSection('style') ?>

</head>

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">

        <header id="page-topbar">
    <div class="layout-width">
        <div class="navbar-header">
            <div class="d-flex">
                <!-- LOGO -->
                <div class="navbar-brand-box horizontal-logo">
                    <a href="index.html" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="<?= base_url()?>assets/images/logo-sm.png" alt="" height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="https://d2mj1s7x3czrue.cloudfront.net/hrms/assets/images/logo-hrms.png" alt="" height="40"> Admin CASN 2024
                        </span>
                    </a>

                    <a href="index.html" class="logo logo-light">
                        <span class="logo-sm">
                            <img src="<?= base_url()?>assets/images/logo-sm.png" alt="" height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="https://d2mj1s7x3czrue.cloudfront.net/hrms/assets/images/logo-hrms.png" alt="" height="22">
                        </span>
                    </a>
                </div>

                <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger" id="topnav-hamburger-icon">
                    <span class="hamburger-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>
            </div>

            <div class="d-flex align-items-center">

                <div class="ms-1 header-item d-none d-sm-flex">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" data-toggle="fullscreen">
                        <i class='bx bx-fullscreen fs-22'></i>
                    </button>
                </div>

                <div class="ms-1 header-item d-none d-sm-flex">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle light-dark-mode">
                        <i class='bx bx-moon fs-22'></i>
                    </button>
                </div>

                <div class="dropdown ms-sm-3 header-item topbar-user">
                    <button type="button" class="btn" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="d-flex align-items-center">
                            <img class="rounded-circle header-profile-user" src="<?= base_url()?>assets/images/users/avatar-1.jpg" alt="Header Avatar">
                            <span class="text-start ms-xl-2">
                                <span class="d-none d-xl-inline-block ms-1 fw-semibold user-name-text"><?= session('nama')?></span>
                                <span class="d-none d-xl-block ms-1 fs-13 text-muted user-name-sub-text"><?= session('satker4')?> (<?= session('lokasi')?>)</span>
                            </span>
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <!-- item-->
                        <h6 class="dropdown-header"><?= session('nama')?><</h6>
                        <a class="dropdown-item" href="<?= site_url('auth/logout')?>"><i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span class="align-middle" data-key="t-logout">Logout</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- removeNotificationModal -->
<div id="removeNotificationModal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="NotificationModalbtn-close"></button>
            </div>
            <div class="modal-body">
                <div class="mt-2 text-center">
                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>
                    <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                        <h4>Are you sure ?</h4>
                        <p class="text-muted mx-4 mb-0">Are you sure you want to remove this Notification ?</p>
                    </div>
                </div>
                <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                    <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn w-sm btn-danger" id="delete-notification">Yes, Delete It!</button>
                </div>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
        <!-- ========== App Menu ========== -->
        <div class="app-menu navbar-menu">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <!-- Dark Logo-->
                <a href="index.html" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="<?= base_url()?>assets/images/logo-sm.png" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="<?= base_url()?>assets/images/logo-dark.png" alt="" height="17">
                    </span>
                </a>
                <!-- Light Logo-->
                <a href="index.html" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="<?= base_url()?>assets/images/logo-sm.png" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="<?= base_url()?>assets/images/logo-light.png" alt="" height="17">
                    </span>
                </a>
                <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
                    <i class="ri-record-circle-line"></i>
                </button>
            </div>

            <div id="scrollbar">
                <div class="container-fluid">

                    <div id="two-column-menu">
                    </div>
                    <ul class="navbar-nav" id="navbar-nav">
                        <li class="menu-title"><span data-key="t-menu">Menu</span></li>

                        <li class="nav-item">
                            <a class="nav-link menu-link" href="<?= site_url()?>">
                                <i class="ri-dashboard-2-line"></i> <span data-key="t-widgets">Dashboard</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link" href="<?= site_url('pelamar')?>">
                                <i class="ri-todo-line"></i> <span data-key="t-widgets">Pelamar</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link" href="<?= site_url('pelamar/pppkteknis')?>">
                                <i class="ri-todo-line"></i> <span data-key="t-widgets">Pelamar PPPK Teknis</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link" href="#sidebarSkb" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarTables">
                                <i class="ri-layout-grid-line"></i> <span data-key="t-tables">SKB</span>
                            </a>
                            <div class="collapse menu-dropdown" id="sidebarSkb">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a href="<?= site_url('skb/lokasi')?>" class="nav-link" data-key="t-basic-tables">Lokasi Ujian</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?= site_url('skb/peserta')?>" class="nav-link" data-key="t-grid-js">Peserta</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?= site_url('skb/penguji')?>" class="nav-link" data-key="t-list-js">Penguji</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?= site_url('skb/jadwal')?>" class="nav-link" data-key="t-datatables">Jadwal Daring</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?= site_url('skb/info')?>" class="nav-link" data-key="t-datatables">Info Satker</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
                <!-- Sidebar -->
            </div>

            <div class="sidebar-background"></div>
        </div>
        <!-- Left Sidebar End -->
        <!-- Vertical Overlay-->
        <div class="vertical-overlay"></div>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
          <?= $this->renderSection('content') ?>

            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            2023 © Admin CASN.
                        </div>
                        <div class="col-sm-6">
                            <div class="text-sm-end d-none d-sm-block">
                                Develop by Biro Kepegawaian
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->



    <!--start back-to-top-->
    <button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
        <i class="ri-arrow-up-line"></i>
    </button>
    <!--end back-to-top-->

    <!--preloader-->
    <div id="preloader">
        <div id="status">
            <div class="spinner-border text-primary avatar-sm" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>


    <!-- JAVASCRIPT -->
    <script src="<?= base_url()?>assets/libs/bootstrap/js/bootstrap.bundle.min.js?v=2"></script>
    <script src="<?= base_url()?>assets/libs/simplebar/simplebar.min.js?v=2"></script>
    <script src="<?= base_url()?>assets/libs/node-waves/waves.min.js?v=2"></script>
    <script src="<?= base_url()?>assets/libs/feather-icons/feather.min.js?v=2"></script>
    <script src="<?= base_url()?>assets/js/pages/plugins/lord-icon-2.1.0.js?v=2"></script>
    <!-- <script src="<?= base_url()?>assets/js/plugins.js?v=2"></script> -->

    <!-- App js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.6.2/js/dataTables.select.min.js"></script>
    <script type="text/javascript" src="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

    <script src="<?= base_url()?>assets/js/app.js?v=2"></script>
    <script type="text/javascript">
        $('.datatable').DataTable({
                      dom:"Bfrtip",
                      buttons:["copy","excel","print","pageLength"],
                      lengthMenu: [
                        [10, 25, 50, 100, -1],
                        [10, 25, 50, 100, 'Semua'],
                      ],
        });

      function alert($text) {
        Toastify({
          text: $text,
          duration: 5000,
          newWindow: true,
          close: true,
          gravity: "top", // `top` or `bottom`
          position: "center", // `left`, `center` or `right`
          stopOnFocus: true, // Prevents dismissing of toast on hover
          style: {
            background: "linear-gradient(to right, #00b09b, #96c93d)",
          },
          onClick: function(){} // Callback after click
        }).showToast();
      }

      <?php
      if(session()->getFlashdata('message')){
        ?>
        alert("<?= session()->getFlashdata('message')?>");
        <?php
      }
      ?>
    </script>
    <?= $this->renderSection('script') ?>
</body>

</html>
