<?php
// FILE: app/Views/admin/chat/index.php
// VERSI PREMIUM BEAUTIFIED & ENHANCED UX

echo $this->extend('layout/app');

echo $this->section('title');
?>
Pusat Pesan - Pasangin
<?php
echo $this->endSection();

echo $this->section('style');
?>
<!-- Import Google Font Outfit untuk estetika premium -->
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    /* Global styling untuk area chat */
    .chat-section-wrapper {
        font-family: 'Outfit', sans-serif;
    }
    
    /* Container Utama Chat */
    .chat-card-container {
        border-radius: 20px !important;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(99, 102, 241, 0.04), 0 5px 15px rgba(0, 0, 0, 0.01) !important;
        border: 1px solid rgba(99, 102, 241, 0.08) !important;
        background: #fff;
        transition: all 0.3s ease;
    }
    .chat-card-container:hover {
        box-shadow: 0 25px 60px rgba(99, 102, 241, 0.08), 0 8px 20px rgba(0, 0, 0, 0.02) !important;
    }

    .pasangin-chat-box { 
        height: 100vh; 
        display: flex; 
        flex-direction: column; 
        background: #f8fafc;
        min-height: 0; /* KRUSIAL: Agar flex child bisa scroll dengan benar */
    }
    
    /* Area Daftar User (Sidebar) */
    .chat-list-container { 
        height: 100vh; 
        overflow-y: auto; 
        background: #fff; 
        border-right: 1px solid #f1f5f9; 
        display: flex;
        flex-direction: column;
    }

    .chat-list-header {
        background: #ffffff;
        padding: 24px 24px 10px 24px;
    }

    /* Search & Filter Section */
    .chat-search-filter-wrapper {
        background: #fff;
        padding: 0 24px 15px 24px;
        border-bottom: 1px solid #f8fafc;
    }
    
    .search-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        background: #f8fafc !important;
        border-radius: 12px !important;
        padding: 0 16px !important;
        border: 1px solid #e2e8f0 !important;
        transition: all 0.3s ease;
        height: 44px !important;
    }
    
    .search-input-wrapper:focus-within {
        background: #fff !important;
        border-color: #6366f1 !important;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.08) !important;
    }
    
    .search-input-wrapper .search-icon {
        color: #94a3b8 !important;
        margin-right: 10px !important;
        font-size: 0.85rem !important;
        display: inline-flex !important;
        align-items: center !important;
    }
    
    .search-input-wrapper input {
        border: none !important;
        background: transparent !important;
        background-color: transparent !important;
        box-shadow: none !important;
        height: 100% !important;
        font-size: 0.85rem !important;
        padding: 0 !important;
        margin: 0 !important;
        color: #334155 !important;
        width: 100% !important;
        outline: none !important;
    }
    
    .search-input-wrapper input:focus,
    .search-input-wrapper input:active {
        outline: none !important;
        border: none !important;
        box-shadow: none !important;
        background: transparent !important;
        background-color: transparent !important;
    }
    
    .search-input-wrapper .clear-search {
        color: #cbd5e0 !important;
        transition: color 0.2s !important;
        font-size: 0.85rem !important;
        padding-left: 5px !important;
        display: inline-flex !important;
        align-items: center !important;
    }
    
    .search-input-wrapper .clear-search:hover {
        color: #64748b !important;
    }
    
    .filter-pills {
        display: flex;
        gap: 6px;
        margin-top: 12px;
    }
    
    .btn-filter {
        border: none !important;
        background: #f8fafc !important;
        color: #64748b !important;
        font-size: 0.75rem !important;
        font-weight: 600 !important;
        padding: 8px 16px !important;
        border-radius: 10px !important;
        transition: all 0.25s ease !important;
        box-shadow: none !important;
    }
    
    .btn-filter:hover {
        background: #f1f5f9 !important;
        color: #334155 !important;
    }
    
    .btn-filter.active,
    .btn-filter.active:focus,
    .btn-filter.active:active,
    .btn-filter.active:hover {
        background: #6366f1 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2) !important;
    }
 
    .btn-filter:focus {
        outline: none !important;
        background: #e2e8f0 !important;
        color: #334155 !important;
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2) !important;
    }
 
    #chat-list {
        flex: 1;
        overflow-y: auto;
    }
 
    .chat-list-user { 
        cursor: pointer; 
        transition: all 0.25s ease; 
        border-left: 4px solid transparent;
        background-color: #ffffff;
        position: relative;
        padding: 22px 24px !important;
    }
 
    .chat-list-user:hover { 
        background-color: #f8fafc; 
    }
 
    .chat-list-user.active { 
        background-color: rgba(99, 102, 241, 0.05) !important; 
        border-left-color: #6366f1;
    }
 
    .chat-list-user.active .chat-client-name {
        color: #6366f1 !important;
    }
    
    /* Badge styling */
    .badge-tukang {
        background-color: #fef3c7;
        color: #d97706;
        border: 1px solid #fde68a;
    }
    
    .badge-klien {
        background-color: #e0f2fe;
        color: #0284c7;
        border: 1px solid #bae6fd;
    }

    .badge-tech {
        background-color: #e0e7ff;
        color: #4f46e5;
        border: 1px solid #c7d2fe;
    }
    
    .badge-acct {
        background-color: #ffedd5;
        color: #d97706;
        border: 1px solid #fed7aa;
    }
    
    .badge-gen {
        background-color: #f1f5f9;
        color: #475569;
        border: 1px solid #cbd5e1;
    }

    .filter-pills-cat {
        display: flex;
        gap: 6px;
        margin-top: 8px;
        flex-wrap: wrap;
    }
    
    .btn-filter-cat {
        border: none !important;
        background: #f8fafc !important;
        color: #64748b !important;
        font-size: 0.72rem !important;
        font-weight: 600 !important;
        padding: 6px 12px !important;
        border-radius: 8px !important;
        transition: all 0.25s ease !important;
        box-shadow: none !important;
    }
    
    .btn-filter-cat:hover {
        background: #f1f5f9 !important;
        color: #334155 !important;
    }
    
    .btn-filter-cat.active,
    .btn-filter-cat.active:focus,
    .btn-filter-cat.active:active,
    .btn-filter-cat.active:hover {
        background: #4f46e5 !important;
        color: #ffffff !important;
        box-shadow: 0 3px 8px rgba(79, 70, 229, 0.2) !important;
    }

    /* Header Chat Aktif */
    .pasangin-chat-header { 
        padding: 16px 25px; 
        background: #fff; 
        border-bottom: 1px solid #f1f5f9; 
        z-index: 10; 
    }

    /* Area Pesan (Bubble Chat) */
    .pasangin-chat-content { 
        flex: 1; 
        overflow-y: auto; 
        padding: 20px; 
        display: flex; 
        flex-direction: column; 
        gap: 12px; /* Spacing yang pas dan konsisten */
        background-color: #f8fafc;
        background-image: radial-gradient(#e2e8f0 0.6px, transparent 0.6px);
        background-size: 18px 18px;
        min-height: 0; /* KRUSIAL: Agar flex child bisa scroll */
    }
    
    .pasangin-chat-item { 
        display: flex; 
        width: 100%; 
        align-items: flex-start; /* Sejajarkan ke atas agar avatar & balon chat sejajar */
        margin-bottom: 0;
        animation: slideUp 0.25s ease-out;
        flex-shrink: 0; /* Jangan compress item, biarkan container scroll */
    }
    
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Layout Body Pesan */
    .pasangin-chat-item-body { 
        max-width: 70%; 
        min-width: 0; 
        display: flex; 
        flex-direction: column; 
    }

    .pasangin-chat-text { 
        padding: 10px 16px; 
        font-size: 14px; 
        line-height: 1.5; 
        position: relative; 
        width: fit-content;
        max-width: 100%;
        word-wrap: break-word;
        overflow-wrap: break-word;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    /* Media preview (gambar/video) dalam chat — batas ukuran agar tidak overflow */
    .chat-media-preview {
        display: block;
        width: 100%;
        max-width: 240px;
        max-height: 240px;
        object-fit: cover;
        border-radius: 8px; /* Diperkecil dari 12px agar sudut lebih proporsional */
        border: 1px solid rgba(0, 0, 0, 0.08); /* Menambahkan border tipis & bersih untuk bingkai gambar/video */
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .chat-media-preview:hover {
        transform: scale(1.02);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    /* Mengurangi ketebalan bingkai/padding balon chat jika isinya hanya media preview */
    .pasangin-chat-text:has(.chat-media-preview) {
        padding: 5px !important;
    }
    .pasangin-chat-item.pasangin-chat-left .pasangin-chat-text:has(.chat-media-preview) {
        border-radius: 12px 12px 12px 4px !important;
    }
    .pasangin-chat-item.pasangin-chat-right .pasangin-chat-text:has(.chat-media-preview) {
        border-radius: 12px 12px 4px 12px !important;
    }

    /* Container & Play Button Overlay untuk Video Preview */
    .chat-media-preview-container {
        position: relative;
        width: fit-content;
        max-width: 240px;
        border-radius: 8px;
        overflow: hidden;
        cursor: pointer;
    }
    .play-button-overlay {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(0, 0, 0, 0.15);
        color: #ffffff;
        font-size: 20px;
        cursor: pointer;
        transition: background 0.3s ease;
    }
    .play-button-overlay i {
        background: rgba(15, 23, 42, 0.75);
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding-left: 3px; /* Helper visual alignment */
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        transition: all 0.3s ease;
    }
    .chat-media-preview-container:hover .play-button-overlay i {
        transform: scale(1.1);
        background: #6366f1;
        box-shadow: 0 6px 14px rgba(99, 102, 241, 0.4);
    }

    /* Lightbox overlay untuk melihat gambar ukuran penuh */
    .chat-lightbox-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.85);
        backdrop-filter: blur(8px);
        z-index: 99999;
        align-items: center;
        justify-content: center;
        animation: fadeIn 0.2s ease;
    }
    .chat-lightbox-overlay.active {
        display: flex;
    }
    .chat-lightbox-image-wrapper {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        position: relative;
    }
    .chat-lightbox-overlay img {
        max-width: 90vw;
        max-height: 90vh;
        object-fit: contain;
        border-radius: 12px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.4);
        animation: zoomIn 0.25s ease;
        transform-origin: center center;
        transition: transform 0.1s ease-out;
        cursor: grab;
        user-select: none;
        -webkit-user-drag: none;
    }
    .chat-lightbox-overlay img:active {
        cursor: grabbing;
    }
    .chat-lightbox-close {
        position: absolute;
        top: 20px;
        right: 28px;
        color: #ffffff;
        font-size: 28px;
        cursor: pointer;
        opacity: 0.8;
        transition: opacity 0.2s, transform 0.2s;
        z-index: 100002;
    }
    .chat-lightbox-close:hover {
        opacity: 1;
        transform: scale(1.15);
    }
    .chat-lightbox-controls {
        position: absolute;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        align-items: center;
        gap: 12px;
        background: rgba(15, 23, 42, 0.85); /* Diubah menjadi dark semi-transparan premium agar kontras di latar putih */
        backdrop-filter: blur(12px) saturate(180%);
        -webkit-backdrop-filter: blur(12px) saturate(180%);
        border: 1px solid rgba(255, 255, 255, 0.1);
        padding: 8px 18px;
        border-radius: 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
        z-index: 100001;
        pointer-events: auto;
    }
    .chat-lightbox-controls button {
        background: transparent;
        border: none;
        color: #ffffff; /* Putih bersih kontras dengan latar belakang tombol yang gelap */
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .chat-lightbox-controls button:hover {
        background: rgba(255, 255, 255, 0.15);
        transform: scale(1.1);
    }
    .chat-lightbox-controls button:active {
        transform: scale(0.95);
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    @keyframes zoomIn {
        from { opacity: 0; transform: scale(0.9); }
        to { opacity: 1; transform: scale(1); }
    }

    .pasangin-chat-time { 
        font-size: 10px; 
        margin-top: 5px; 
        display: inline-flex; 
        align-items: center;
        gap: 4px;
        font-weight: 500;
    }

    /* Style Chat Kiri (User/Client/Tukang) */
    .pasangin-chat-item.pasangin-chat-left { justify-content: flex-start; }
    .pasangin-chat-item.pasangin-chat-left .pasangin-chat-text { 
        background: #ffffff; 
        color: #1e293b; 
        border-radius: 16px 16px 16px 4px; 
        border: 1px solid #e2e8f0;
    }
    .pasangin-chat-item.pasangin-chat-left .pasangin-chat-avatar { 
        margin-right: 12px; 
        order: 1; 
        margin-top: 2px; /* Margin kecil agar sejajar baris pertama teks */
    }
    .pasangin-chat-item.pasangin-chat-left .pasangin-chat-item-body { 
        order: 2; 
        align-items: flex-start;
    }
    .pasangin-chat-item.pasangin-chat-left .pasangin-chat-time { 
        color: #94a3b8; 
    }

    /* Style Chat Kanan (Admin) */
    .pasangin-chat-item.pasangin-chat-right { justify-content: flex-end; }
    .pasangin-chat-item.pasangin-chat-right .pasangin-chat-text { 
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); 
        color: #fff; 
        border-radius: 16px 16px 4px 16px; 
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.12);
    }
    .pasangin-chat-item.pasangin-chat-right.pasangin-chat-item-failed .pasangin-chat-text {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2) !important;
    }
    .pasangin-chat-item.pasangin-chat-right .pasangin-chat-avatar { 
        margin-left: 12px; 
        order: 2; 
        margin-top: 2px; /* Margin kecil agar sejajar baris pertama teks */
    }
    .pasangin-chat-item.pasangin-chat-right .pasangin-chat-item-body { 
        order: 1; 
        align-items: flex-end; 
    }
    .pasangin-chat-item.pasangin-chat-right .pasangin-chat-time { 
        color: #a5b4fc; 
        justify-content: flex-end;
    }

    .pasangin-chat-avatar img { 
        width: 36px; 
        height: 36px; 
        border-radius: 50%; 
        object-fit: cover; 
        border: 2px solid #fff; 
        box-shadow: 0 2px 5px rgba(0,0,0,0.05); 
    }

    /* Date Separator */
    .chat-date-separator {
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 12px 0;
        position: relative;
    }
    
    .chat-date-separator::before {
        content: '';
        position: absolute;
        left: 0;
        right: 0;
        height: 1px;
        background: #e2e8f0;
        z-index: 1;
    }
    
    .chat-date-pill {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        color: #64748b;
        font-size: 11px;
        font-weight: 600;
        padding: 5px 14px;
        border-radius: 20px;
        z-index: 2;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Unread Indicator Badge (Glowing Count Badge) - Warna Merah Terang (Vibrant) */
    .unread-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 18px;
        height: 18px;
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: #ffffff;
        font-size: 10px;
        font-weight: 700;
        border-radius: 10px;
        padding: 0 5px;
        box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4);
        animation: pulse-unread 1.5s infinite;
    }

    @keyframes pulse-unread {
        0% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4);
        }
        70% {
            transform: scale(1);
            box-shadow: 0 0 0 6px rgba(239, 68, 68, 0);
        }
        100% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(239, 68, 68, 0);
        }
    }

    /* Form Input Pesan */
    .pasangin-chat-footer { 
        background: #fff; 
        padding: 18px 25px; 
        border-top: 1px solid #f1f5f9; 
    }

    .input-wrapper { 
        background: #f1f5f9; 
        border-radius: 30px; 
        padding: 4px 6px 4px 20px; 
        display: flex; 
        align-items: center; 
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }

    .input-wrapper:focus-within {
        background: #fff;
        border-color: #6366f1;
        box-shadow: 0 4px 16px rgba(99, 102, 241, 0.08);
    }

    .input-wrapper input { 
        border: none !important; 
        background: transparent !important; 
        box-shadow: none !important; 
        height: 44px; 
        font-size: 14px;
        color: #334155;
    }

    .btn-send { 
        width: 44px; 
        height: 44px; 
        border-radius: 50% !important; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        transition: all 0.3s ease; 
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        border: none;
        color: #fff;
        box-shadow: 0 4px 10px rgba(99, 102, 241, 0.3);
    }

    .btn-send:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 14px rgba(99, 102, 241, 0.4);
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%) !important;
        color: #fff !important;
    }

    .btn-send:focus,
    .btn-send:active {
        outline: none !important;
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%) !important;
        color: #fff !important;
        box-shadow: 0 4px 10px rgba(99, 102, 241, 0.3) !important;
    }

    /* Loading Spinner */
    .chat-loading-spinner {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
    }
    
    /* Empty State Animation */
    @keyframes floating {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
        100% { transform: translateY(0px); }
    }
    .floating-illustration {
        animation: floating 4s ease-in-out infinite;
    }
    
    /* Scrollbar Premium */
    .pasangin-chat-content::-webkit-scrollbar { width: 5px; }
    .pasangin-chat-content::-webkit-scrollbar-track { background: transparent; }
    .pasangin-chat-content::-webkit-scrollbar-thumb { background: #c7d2fe; border-radius: 10px; }
    .pasangin-chat-content::-webkit-scrollbar-thumb:hover { background: #a5b4fc; }
    
    .chat-list-container::-webkit-scrollbar { width: 4px; }
    .chat-list-container::-webkit-scrollbar-track { background: transparent; }
    .chat-list-container::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    
    #chat-list::-webkit-scrollbar { width: 4px; }
    #chat-list::-webkit-scrollbar-track { background: transparent; }
    #chat-list::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }

    /* Firefox premium scrollbar support */
    .pasangin-chat-content {
        scrollbar-width: thin;
        scrollbar-color: #c7d2fe transparent;
    }
    .chat-list-container, #chat-list {
        scrollbar-width: thin;
        scrollbar-color: #e2e8f0 transparent;
    }

    /* Attachment Button styling */
    #attachment-btn {
        background: transparent;
        border: none;
        outline: none;
        color: #64748b;
        cursor: pointer;
        padding: 8px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.25s ease;
        margin-right: 5px;
    }
    #attachment-btn:hover {
        background: #e2e8f0;
        color: #6366f1;
    }
    
    /* Attachment Preview Container */
    #attachment-preview-container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 8px 16px;
        margin-bottom: 8px;
        animation: slideUp 0.2s ease-out;
    }

    @keyframes pulse-green {
        0% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.5);
        }
        70% {
            transform: scale(1);
            box-shadow: 0 0 0 6px rgba(16, 185, 129, 0);
        }
        100% {
            transform: scale(0.95);
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
        }
    }

    /* Override radius, margins, and styling of section-header to match section-body chat card */
    .chat-section-wrapper .section-header {
        border-radius: 20px !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
        margin-top: 0 !important;
        border: 1px solid rgba(99, 102, 241, 0.08) !important;
        box-shadow: 0 20px 50px rgba(99, 102, 241, 0.04), 0 5px 15px rgba(0, 0, 0, 0.01) !important;
        background: #fff;
        padding: 16px 25px !important;
    }

    /* Mobile-responsive refinements */
    @media (max-width: 767.98px) {
        .chat-list-container, .pasangin-chat-box {
            height: calc(100vh - 115px) !important;
        }
        
        /* Toggle state for mobile columns */
        .chat-card-container:not(.mobile-chat-active) .chat-list-container {
            display: flex !important;
            width: 100% !important;
        }
        .chat-card-container:not(.mobile-chat-active) .col-md-8.col-12 {
            display: none !important;
        }
        
        .chat-card-container.mobile-chat-active .chat-list-container {
            display: none !important;
        }
        .chat-card-container.mobile-chat-active .col-md-8.col-12 {
            display: block !important;
            width: 100% !important;
        }
        
        /* Save space on mobile headers/footers */
        .pasangin-chat-header {
            padding: 12px 15px !important;
        }
        .pasangin-chat-footer {
            padding: 12px 15px !important;
        }
        .chat-list-header {
            padding: 16px 16px 8px 16px !important;
        }
        .chat-search-filter-wrapper {
            padding: 0 16px 12px 16px !important;
        }
        .chat-list-user {
            padding: 16px 16px !important;
        }
        
        /* Adjust active chat header elements on mobile */
        #btn-back-to-list {
            display: flex !important;
        }
    }
</style>
<?php
echo $this->endSection();

echo $this->section('content');
?>
<section class="section chat-section-wrapper">
    <div class="section-header d-flex justify-content-between align-items-center py-3 mb-2">
        <div>
            <h1 style="font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 1.5rem; color: #1e293b; margin: 0; line-height: 1.2;">Pusat Pesan Real-time</h1>
            <div class="text-muted mt-1" style="font-size: 0.8rem; font-weight: 500; letter-spacing: 0.2px;">Pantau dan respon chat pelanggan secara langsung</div>
        </div>
        <div class="d-none d-sm-block">
            <span class="badge px-3 py-2 d-flex align-items-center" style="border-radius: 30px; font-weight: 600; font-size: 0.75rem; background-color: rgba(16, 185, 129, 0.08); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.15);">
                <span class="me-2 d-inline-block" style="width: 7px; height: 7px; border-radius: 50%; background-color: #10b981; animation: pulse-green 1.5s infinite;"></span> 
                Live System Active
            </span>
        </div>
    </div>

    <div class="section-body">
        <div class="card chat-card-container">
            <div class="card-body p-0">
                <div class="row g-0">
                    <!-- Sidebar: Daftar User Chat -->
                    <?= $this->include('App\Modules\Chat\Views\components\_chat_sidebar') ?>

                    <!-- Panel Kanan: Isi Chat Box -->
                    <?= $this->include('App\Modules\Chat\Views\components\_chat_box') ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Lightbox Overlay untuk melihat gambar ukuran penuh -->
<?= $this->include('App\Modules\Chat\Views\components\_lightbox') ?>
<?php
echo $this->endSection();

echo $this->section('script');
?>
<?= $this->include('App\Modules\Chat\Views\components\_scripts') ?>
<?= $this->include('App\Modules\Chat\Views\components\_lightbox_scripts') ?>
<?php
echo $this->endSection();
?>