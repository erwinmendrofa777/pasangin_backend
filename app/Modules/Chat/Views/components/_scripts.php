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
            } else if (type === 'supplier') {
                return `<?= base_url('uploads/supplier') ?>/` + avatar;
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
            const supplierId = item.attr('data-supplier-id') || '';
            
            const matchesSearch = name.includes(searchQuery);
            
            let matchesFilter = false;
            if (activeFilter === 'all') {
                matchesFilter = true;
            } else if (activeFilter === 'supplier') {
                matchesFilter = (supplierId !== '');
            } else {
                matchesFilter = (type === activeFilter && supplierId === '');
            }
            
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
                            const is_supplier = (msg.sender_type === 'supplier');
                            const is_right = (is_admin || is_supplier);
                            const alignClass = is_right ? 'pasangin-chat-right' : 'pasangin-chat-left';
                            
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
                            const avatarColor = is_admin ? 'ff5c5c' : 'f59e0b';

                            const formattedTime = formatTime(msg.created_at);
                            const formattedBody = formatMessageBody(msg.body);

                            // Tick indicator for admin messages (WhatsApp style)
                            let tickHtml = '';
                            if (is_admin) {
                                if (msg.is_read_by_client == 1) {
                                    tickHtml = `<i class="fas fa-check-double ms-1" style="color: #38bdf8; font-size: 10px;" title="Dibaca oleh client"></i>`;
                                } else {
                                    tickHtml = `<i class="fas fa-check ms-1" style="color: #94a3b8; font-size: 10px;" title="Terkirim"></i>`;
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
                            if (msg.sender_type === 'admin') {
                                bubbleAvatarUrl = "<?= base_url() ?>/" + msg.sender_avatar;
                            } else {
                                bubbleAvatarUrl = getAvatarUrl(msg.sender_avatar, msg.sender_type, msg.sender_name);
                            }

                            const senderNameHtml = `<div class="chat-sender-name fw-bold" style="font-size: 0.72rem; color: #64748b; margin-bottom: 3px; font-weight: 600;">${escapeHtml(msg.sender_name)}</div>`;

                            chatHtml += `
                                <div class="pasangin-chat-item ${alignClass}">
                                    <div class="pasangin-chat-avatar" title="${escapeHtml(msg.sender_name)}">
                                        <img src="${bubbleAvatarUrl}" alt="avatar">
                                    </div>
                                    <div class="pasangin-chat-item-body">
                                        ${senderNameHtml}
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
                } else {
                    $('#chat-messages').html('<div class="text-center my-auto text-danger">Gagal memuat pesan.</div>');
                }
            },
            error: function() {
                $('#chat-messages').html('<div class="text-center my-auto text-danger">Terjadi kesalahan koneksi. Silakan coba lagi.</div>');
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
        const supplierId = $(this).attr('data-supplier-id') || '';

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
        
        // Atur badge warna role dan tulisan
        if (supplierId) {
            $('#chat-with-role').text('Pemantauan').removeClass('bg-warning text-dark bg-info text-white').addClass('bg-secondary text-white');
        } else if (clientRole === 'tukang') {
            $('#chat-with-role').text('Tukang').removeClass('bg-info bg-secondary text-white').addClass('bg-warning text-dark');
        } else {
            $('#chat-with-role').text('Klien').removeClass('bg-warning text-dark bg-secondary').addClass('bg-info text-white');
        }

        // Set avatar di header chat
        if (supplierId) {
            $('#active-chat-avatar').html(`
                <div class="rounded-circle border shadow-sm d-flex align-items-center justify-content-center bg-light text-secondary" style="width: 40px; height: 40px;">
                    <i class="fas fa-eye" style="font-size: 1.1rem;"></i>
                </div>
            `);
        } else {
            const avatarUrl = getAvatarUrl(activeClientAvatar, activeClientRole, clientName);
            $('#active-chat-avatar').html(`<img src="${avatarUrl}" class="rounded-circle border shadow-sm" width="40" height="40" alt="avatar">`);
        }

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
        const activeItem = $(`.chat-list-user[data-id="${activeConversationId}"]`);
        const isMonitored = (activeItem.length > 0 && activeItem.attr('data-supplier-id'));

        if (isMonitored) {
            $('#chat-status-action-wrapper').html('');
            $('#btn-toggle-report').hide();
            $('#header-chat-box').css('background-color', '#f8fafc');
            $('#message-form').hide();
            $('#chat-closed-notice').hide();
            $('#chat-readonly-notice').show();
        } else {
            $('#btn-toggle-report').show();
            $('#header-chat-box').css('background-color', '#ffffff');
            $('#chat-readonly-notice').hide();
            
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
        const avatarColor = 'ff5c5c';
        
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
                    indicator.html(`<i class="fas fa-check ms-1" style="color: #94a3b8; font-size: 10px;" title="Terkirim"></i>`);
                    
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

    // Filter Kategori Departemen (Semua Dept / Technical / Accounting / General via Dropdown)
    $('body').on('change', '#chat-category-select', function() {
        activeCatFilter = $(this).val();
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
                
                let badgeHtml = '';
                if (convo.supplier_id) {
                    badgeHtml = `<span class="badge px-2 py-1 text-uppercase" style="font-size: 0.58rem; letter-spacing: 0.5px; border-radius: 4px; background-color: #d1fae5; color: #065f46; border: 1px solid #a7f3d0;">Monitored</span>`;
                } else {
                    badgeHtml = `
                        <span class="badge px-2 py-1 text-uppercase" style="font-size: 0.58rem; letter-spacing: 0.5px; border-radius: 4px; background-color: #ffe4e6; color: #9f1239; border: 1px solid #fecdd3;">CS Active</span>
                        <span class="badge ${badgeClass} px-2 py-1 text-uppercase" style="font-size: 0.58rem; letter-spacing: 0.5px; border-radius: 4px;">${badgeText}</span>
                    `;
                }

                const itemHtml = `
                    <li class="d-flex chat-list-user p-3 align-items-center border-bottom ${activeClass}" 
                         data-id="${convo.id}" 
                         data-name="${clientNameEsc}"
                         data-type="${escapeHtml(convo.client_type)}"
                         data-avatar="${clientAvatarEsc}"
                         data-status="${escapeHtml(convo.status || 'open')}"
                         data-title="${escapeHtml(convo.title || 'Obrolan')}"
                         data-category="${escapeHtml(cat)}"
                         data-supplier-id="${escapeHtml(convo.supplier_id || '')}"
                         data-supplier-name="${escapeHtml(convo.supplier_name || '')}">
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
                                    ${badgeHtml}
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
</script>
