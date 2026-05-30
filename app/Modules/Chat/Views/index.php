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
                    <div class="col-md-4 col-12 chat-list-container">
                        <div class="chat-list-header d-flex justify-content-between align-items-center">
                            <h6 class="m-0 fw-bold text-dark" style="font-size: 1rem;">Obrolan Aktif</h6>
                            <div class="d-flex align-items-center" style="gap: 5px;">
                                <?php 
                                    $totalUnread = 0;
                                    if (!empty($conversations)) {
                                        foreach ($conversations as $c) {
                                            $totalUnread += intval($c['unread_by_admin_count'] ?? 0);
                                        }
                                    }
                                ?>
                                <span id="total-unread-badge" class="badge px-2 py-1 text-danger" style="font-size: 0.72rem; border-radius: 12px; background-color: #fee2e2; font-weight: 600; <?= $totalUnread > 0 ? '' : 'display: none;' ?>">
                                    <?= $totalUnread ?> Belum Dibaca
                                </span>
                                <span class="badge px-2 py-1 text-primary" style="font-size: 0.72rem; border-radius: 12px; background-color: #e0e7ff; font-weight: 600;">
                                    <?= count($conversations ?? []) ?> Percakapan
                                </span>
                            </div>
                        </div>
                        
                        <!-- Search & Filter Tab Area -->
                        <div class="chat-search-filter-wrapper">
                            <div class="search-input-wrapper">
                                <i class="fas fa-search search-icon"></i>
                                <input type="text" id="chat-search" placeholder="Cari nama klien/tukang..." autocomplete="off">
                                <i class="fas fa-times clear-search" id="clear-search" style="display: none; cursor: pointer;"></i>
                            </div>
                            <div class="filter-pills">
                                <button class="btn btn-filter active" data-filter="all">Semua</button>
                                <button class="btn btn-filter" data-filter="client">Klien</button>
                                <button class="btn btn-filter" data-filter="tukang">Tukang</button>
                            </div>
                            <?php if (can('super_admin_override') || count($allowedCategories ?? []) > 1) : ?>
                            <div class="filter-pills-cat">
                                <button class="btn btn-filter-cat active" data-filter-cat="all">Semua Dept</button>
                                <?php if (can('super_admin_override') || in_array('technical', $allowedCategories ?? [])) : ?>
                                    <button class="btn btn-filter-cat" data-filter-cat="technical">Technical</button>
                                <?php endif; ?>
                                <?php if (can('super_admin_override') || in_array('accounting', $allowedCategories ?? [])) : ?>
                                    <button class="btn btn-filter-cat" data-filter-cat="accounting">Accounting</button>
                                <?php endif; ?>
                                <?php if (can('super_admin_override') || in_array('general', $allowedCategories ?? [])) : ?>
                                    <button class="btn btn-filter-cat" data-filter-cat="general">General</button>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- List Users -->
                        <div id="chat-list">
                            <ul class="list-unstyled mb-0">
                                <?php if (!empty($conversations)) : ?>
                                    <?php foreach ($conversations as $convo) : ?>
                                        <?php 
                                            $isTukang = ($convo['client_type'] === 'tukang');
                                            $badgeClass = $isTukang ? 'badge-tukang' : 'badge-klien';
                                            $badgeText = $isTukang ? 'Tukang' : 'Klien';
                                            
                                            // Format waktu pesan terakhir secara rapi
                                            $timeStr = '';
                                            if (!empty($convo['last_message_at'])) {
                                                $timeStr = date('H:i', strtotime($convo['last_message_at']));
                                                if (date('Y-m-d', strtotime($convo['last_message_at'])) !== date('Y-m-d')) {
                                                    $timeStr = date('d M', strtotime($convo['last_message_at']));
                                                }
                                            }
                                            
                                            $lastMsg = esc($convo['last_message_preview'] ?? 'Belum ada riwayat pesan');

                                            // Determine category badge class & text
                                            $cat = $convo['category'] ?? 'general';
                                            if ($cat === 'technical') {
                                                $catBadgeClass = 'badge-tech';
                                                $catText = 'Technical';
                                            } elseif ($cat === 'accounting') {
                                                $catBadgeClass = 'badge-acct';
                                                $catText = 'Accounting';
                                            } else {
                                                $catBadgeClass = 'badge-gen';
                                                $catText = 'General';
                                            }
                                        ?>
                                        <?php 
                                            // Menentukan foto profil asli jika ada
                                            $avatarUrl = '';
                                            if (!empty($convo['client_avatar'])) {
                                                if ($isTukang) {
                                                    $avatarUrl = base_url('uploads/tukang/' . $convo['client_avatar']);
                                                } else {
                                                    if (strpos($convo['client_avatar'], 'http') === 0) {
                                                        $avatarUrl = $convo['client_avatar'];
                                                    } else {
                                                        $avatarUrl = base_url('uploads/profile/' . $convo['client_avatar']);
                                                    }
                                                }
                                            } else {
                                                $avatarUrl = "https://ui-avatars.com/api/?name=" . urlencode($convo['client_name'] ?? 'User') . "&background=random&color=fff";
                                            }
                                        ?>
                                        <li class="d-flex chat-list-user p-3 align-items-center border-bottom" 
                                             data-id="<?= $convo['id'] ?>" 
                                             data-name="<?= esc($convo['client_name']) ?>"
                                             data-type="<?= esc($convo['client_type']) ?>"
                                             data-avatar="<?= esc($convo['client_avatar'] ?? '') ?>"
                                             data-status="<?= esc($convo['status'] ?? 'open') ?>"
                                             data-title="<?= esc($convo['title'] ?? 'Obrolan') ?>"
                                             data-category="<?= esc($cat) ?>">
                                            <img class="me-3 rounded-circle border shadow-sm" width="48" height="48" 
                                                 src="<?= $avatarUrl ?>" alt="avatar">
                                            <div class="flex-grow-1" style="overflow: hidden;">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <h6 class="mt-0 mb-0 fw-bold text-truncate text-dark chat-client-name" style="font-size: 0.92rem; max-width: 65%;">
                                                        <?= esc($convo['client_name']) ?>
                                                        <?php if (($convo['status'] ?? 'open') === 'closed') : ?>
                                                            <i class="fas fa-lock text-danger ms-1" style="font-size: 0.75rem;" title="Tertutup"></i>
                                                        <?php endif; ?>
                                                    </h6>
                                                    <span class="text-small text-muted chat-item-time" style="font-size: 0.72rem; font-weight: 500;"><?= $timeStr ?></span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <p class="text-small text-muted text-truncate mb-0 chat-item-preview" style="max-width: 70%; font-size: 0.8rem;"><?= $lastMsg ?></p>
                                                    <div class="d-flex align-items-center" style="gap: 4px;">
                                                        <?php $unreadCount = intval($convo['unread_by_admin_count'] ?? 0); ?>
                                                        <span class="unread-badge me-2" id="unread-badge-<?= $convo['id'] ?>" <?= $unreadCount > 0 ? '' : 'style="display: none;"' ?>><?= $unreadCount ?></span>
                                                        <span class="badge <?= $catBadgeClass ?> px-2 py-1 text-uppercase" style="font-size: 0.58rem; letter-spacing: 0.5px; border-radius: 4px;"><?= $catText ?></span>
                                                        <span class="badge <?= $badgeClass ?> px-2 py-1 text-uppercase" style="font-size: 0.58rem; letter-spacing: 0.5px; border-radius: 4px;"><?= $badgeText ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <li class="text-center p-5 text-muted">
                                        <i class="fas fa-inbox fa-2x mb-3 text-light"></i>
                                        <p class="mb-0">Belum ada percakapan masuk.</p>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>

                    <!-- Panel Kanan: Isi Chat Box -->
                    <div class="col-md-8 col-12 px-0">
                        <div class="pasangin-chat-box">
                            <!-- Header Chat Box -->
                            <div class="pasangin-chat-header d-flex justify-content-between align-items-center shadow-sm" id="header-chat-box" style="display:none;">
                                <div class="d-flex align-items-center">
                                    <!-- Tombol Kembali untuk Mobile -->
                                    <button type="button" class="btn btn-sm btn-light me-3 d-none" id="btn-back-to-list" style="border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; border: 1px solid #e2e8f0; color: #64748b;">
                                        <i class="fas fa-arrow-left"></i>
                                    </button>
                                    <div id="active-chat-avatar" class="me-2">
                                        <!-- Diisi dinamis via JS -->
                                    </div>
                                    <div>
                                        <h5 id="chat-with-name" class="fw-bold mb-0" style="font-size: 1.05rem; color: #1e293b;">Nama Client</h5>
                                        <div class="d-flex align-items-center mt-1">
                                            <span class="badge text-uppercase text-white" id="chat-with-role" style="font-size: 0.58rem; border-radius: 4px; padding: 2px 8px; background-color: #6366f1;">Role</span>
                                            <span class="ms-1 ms-2 d-inline-block" style="width: 8px; height: 8px; border-radius: 50%; background-color: #10b981;"></span>
                                            <small class="text-muted ms-1" style="font-size: 0.75rem;margin-left: 2px;">Online</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <!-- Detail Keluhan Button -->
                                    <button type="button" class="btn btn-sm btn-outline-primary px-3 me-2 d-flex align-items-center" id="btn-toggle-report" style="border-radius: 30px; font-weight: 600; font-size: 0.75rem; gap: 5px; box-shadow: none;">
                                        <i class="fas fa-file-alt"></i> Detail Keluhan
                                    </button>
                                    <div class="d-flex align-items-center" id="chat-status-action-wrapper">
                                        <!-- Diisi dinamis via JS -->
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Detail Keluhan Collapsible Panel -->
                            <div id="report-detail-collapse" class="bg-white border-bottom p-3" style="display: none; border-left: 4px solid #6366f1;">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="fw-bold text-dark mb-0" style="font-size: 0.85rem;"><i class="fas fa-file-alt text-primary me-2"></i>Detail Keluhan / Laporan User</h6>
                                    <button type="button" class="btn-close" id="btn-close-report" style="font-size: 0.75rem; background: transparent; border: none; font-weight: bold; color: #64748b;" title="Tutup Detail">&times;</button>
                                </div>
                                <div class="bg-light p-3 rounded-3 border" style="font-size: 0.82rem; line-height: 1.5; color: #475569; white-space: pre-wrap; max-height: 150px; overflow-y: auto;" id="report-detail-text">
                                    <!-- Diisi dinamis via JS -->
                                </div>
                            </div>
                            
                            <!-- Isi Percakapan -->
                            <div class="pasangin-chat-content" id="chat-messages">
                                <div class="text-center my-auto px-4" id="chat-placeholder">
                                    <div class="empty-state-illustration mb-4">
                                        <svg width="150" height="150" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg" class="floating-illustration">
                                            <defs>
                                                <linearGradient id="bubbleGrad1" x1="0" y1="0" x2="1" y2="1">
                                                    <stop offset="0%" stop-color="#818cf8" />
                                                    <stop offset="100%" stop-color="#4f46e5" />
                                                </linearGradient>
                                                <linearGradient id="bubbleGrad2" x1="0" y1="0" x2="1" y2="1">
                                                    <stop offset="0%" stop-color="#38bdf8" />
                                                    <stop offset="100%" stop-color="#0284c7" />
                                                </linearGradient>
                                                <filter id="softShadow" x="-10%" y="-10%" width="120%" height="120%">
                                                    <feDropShadow dx="0" dy="8" stdDeviation="6" flood-color="#4f46e5" flood-opacity="0.15" />
                                                </filter>
                                            </defs>
                                            <circle cx="100" cy="100" r="70" fill="#f1f5f9" />
                                            <circle cx="50" cy="70" r="8" fill="#e2e8f0" />
                                            <circle cx="150" cy="130" r="12" fill="#e2e8f0" />
                                            <rect x="60" y="60" width="80" height="60" rx="16" fill="url(#bubbleGrad1)" filter="url(#softShadow)" />
                                            <path d="M75 120 L75 132 L90 120 Z" fill="url(#bubbleGrad1)" />
                                            <line x1="75" y1="80" x2="125" y2="80" stroke="#ffffff" stroke-width="3" stroke-linecap="round" />
                                            <line x1="75" y1="92" x2="115" y2="92" stroke="#ffffff" stroke-width="3" stroke-linecap="round" />
                                            <line x1="75" y1="104" x2="100" y2="104" stroke="#ffffff" stroke-width="3" stroke-linecap="round" opacity="0.7" />
                                            <rect x="110" y="100" width="50" height="40" rx="10" fill="url(#bubbleGrad2)" filter="url(#softShadow)" />
                                            <path d="M145 140 L145 148 L135 140 Z" fill="url(#bubbleGrad2)" />
                                            <line x1="120" y1="115" x2="150" y2="115" stroke="#ffffff" stroke-width="2" stroke-linecap="round" />
                                            <line x1="120" y1="125" x2="140" y2="125" stroke="#ffffff" stroke-width="2" stroke-linecap="round" />
                                        </svg>
                                    </div>
                                    <h5 class="text-dark fw-bold mb-2" style="font-size: 1.2rem;">Mulai Obrolan Real-time</h5>
                                    <p class="text-muted" style="max-width: 340px; margin: 0 auto; font-size: 0.88rem; line-height: 1.6;">Pilih salah satu percakapan Klien atau Tukang di panel kiri untuk melihat riwayat pesan dan membalas secara langsung.</p>
                                </div>
                            </div>

                            <!-- Footer Form Input Chat -->
                            <div class="pasangin-chat-footer" style="display: none;">
                                <!-- Banner Pemberitahuan Chat Ditutup -->
                                <div id="chat-closed-notice" class="text-center py-2 text-muted" style="display: none; font-size: 0.9rem; font-weight: 500;">
                                    <i class="fas fa-info-circle text-danger me-1"></i> Obrolan ini telah ditutup. <a href="javascript:void(0)" id="link-reopen-chat" class="fw-bold text-primary text-decoration-none">Buka Kembali</a> untuk mengirim pesan.
                                </div>

                                <form id="message-form" enctype="multipart/form-data">
                                    <!-- Preview file terpilih -->
                                    <div id="attachment-preview-container" style="display: none;">
                                        <div class="d-flex align-items-center text-truncate" style="max-width: 80%;">
                                            <i class="fas fa-file-alt text-primary fa-lg me-2" id="attachment-preview-icon"></i>
                                            <span class="text-truncate text-small fw-bold text-dark" id="attachment-preview-name" style="font-size: 0.85rem;">Nama_File.jpg</span>
                                        </div>
                                        <button type="button" id="clear-attachment-btn" class="btn btn-close text-danger p-0 ms-2" style="border: none; background: transparent;" title="Batalkan Lampiran">
                                            <i class="fas fa-times-circle"></i>
                                        </button>
                                    </div>

                                    <div class="input-wrapper">
                                        <!-- Tombol Attachment -->
                                        <button type="button" id="attachment-btn" title="Kirim Berkas (Gambar/Video/Dokumen)">
                                            <i class="fas fa-paperclip"></i>
                                        </button>
                                        <input type="file" id="attachment-file-input" name="file" style="display: none;">

                                        <input type="text" id="message-input" class="form-control" placeholder="Tulis pesan Anda di sini..." autocomplete="off">
                                        <button type="submit" id="send-btn" class="btn btn-primary btn-send">
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Lightbox Overlay untuk melihat gambar ukuran penuh -->
<div class="chat-lightbox-overlay" id="chat-lightbox">
    <span class="chat-lightbox-close" id="chat-lightbox-close">&times;</span>
    <div class="chat-lightbox-image-wrapper" id="chat-lightbox-wrapper">
        <img src="" alt="Preview Gambar" id="chat-lightbox-img">
        <video src="" id="chat-lightbox-video" controls class="shadow-lg" style="display:none; max-width:90%; max-height:80vh; border-radius:8px; outline:none;"></video>
    </div>
    <!-- Floating zoom controls -->
    <div class="chat-lightbox-controls">
        <button type="button" id="zoom-out-btn" title="Zoom Out"><i class="fas fa-search-minus"></i></button>
        <button type="button" id="zoom-reset-btn" title="Reset Zoom">1:1</button>
        <button type="button" id="zoom-in-btn" title="Zoom In"><i class="fas fa-search-plus"></i></button>
        <button type="button" id="zoom-rotate-btn" title="Putar Gambar"><i class="fas fa-sync-alt"></i></button>
    </div>
</div>
<?php
echo $this->endSection();

echo $this->section('script');
?>
<script>
$(document).ready(function() {
    let activeConversationId = null;
    let activeClientName = "";
    let activeClientAvatar = "";
    let activeClientRole = "";
    let activeConversationStatus = "open";
    
    // CSRF Token dynamic handling
    let csrfName = '<?= csrf_token() ?>';
    let csrfHash = '<?= csrf_hash() ?>';
    
    // Variabel filter pencarian & kategori
    let searchQuery = "";
    let activeFilter = "all";
    let activeCatFilter = "all";

    function scrollToBottom() {
        const container = $("#chat-messages");
        container.animate({ scrollTop: container[0].scrollHeight }, 200);
    }

    // Fungsi untuk memperbarui jumlah total pesan belum dibaca di bagian atas sidebar
    function updateTotalUnreadBadge() {
        let total = 0;
        $('.unread-badge').each(function() {
            const val = parseInt($(this).text()) || 0;
            total += val;
        });
        
        const totalBadge = $('#total-unread-badge');
        if (total > 0) {
            totalBadge.text(total + ' Belum Dibaca').show();
        } else {
            totalBadge.hide();
        }
    }
    
    // Escape HTML Helper (Keamanan XSS)
    function escapeHtml(text) {
        if (!text) return "";
        return text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    // Helper function to get correct user avatar URL
    function getAvatarUrl(avatar, type, name) {
        if (avatar && avatar.trim() !== '') {
            if (avatar.startsWith('http://') || avatar.startsWith('https://')) {
                return avatar;
            }
            if (type === 'tukang') {
                return `<?= base_url('uploads/tukang') ?>/` + avatar;
            } else {
                return `<?= base_url('uploads/profile') ?>/` + avatar;
            }
        }
        return `https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&background=random&color=fff`;
    }

    // Format body pesan (Newline support)
    function formatMessageBody(body) {
        return escapeHtml(body).replace(/\n/g, '<br>');
    }

    // Format Jam & Menit (HH:MM)
    function formatTime(dateStr) {
        try {
            const d = new Date(dateStr.replace(' ', 'T'));
            const hours = ('0' + d.getHours()).slice(-2);
            const minutes = ('0' + d.getMinutes()).slice(-2);
            return `${hours}:${minutes}`;
        } catch (e) {
            return dateStr;
        }
    }

    // Format Pembatas Tanggal
    function formatDateSeparator(dateStr) {
        try {
            const messageDate = new Date(dateStr.replace(' ', 'T'));
            const today = new Date();
            const yesterday = new Date();
            yesterday.setDate(today.getDate() - 1);

            const mDateString = messageDate.toDateString();
            const todayString = today.toDateString();
            const yesterdayString = yesterday.toDateString();

            if (mDateString === todayString) {
                return 'Hari ini';
            } else if (mDateString === yesterdayString) {
                return 'Kemarin';
            } else {
                const options = { day: 'numeric', month: 'long', year: 'numeric' };
                return messageDate.toLocaleDateString('id-ID', options);
            }
        } catch (e) {
            return dateStr.split(' ')[0];
        }
    }

    let hasUserGesture = false;
    ['click', 'mousedown', 'keydown', 'touchstart'].forEach(function(eventName) {
        document.addEventListener(eventName, function() {
            hasUserGesture = true;
        }, { once: true });
    });

    // Synthesize Chime Sound via Web Audio API
    function playNotificationSound() {
        if (!hasUserGesture) {
            console.log('Audio playback skipped: waiting for user gesture.');
            return;
        }
        try {
            const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            if (audioCtx.state === 'suspended') {
                audioCtx.resume();
            }
            const now = audioCtx.currentTime;
            
            // Note 1: G5 (783.99 Hz) - Warm Bell tone
            const osc1 = audioCtx.createOscillator();
            const gain1 = audioCtx.createGain();
            osc1.type = 'triangle';
            osc1.frequency.setValueAtTime(783.99, now);
            gain1.gain.setValueAtTime(0.3, now);
            gain1.gain.exponentialRampToValueAtTime(0.001, now + 0.3);
            osc1.connect(gain1);
            gain1.connect(audioCtx.destination);
            osc1.start(now);
            osc1.stop(now + 0.3);
            
            // Note 2: C6 (1046.50 Hz)
            const osc2 = audioCtx.createOscillator();
            const gain2 = audioCtx.createGain();
            osc2.type = 'triangle';
            osc2.frequency.setValueAtTime(1046.50, now + 0.08);
            gain2.gain.setValueAtTime(0.3, now + 0.08);
            gain2.gain.exponentialRampToValueAtTime(0.001, now + 0.38);
            osc2.connect(gain2);
            gain2.connect(audioCtx.destination);
            osc2.start(now + 0.08);
            osc2.stop(now + 0.38);

            // Note 3: E6 (1318.51 Hz)
            const osc3 = audioCtx.createOscillator();
            const gain3 = audioCtx.createGain();
            osc3.type = 'triangle';
            osc3.frequency.setValueAtTime(1318.51, now + 0.16);
            gain3.gain.setValueAtTime(0.3, now + 0.16);
            gain3.gain.exponentialRampToValueAtTime(0.001, now + 0.5);
            osc3.connect(gain3);
            gain3.connect(audioCtx.destination);
            osc3.start(now + 0.16);
            osc3.stop(now + 0.5);
        } catch (e) {
            console.log('Audio Autoplay diblokir/tidak didukung sebelum ada interaksi.');
        }
    }

    // Penyaringan Percakapan di Sidebar
    function filterConversations() {
        const listItems = $('.chat-list-user');
        let visibleCount = 0;
        
        listItems.each(function() {
            const item = $(this);
            const name = (item.attr('data-name') || '').toLowerCase();
            const type = item.attr('data-type'); // 'client' atau 'tukang'
            const category = item.attr('data-category') || 'general';
            
            const matchesSearch = name.includes(searchQuery);
            const matchesFilter = (activeFilter === 'all' || type === activeFilter);
            const matchesCatFilter = (activeCatFilter === 'all' || category === activeCatFilter);
            
            if (matchesSearch && matchesFilter && matchesCatFilter) {
                item.addClass('d-flex').removeClass('d-none');
                visibleCount++;
            } else {
                item.removeClass('d-flex').addClass('d-none');
            }
        });
        
        // Handle empty state di list sidebar
        const existingNoResults = $('#no-search-results');
        if (visibleCount === 0 && listItems.length > 0) {
            if (existingNoResults.length === 0) {
                $('#chat-list ul').append(`
                    <li id="no-search-results" class="text-center p-4 text-muted" style="font-size: 0.85rem;">
                        <i class="fas fa-search mb-2 opacity-5" style="font-size: 1.5rem;"></i>
                        <p class="mb-0">Tidak ada obrolan yang cocok.</p>
                    </li>
                `);
            }
        } else {
            existingNoResults.remove();
        }
    }

    function showLoader() {
        const loaderHtml = `
            <div class="chat-loading-spinner my-auto text-center">
                <div class="spinner-border text-primary" role="status" style="width: 2.2rem; height: 2.2rem; border-width: 0.2em;">
                    <span class="sr-only">Loading...</span>
                </div>
                <p class="text-muted mt-2 mb-0" style="font-size: 0.82rem; font-weight: 500;">Memuat pesan...</p>
            </div>`;
        $('#chat-messages').html(loaderHtml);
    }

    function loadMessages(conversationId) {
        if (!conversationId) return;
        const url = `<?= site_url('admin/api/chat/') ?>${conversationId}/messages`;

        $.ajax({
            url: url,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === true) {
                    let chatHtml = '';
                    if (response.data && response.data.length > 0) {
                        let lastDate = null;
                        
                        response.data.forEach(function(msg) {
                            const is_admin = (msg.sender_type === 'admin');
                            const alignClass = is_admin ? 'pasangin-chat-right' : 'pasangin-chat-left';
                            
                            // Logika Pengelompokan Tanggal
                            const msgDateStr = msg.created_at.split(' ')[0]; // YYYY-MM-DD
                            if (msgDateStr !== lastDate) {
                                lastDate = msgDateStr;
                                const displayDate = formatDateSeparator(msg.created_at);
                                chatHtml += `
                                    <div class="chat-date-separator">
                                        <span class="chat-date-pill">${displayDate}</span>
                                    </div>`;
                            }
                            
                            const avatarChar = is_admin ? 'AD' : (activeClientName ? activeClientName.substring(0, 2).toUpperCase() : 'CL');
                            const avatarColor = is_admin ? '6366f1' : 'f59e0b';

                            const formattedTime = formatTime(msg.created_at);
                            const formattedBody = formatMessageBody(msg.body);

                            // Tick indicator for admin messages (WhatsApp style)
                            let tickHtml = '';
                            if (is_admin) {
                                if (msg.is_read_by_client == 1) {
                                    tickHtml = `<i class="fas fa-check-double ms-1" style="color: #a5b4fc; font-size: 10px;" title="Dibaca oleh client"></i>`;
                                } else {
                                    tickHtml = `<i class="fas fa-check ms-1" style="color: #c7d2fe; font-size: 10px;" title="Terkirim"></i>`;
                                }
                            }

                            // Render message body based on message type
                            let contentHtml = '';
                            if (msg.message_type === 'image') {
                                contentHtml = `
                                    <div class="mb-1">
                                        <img src="<?= base_url() ?>/${msg.file_url}" class="chat-media-preview" alt="Gambar" onclick="openChatLightbox(this.src, 'image')">
                                    </div>`;
                                if (msg.body && msg.body.trim() !== '') {
                                    contentHtml += `<div>${formattedBody}</div>`;
                                }
                            } else if (msg.message_type === 'video') {
                                contentHtml = `
                                    <div class="mb-1">
                                        <div class="chat-media-preview-container" onclick="openChatLightbox(this.querySelector('video').src, 'video')">
                                            <video src="<?= base_url() ?>/${msg.file_url}" class="chat-media-preview"></video>
                                            <div class="play-button-overlay">
                                                <i class="fas fa-play"></i>
                                            </div>
                                        </div>
                                    </div>`;
                                if (msg.body && msg.body.trim() !== '') {
                                    contentHtml += `<div>${formattedBody}</div>`;
                                }
                            } else if (msg.message_type === 'file') {
                                contentHtml = `
                                    <div class="mb-1">
                                        <a href="<?= base_url() ?>/${msg.file_url}" target="_blank" class="d-flex align-items-center text-decoration-none p-2 rounded bg-light border text-dark" style="font-size: 0.85rem; max-width: 250px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                                            <i class="fas fa-file-download fa-lg me-2 text-primary" style="font-size: 1.25rem;"></i>
                                            <span class="text-truncate ms-1" style="max-width: 180px; font-weight: 500;">${escapeHtml(msg.body) || 'Unduh Berkas'}</span>
                                        </a>
                                    </div>`;
                            } else if (msg.message_type === 'location') {
                                contentHtml = `
                                    <div class="mb-1">
                                        <a href="https://www.google.com/maps?q=${msg.latitude},${msg.longitude}" target="_blank" class="d-flex align-items-center text-decoration-none p-2 rounded bg-light border text-dark" style="font-size: 0.85rem; max-width: 250px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                                            <i class="fas fa-map-marker-alt fa-lg me-2 text-danger" style="font-size: 1.25rem;"></i>
                                            <span class="ms-1" style="font-weight: 500;">Buka Lokasi (Google Maps)</span>
                                        </a>
                                    </div>`;
                                if (msg.body && msg.body.trim() !== '') {
                                    contentHtml += `<div>${formattedBody}</div>`;
                                }
                            } else {
                                contentHtml = `<div>${formattedBody}</div>`;
                            }

                            let bubbleAvatarUrl = "";
                            if (is_admin) {
                                bubbleAvatarUrl = "https://ui-avatars.com/api/?name=AD&background=6366f1&color=fff";
                            } else {
                                bubbleAvatarUrl = getAvatarUrl(activeClientAvatar, activeClientRole, activeClientName);
                            }

                            chatHtml += `
                                <div class="pasangin-chat-item ${alignClass}">
                                    <div class="pasangin-chat-avatar">
                                        <img src="${bubbleAvatarUrl}" alt="avatar">
                                    </div>
                                    <div class="pasangin-chat-item-body">
                                        <div class="pasangin-chat-text">${contentHtml}</div>
                                        <small class="pasangin-chat-time">${formattedTime} ${tickHtml}</small>
                                    </div>
                                </div>`;
                        });
                        $('#chat-messages').html(chatHtml);
                    } else {
                        $('#chat-messages').html('<div class="text-center my-auto text-muted">Belum ada riwayat pesan.</div>');
                    }
                    scrollToBottom();
                }
            }
        });
    }

    // Klik List User
    $('body').on('click', '.chat-list-user', function() {
        $('.chat-card-container').addClass('mobile-chat-active');

        const conversationId = $(this).data('id');
        const clientName = $(this).data('name');
        const clientRole = $(this).data('type');
        const clientAvatar = $(this).data('avatar') || '';
        const clientStatus = $(this).data('status') || 'open';

        activeConversationId = conversationId;
        activeClientName = clientName;
        activeClientAvatar = clientAvatar;
        activeClientRole = clientRole;
        activeConversationStatus = clientStatus;

        $('.chat-list-user').removeClass('active');
        $(this).addClass('active');

        // Reset Unread Badge di UI
        const badge = $(`#unread-badge-${conversationId}`);
        badge.text('0').hide();
        updateTotalUnreadBadge();

        $('#chat-placeholder').hide();
        $('#header-chat-box').show();
        $('.pasangin-chat-footer').show();
        updateChatStatusUI(activeConversationStatus);

        // Update Info Header Chat
        $('#chat-with-name').text(clientName);
        $('#chat-with-role').text(clientRole === 'tukang' ? 'Tukang' : 'Klien');
        
        // Atur badge warna role
        if (clientRole === 'tukang') {
            $('#chat-with-role').removeClass('bg-info').addClass('bg-warning text-dark');
        } else {
            $('#chat-with-role').removeClass('bg-warning text-dark').addClass('bg-info text-white');
        }

        // Set avatar di header chat
        const avatarUrl = getAvatarUrl(activeClientAvatar, activeClientRole, clientName);
        $('#active-chat-avatar').html(`<img src="${avatarUrl}" class="rounded-circle border shadow-sm" width="40" height="40" alt="avatar">`);

        // Update detail keluhan rincian text
        const clientTitle = $(this).attr('data-title') || 'Obrolan';
        $('#report-detail-text').text(clientTitle);
        $('#report-detail-collapse').hide(); // Tutup panel secara default saat berganti user

        // Tampilkan loading skeleton sebelum AJAX memuat data
        showLoader();
        loadMessages(conversationId);
    });

    // Toggle Rincian Detail Keluhan
    $('body').on('click', '#btn-toggle-report', function(e) {
        e.preventDefault();
        $('#report-detail-collapse').slideToggle(200);
    });

    // Close Rincian Detail Keluhan
    $('body').on('click', '#btn-close-report', function(e) {
        e.preventDefault();
        $('#report-detail-collapse').slideUp(200);
    });

    // Tombol Kembali di Mobile
    $('body').on('click', '#btn-back-to-list', function() {
        $('.chat-card-container').removeClass('mobile-chat-active');
        activeConversationId = null;
        $('.chat-list-user').removeClass('active');
    });

    function updateChatStatusUI(status) {
        if (status === 'closed') {
            // Ubah badge di header: Merah (bg-danger / text-danger)
            $('#chat-status-action-wrapper').html(`
                <span class="badge px-3 py-2 text-danger me-2 d-flex align-items-center" style="font-weight: 600; font-size: 0.75rem; background-color: rgba(239, 68, 68, 0.08); border: 1px solid rgba(239, 68, 68, 0.15); border-radius: 30px;">
                    <span class="me-2 d-inline-block" style="width: 7px; height: 7px; border-radius: 50%; background-color: #ef4444;"></span> 
                    Closed
                </span>
                <button type="button" class="btn btn-sm btn-success text-white px-3" id="btn-toggle-chat-status" data-status="open" style="border-radius: 30px; font-weight: 600; font-size: 0.75rem;">
                    <i class="fas fa-lock-open me-1"></i> Buka Kembali
                </button>
            `);
            
            // Sembunyikan form input, tampilkan banner closed notice
            $('#message-form').hide();
            $('#chat-closed-notice').show();
        } else {
            // Status open: Hijau (bg-success / text-success)
            $('#chat-status-action-wrapper').html(`
                <span class="badge px-3 py-2 text-success me-2 d-flex align-items-center" style="font-weight: 600; font-size: 0.75rem; background-color: rgba(16, 185, 129, 0.08); border: 1px solid rgba(16, 185, 129, 0.15); border-radius: 30px;">
                    <span class="me-2 d-inline-block" style="width: 7px; height: 7px; border-radius: 50%; background-color: #10b981;"></span> 
                    Open
                </span>
                <button type="button" class="btn btn-sm btn-outline-danger px-3" id="btn-toggle-chat-status" data-status="closed" style="border-radius: 30px; font-weight: 600; font-size: 0.75rem;">
                    <i class="fas fa-lock me-1"></i> Tutup Obrolan
                </button>
            `);
            
            // Tampilkan form input, sembunyikan banner closed notice
            $('#chat-closed-notice').hide();
            $('#message-form').show();
        }
    }

    // AJAX to change status
    function changeConversationStatus(newStatus) {
        if (!activeConversationId) return;
        const url = `<?= site_url('admin/api/chat/') ?>${activeConversationId}/status`;
        
        $.ajax({
            url: url,
            method: 'POST',
            data: {
                status: newStatus,
                [csrfName]: csrfHash
            },
            dataType: 'json',
            success: function(response) {
                if (response.csrf_hash) {
                    csrfHash = response.csrf_hash;
                }
                if (response.status === true) {
                    activeConversationStatus = newStatus;
                    
                    // Update data-status attribute in the sidebar list item
                    const activeItem = $(`.chat-list-user[data-id="${activeConversationId}"]`);
                    if (activeItem.length > 0) {
                        activeItem.attr('data-status', newStatus);
                        activeItem.data('status', newStatus);
                    }
                    
                    // Update UI state
                    updateChatStatusUI(newStatus);
                    
                    // Reload conversations list to sync sidebar status icons
                    loadConversations();
                    
                    if (typeof iziToast !== 'undefined') {
                        iziToast.success({
                            title: 'Sukses',
                            message: response.message,
                            position: 'topCenter',
                            timeout: 2500
                        });
                    }
                } else {
                    if (typeof iziToast !== 'undefined') {
                        iziToast.error({
                            title: 'Gagal',
                            message: response.message || 'Gagal mengubah status obrolan.',
                            position: 'topCenter'
                        });
                    } else {
                        alert(response.message || 'Gagal mengubah status obrolan.');
                    }
                }
            },
            error: function(xhr) {
                if (xhr.responseJSON && xhr.responseJSON.csrf_hash) {
                    csrfHash = xhr.responseJSON.csrf_hash;
                }
                const msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Terjadi kesalahan sistem.';
                if (typeof iziToast !== 'undefined') {
                    iziToast.error({
                        title: 'Gagal',
                        message: msg,
                        position: 'topCenter'
                    });
                } else {
                    alert(msg);
                }
            }
        });
    }

    // Toggle Button Click
    $('body').on('click', '#btn-toggle-chat-status', function(e) {
        e.preventDefault();
        const nextStatus = $(this).attr('data-status');
        changeConversationStatus(nextStatus);
    });

    // Reopen Link Click (from notice banner)
    $('body').on('click', '#link-reopen-chat', function(e) {
        e.preventDefault();
        changeConversationStatus('open');
    });

    // Trigger file input click when paperclip is clicked
    $('#attachment-btn').on('click', function() {
        $('#attachment-file-input').click();
    });

    // Handle file selection change and size validation
    $('#attachment-file-input').on('change', function() {
        const fileInput = this;
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            const name = file.name;
            const size = file.size; // bytes
            
            // Limit file size to 20MB
            if (size > 20 * 1024 * 1024) {
                if (typeof iziToast !== 'undefined') {
                    iziToast.error({ title: 'Gagal', message: 'Ukuran berkas terlalu besar (maksimal 20MB)', position: 'topCenter' });
                } else {
                    alert('Ukuran berkas terlalu besar (maksimal 20MB)');
                }
                $(this).val('');
                $('#attachment-preview-container').hide();
                return;
            }

            $('#attachment-preview-name').text(name);
            
            // Choose preview icon based on type
            const icon = $('#attachment-preview-icon');
            icon.removeClass();
            if (file.type.startsWith('image/')) {
                icon.addClass('fas fa-file-image text-success fa-lg me-2');
            } else if (file.type.startsWith('video/')) {
                icon.addClass('fas fa-file-video text-warning fa-lg me-2');
            } else {
                icon.addClass('fas fa-file-alt text-primary fa-lg me-2');
            }
            
            $('#attachment-preview-container').css('display', 'flex');
        } else {
            $('#attachment-preview-container').hide();
        }
    });

    // Cancel/clear selected attachment
    $('#clear-attachment-btn').on('click', function() {
        $('#attachment-file-input').val('');
        $('#attachment-preview-container').hide();
    });

    // Handle Form Submit (Kirim Pesan & Lampiran dengan Optimistic UI)
    $('#message-form').on('submit', function(e) {
        e.preventDefault();
        const input = $('#message-input');
        const text = input.val().trim();
        
        const fileInput = $('#attachment-file-input')[0];
        const hasFile = fileInput && fileInput.files.length > 0;

        if ((text === '' && !hasFile) || !activeConversationId) return;

        // 1. Bersihkan input teks secara instan
        input.val('');
        const chatMessages = $('#chat-messages');
        const emptyState = chatMessages.find('.text-center.my-auto');
        if (emptyState.length > 0) {
            emptyState.remove();
        }

        // 2. Periksa pembatas tanggal "Hari ini"
        const dateSeparators = $('.chat-date-pill');
        let hasToday = false;
        if (dateSeparators.length > 0) {
            const lastPillText = dateSeparators.last().text().trim().toUpperCase();
            if (lastPillText === 'HARI INI') {
                hasToday = true;
            }
        }
        if (!hasToday) {
            chatMessages.append(`
                <div class="chat-date-separator">
                    <span class="chat-date-pill">Hari ini</span>
                </div>
            `);
        }

        // 3. Sisipkan balon chat baru dengan status sending (Optimistic UI)
        const tempId = 'temp-' + Date.now();
        const avatarChar = 'AD';
        const avatarColor = '6366f1';
        
        const now = new Date();
        const formattedTime = ('0' + now.getHours()).slice(-2) + ':' + ('0' + now.getMinutes()).slice(-2);
        const formattedBody = formatMessageBody(text);

        // Render content optimistically berdasarkan tipe file
        let optimisticContentHtml = `<div>${formattedBody}</div>`;
        let previewText = text;
        let objectUrlToRevoke = null;

        if (hasFile) {
            const file = fileInput.files[0];
            if (file.type.startsWith('image/')) {
                previewText = '📷 ' + (text || 'Gambar');
                optimisticContentHtml = `
                    <div class="mb-1">
                        <img id="optimistic-img-${tempId}" class="chat-media-preview" onclick="openChatLightbox(this.src, 'image')">
                    </div>`;
                if (text !== '') {
                    optimisticContentHtml += `<div>${formattedBody}</div>`;
                }
 
                // Render image preview lokal instan menggunakan Object URL
                const objectUrl = URL.createObjectURL(file);
                objectUrlToRevoke = objectUrl;
                setTimeout(() => {
                    const imgEl = $(`#optimistic-img-${tempId}`);
                    imgEl.attr('src', objectUrl);
                }, 50);
            } else if (file.type.startsWith('video/')) {
                previewText = '🎥 ' + (text || 'Video');
                optimisticContentHtml = `
                    <div class="mb-1">
                        <div class="chat-media-preview-container" onclick="openChatLightbox(this.querySelector('video').src, 'video')">
                            <video id="optimistic-video-${tempId}" class="chat-media-preview"></video>
                            <div class="play-button-overlay">
                                <i class="fas fa-play"></i>
                            </div>
                        </div>
                    </div>`;
                if (text !== '') {
                    optimisticContentHtml += `<div>${formattedBody}</div>`;
                }

                // Render video preview lokal instan menggunakan Object URL
                const objectUrl = URL.createObjectURL(file);
                objectUrlToRevoke = objectUrl;
                setTimeout(() => {
                    const videoEl = $(`#optimistic-video-${tempId}`);
                    videoEl.attr('src', objectUrl);
                }, 50);
            } else {
                previewText = '📁 ' + (text || file.name);
                optimisticContentHtml = `
                    <div class="mb-1">
                        <div class="d-flex align-items-center text-decoration-none p-2 rounded bg-light border text-dark" style="font-size: 0.85rem; max-width: 250px;">
                            <i class="fas fa-file me-2 text-primary"></i>
                            <span class="text-truncate" style="max-width: 180px; font-weight: 500;">${escapeHtml(file.name)}</span>
                        </div>
                    </div>`;
                if (text !== '') {
                    optimisticContentHtml += `<div>${formattedBody}</div>`;
                }
            }
        }

        const tempBubbleHtml = `
            <div class="pasangin-chat-item pasangin-chat-right" id="msg-${tempId}" style="opacity: 0.75; transition: opacity 0.3s ease;">
                <div class="pasangin-chat-avatar">
                    <img src="https://ui-avatars.com/api/?name=${avatarChar}&background=${avatarColor}&color=fff" alt="avatar">
                </div>
                <div class="pasangin-chat-item-body">
                    <div class="pasangin-chat-text">${optimisticContentHtml}</div>
                    <small class="pasangin-chat-time">
                        ${formattedTime}
                        <span class="status-indicator-wrapper" id="indicator-${tempId}">
                            <i class="fas fa-spinner fa-spin ms-1 text-muted" style="font-size: 9px;" title="Mengirim..."></i>
                        </span>
                    </small>
                </div>
            </div>`;
        
        chatMessages.append(tempBubbleHtml);
        scrollToBottom();

        // 4. Perbarui preview dan posisi percakapan di sidebar secara instan
        const activeItem = $(`.chat-list-user[data-id="${activeConversationId}"]`);
        if (activeItem.length > 0) {
            activeItem.find('.chat-item-preview').text(previewText);
            activeItem.find('.chat-item-time').text(formattedTime);
            
            // Pindahkan kontak aktif ke baris paling atas
            const listContainer = $('#chat-list ul');
            activeItem.prependTo(listContainer);
        }

        // 5. Siapkan pengiriman data (AJAX FormData jika ada file)
        let ajaxData;
        let ajaxProcessData = true;
        let ajaxContentType = 'application/x-www-form-urlencoded; charset=UTF-8';

        if (hasFile) {
            ajaxData = new FormData();
            ajaxData.append('conversation_id', activeConversationId);
            ajaxData.append('message', text);
            ajaxData.append('file', fileInput.files[0]);
            ajaxData.append(csrfName, csrfHash);
            
            ajaxProcessData = false;
            ajaxContentType = false;
        } else {
            ajaxData = {
                'conversation_id': activeConversationId,
                'message': text
            };
            ajaxData[csrfName] = csrfHash;
        }

        // Bersihkan input lampiran di form secara instan setelah data ditampung
        $('#attachment-file-input').val('');
        $('#attachment-preview-container').hide();

        // 6. Jalankan pengiriman AJAX di latar belakang
        $.ajax({
            url: '<?= site_url('admin/api/chat/send') ?>',
            method: 'POST',
            data: ajaxData,
            processData: ajaxProcessData,
            contentType: ajaxContentType,
            success: function(response) {
                // Update CSRF token
                if (response.csrf_hash) {
                    csrfHash = response.csrf_hash;
                }
                const bubble = $(`#msg-${tempId}`);
                const indicator = $(`#indicator-${tempId}`);
                if (response.status === true) {
                    // Update balon chat menjadi "Sukses Terkirim" (centang satu abu-abu)
                    bubble.css('opacity', '1');
                    indicator.html(`<i class="fas fa-check ms-1" style="color: #c7d2fe; font-size: 10px;" title="Terkirim"></i>`);
                    
                    // Reload daftar sidebar agar urutan dan info ter-update secara akurat dari DB
                    loadConversations();
                    // Reload pesan agar balon chat sinkron dengan database (menggunakan url file asli dari server)
                    loadMessages(activeConversationId);
                } else {
                    // Tampilkan tanda gagal kirim
                    bubble.addClass('pasangin-chat-item-failed');
                    bubble.css('opacity', '1');
                    indicator.html(`<span class="text-danger ms-1" style="font-size: 9px; font-weight: bold;" title="Gagal mengirim"><i class="fas fa-exclamation-circle"></i> Gagal</span>`);
                    if (objectUrlToRevoke) URL.revokeObjectURL(objectUrlToRevoke);
                }
            },
            error: function(xhr) {
                // Update CSRF token on error if returned
                if (xhr.responseJSON && xhr.responseJSON.csrf_hash) {
                    csrfHash = xhr.responseJSON.csrf_hash;
                }
                // Tampilkan tanda gagal kirim karena kendala jaringan/server
                const bubble = $(`#msg-${tempId}`);
                const indicator = $(`#indicator-${tempId}`);
                bubble.addClass('pasangin-chat-item-failed');
                bubble.css('opacity', '1');
                indicator.html(`<span class="text-danger ms-1" style="font-size: 9px; font-weight: bold;" title="Gagal mengirim"><i class="fas fa-exclamation-circle"></i> Gagal</span>`);
                if (objectUrlToRevoke) URL.revokeObjectURL(objectUrlToRevoke);
            }
        });
    });

    // Input Pencarian Sidebar
    $('#chat-search').on('input', function() {
        searchQuery = $(this).val().toLowerCase().trim();
        if (searchQuery.length > 0) {
            $('#clear-search').show();
        } else {
            $('#clear-search').hide();
        }
        filterConversations();
    });

    // Clear Search Input
    $('#clear-search').on('click', function() {
        $('#chat-search').val('');
        searchQuery = '';
        $(this).hide();
        filterConversations();
    });

    // Filter Kategori Tab (Semua / Klien / Tukang)
    $('.btn-filter').on('click', function() {
        $('.btn-filter').removeClass('active');
        $(this).addClass('active');
        activeFilter = $(this).data('filter');
        filterConversations();
    });

    // Filter Kategori Departemen (Semua Dept / Technical / Accounting / General)
    $('body').on('click', '.btn-filter-cat', function() {
        $('.btn-filter-cat').removeClass('active');
        $(this).addClass('active');
        activeCatFilter = $(this).attr('data-filter-cat');
        filterConversations();
    });

    // Load conversations list via AJAX and render it
    function loadConversations() {
        const url = '<?= site_url('admin/api/chat/conversations') ?>';
        $.ajax({
            url: url,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === true) {
                    if (response.csrf_hash) {
                        csrfHash = response.csrf_hash;
                    }
                    renderConversations(response.data);
                }
            }
        });
    }

    // Render conversations list to sidebar
    function renderConversations(conversations) {
        const listUl = $('#chat-list ul');
        listUl.empty();
        
        if (conversations && conversations.length > 0) {
            conversations.forEach(function(convo) {
                const isTukang = (convo.client_type === 'tukang');
                const badgeClass = isTukang ? 'badge-tukang' : 'badge-klien';
                const badgeText = isTukang ? 'Tukang' : 'Klien';
                
                // Format waktu pesan terakhir secara rapi
                let timeStr = '';
                if (convo.last_message_at) {
                    try {
                        const messageDate = new Date(convo.last_message_at.replace(' ', 'T'));
                        const today = new Date();
                        const timeParts = convo.last_message_at.split(' ')[1].split(':');
                        const hours = timeParts[0];
                        const minutes = timeParts[1];
                        
                        if (messageDate.toDateString() === today.toDateString()) {
                            timeStr = `${hours}:${minutes}`;
                        } else {
                            // Tampilkan tanggal & bulan (e.g. 24 Mei)
                            const monthNames = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
                            const day = messageDate.getDate();
                            const month = monthNames[messageDate.getMonth()];
                            timeStr = `${day} ${month}`;
                        }
                    } catch (e) {
                        timeStr = convo.last_message_at;
                    }
                }
                
                const lastMsg = escapeHtml(convo.last_message_preview || 'Belum ada riwayat pesan');
                const activeClass = (activeConversationId == convo.id) ? 'active' : '';
                const unreadCount = parseInt(convo.unread_by_admin_count) || 0;
                const badgeDisplayStyle = unreadCount > 0 ? '' : 'style="display: none;"';
                const clientNameEsc = escapeHtml(convo.client_name);
                const avatarUrl = getAvatarUrl(convo.client_avatar, convo.client_type, convo.client_name);
                const clientAvatarEsc = escapeHtml(convo.client_avatar || '');

                // Determine category badge class & text
                const cat = convo.category || 'general';
                let catBadgeClass = 'badge-gen';
                let catText = 'General';
                if (cat === 'technical') {
                    catBadgeClass = 'badge-tech';
                    catText = 'Technical';
                } else if (cat === 'accounting') {
                    catBadgeClass = 'badge-acct';
                    catText = 'Accounting';
                }
                
                const itemHtml = `
                    <li class="d-flex chat-list-user p-3 align-items-center border-bottom ${activeClass}" 
                         data-id="${convo.id}" 
                         data-name="${clientNameEsc}"
                         data-type="${escapeHtml(convo.client_type)}"
                         data-avatar="${clientAvatarEsc}"
                         data-status="${escapeHtml(convo.status || 'open')}"
                         data-title="${escapeHtml(convo.title || 'Obrolan')}"
                         data-category="${escapeHtml(cat)}">
                        <img class="me-3 rounded-circle border shadow-sm" width="48" height="48" 
                             src="${avatarUrl}" alt="avatar">
                        <div class="flex-grow-1" style="overflow: hidden;">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="mt-0 mb-0 fw-bold text-truncate text-dark chat-client-name" style="font-size: 0.92rem; max-width: 65%;">
                                    ${clientNameEsc}
                                    ${convo.status === 'closed' ? '<i class="fas fa-lock text-danger ms-1" style="font-size: 0.75rem;" title="Tertutup"></i>' : ''}
                                </h6>
                                <span class="text-small text-muted chat-item-time" style="font-size: 0.72rem; font-weight: 500;">${timeStr}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <p class="text-small text-muted text-truncate mb-0 chat-item-preview" style="max-width: 70%; font-size: 0.8rem;">${lastMsg}</p>
                                <div class="d-flex align-items-center" style="gap: 4px;">
                                    <span class="unread-badge me-2" id="unread-badge-${convo.id}" ${badgeDisplayStyle}>${unreadCount}</span>
                                    <span class="badge ${catBadgeClass} px-2 py-1 text-uppercase" style="font-size: 0.58rem; letter-spacing: 0.5px; border-radius: 4px;">${catText}</span>
                                    <span class="badge ${badgeClass} px-2 py-1 text-uppercase" style="font-size: 0.58rem; letter-spacing: 0.5px; border-radius: 4px;">${badgeText}</span>
                                </div>
                            </div>
                        </div>
                    </li>`;
                listUl.append(itemHtml);
            });
            // Terapkan filter pencarian & update total unread badge
            filterConversations();
            updateTotalUnreadBadge();
        } else {
            listUl.append(`
                <li class="text-center p-5 text-muted">
                    <i class="fas fa-inbox fa-2x mb-3 text-light"></i>
                    <p class="mb-0">Belum ada percakapan masuk.</p>
                </li>
            `);
        }
    }

    // Dengarkan event FCM chat masuk (Real-time update)
    window.addEventListener('fcm_chat_received', function(e) {
        const payload = e.detail;
        if (payload.data && payload.data.type === 'chat') {
            const incomingConvoId = payload.data.conversation_id;
            
            // Jika chat yang masuk adalah chat yang sedang aktif dibuka admin
            if (incomingConvoId == activeConversationId) {
                loadMessages(activeConversationId);
                
                // Bunyi chime jika admin sedang di background tab
                if (document.hidden) {
                    playNotificationSound();
                }
            } else {
                // Mainkan bunyi chime notifikasi untuk pesan masuk tidak aktif
                playNotificationSound();
            }
            
            // Reload daftar obrolan secara dinamis agar urutan/kontak baru sinkron seketika
            loadConversations();
        }
    });

    // Inisialisasi total unread badge saat halaman pertama dimuat
    updateTotalUnreadBadge();
});

// Lightbox State Variables
let zoomScale = 1;
let rotateAngle = 0;
let isPanning = false;
let startX = 0;
let startY = 0;
let translateX = 0;
let translateY = 0;

function updateImageTransform() {
    const lightboxImg = document.getElementById('chat-lightbox-img');
    if (lightboxImg) {
        lightboxImg.style.transform = `translate(${translateX}px, ${translateY}px) scale(${zoomScale}) rotate(${rotateAngle}deg)`;
    }
}

// Lightbox: Buka gambar atau video ukuran penuh di overlay
function openChatLightbox(src, type = 'image') {
    const overlay = document.getElementById('chat-lightbox');
    const lightboxImg = document.getElementById('chat-lightbox-img');
    const lightboxVideo = document.getElementById('chat-lightbox-video');
    const controls = document.querySelector('.chat-lightbox-controls');
    
    // Reset zoom, rotate, and pan state
    zoomScale = 1;
    rotateAngle = 0;
    translateX = 0;
    translateY = 0;
    updateImageTransform();
    
    if (type === 'video') {
        if (lightboxImg) lightboxImg.style.display = 'none';
        if (lightboxVideo) {
            lightboxVideo.src = src;
            lightboxVideo.style.display = 'block';
            lightboxVideo.load();
            lightboxVideo.play().catch(e => console.log("Auto-play blocked or failed", e));
        }
        if (controls) controls.style.display = 'none';
    } else {
        if (lightboxVideo) {
            lightboxVideo.style.display = 'none';
            lightboxVideo.pause();
            lightboxVideo.src = "";
        }
        if (lightboxImg) {
            lightboxImg.src = src;
            lightboxImg.style.display = 'block';
        }
        if (controls) controls.style.display = 'flex';
    }
    
    overlay.classList.add('active');
}

// Lightbox: Tutup overlay dan hentikan video jika ada
function closeChatLightbox() {
    const overlay = document.getElementById('chat-lightbox');
    if (overlay) {
        overlay.classList.remove('active');
    }
    const lightboxVideo = document.getElementById('chat-lightbox-video');
    if (lightboxVideo) {
        lightboxVideo.pause();
        lightboxVideo.src = "";
    }
}

function initLightbox() {
    const lightboxWrapper = document.getElementById('chat-lightbox-wrapper');
    const lightboxImg = document.getElementById('chat-lightbox-img');
    const closeBtn = document.getElementById('chat-lightbox-close');

    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            closeChatLightbox();
        });
    }

    if (lightboxWrapper) {
        // Zoom with mouse scroll wheel
        lightboxWrapper.addEventListener('wheel', function(e) {
            e.preventDefault();
            const zoomIntensity = 0.1;
            if (e.deltaY < 0) {
                // Zoom In
                zoomScale = Math.min(zoomScale + zoomIntensity * zoomScale, 5);
            } else {
                // Zoom Out
                zoomScale = Math.max(zoomScale - zoomIntensity * zoomScale, 0.5);
            }
            updateImageTransform();
        }, { passive: false });

        // Close when clicking directly on wrapper background
        lightboxWrapper.addEventListener('click', function(e) {
            if (e.target === this) {
                closeChatLightbox();
            }
        });
    }

    // Mouse panning events
    if (lightboxImg) {
        lightboxImg.addEventListener('mousedown', function(e) {
            if (e.button !== 0) return; // Only drag with left click
            isPanning = true;
            startX = e.clientX - translateX;
            startY = e.clientY - translateY;
            lightboxImg.style.transition = 'none'; // Disable transition while dragging for instant feedback
            e.preventDefault();
        });

        // Touch events for mobile zooming/panning
        let touchStartX = 0;
        let touchStartY = 0;
        let initialTouchDist = 0;
        let initialScale = 1;

        lightboxImg.addEventListener('touchstart', function(e) {
            if (e.touches.length === 1) {
                isPanning = true;
                touchStartX = e.touches[0].clientX - translateX;
                touchStartY = e.touches[0].clientY - translateY;
                lightboxImg.style.transition = 'none';
            } else if (e.touches.length === 2) {
                isPanning = false;
                initialTouchDist = Math.hypot(
                    e.touches[0].clientX - e.touches[1].clientX,
                    e.touches[0].clientY - e.touches[1].clientY
                );
                initialScale = zoomScale;
            }
        });

        lightboxImg.addEventListener('touchmove', function(e) {
            if (isPanning && e.touches.length === 1) {
                translateX = e.touches[0].clientX - touchStartX;
                translateY = e.touches[0].clientY - touchStartY;
                updateImageTransform();
                e.preventDefault();
            } else if (e.touches.length === 2) {
                const dist = Math.hypot(
                    e.touches[0].clientX - e.touches[1].clientX,
                    e.touches[0].clientY - e.touches[1].clientY
                );
                const factor = dist / initialTouchDist;
                zoomScale = Math.min(Math.max(initialScale * factor, 0.5), 5);
                updateImageTransform();
                e.preventDefault();
            }
        });

        lightboxImg.addEventListener('touchend', function() {
            isPanning = false;
            lightboxImg.style.transition = 'transform 0.1s ease-out';
        });
    }

    window.addEventListener('mousemove', function(e) {
        if (!isPanning) return;
        translateX = e.clientX - startX;
        translateY = e.clientY - startY;
        updateImageTransform();
    });

    window.addEventListener('mouseup', function() {
        if (isPanning) {
            isPanning = false;
            const img = document.getElementById('chat-lightbox-img');
            if (img) {
                img.style.transition = 'transform 0.1s ease-out';
            }
        }
    });

    // Control buttons
    const zoomInBtn = document.getElementById('zoom-in-btn');
    const zoomOutBtn = document.getElementById('zoom-out-btn');
    const zoomResetBtn = document.getElementById('zoom-reset-btn');
    const zoomRotateBtn = document.getElementById('zoom-rotate-btn');

    if (zoomInBtn) {
        zoomInBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            zoomScale = Math.min(zoomScale + 0.25, 5);
            updateImageTransform();
        });
    }

    if (zoomOutBtn) {
        zoomOutBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            zoomScale = Math.max(zoomScale - 0.25, 0.5);
            updateImageTransform();
        });
    }

    if (zoomResetBtn) {
        zoomResetBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            zoomScale = 1;
            translateX = 0;
            translateY = 0;
            updateImageTransform();
        });
    }

    if (zoomRotateBtn) {
        zoomRotateBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            rotateAngle = (rotateAngle + 90) % 360;
            updateImageTransform();
        });
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeChatLightbox();
        }
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initLightbox);
} else {
    initLightbox();
}
</script>
<?php
echo $this->endSection();
?>