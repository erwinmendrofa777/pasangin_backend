<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title><?= $this->renderSection('title') ?> &mdash; Dasbor Supplier</title>

  <!-- === CSS WAJIB STISLA (via CDN) === -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

  <!-- === CSS Template STISLA (via CDN) === -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/stisla@2.3.0/assets/css/style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/stisla@2.3.0/assets/css/components.css">

  <!-- Kamu bisa menambahkan CSS spesifik halaman di sini -->
  <?= $this->renderSection('css') ?>

</head>

<body>
  <div id="app">
    <div class="main-wrapper">
      <div class="navbar-bg"></div>
      
      <!-- ================== TOPBAR / NAVBAR ================== -->
      <nav class="navbar navbar-expand-lg main-navbar">
        <form class="form-inline mr-auto">
          <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
          </ul>
        </form>
        <ul class="navbar-nav navbar-right">
          <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
            <img alt="image" src="https://cdn.jsdelivr.net/npm/stisla@2.3.0/assets/img/avatar/avatar-1.png" class="rounded-circle mr-1">
            <div class="d-sm-none d-lg-inline-block">Hi, <?= esc(session()->get('supplier_name')) ?></div></a>
            <div class="dropdown-menu dropdown-menu-right">
              <div class="dropdown-title">Logged in 5 min ago</div>
              <a href="#" class="dropdown-item has-icon">
                <i class="far fa-user"></i> Profil
              </a>
              <a href="#" class="dropdown-item has-icon">
                <i class="fas fa-bolt"></i> Aktivitas
              </a>
              <div class="dropdown-divider"></div>
              <a href="<?= site_url('supplier/logout') ?>" class="dropdown-item has-icon text-danger">
                <i class="fas fa-sign-out-alt"></i> Logout
              </a>
            </div>
          </li>
        </ul>
      </nav>

      <!-- ================== SIDEBAR / MENU SAMPING ================== -->
      <div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="<?= site_url('supplier/dashboard') ?>">Pasangin.co.id</a>
          </div>
          <div class="sidebar-brand sidebar-brand-sm">
            <a href="<?= site_url('supplier/dashboard') ?>">P</a>
          </div>
          <ul class="sidebar-menu">
            
            <?php
            // Mengambil segmen URL setelah '.../index.php/'.
            // Contoh: dari URL '.../supplier/produk', segmen ke-2 adalah 'produk'.
            $uri = service('uri');
            $current_segment = $uri->getSegment(2); // Ambil segmen ke-2 (supplier adalah ke-1)
            ?>

            <li class="menu-header">Dasbor</li>
            <li class="<?= ($current_segment == 'dashboard') ? 'active' : '' ?>">
                <a class="nav-link" href="<?= site_url('supplier/dashboard') ?>">
                    <i class="fas fa-fire"></i><span>Dasbor</span>
                </a>
            </li>

            <li class="menu-header">Manajemen</li>
            <li class="<?= ($current_segment == 'produk') ? 'active' : '' ?>">
                <a class="nav-link" href="<?= site_url('supplier/produk') ?>">
                    <i class="fas fa-box-open"></i> <span>Produk Saya</span>
                </a>
            </li>
            <li class="<?= ($current_segment == 'pesanan') ? 'active' : '' ?>">
                <a class="nav-link" href="#">
                    <i class="fas fa-shopping-cart"></i> <span>Pesanan Masuk</span>
                </a>
            </li>
            
            <li class="menu-header">Lainnya</li>
            <li class="<?= ($current_segment == 'profil') ? 'active' : '' ?>">
                <a class="nav-link" href="#">
                    <i class="fas fa-user-circle"></i> <span>Profil Toko</span>
                </a>
            </li>
          </ul>

            <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
            <a href="<?= site_url('supplier/produk/new') ?>" class="btn btn-primary btn-lg btn-block btn-icon-split">
            <i class="fas fa-plus"></i> Tambah Produk
              </a>
            </div>
        </aside>
      </div>

      <!-- ================== KONTEN UTAMA ================== -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1><?= $this->renderSection('page_title') ?></h1>
          </div>

          <div class="section-body">
            <!-- Di sinilah konten dari setiap halaman akan dimuat -->
            <?= $this->renderSection('content') ?>
          </div>
        </section>
      </div>
      
      <!-- ================== FOOTER ================== -->
      <footer class="main-footer">
        <div class="footer-left">
          Copyright &copy; <?= date('Y') ?> <div class="bullet"></div> Design By <a href="https://nauval.in/">Muhamad Nauval Azhar</a>
        </div>
        <div class="footer-right">
          2.3.0
        </div>
      </footer>
    </div>
  </div>

  <!-- === JS WAJIB STISLA (via CDN) === -->
  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
  
  <!-- === JS Template STISLA (via CDN) === -->
  <script src="https://cdn.jsdelivr.net/npm/stisla@2.3.0/assets/js/stisla.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/stisla@2.3.0/assets/js/scripts.js"></script>

  <!-- Kamu bisa menambahkan JS spesifik halaman di sini -->
  <?= $this->renderSection('js') ?>

</body>
</html>
