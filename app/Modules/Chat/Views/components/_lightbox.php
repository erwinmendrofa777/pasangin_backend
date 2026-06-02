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
