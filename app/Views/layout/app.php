<?php
// Ini adalah file layout utama (template master) untuk Stisla.
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">

  <title><?= $this->renderSection('title') ?> &mdash; Pasangin.co.id</title>

  <!-- Favicon -->
  <link rel="shortcut icon" href="<?= base_url('favicon.ico?v=1') ?>" type="image/x-icon">

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

  <!-- GLightbox CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css">
  <?= $this->renderSection('style') ?>

  <!-- Template CSS -->
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/components.css') ?>">

  <style>
    /* Overlay for mandatory notification */
    #notif-force-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(255, 255, 255, 0.95);
      z-index: 9999;
      display: none;
      align-items: center;
      justify-content: center;
      text-align: center;
      backdrop-filter: blur(8px);
    }

    .notif-box {
      background: #fff;
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
      max-width: 500px;
      width: 90%;
    }

    .notif-box i {
      font-size: 60px;
      color: #6777ef;
      margin-bottom: 20px;
    }

    .notif-box h2 {
      font-weight: 800;
      color: #2d3748;
      margin-bottom: 15px;
    }

    .notif-box p {
      color: #718096;
      margin-bottom: 30px;
      line-height: 1.6;
    }

    .btn-notif {
      background: #6777ef;
      color: #fff;
      padding: 12px 30px;
      border-radius: 10px;
      font-weight: 700;
      border: none;
      transition: 0.3s;
      cursor: pointer;
    }

    .btn-notif:hover {
      background: #394eea;
      transform: translateY(-2px);
    }

    .btn-notif:active {
      transform: translateY(0);
    }

    .denied-instruction {
      display: none;
      margin-top: 20px;
      padding: 15px;
      background: #fff5f5;
      border-radius: 10px;
      color: #c53030;
      font-size: 0.85rem;
      border: 1px solid #feb2b2;
    }

    /* Prevent double scrollbar conflicts on NiceScroll targets under Bootstrap 5 */
    .main-sidebar::-webkit-scrollbar,
    .dropdown-list-icons::-webkit-scrollbar,
    .dropdown-list-message::-webkit-scrollbar,
    .chat-content::-webkit-scrollbar,
    #top-5-scroll::-webkit-scrollbar {
      display: none !important;
      width: 0 !important;
      height: 0 !important;
    }

    .main-sidebar,
    .dropdown-list-icons,
    .dropdown-list-message,
    .chat-content,
    #top-5-scroll {
      -ms-overflow-style: none !important;
      /* IE/Edge */
      scrollbar-width: none !important;
      /* Firefox */
    }

    /* Enable scroll on mini sidebar */
    body.sidebar-mini .main-sidebar {
      position: fixed !important;
      height: 100vh !important;
      overflow-y: auto !important;
      scrollbar-width: thin !important;
      -ms-overflow-style: auto !important;
    }

    body.sidebar-mini .main-sidebar::-webkit-scrollbar {
      display: block !important;
      width: 4px !important;
      height: 0 !important;
    }

    body.sidebar-mini .main-sidebar::-webkit-scrollbar-thumb {
      background: rgba(0, 0, 0, 0.15) !important;
      border-radius: 4px !important;
    }
  </style>

</head>

<body>
  <div id="notif-force-overlay">
    <div class="notif-box">
      <i class="fas fa-bell"></i>
      <h2>Aktifkan Notifikasi</h2>
      <p>Untuk mengakses dashboard admin, Anda wajib mengaktifkan notifikasi agar tetap mendapatkan update real-time
        mengenai pesanan dan status proyek.</p>

      <button id="btn-allow-notif" class="btn-notif">
        <i class="fas fa-check-circle me-2"></i> Klik untuk Izinkan
      </button>

      <div id="denied-msg" class="denied-instruction">
        <strong>PENTING:</strong> Anda sebelumnya memblokir notifikasi. <br>
        Silakan klik ikon gembok di pojok kiri atas (URL Bar) dan ubah izin Notifikasi menjadi <strong>"Allow"</strong>,
        lalu refresh halaman ini.
      </div>
    </div>
  </div>

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
          <!-- Notification Dropdown -->
          <li class="dropdown dropdown-list-toggle">
            <a href="#" data-bs-toggle="dropdown" class="nav-link notification-toggle nav-link-lg" id="notif-bell">
              <i class="far fa-bell"></i>
              <span class="badge bg-danger" id="notif-badge" style="display: none;">0</span>
            </a>
            <div class="dropdown-menu dropdown-list dropdown-menu-end">
              <div class="dropdown-header">Notifikasi
              </div>
              <div class="dropdown-list-content dropdown-list-icons" id="notif-list">
                <!-- Data akan dimuat via AJAX -->
                <div class="p-3 text-center text-muted">Memuat notifikasi...</div>
              </div>
              <div class="dropdown-footer text-center">
                <a href="<?= base_url('admin/notification') ?>">Lihat Semua <i class="fas fa-chevron-right"></i></a>
              </div>
            </div>
          </li>

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
            <a href="<?= site_url('admin/dashboard') ?>" class="d-flex align-items-start justify-content-start">
              <img src="<?= base_url('assets/img/logo.png') ?>" alt="Logo"
                style="width: 64px; height: 64px; object-fit: contain;">
              <span class="fs-4">PASANG<span class="text-danger">IN</span></span>
            </a>
          </div>
          <div class="sidebar-brand sidebar-brand-sm">
            <a href="<?= site_url('admin/dashboard') ?>" class="d-flex align-items-center justify-content-center"
              style="height: 60px;">
              <img src="<?= base_url('assets/img/logo.png') ?>" alt="Logo"
                style="width: 30px; height: 30px; object-fit: contain;">
            </a>
          </div>

          <?php $uri = service('uri'); ?>
          <?php $seg2 = ($uri->getTotalSegments() >= 2) ? $uri->getSegment(2) : ''; ?>
          <?php
          $isAccounting = (strtolower(session()->get('role') ?? '') === 'accounting' || can('dashboard_accounting'));
          ?>

          <ul class="sidebar-menu">

            <!-- ============ DASHBOARD ============ -->
            <?php if (can('dashboard') || $isAccounting): ?>
              <li class="menu-header">DASHBOARD</li>
              <li class="<?= ($seg2 == 'dashboard' || $seg2 == '') ? 'active' : '' ?>">
                <a class="nav-link" href="<?= site_url('admin/dashboard') ?>"><i class="fas fa-fire"></i>
                  <span>Dashboard</span></a>
              </li>
            <?php endif; ?>

            <!-- ============ MANAJEMEN ============ -->
            <?php if (canAny(['chat', 'users', 'suppliers', 'products', 'orders', 'wallet', 'tukang']) || $isAccounting): ?>
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

              <?php if (can('wallet') || $isAccounting): ?>
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
            <?php if (canAny(['design', 'construction', 'renovation']) || $isAccounting): ?>
              <li class="menu-header">PROYEK</li>

              <?php if (can('design') || $isAccounting): ?>
                <li class="<?= ($seg2 == 'design') ? 'active' : '' ?>">
                  <a class="nav-link" href="<?= site_url('admin/design') ?>"><i class="fas fa-paint-brush"></i>
                    <span>Desain</span></a>
                </li>
              <?php endif; ?>

              <?php if (can('construction') || $isAccounting): ?>
                <li class="<?= ($seg2 == 'construction') ? 'active' : '' ?>">
                  <a class="nav-link" href="<?= site_url('admin/construction') ?>"><i class="fas fa-building"></i>
                    <span>Konstruksi</span></a>
                </li>
              <?php endif; ?>

              <?php if (can('renovation') || $isAccounting): ?>
                <li class="<?= ($seg2 == 'renovation') ? 'active' : '' ?>">
                  <a class="nav-link" href="<?= site_url('admin/renovation') ?>"><i class="fas fa-paint-roller"></i>
                    <span>Renovasi</span></a>
                </li>
              <?php endif; ?>
            <?php endif; ?>

            <!-- ============ KONTEN ============ -->
            <?php if (canAny(['banner', 'banner_supplier', 'vouchers', 'tips', 'promo', 'notification', 'price-estimate', 'syarat_ketentuan', 'about_application']) || $isAccounting): ?>
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

              <?php if (can('vouchers') || $isAccounting): ?>
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
              <?php if (can('about_application')): ?>
                <li class="<?= ($seg2 == 'about_application') ? 'active' : '' ?>">
                  <a class="nav-link" href="<?= site_url('admin/about_application') ?>"><i class="fas fa-info-circle"></i>
                    <span>Tentang Aplikasi</span></a>
                </li>
              <?php endif; ?>
            <?php endif; ?>

            <!-- ============ AKSES ============ -->
            <?php if (canAny(['roles', 'admin', 'activity_log_view'])): ?>
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

              <?php if (can('activity_log_view')): ?>
                <li class="<?= ($seg2 == 'activity-logs') ? 'active' : '' ?>">
                  <a class="nav-link" href="<?= site_url('admin/activity-logs') ?>"><i class="fas fa-history"></i> <span>Log
                      Aktivitas</span></a>
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

  <!-- Global Modals -->
  <?= $this->include('components/_global_delete_modal') ?>
  <?= $this->include('components/_global_status_modal') ?>

  <!-- General JS Scripts -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment-with-locales.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Ladda/1.0.6/spin.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Ladda/1.0.6/ladda.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <!-- GLightbox JS -->
  <script src="https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      if (typeof GLightbox !== 'undefined') {
        window.globalLightbox = GLightbox({
          selector: '.glightbox',
          zoomable: true,
          draggable: true,
          openEffect: 'zoom',
          closeEffect: 'zoom',
          slideEffect: 'slide',
          closeOnOutsideClick: true,
          keyboardNavigation: true,
          touchNavigation: true,
          descPosition: 'bottom'
        });
      }
    });
  </script>
  <!-- JS Libraries -->
  <?= $this->renderSection('script') ?>

  <!-- Template JS File -->
  <script src="<?= base_url('assets/js/stisla.js') ?>"></script>
  <script src="<?= base_url('assets/js/scripts.js') ?>"></script>
  <script src="<?= base_url('assets/js/custom.js') ?>"></script>

  <!-- Firebase FCM Implementation -->
  <script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-app-compat.js"></script>
  <script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-messaging-compat.js"></script>

  <script>
    const firebaseConfig = {
      apiKey: "AIzaSyB-2VsVBZ1ayN4Qj2Z1bvGSjlzeVasNu8A",
      projectId: "pasangin-c8050",
      messagingSenderId: "1016256565116",
      appId: "1:1016256565116:web:574dc80f84ac3dd2d05ef9"
    };

    firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();
    const overlay = document.getElementById('notif-force-overlay');
    const deniedMsg = document.getElementById('denied-msg');
    const btnAllow = document.getElementById('btn-allow-notif');

    function checkNotifPermission() {
      if (!('Notification' in window)) {
        console.warn('Browser ini tidak mendukung notifikasi.');
        overlay.style.display = 'none';
        return;
      }
      if (Notification.permission === 'granted') {
        overlay.style.display = 'none';

        // Selalu daftarkan Service Worker jika sudah granted agar bisa terima notif saat browser closed
        if ('serviceWorker' in navigator) {
          navigator.serviceWorker.register('<?= base_url('firebase-messaging-sw.js') ?>')
            .then(function (registration) {
              console.log('FCM Service Worker registered');
              // Ambil token setelah SW siap, sertakan registration
              getFCMToken(registration);
            });
        }
      } else if (Notification.permission === 'denied') {
        overlay.style.display = 'flex';
        deniedMsg.style.display = 'block';
        btnAllow.style.display = 'none';
      } else {
        overlay.style.display = 'flex';
        deniedMsg.style.display = 'none';
        btnAllow.style.display = 'inline-block';
      }
    }

    function getFCMToken(registration) {
      messaging.getToken({
        vapidKey: 'BOZaLfwpGedtEE1EmKYvscUvpciGC0eSw09Z6ro9CAwkiFT14_N2hlfkg2eKkx6CA6IA3FJ44nuFgmi3UKRF31g',
        serviceWorkerRegistration: registration
      }).then((currentToken) => {
        if (currentToken) {
          console.log('FCM Token:', currentToken);

          // Gunakan try-catch agar aman dari Tracking Prevention yang memblokir akses ke storage
          let shouldSave = true;
          const currentAdminId = '<?= session()->get('user_id') ?>';
          try {
            if (typeof window.localStorage !== 'undefined') {
              const savedToken = localStorage.getItem('fcm_token_saved');
              const savedAdminId = localStorage.getItem('fcm_token_admin_id');
              if (savedToken === currentToken && savedAdminId === currentAdminId) {
                shouldSave = false;
                console.log('FCM Token already saved for current admin session.');
              }
            }
          } catch (e) {
            console.warn('Storage access blocked by Tracking Prevention, falling back to direct save:', e);
          }

          if (shouldSave) {
            $.post('<?= base_url('admin/notification/saveToken') ?>', {
              token: currentToken
            }, function (res) {
              console.log('Token saved to backend:', res);
              if (res && res.status) {
                try {
                  if (typeof window.localStorage !== 'undefined') {
                    localStorage.setItem('fcm_token_saved', currentToken);
                    localStorage.setItem('fcm_token_admin_id', currentAdminId);
                  }
                } catch (e) {
                  // Gagal menyimpan cache (misal storage diblokir), abaikan saja
                }
              }
            });
          }
        }
      }).catch((err) => {
        console.log('An error occurred while retrieving token. ', err);
      });
    }

    btnAllow.addEventListener('click', function () {
      Notification.requestPermission().then((permission) => {
        checkNotifPermission();
      });
    });

    // Jalankan pengecekan awal
    checkNotifPermission();

    // --- LOGIKA NAVBAR NOTIFIKASI ---
    function loadNavbarNotifications() {
      $.get('<?= base_url('admin/notification/getLatest') ?>', function (data) {
        let html = '';
        if (data.length === 0) {
          html = '<div class="p-3 text-center text-muted">Tidak ada notifikasi baru</div>';
        } else {
          data.forEach(function (n) {
            let icon = 'fa-bell';
            let bg = 'bg-primary';

            if (n.target_type && n.target_type.includes('client')) { icon = 'fa-user'; bg = 'bg-info'; }
            else if (n.target_type && n.target_type.includes('tukang')) { icon = 'fa-tools'; bg = 'bg-warning'; }
            else if (n.target_type && n.target_type.includes('supplier')) { icon = 'fa-store'; bg = 'bg-success'; }

            // Gunakan moment ID untuk waktu yang lebih manusiawi
            moment.locale('id');
            let relativeTime = moment(n.created_at).fromNow();
            let fullDateTime = moment(n.created_at).format('dddd, DD MMM YYYY - HH:mm');

            html += `
              <a href="<?= base_url('admin/notification') ?>" class="dropdown-item">
                <div class="dropdown-item-icon ${bg} text-white">
                  <i class="fas ${icon}"></i>
                </div>
                <div class="dropdown-item-desc">
                  <b>${n.title}</b>
                  <p class="mb-0 text-truncate" style="max-width: 250px;">${n.message}</p>
                  <div class="time text-primary fw-bold" style="font-size: 0.65rem; margin-top: -3px;">${fullDateTime}</div>
                </div>
              </a>`;
          });
        }
        $('#notif-list').html(html);
      });
    }

    // Load saat pertama kali buka
    loadNavbarNotifications();

    messaging.onMessage((payload) => {
      console.log('Message received: ', payload);

      // Dispatch custom event to window so active chat view can listen
      const chatEvent = new CustomEvent('fcm_chat_received', { detail: payload });
      window.dispatchEvent(chatEvent);

      // Update Navbar & Badge
      loadNavbarNotifications();
      $('#notif-badge').text('!').fadeIn();

      if (typeof iziToast !== 'undefined') {
        iziToast.info({
          title: payload.notification.title,
          message: payload.notification.body,
          position: 'topCenter',
          displayMode: 'replace',
          timeout: 5000
        });
      } else {
        alert(payload.notification.title + "\n" + payload.notification.body);
      }
    });
  </script>
</body>

</html>