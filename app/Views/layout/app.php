<?php
// Ini adalah file layout utama (template master) untuk Stisla.
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  
  <title><?= $this->renderSection('title') ?> &mdash; Pasangin.co.id</title>

  <!-- General CSS Files -->
  <link   href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <!-- css component -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?= base_url('assets/modules/izitoast/css/iziToast.min.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Ladda/1.0.6/ladda-themeless.min.css">

  <link rel="stylesheet" href="assets/modules/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/modules/fontawesome/css/all.min.css">

  <!-- CSS Libraries -->
  <?= $this->renderSection('style') ?>

  <!-- Template CSS -->
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/components.css') ?>">
</head>

<body>
  <div id="app">
    <div class="main-wrapper">
      
      <!-- Navbar (Bagian Atas) -->
      <div class="navbar-bg"></div>
      <nav class="navbar navbar-expand-lg main-navbar">
        <div class="container-fluid">
          <form class="form-inline mr-auto">
            <ul class="navbar-nav mr-3">
              <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
            </ul>
          </form>
          <ul class="navbar-nav navbar-right d-flex">
            <li class="nav-item dropdown me-4">
              <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user me-1" >
                <img alt="image" src="<?= base_url('assets/img/avatar/avatar-1.png') ?>" class="rounded-circle mr-1">
                <div class="d-sm-none d-lg-inline-block">Hi, Super Admin</div></a>
                <div class="dropdown-menu dropdown-menu-right">
                  <a href="<?= site_url('admin/logout') ?>" class="dropdown-item has-icon text-danger">
                    <i class="fas fa-sign-out-alt"></i> Logout
                  </a>
                </div>
              </li>
            </li>
          </ul>
        </div>
      </nav>

      <!-- Sidebar (Menu Samping) -->
      <div class="main-sidebar">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="<?= site_url('admin/dashboard') ?>">PASANGIN</a>
          </div>
          <div class="sidebar-brand sidebar-brand-sm">
            <a href="<?= site_url('admin/dashboard') ?>">PSGN</a>
          </div>
          
          <ul class="sidebar-menu ">
            <li class="menu-header">DASHBOARD</li>
            <?php $uri = service('uri'); ?>
            <li class="<?= ($uri->getTotalSegments() >= 2 && $uri->getSegment(2) == 'dashboard') ? 'active' : '' ?>">
                <a class="nav-link" href="<?= site_url('admin/dashboard') ?>"><i class="fas fa-fire"></i> <span>Dashboard</span></a>
            </li>

            <li class="menu-header">MANAJEMEN</li>

            <li class="<?= ($uri->getTotalSegments() >= 2 && $uri->getSegment(2) == 'chat') ? 'active' : '' ?>">
                <a class="nav-link" href="<?= site_url('admin/chat') ?>"><i class="fas fa-comments"></i> <span>Pesan Masuk</span></a>
            </li>

            <li class="<?= ($uri->getTotalSegments() >= 2 && $uri->getSegment(2) == 'users') ? 'active' : '' ?>">
                <a class="nav-link" href="<?= site_url('admin/users') ?>"><i class="fas fa-users"></i> <span>Users</span></a>
            </li>

            <li class="<?= ($uri->getTotalSegments() >= 2 && $uri->getSegment(2) == 'suppliers') ? 'active' : '' ?>">
                <a class="nav-link ms-1 " href="<?= site_url('admin/suppliers') ?>"><i class="fa-solid fa-warehouse"></i> <span>Suppliers</span></a>
            </li>

            <li class="<?= ($uri->getTotalSegments() >= 2 && $uri->getSegment(2) == 'products') ? 'active' : '' ?>">
                <a class="nav-link" href="<?= site_url('admin/products') ?>"><i class="fas fa-box"></i> <span>Produk</span></a>
            </li>

            <!-- MENU PESANAN TERINTREGASI -->
            <li class="<?= ($uri->getTotalSegments() >= 2 && $uri->getSegment(2) == 'orders') ? 'active' : '' ?>">
                <a class="nav-link" href="<?= site_url('admin/orders') ?>"><i class="fas fa-shopping-cart"></i> <span>Pesanan</span></a>
            </li>

            <li class="<?= ($uri->getTotalSegments() >= 2 && $uri->getSegment(2) == 'design') ? 'active' : '' ?>">
                <a class="nav-link" href="<?= site_url('admin/design') ?>"><i class="fas fa-paint-brush"></i> <span>Desain</span></a>
            </li>

            <li class="<?= ($uri->getTotalSegments() >= 2 && $uri->getSegment(2) == 'construction') ? 'active' : '' ?>">
                <a class="nav-link" href="<?= site_url('admin/construction') ?>"><i class="fas fa-hard-hat"></i> <span>Konstruksi</span></a>
            </li>

            <li class="<?= ($uri->getTotalSegments() >= 2 && $uri->getSegment(2) == 'renovation') ? 'active' : '' ?>">
                <a class="nav-link" href="<?= site_url('admin/renovation') ?>"><i class="fas fa-paint-roller"></i> <span>Renovasi</span></a>
            </li>

            <li class="<?= ($uri->getTotalSegments() >= 2 && $uri->getSegment(2) == 'wallet') ? 'active' : '' ?>">
                <a class="nav-link" href="<?= site_url('admin/wallet') ?>"><i class="fas fa-wallet"></i> <span>Wallet (Saldo)</span></a>
            </li>

            <li class="menu-header">KONTEN</li>
            
            <li class="<?= ($uri->getTotalSegments() >= 2 && $uri->getSegment(2) == 'banner') ? 'active' : '' ?>">
                <a class="nav-link" href="<?= site_url('admin/banner') ?>"><i class="fas fa-image"></i> <span>Banner</span></a>
            </li>

            <li class="<?= ($uri->getTotalSegments() >= 2 && $uri->getSegment(2) == 'vouchers') ? 'active' : '' ?>">
                <a class="nav-link" href="<?= site_url('admin/vouchers') ?>"><i class="fas fa-ticket-alt"></i> <span>Voucher</span></a>
            </li>

            <li class="<?= ($uri->getTotalSegments() >= 2 && $uri->getSegment(2) == 'tips') ? 'active' : '' ?>">
                <a class="nav-link" href="<?= site_url('admin/tips') ?>"><i class="fas fa-lightbulb"></i> <span>Tips</span></a>
            </li>
            
            <li class="<?= ($uri->getTotalSegments() >= 2 && $uri->getSegment(2) == 'tukang') ? 'active' : '' ?>">
                <a class="nav-link" href="<?= site_url('admin/tukang') ?>"><i class="fas fa-user-cog"></i> <span>Tukang</span></a>
            </li>
            <li class="<?= (service('uri')->getSegment(2) == 'notification') ? 'active' : '' ?>">
                <a class="nav-link" href="<?= base_url('admin/notification') ?>"><i class="fas fa-bell"></i> <span>Notifikasi</span></a>
            </li>
            <li class="<?= (service('uri')->getSegment(2) == 'promo') ? 'active' : '' ?>">
                <a class="nav-link ms-1" href="<?= base_url('admin/promo') ?>"><i class="fa-solid fa-gift"></i><span>Promosi</span></a>
            </li>
            <li class="<?= (service('uri')->getSegment(2) == 'price-estimate') ? 'active' : '' ?>">
                <a class="nav-link ms-1" href="<?= base_url('admin/price-estimate') ?>"><i class="fa-solid fa-money-bill-1-wave"></i><span>Estimasi Harga</span></a>
            </li>
            <li class="<?= (service('uri')->getSegment(2) == 'syarat_ketentuan') ? 'active' : '' ?>">
                <a class="nav-link mb-3 ms-1" href="<?= base_url('admin/syarat_ketentuan') ?>"><i class="fa-solid fa-file-contract"></i><span>Syarat & Ketentuan</span></a>
            </li>
          </ul>
        </aside>
      </div>

      <!-- Main Content -->
      <div class="main-content">
        <?= $this->renderSection('content') ?>
      </div>

      <!-- Footer -->
      <footer class="main-footer">
        <div class="footer-left">
          Copyright &copy; <?= date('Y') ?> <div class="bullet"></div> Pasangin.co.id
        </div>
      </footer>
    </div>
  </div>

  <!-- General JS Scripts -->
  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  
  <!-- bootstrap + poppin -->
  <!-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script> -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
  <script src="<?= base_url('assets/js/stisla.js') ?>"></script>  

  <!-- JS Libraries -->
  <script src="<?= base_url('assets/modules/izitoast/js/iziToast.min.js') ?>"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/spin.js/2.3.2/spin.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Ladda/1.0.6/ladda.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  
  <?= $this->renderSection('script') ?>

  <!-- Page Specific JS File -->
  <script src="<?= base_url('assets/js/page/modules-toastr.js') ?>"></script>

  <!-- Template JS File -->
  <script src="<?= base_url('assets/js/scripts.js') ?>"></script>
  <script src="<?= base_url('assets/js/custom.js') ?>"></script>
</body>
</html>