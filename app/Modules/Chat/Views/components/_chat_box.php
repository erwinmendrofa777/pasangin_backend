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
                        <span class="badge text-uppercase text-white" id="chat-with-role" style="font-size: 0.58rem; border-radius: 4px; padding: 2px 8px; background-color: var(--palette-primary);">Role</span>
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
        <div id="report-detail-collapse" class="bg-white border-bottom p-3" style="display: none; border-left: 4px solid var(--palette-primary);">
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
                                <stop offset="0%" stop-color="var(--palette-primary)" />
                                <stop offset="100%" stop-color="var(--palette-primary-hover, #ff3b3b)" />
                            </linearGradient>
                            <linearGradient id="bubbleGrad2" x1="0" y1="0" x2="1" y2="1">
                                <stop offset="0%" stop-color="#38bdf8" />
                                <stop offset="100%" stop-color="#0284c7" />
                            </linearGradient>
                            <filter id="softShadow" x="-10%" y="-10%" width="120%" height="120%">
                                <feDropShadow dx="0" dy="8" stdDeviation="6" flood-color="var(--palette-primary)" flood-opacity="0.15" />
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

            <!-- Banner Mode Pemantauan Chat Client-Supplier -->
            <div id="chat-readonly-notice" class="text-center py-3 text-muted w-100" style="display: none; font-size: 0.9rem; font-weight: 500; background-color: #f1f5f9; border-top: 1px solid #e2e8f0; border-radius: 8px;">
                <i class="fas fa-eye text-info me-2"></i> <strong>Mode Pemantauan:</strong> Obrolan ini dilakukan oleh Klien &amp; Supplier. Admin hanya bisa membaca.
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
