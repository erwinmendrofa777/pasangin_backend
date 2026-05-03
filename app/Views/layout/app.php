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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    crossorigin="anonymous">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
    integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

  <!-- CSS Libraries -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Ladda/1.0.6/ladda-themeless.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
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
        <form class="form-inline me-auto">
          <ul class="navbar-nav me-3">
            <li><a href="#" data-bs-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
          </ul>
        </form>
        <ul class="navbar-nav navbar-right">
          <li class="dropdown"><a href="#" data-bs-toggle="dropdown"
              class="nav-link dropdown-toggle nav-link-lg nav-link-user">
              <?php
              $sessionPhoto = session()->get('photo') ?? null;
              $avatarUrl = $sessionPhoto
                ? (strpos($sessionPhoto, 'http') === 0 ? $sessionPhoto : base_url('uploads/profile/' . $sessionPhoto))
                : base_url('assets/img/avatar/avatar-1.png');
              ?>
              <img alt="image" src="<?= $avatarUrl ?>" class="rounded-circle me-1">
              <div class="d-sm-none d-lg-inline-block">Hi, <?= esc(session()->get('full_name') ?? 'Admin') ?></div>
            </a>
            <div class="dropdown-menu dropdown-menu-end">
              <div class="dropdown-title">
                <span
                  class="badge bg-primary text-white"><?= esc(ucwords(str_replace('_', ' ', session()->get('role') ?? 'admin'))) ?></span>
              </div>
              <div class="dropdown-divider"></div>
              <a href="<?= site_url('admin/logout') ?>" class="dropdown-item has-icon text-danger">
                <i class="fas fa-sign-out-alt"></i> Logout
              </a>
            </div>
          </li>
        </ul>
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

          <?php $uri = service('uri'); ?>
          <?php $seg2 = ($uri->getTotalSegments() >= 2) ? $uri->getSegment(2) : ''; ?>

          <ul class="sidebar-menu">

            <!-- ============ DASHBOARD ============ -->
            <?php if (can('dashboard')): ?>
              <li class="menu-header">DASHBOARD</li>
              <li class="<?= ($seg2 == 'dashboard' || $seg2 == '') ? 'active' : '' ?>">
                <a class="nav-link" href="<?= site_url('admin/dashboard') ?>"><i class="fas fa-fire"></i>
                  <span>Dashboard</span></a>
              </li>
            <?php endif; ?>

            <!-- ============ MANAJEMEN ============ -->
            <?php if (canAny(['chat', 'users', 'suppliers', 'products', 'orders', 'wallet', 'tukang'])): ?>
              <li class="menu-header">MANAJEMEN</li>

              <?php if (can('chat')): ?>
                <li class="<?= ($seg2 == 'chat') ? 'active' : '' ?>">
                  <a class="nav-link" href="<?= site_url('admin/chat') ?>"><i class="fas fa-comments"></i> <span>Pesan
                      Masuk</span></a>
                </li>
              <?php endif; ?>

              <?php if (can('users')): ?>
                <li class="<?= ($seg2 == 'users') ? 'active' : '' ?>">
                  <a class="nav-link" href="<?= site_url('admin/users') ?>"><i class="fas fa-users"></i>
                    <span>Users</span></a>
                </li>
              <?php endif; ?>

              <?php if (can('suppliers')): ?>
                <li class="<?= ($seg2 == 'suppliers') ? 'active' : '' ?>">
                  <a class="nav-link" href="<?= site_url('admin/suppliers') ?>"><i class="fas fa-store"></i>
                    <span>Suppliers</span></a>
                </li>
              <?php endif; ?>

              <?php if (can('products')): ?>
                <li class="<?= ($seg2 == 'products') ? 'active' : '' ?>">
                  <a class="nav-link" href="<?= site_url('admin/products') ?>"><i class="fas fa-box"></i>
                    <span>Produk</span></a>
                </li>
              <?php endif; ?>

              <?php if (can('orders')): ?>
                <li class="<?= ($seg2 == 'orders') ? 'active' : '' ?>">
                  <a class="nav-link" href="<?= site_url('admin/orders') ?>"><i class="fas fa-shopping-cart"></i>
                    <span>Pesanan</span></a>
                </li>
              <?php endif; ?>

              <?php if (can('wallet')): ?>
                <li class="<?= ($seg2 == 'wallet') ? 'active' : '' ?>">
                  <a class="nav-link" href="<?= site_url('admin/wallet') ?>"><i class="fas fa-wallet"></i> <span>Wallet
                      (Saldo)</span></a>
                </li>
              <?php endif; ?>

              <?php if (can('tukang')): ?>
                <li class="<?= ($seg2 == 'tukang') ? 'active' : '' ?>">
                  <a class="nav-link" href="<?= site_url('admin/tukang/index') ?>"><i class="fas fa-hard-hat"></i>
                    <span>Tukang</span></a>
                </li>
              <?php endif; ?>
            <?php endif; ?>

            <!-- ============ PROYEK ============ -->
            <?php if (canAny(['design', 'construction', 'renovation'])): ?>
              <li class="menu-header">PROYEK</li>

              <?php if (can('design')): ?>
                <li class="<?= ($seg2 == 'design') ? 'active' : '' ?>">
                  <a class="nav-link" href="<?= site_url('admin/design') ?>"><i class="fas fa-paint-brush"></i>
                    <span>Desain</span></a>
                </li>
              <?php endif; ?>

              <?php if (can('construction')): ?>
                <li class="<?= ($seg2 == 'construction') ? 'active' : '' ?>">
                  <a class="nav-link" href="<?= site_url('admin/construction') ?>"><i class="fas fa-building"></i>
                    <span>Konstruksi</span></a>
                </li>
              <?php endif; ?>

              <?php if (can('renovation')): ?>
                <li class="<?= ($seg2 == 'renovation') ? 'active' : '' ?>">
                  <a class="nav-link" href="<?= site_url('admin/renovation') ?>"><i class="fas fa-paint-roller"></i>
                    <span>Renovasi</span></a>
                </li>
              <?php endif; ?>
            <?php endif; ?>

            <!-- ============ KONTEN ============ -->
            <?php if (canAny(['banner', 'vouchers', 'tips', 'promo', 'notification', 'price-estimate', 'syarat_ketentuan'])): ?>
              <li class="menu-header">KONTEN</li>

              <?php if (can('banner')): ?>
                <li class="<?= ($seg2 == 'banner') ? 'active' : '' ?>">
                  <a class="nav-link" href="<?= site_url('admin/banner') ?>"><i class="fas fa-image"></i>
                    <span>Banner</span></a>
                </li>
              <?php endif; ?>

              <?php if (can('banner_supplier')): ?>
                <li class="<?= ($seg2 == 'banner-supplier') ? 'active' : '' ?>">
                  <a class="nav-link" href="<?= site_url('admin/banner-supplier') ?>"><i class="fas fa-store"></i>
                    <span>Banner Supplier</span></a>
                </li>
              <?php endif; ?>

              <?php if (can('vouchers')): ?>
                <li class="<?= ($seg2 == 'vouchers') ? 'active' : '' ?>">
                  <a class="nav-link" href="<?= site_url('admin/vouchers') ?>"><i class="fas fa-ticket-alt"></i>
                    <span>Voucher</span></a>
                </li>
              <?php endif; ?>

              <?php if (can('tips')): ?>
                <li class="<?= ($seg2 == 'tips') ? 'active' : '' ?>">
                  <a class="nav-link" href="<?= site_url('admin/tips') ?>"><i class="fas fa-lightbulb"></i>
                    <span>Tips</span></a>
                </li>
              <?php endif; ?>

              <?php if (can('promo')): ?>
                <li class="<?= ($seg2 == 'promo') ? 'active' : '' ?>">
                  <a class="nav-link" href="<?= site_url('admin/promo') ?>"><i class="fas fa-percentage"></i>
                    <span>Promosi</span></a>
                </li>
              <?php endif; ?>

              <?php if (can('notification')): ?>
                <li class="<?= ($seg2 == 'notification') ? 'active' : '' ?>">
                  <a class="nav-link" href="<?= site_url('admin/notification') ?>"><i class="fas fa-bell"></i>
                    <span>Notifikasi</span></a>
                </li>
              <?php endif; ?>

              <?php if (can('price-estimate')): ?>
                <li class="<?= ($seg2 == 'price-estimate') ? 'active' : '' ?>">
                  <a class="nav-link" href="<?= site_url('admin/price-estimate') ?>"><i class="fas fa-calculator"></i>
                    <span>Estimasi Harga</span></a>
                </li>
              <?php endif; ?>

              <?php if (can('syarat_ketentuan')): ?>
                <li class="<?= ($seg2 == 'syarat_ketentuan') ? 'active' : '' ?>">
                  <a class="nav-link" href="<?= site_url('admin/syarat_ketentuan') ?>"><i class="fas fa-file-contract"></i>
                    <span>Syarat & Ketentuan</span></a>
                </li>
              <?php endif; ?>
            <?php endif; ?>

            <!-- ============ AKSES ============ -->
            <?php if (canAny(['roles', 'admin'])): ?>
              <li class="menu-header">AKSES</li>

              <?php if (can('admin')): ?>
                <li class="<?= ($seg2 == 'admin') ? 'active' : '' ?>">
                  <a class="nav-link" href="<?= site_url('admin/admin') ?>"><i class="fas fa-user-tie"></i> <span>Kelola
                      Admin</span></a>
                </li>
              <?php endif; ?>

              <?php if (can('roles')): ?>
                <li class="<?= ($seg2 == 'roles') ? 'active' : '' ?>">
                  <a class="nav-link" href="<?= site_url('admin/roles') ?>"><i class="fas fa-user-shield"></i> <span>Kelola
                      Role</span></a>
                </li>
              <?php endif; ?>
            <?php endif; ?>

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
          Copyright &copy; <?= date('Y') ?>
        </div>
      </footer>
    </div>
  </div>

  <!-- General JS Scripts -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Ladda/1.0.6/spin.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Ladda/1.0.6/ladda.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- JS Libraries -->
  <?= $this->renderSection('script') ?>

  <!-- Template JS File -->
  <script src="<?= base_url('assets/js/stisla.js') ?>"></script>
  <script src="<?= base_url('assets/js/scripts.js') ?>"></script>
  <script src="<?= base_url('assets/js/custom.js') ?>"></script>
</body>

</html>