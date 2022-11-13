<?php 
if (!isset($highlight_menu)){
    $highlight_menu = null;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Koperasi Simpan Pinjam</title>

    <!-- Custom fonts for this template-->
    <link rel="icon" href="<?=base_url('assets/img/koperasi-logo.png') ?>" />
    <link href="<?=base_url('assets/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?=base_url('assets/css/sb-admin-2.min.css') ?>" rel="stylesheet">
    <link href="<?=base_url('assets/css/custom.css') ?>" rel="stylesheet">

    <!-- JQuery -->
    <script src="<?=base_url('assets/vendor/jquery/jquery.min.js') ?>"></script>

    <!-- Bootstrap core JavaScript-->
    <script src="<?=base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav my-bg-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <div class="sidebar-brand-text mx-3">Koperasi Simpnan Pinjam</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item <?= $highlight_menu === 'dashboard' ? 'active' : '' ?>">
                <a class="nav-link" href="<?= site_url('/') ?>">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <?php 
                if ($role == 2): 
            ?>

            <li class="nav-item <?= $highlight_menu === 'simpanan' ? 'active' : '' ?>">
                <a class="nav-link" href="<?= site_url('/simpanan') ?>">
                    <i class="fas fa-fw fa-hand-holding-usd"></i>
                    <span>Simpanan</span></a>
            </li>

            <li class="nav-item <?= $highlight_menu === 'pinjaman' ? 'active' : '' ?>">
                <a class="nav-link" href="<?= site_url('/pinjaman') ?>">
                    <i class="fas fa-fw fa-file-invoice-dollar"></i>
                    <span>Pinjaman</span></a>
            </li>

            <li class="nav-item <?= $highlight_menu === 'laporan' ? 'active' : '' ?>">
                <a class="nav-link" href="<?= site_url('/laporan') ?>">
                    <i class="fas fa-fw fa-file-contract"></i>
                    <span>Laporan</span></a>
            </li>

            <li class="nav-item <?= $highlight_menu === 'anggota_settings' ? 'active' : '' ?>">
                <a class="nav-link" href="<?= site_url('/anggota/settings') ?>">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Ubah data diri</span></a>
            </li>

            <?php endif; ?>

            <?php 
                if ($role == 1): 
                $simpanan = in_array($highlight_menu, ["simpanan_pokok","simpanan_wajib","simpanan_sukarela", "investasi","simpanan"]);
                $master = in_array($highlight_menu, ["depo","position","simpanan_settings"]);
            ?>
            <li class="nav-item <?= $highlight_menu === 'anggota' ? 'active' : '' ?>">
                <a class="nav-link" href="<?= site_url('/anggota') ?>">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Data Anggota</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= $simpanan ? 'text-white' : 'collapsed' ?>" href="#" data-toggle="collapse" data-target="#collapseSimpanan"
                    aria-expanded="true" aria-controls="collapseSimpanan">
                    <i class="fas fa-fw fa-hand-holding-usd  <?= $simpanan ? 'text-white' : '' ?>"></i>
                    <span>Simpanan Anggota</span>
                </a>
                <div id="collapseSimpanan" class="collapse <?= $simpanan ? 'show' : '' ?>" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="my-bg-primary py-2 collapse-inner rounded">
                        <a class="collapse-item my-link-primary <?= $highlight_menu === 'simpanan_pokok' ? 'active' : '' ?>" href="<?= site_url('/simpanan/page/pokok') ?>">Simpanan Pokok</a>
                        <a class="collapse-item my-link-primary <?= $highlight_menu === 'simpanan_wajib' ? 'active' : '' ?>" href="<?= site_url('/simpanan/page/wajib') ?>">Simpanan Wajib</a>
                        <a class="collapse-item my-link-primary <?= $highlight_menu === 'simpanan_sukarela' ? 'active' : '' ?>" href="<?= site_url('/simpanan/page/sukarela') ?>">Simpanan Sukarela</a>
                        <a class="collapse-item my-link-primary <?= $highlight_menu === 'investasi' ? 'active' : '' ?>" href="<?= site_url('/simpanan/page/investasi') ?>">Investasi</a>
                    </div>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link <?= $master ? 'text-white' : 'collapsed' ?>" href="#" data-toggle="collapse" data-target="#collapseMaster"
                    aria-expanded="true" aria-controls="collapseMaster">
                    <i class="fas fa-fw fa-wrench  <?= $master ? 'text-white' : '' ?>"></i>
                    <span>Master Data</span>
                </a>
                <div id="collapseMaster" class="collapse <?= $master ? 'show' : '' ?>" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="my-bg-primary py-2 collapse-inner rounded">
                        <a class="collapse-item my-link-primary <?= $highlight_menu === 'depo' ? 'active' : '' ?>" href="<?= site_url('/master/depo') ?>">Depo / Stock Point</a>
                        <a class="collapse-item my-link-primary <?= $highlight_menu === 'position' ? 'active' : '' ?>" href="<?= site_url('/master/jabatan') ?>">Jabatan</a>
                        <a class="collapse-item my-link-primary <?= $highlight_menu === 'simpanan_settings' ? 'active' : '' ?>" href="<?= site_url('/master/simpanan_settings') ?>">Pengaturan Simpanan</a>
                    </div>
                </div>
            </li>

            <?php endif; ?>

            <li class="nav-item <?= $highlight_menu === 'user_settings' ? 'active' : '' ?>">
                <a class="nav-link" href="<?= site_url('/user/settings') ?>">
                    <i class="fas fa-fw fa-cogs"></i>
                    <span>Pengaturan Akun</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">
            
            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <!-- Title -->
                    <div class="text-lg font-weight-bold"><?= !empty($title)? $title : '' ?></div>

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <?php if ($count_notification > 0): ?>
                                <span class="badge badge-danger badge-counter"><?= ($count_notification > 3)? '3+' : $count_notification ?></span>
                                <?php endif; ?>
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header my-bg-primary my-border-primary">
                                    Notifikasi
                                </h6>
                                <?php 
                                    if (count($notification)):
                                    foreach($notification as $key => $item):
                                        switch($item["status"]){
                                            case "Success":
                                                $icon = "check-circle";
                                                $type = "success";
                                            break;
                                            case "Pending":
                                                $icon = "clock";
                                                $type = "warning";
                                            break;
                                            case "Failed":
                                                $icon = "times-circle";
                                                $type = "danger";
                                            break;
                                        } 
                                ?>
                                <a href="<?= site_url('user/notifications_detail/').$item['id'] ?>" class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-<?= $type ?>">
                                            <i class="fas fa-<?= $icon ?> text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500"><?= $item["person_name"] ?> - <?= $item["time"] ?></div>
                                        <span class="font-weight-bold"><?= $item["message"] ?></span>
                                    </div>
                                </a>
                                <?php 
                                    endforeach; 
                                    else:
                                ?>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <strong>Tidak ada notifikasi</strong>
                                </a>
                                <?php 
                                    endif; 
                                ?>
                                <a class="dropdown-item text-center small text-gray-500" href="<?= site_url('user/notifications') ?>">Show All Alerts</a>
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $name ?></span>
                                <img class="img-profile rounded-circle"
                                    src="<?=base_url('files/').$profile_photo?>">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="<?= site_url('user/settings') ?>">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                <?php $this->load->view($content_view); ?>                    

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Koperasi &copy; NNF Production 2021</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="<?= site_url('user/logout') ?>">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Core plugin JavaScript-->
    <script src="<?=base_url('assets/vendor/jquery-easing/jquery.easing.min.js') ?>"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?=base_url('assets/js/sb-admin-2.min.js') ?>"></script>
    
    <script>
        $(function () {
          $('[data-toggle="tooltip"]').tooltip()
        });
    </script>
</body>

</html>