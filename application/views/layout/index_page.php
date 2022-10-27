<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Perpustakaan | <?= $title ?></title>
  <?php
  $petugas = $this->db->get_where('petugas', ['id_petugas' => $this->session->id_petugas])->row();
  $foto_profile = $petugas->foto_profile ? 'assets/img/' . $petugas->foto_profile : 'assets/dist/img/default-150x150.png';
  ?>
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/fontawesome-free/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="<?= base_url('assets') ?>/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url('assets') ?>/dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini" style="font-family: Arial;">
  <!-- Site wrapper -->
  <div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-orange navbar-dark" style="border-bottom: 1px solid #fd7e14;">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="javascript:void(0)" role="button"><i class="fas fa-bars"></i></a>
        </li>
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="javascript:void(0)">
            <i class="fas fa-user-alt"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
            <a href="<?= base_url('settings') ?>" class="dropdown-item">
              <i class="fas fa-cogs mr-2"></i> Settings
            </a>
            <div class="dropdown-divider"></div>
            <a href="<?= base_url('logout') ?>" class="dropdown-item">
              <i class="fas fa-sign-out-alt mr-2"></i> Logout
            </a>
          </div>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-white elevation-4">
      <!-- Brand Logo -->
      <a href="javascript:void(0)" class="brand-link">
        <img src="<?= base_url('assets') ?>/dist/img/p.png" alt="" class="brand-image img-circle" style="width: 33px; height: 33px;">
        <span class="brand-text" style="font-weight: bold; color: white;">PERPUSTAKAAN</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="<?= base_url($foto_profile) ?>" class="img-circle" alt="" style="width: 33.5px; height: 33.5px;">
          </div>
          <div class="info">
            <a href="javascript:void(0)" class="d-block"><?= substr($petugas->nama_petugas, 0, 20) ?></a>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
              <a href="<?= site_url('home') ?>" class="nav-link <?php if ($this->uri->segment(1) == 'home') echo 'active' ?>">
                <i class="nav-icon fas fa-home"></i>
                <p>Beranda</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= site_url('siswa') ?>" class="nav-link <?php if ($this->uri->segment(1) == 'siswa' || $this->uri->segment(2) == 'siswa') echo 'active' ?>">
                <i class="nav-icon fas fa-users"></i>
                <p>Siswa</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= site_url('buku') ?>" class="nav-link <?php if ($this->uri->segment(1) == 'buku') echo 'active' ?>">
                <i class="nav-icon fas fa-book"></i>
                <p>Buku</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= site_url('peminjaman') ?>" class="nav-link <?php if ($this->uri->segment(1) == 'peminjaman') echo 'active' ?>">
                <i class="nav-icon fas fa-exchange-alt"></i>
                <p>Peminjaman</p>
              </a>
            </li>
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="background-color: white;">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1><?= $title ?></h1>
            </div>
            <div class="col-sm-6"></div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <!-- jQuery -->
      <script src="<?= base_url('assets') ?>/plugins/jquery/jquery.min.js"></script>
      <!-- Bootstrap 4 -->
      <script src="<?= base_url('assets') ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
      <!-- DataTables  & Plugins -->
      <script src="<?= base_url('assets') ?>/plugins/datatables/jquery.dataTables.min.js"></script>
      <script src="<?= base_url('assets') ?>/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
      <script src="<?= base_url('assets') ?>/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
      <script src="<?= base_url('assets') ?>/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
      <!-- Select2 -->
      <script src="<?= base_url('assets') ?>/plugins/select2/js/select2.full.min.js"></script>
      <!-- AdminLTE App -->
      <script src="<?= base_url('assets') ?>/dist/js/adminlte.min.js"></script>
      <script type="text/javascript">
        $(function() {
          $('.select2').select2();
          setTimeout(function() {
            $('.alert').slideUp('slow');
          }, 1250);
        });
      </script>
      <?= $content ?>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer">
      <div class="float-right d-none d-sm-block">
        <b>Versi</b> 1.0
      </div>
      <strong>Copyright &copy; 2022- <?= date('Y') ?> <a href="javascript:void(0)" style="color: #869099;">Perpustakaan</a>.</strong>
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->

</body>

</html>