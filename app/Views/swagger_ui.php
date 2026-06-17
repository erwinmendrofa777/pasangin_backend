<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pasangin API Console & Documentation</title>
    <!-- Preconnect for Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- Plus Jakarta Sans & JetBrains Mono Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Swagger UI CSS from Cloudflare CDN (Jakarta Edge Server) -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/swagger-ui/5.11.0/swagger-ui.min.css">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="https://cdnjs.cloudflare.com/ajax/libs/swagger-ui/5.11.0/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="https://cdnjs.cloudflare.com/ajax/libs/swagger-ui/5.11.0/favicon-16x16.png" sizes="16x16" />
    
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            background-color: #f8fafc;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            color: #0f172a;
            overflow: hidden;
        }

        /* Container Utama Dashboard */
        .app-container {
            display: flex;
            width: 100%;
            height: 100vh;
            overflow: hidden;
        }

        /* Sidebar Kiri */
        .sidebar {
            width: 340px;
            background-color: #ffffff;
            border-right: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            height: 100%;
            box-sizing: border-box;
        }

        /* Sidebar Header */
        .sidebar-header {
            padding: 24px;
            border-bottom: 1px solid #f1f5f9;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 18px;
        }

        .logo-img {
            height: 34px;
            object-fit: contain;
        }

        .brand-text {
            font-size: 1.15rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
        }

        .brand-badge {
            font-size: 0.7rem;
            font-weight: 700;
            color: #64748b;
            background-color: #f1f5f9;
            padding: 2px 8px;
            border-radius: 4px;
        }

        /* Tampilkan Semua API Button */
        .btn-show-all {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 10px;
            background-color: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #475569;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            box-sizing: border-box;
        }

        .btn-show-all:hover, .btn-show-all.active {
            background-color: #eff6ff;
            border-color: #3b82f6;
            color: #1d4ed8;
        }

        /* Sidebar Menu List */
        .sidebar-menu {
            flex-grow: 1;
            overflow-y: auto;
            padding: 20px 24px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        /* Custom Scrollbar Premium untuk Sidebar */
        .sidebar-menu::-webkit-scrollbar {
            width: 5px;
        }
        .sidebar-menu::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar-menu::-webkit-scrollbar-thumb {
            background: transparent;
            border-radius: 10px;
            transition: all 0.2s ease;
        }
        .sidebar-menu:hover::-webkit-scrollbar-thumb {
            background: #cbd5e1;
        }
        .sidebar-menu::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .menu-title-header {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #94a3b8;
            margin-bottom: 6px;
            padding-left: 8px;
        }

        .menu-group-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            border-radius: 8px;
            text-decoration: none;
            color: #475569;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.15s ease;
            border: 1px solid transparent;
            gap: 12px;
        }

        .menu-group-link:hover {
            background-color: #f8fafc;
            color: #0f172a;
            border-color: #f1f5f9;
        }

        .menu-group-link.active {
            background-color: #eff6ff;
            color: #1d4ed8;
            border-color: #bfdbfe;
        }

        .menu-group-badge {
            font-size: 0.7rem;
            background-color: #e2e8f0;
            padding: 3px 8px;
            border-radius: 10px;
            color: #475569;
            white-space: nowrap;
            flex-shrink: 0;
            font-weight: 700;
            transition: all 0.15s ease;
        }

        .menu-group-link.active .menu-group-badge {
            background-color: #dbeafe;
            color: #1e40af;
        }


        /* Sidebar Footer Info */
        .sidebar-footer {
            padding: 20px 24px;
            border-top: 1px solid #f1f5f9;
            background-color: #f8fafc;
            font-size: 0.8rem;
        }

        .env-badge {
            display: inline-block;
            background-color: #d1fae5;
            color: #065f46;
            font-weight: 700;
            font-size: 0.65rem;
            padding: 2px 6px;
            border-radius: 4px;
            text-transform: uppercase;
        }

        /* Area Konten Utama */
        .main-content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* Navbar Atas */
        .navbar {
            background-color: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 40px;
            flex-shrink: 0;
        }

        .navbar h1 {
            font-size: 1.05rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
        }

        .btn-json {
            font-size: 0.75rem;
            font-weight: 600;
            color: #2563eb;
            text-decoration: none;
            border: 1px solid #e2e8f0;
            padding: 6px 14px;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .btn-json:hover {
            background-color: #f8fafc;
            border-color: #cbd5e1;
        }

        /* Wadah Scroll Dokumen */
        .content-scrollable {
            padding: 40px;
            overflow-y: auto;
            flex-grow: 1;
            scroll-behavior: smooth;
        }

        /* Card Pembungkus Swagger UI & Placeholder */
        .swagger-card {
            background-color: #ffffff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
            padding: 24px;
            margin-bottom: 40px;
        }

        /* Modifikasi CSS Swagger UI */
        .swagger-ui {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            padding: 0 !important;
        }
        .swagger-ui .topbar {
            display: none !important;
        }
        .swagger-ui .info {
            margin: 0 0 24px 0 !important;
        }
        
        /* Tombol Authorize */
        .swagger-ui .btn.authorize {
            background-color: #2563eb !important;
            color: #ffffff !important;
            border-color: #2563eb !important;
            font-weight: 700;
            border-radius: 6px;
            font-size: 0.75rem;
            padding: 6px 14px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .swagger-ui .btn.authorize svg {
            fill: #ffffff !important;
            width: 14px;
            height: 14px;
        }
        .swagger-ui .btn.authorize:hover {
            background-color: #1d4ed8 !important;
            border-color: #1d4ed8 !important;
        }

        /* STYLE PLACEHOLDER AWAL */
        .api-placeholder {
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 80px 40px;
            text-align: center;
            background-color: #ffffff;
            border-radius: 8px;
            border: 1px dashed #cbd5e1;
            color: #64748b;
            margin-top: 24px;
        }

        .api-placeholder h3 {
            margin: 0 0 10px 0;
            color: #0f172a;
            font-size: 1.2rem;
            font-weight: 700;
        }

        .api-placeholder p {
            margin: 0;
            max-width: 460px;
            font-size: 0.85rem;
            line-height: 1.6;
        }

        /* Logic saat placeholder aktif (hanya berlaku jika url di /swagger atau group=all) */
        .placeholder-active .api-placeholder {
            display: flex !important;
        }
        .placeholder-active .swagger-ui .wrapper {
            display: none !important;
        }
    </style>
</head>
<body class="<?= ($activeGroup === 'all') ? 'placeholder-active' : '' ?>">
    <div class="app-container">
        <!-- Sidebar Menu Navigasi -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-brand">
                    <img src="<?= base_url('assets/img/logo.png') ?>" alt="Logo" class="logo-img">
                    <span class="brand-text">Pasangin Console</span>
                    <span class="brand-badge">v1.0</span>
                </div>
                <!-- Tampilkan Semua API -->
                <button id="show-all-btn" class="btn-show-all <?= ($activeGroup === 'all') ? 'active' : '' ?>">
                    <span>Lihat Semua API</span>
                </button>
            </div>

            <!-- Static Menu Container (Halaman Berbeda-beda) -->
            <div id="sidebar-menu" class="sidebar-menu">
                <div class="menu-title-header">Kategori Modul API</div>
                
                <!-- 1. Authentication -->
                <a class="menu-group-link <?= $activeGroup === 'auth' ? 'active' : '' ?>" href="<?= base_url('swagger?group=auth') ?>">
                    <span>Authentication (Client & Recovery)</span>
                    <span class="menu-group-badge">9 API</span>
                </a>

                <!-- 2. Authentication (Supplier) -->
                <a class="menu-group-link <?= $activeGroup === 'supplier-auth' ? 'active' : '' ?>" href="<?= base_url('swagger?group=supplier-auth') ?>">
                    <span>Authentication (Supplier)</span>
                    <span class="menu-group-badge">7 API</span>
                </a>

                <!-- 3. Authentication (Tukang) -->
                <a class="menu-group-link <?= $activeGroup === 'tukang-auth' ? 'active' : '' ?>" href="<?= base_url('swagger?group=tukang-auth') ?>">
                    <span>Authentication (Tukang)</span>
                    <span class="menu-group-badge">7 API</span>
                </a>

                <!-- 4. Supplier Banner -->
                <a class="menu-group-link <?= $activeGroup === 'supplier-banner' ? 'active' : '' ?>" href="<?= base_url('swagger?group=supplier-banner') ?>">
                    <span>Supplier Banner</span>
                    <span class="menu-group-badge">6 API</span>
                </a>

                <!-- 5. Supplier Shipping Fee -->
                <a class="menu-group-link <?= $activeGroup === 'supplier-ongkir' ? 'active' : '' ?>" href="<?= base_url('swagger?group=supplier-ongkir') ?>">
                    <span>Supplier Shipping Fee</span>
                    <span class="menu-group-badge">6 API</span>
                </a>
                
                <!-- 6. Supplier Orders -->
                <a class="menu-group-link <?= $activeGroup === 'supplier-orders' ? 'active' : '' ?>" href="<?= base_url('swagger?group=supplier-orders') ?>">
                    <span>Supplier Orders</span>
                    <span class="menu-group-badge">7 API</span>
                </a>

                <!-- 7. Supplier Ratings -->
                <a class="menu-group-link <?= $activeGroup === 'supplier-rating' ? 'active' : '' ?>" href="<?= base_url('swagger?group=supplier-rating') ?>">
                    <span>Supplier Ratings</span>
                    <span class="menu-group-badge">2 API</span>
                </a>

                <!-- 8. User Address -->
                <a class="menu-group-link <?= $activeGroup === 'alamat' ? 'active' : '' ?>" href="<?= base_url('swagger?group=alamat') ?>">
                    <span>User Address</span>
                    <span class="menu-group-badge">5 API</span>
                </a>

                <!-- 9. User Profile & Account -->
                <a class="menu-group-link <?= $activeGroup === 'user-profile' ? 'active' : '' ?>" href="<?= base_url('swagger?group=user-profile') ?>">
                    <span>User Profile & Account</span>
                    <span class="menu-group-badge">6 API</span>
                </a>

                <!-- 10. Cart (Keranjang) -->
                <a class="menu-group-link <?= $activeGroup === 'cart' ? 'active' : '' ?>" href="<?= base_url('swagger?group=cart') ?>">
                    <span>Cart (Keranjang)</span>
                    <span class="menu-group-badge">4 API</span>
                </a>

                <!-- 11. Category (Kategori) -->
                <a class="menu-group-link <?= $activeGroup === 'category' ? 'active' : '' ?>" href="<?= base_url('swagger?group=category') ?>">
                    <span>Category (Kategori)</span>
                    <span class="menu-group-badge">4 API</span>
                </a>

                <!-- 12. Chat (Obrolan) -->
                <a class="menu-group-link <?= $activeGroup === 'chat' ? 'active' : '' ?>" href="<?= base_url('swagger?group=chat') ?>">
                    <span>Chat (Obrolan)</span>
                    <span class="menu-group-badge">4 API</span>
                </a>

                <!-- 13. Construction (Konstruksi) -->
                <a class="menu-group-link <?= $activeGroup === 'construction' ? 'active' : '' ?>" href="<?= base_url('swagger?group=construction') ?>">
                    <span>Construction (Konstruksi)</span>
                    <span class="menu-group-badge">22 API</span>
                </a>

                <!-- 14. Renovation (Renovasi) -->
                <a class="menu-group-link <?= $activeGroup === 'renovation' ? 'active' : '' ?>" href="<?= base_url('swagger?group=renovation') ?>">
                    <span>Renovation (Renovasi)</span>
                    <span class="menu-group-badge">22 API</span>
                </a>

                <!-- 15. Design (Desain) -->
                <a class="menu-group-link <?= $activeGroup === 'design' ? 'active' : '' ?>" href="<?= base_url('swagger?group=design') ?>">
                    <span>Design (Desain)</span>
                    <span class="menu-group-badge">10 API</span>
                </a>

                <!-- 16. General Content -->
                <a class="menu-group-link <?= $activeGroup === 'content' ? 'active' : '' ?>" href="<?= base_url('swagger?group=content') ?>">
                    <span>General Content (Konten)</span>
                    <span class="menu-group-badge">3 API</span>
                </a>

                <!-- 17. Contract (Kontrak) -->
                <a class="menu-group-link <?= $activeGroup === 'contract' ? 'active' : '' ?>" href="<?= base_url('swagger?group=contract') ?>">
                    <span>Contract (Kontrak)</span>
                    <span class="menu-group-badge">2 API</span>
                </a>

                <!-- 18. Tukang Content -->
                <a class="menu-group-link <?= $activeGroup === 'tukang-content' ? 'active' : '' ?>" href="<?= base_url('swagger?group=tukang-content') ?>">
                    <span>Tukang Content</span>
                    <span class="menu-group-badge">2 API</span>
                </a>

                <!-- 19. Tukang Jobs & Progress -->
                <a class="menu-group-link <?= $activeGroup === 'job' ? 'active' : '' ?>" href="<?= base_url('swagger?group=job') ?>">
                    <span>Tukang Jobs & Progress</span>
                    <span class="menu-group-badge">13 API</span>
                </a>

                <!-- 20. Tukang Ratings -->
                <a class="menu-group-link <?= $activeGroup === 'tukang-rating' ? 'active' : '' ?>" href="<?= base_url('swagger?group=tukang-rating') ?>">
                    <span>Tukang Ratings</span>
                    <span class="menu-group-badge">3 API</span>
                </a>

                <!-- 21. Tukang Wallet -->
                <a class="menu-group-link <?= $activeGroup === 'tukang-wallet' ? 'active' : '' ?>" href="<?= base_url('swagger?group=tukang-wallet') ?>">
                    <span>Tukang Wallet</span>
                    <span class="menu-group-badge">3 API</span>
                </a>

                <!-- 22. Payment Webhook -->
                <a class="menu-group-link <?= $activeGroup === 'payment-webhook' ? 'active' : '' ?>" href="<?= base_url('swagger?group=payment-webhook') ?>">
                    <span>Payment Webhook</span>
                    <span class="menu-group-badge">1 API</span>
                </a>

                <!-- 23. Notification (Notifikasi) -->
                <a class="menu-group-link <?= $activeGroup === 'notification' ? 'active' : '' ?>" href="<?= base_url('swagger?group=notification') ?>">
                    <span>Notification (Notifikasi)</span>
                    <span class="menu-group-badge">7 API</span>
                </a>

                <!-- 24. Orders (Pesanan) -->
                <a class="menu-group-link <?= $activeGroup === 'orders' ? 'active' : '' ?>" href="<?= base_url('swagger?group=orders') ?>">
                    <span>Orders (Pesanan)</span>
                    <span class="menu-group-badge">8 API</span>
                </a>

                <!-- 25. Payments (Pembayaran) -->
                <a class="menu-group-link <?= $activeGroup === 'payment' ? 'active' : '' ?>" href="<?= base_url('swagger?group=payment') ?>">
                    <span>Payments (Pembayaran)</span>
                    <span class="menu-group-badge">5 API</span>
                </a>

                <!-- 26. Products (Produk) -->
                <a class="menu-group-link <?= $activeGroup === 'products' ? 'active' : '' ?>" href="<?= base_url('swagger?group=products') ?>">
                    <span>Products (Produk)</span>
                    <span class="menu-group-badge">12 API</span>
                </a>

                <!-- 27. Projects (Proyek) -->
                <a class="menu-group-link <?= $activeGroup === 'project' ? 'active' : '' ?>" href="<?= base_url('swagger?group=project') ?>">
                    <span>Projects (Proyek)</span>
                    <span class="menu-group-badge">6 API</span>
                </a>

                <!-- 28. Promos (Promo) -->
                <a class="menu-group-link <?= $activeGroup === 'promo' ? 'active' : '' ?>" href="<?= base_url('swagger?group=promo') ?>">
                    <span>Promos (Promo)</span>
                    <span class="menu-group-badge">5 API</span>
                </a>

                <!-- 29. Vouchers -->
                <a class="menu-group-link <?= $activeGroup === 'voucher' ? 'active' : '' ?>" href="<?= base_url('swagger?group=voucher') ?>">
                    <span>Vouchers</span>
                    <span class="menu-group-badge">1 API</span>
                </a>

                <!-- 30. Agreements & Terms -->
                <a class="menu-group-link <?= $activeGroup === 'agreement' ? 'active' : '' ?>" href="<?= base_url('swagger?group=agreement') ?>">
                    <span>Agreements & Terms</span>
                    <span class="menu-group-badge">3 API</span>
                </a>

                <!-- 31. About Application -->
                <a class="menu-group-link <?= $activeGroup === 'about' ? 'active' : '' ?>" href="<?= base_url('swagger?group=about') ?>">
                    <span>About Application</span>
                    <span class="menu-group-badge">1 API</span>
                </a>

                <!-- 32. Settings (Pengaturan) -->
                <a class="menu-group-link <?= $activeGroup === 'settings' ? 'active' : '' ?>" href="<?= base_url('swagger?group=settings') ?>">
                    <span>Settings (Pengaturan)</span>
                    <span class="menu-group-badge">1 API</span>
                </a>
            </div>

            <div class="sidebar-footer">
                <div style="margin-bottom: 6px;">
                    <strong>Env:</strong> <span class="env-badge"><?= ENVIRONMENT ?></span>
                </div>
                <div style="font-size: 0.7rem; color: #64748b; font-family: monospace; overflow: hidden; text-overflow: ellipsis;">
                    <?= base_url() ?>
                </div>
            </div>
        </aside>

        <!-- Area Konten Utama -->
        <main class="main-content">
            <!-- Navbar Atas -->
            <header class="navbar">
                <h1>Pasangin API Console & Documentation</h1>
                <div class="navbar-actions">
                    <a href="<?= base_url('swagger/json' . (!empty($activeGroup) ? '?group=' . $activeGroup : '')) ?>" target="_blank" class="btn-json">OpenAPI JSON</a>
                </div>
            </header>

            <!-- Scroll Area Dokumen Swagger UI -->
            <div class="content-scrollable" id="doc-scroll-container">
                <div class="swagger-card">
                    <!-- Swagger UI Container -->
                    <div id="swagger-ui"></div>
                    
                    <!-- Placeholder Welcome Screen -->
                    <div id="api-placeholder" class="api-placeholder">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width: 48px; height: 48px; color: #94a3b8; margin-bottom: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <h3>Pasangin Developer Console</h3>
                        <p>Silakan pilih salah satu kategori modul API di sidebar sebelah kiri untuk melihat dokumentasi secara terpisah per halaman, atau klik <strong>"Lihat Semua API"</strong> untuk memuat keseluruhan.</p>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Swagger UI JS Bundle and Preset from Cloudflare CDN (Jakarta Edge) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/swagger-ui/5.11.0/swagger-ui-bundle.min.js" charset="UTF-8"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/swagger-ui/5.11.0/swagger-ui-standalone-preset.min.js" charset="UTF-8"></script>
    
    <script>
        window.onload = function() {
            // URL JSON disaring berdasarkan group aktif di URL
            const jsonUrl = "<?= base_url('swagger/json') . (!empty($activeGroup) ? '?group=' . $activeGroup : '') ?>";

            // Inisialisasi Swagger UI asli (Nativ dan Stabil)
            const ui = SwaggerUIBundle({
                url: jsonUrl,
                dom_id: '#swagger-ui',
                deepLinking: true,
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],
                plugins: [
                    SwaggerUIBundle.plugins.DownloadUrl
                ],
                layout: "BaseLayout",
                persistAuthorization: true
            });

            window.ui = ui;

            // Logika Klik "Lihat Semua API" secara instan tanpa reload page jika saat ini di halaman welcome
            document.getElementById('show-all-btn').onclick = function(e) {
                if (document.body.classList.contains('placeholder-active')) {
                    document.body.classList.remove('placeholder-active');
                    this.classList.add('active');
                    // Bersihkan active state dari sidebar kategori link
                    document.querySelectorAll('.menu-group-link').forEach(el => el.classList.remove('active'));
                } else {
                    // Jika sedang di modul tertentu, arahkan ulang ke halaman utama
                    window.location.href = "<?= base_url('swagger') ?>";
                }
            };
        };
    </script>
</body>
</html>
