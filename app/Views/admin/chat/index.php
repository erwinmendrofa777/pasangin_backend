<?php
// FILE: app/Views/admin/chat/index.php
// VERSI FULL - DESAIN MODERN & LOGIKA FIXED

echo $this->extend('layout/app');

echo $this->section('title');
?>
Pusat Pesan - Pasangin
<?php
echo $this->endSection();

echo $this->section('style');
?>
<style>
    /* Container Utama Chat */
    .chat-box { height: 75vh; display: flex; flex-direction: column; background: #f0f2f5; border-radius: 12px; overflow: hidden; border: 1px solid #e0e0e0; }
    
    /* Area Daftar User (Sidebar) */
    .chat-list-container { height: 75vh; overflow-y: auto; background: #fff; border-right: 1px solid #eee; }
    .chat-list-user { cursor: pointer; border-bottom: 1px solid #f5f5f5; transition: 0.2s; }
    .chat-list-user:hover { background-color: #f8f9fa; }
    .chat-list-user.active { background-color: #6777ef; color: #fff; }
    .chat-list-user.active .text-muted { color: #fff !important; opacity: 0.8; }

    /* Header Chat Aktif */
    .chat-header { padding: 15px 20px; background: #fff; border-bottom: 1px solid #ddd; z-index: 10; }
    #chat-with-name { font-size: 16px; font-weight: 700; margin: 0; color: #333; }

    /* Area Pesan (Bubble Chat) */
    .chat-content { flex: 1; overflow-y: auto; padding: 20px; display: flex; flex-direction: column; gap: 12px; background-image: url('https://www.transparenttextures.com/patterns/cubes.png'); }
    
    .chat-item { display: flex; width: 100%; align-items: flex-end; }
    .chat-text { 
        padding: 10px 16px; 
        border-radius: 18px; 
        font-size: 14px; 
        line-height: 1.5; 
        position: relative; 
        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        max-width: 75%;
        word-wrap: break-word;
    }
    .chat-time { font-size: 10px; color: #888; margin-top: 5px; display: block; }

    /* Style Chat Kiri (User/Client) */
    .chat-item.chat-left { justify-content: flex-start; }
    .chat-item.chat-left .chat-text { background: #fff; color: #333; border-bottom-left-radius: 2px; }
    .chat-item.chat-left .chat-avatar { margin-right: 10px; order: 1; }
    .chat-item.chat-left .chat-item-body { order: 2; }

    /* Style Chat Kanan (Admin) */
    .chat-item.chat-right { justify-content: flex-end; }
    .chat-item.chat-right .chat-text { background: #6777ef; color: #fff; border-bottom-right-radius: 2px; }
    .chat-item.chat-right .chat-avatar { margin-left: 10px; order: 2; }
    .chat-item.chat-right .chat-item-body { order: 1; display: flex; flex-direction: column; align-items: flex-end; }

    .chat-avatar img { width: 35px; height: 35px; border-radius: 50%; object-fit: cover; border: 2px solid #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }

    /* Form Input Pesan */
    .chat-footer { background: #fff; padding: 15px; border-top: 1px solid #eee; }
    .input-wrapper { background: #f0f2f5; border-radius: 25px; padding: 5px 15px; display: flex; align-items: center; }
    .input-wrapper input { border: none !important; background: transparent !important; box-shadow: none !important; height: 40px; }
    .btn-send { width: 40px; height: 40px; border-radius: 50% !important; display: flex; align-items: center; justify-content: center; transition: 0.3s; }
    
    /* Scrollbar */
    .chat-content::-webkit-scrollbar { width: 5px; }
    .chat-content::-webkit-scrollbar-thumb { background: #cbd5e0; border-radius: 10px; }
</style>
<?php
echo $this->endSection();

echo $this->section('content');
?>
<section class="section">
    <div class="section-header">
        <h1>Pusat Pesan</h1>
    </div>

    <div class="section-body">
        <div class="card">
            <div class="card-body p-0">
                <div class="row no-gutters">
                    <div class="col-md-4 col-12 chat-list-container">
                        <div class="p-3 border-bottom bg-light">
                            <h6 class="m-0 font-weight-bold text-primary">Obrolan Aktif</h6>
                        </div>
                        <div id="chat-list">
                            <ul class="list-unstyled mb-0">
                                <?php if (!empty($conversations)) : ?>
                                    <?php foreach ($conversations as $convo) : ?>
                                        <li class="media chat-list-user p-3 align-items-center" 
                                            data-id="<?= $convo['id'] ?>" 
                                            data-name="<?= esc($convo['client_name']) ?>">
                                            <img class="mr-3 rounded-circle" width="45" 
                                                 src="https://ui-avatars.com/api/?name=<?= urlencode($convo['client_name']) ?>&background=random&color=fff" alt="avatar">
                                            <div class="media-body" style="overflow: hidden;">
                                                <div class="mt-0 mb-1 font-weight-bold text-truncate"><?= esc($convo['client_name']) ?></div>
                                                <div class="text-small text-muted text-truncate">Klik untuk membalas pesan...</div>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <li class="text-center p-4 text-muted">Belum ada percakapan masuk.</li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-8 col-12">
                        <div class="chat-box">
                            <div class="chat-header" id="header-chat-box" style="display:none;">
                                <h4 id="chat-with-name">Nama Client</h4>
                            </div>
                            
                            <div class="chat-content" id="chat-messages">
                                <div class="text-center my-auto" id="chat-placeholder">
                                    <i class="fas fa-comments fa-4x text-light mb-3"></i>
                                    <h5 class="text-muted">Pilih percakapan untuk memulai</h5>
                                    <p class="text-small text-muted">Klik salah satu nama di samping untuk melihat riwayat pesan.</p>
                                </div>
                            </div>

                            <div class="chat-footer" style="display: none;">
                                <form id="message-form">
                                    <div class="input-wrapper">
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
<?php
echo $this->endSection();

echo $this->section('script');
?>
<script>
$(document).ready(function() {
    let activeConversationId = null;
    let refreshInterval = null;

    function scrollToBottom() {
        const container = $("#chat-messages");
        container.animate({ scrollTop: container[0].scrollHeight }, 200);
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
                        response.data.forEach(function(msg) {
                            const is_admin = (msg.sender_type === 'admin');
                            const alignClass = is_admin ? 'chat-right' : 'chat-left';
                            const avatarChar = is_admin ? 'AD' : $('#chat-with-name').text().substring(0,2);
                            const avatarColor = is_admin ? '6777ef' : 'fc544b';

                            chatHtml += `
                                <div class="chat-item ${alignClass}">
                                    <div class="chat-avatar">
                                        <img src="https://ui-avatars.com/api/?name=${avatarChar}&background=${avatarColor}&color=fff" alt="avatar">
                                    </div>
                                    <div class="chat-item-body">
                                        <div class="chat-text">${msg.body}</div>
                                        <small class="chat-time">${msg.created_at}</small>
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
        const conversationId = $(this).data('id');
        const clientName = $(this).data('name');

        activeConversationId = conversationId;
        $('.chat-list-user').removeClass('active');
        $(this).addClass('active');

        $('#chat-placeholder').hide();
        $('#header-chat-box').show();
        $('.chat-footer').show();
        $('#chat-with-name').text('Chat dengan ' + clientName);

        loadMessages(conversationId);

        // Reset & Set Interval Auto Refresh (3 Detik)
        clearInterval(refreshInterval);
        refreshInterval = setInterval(() => {
            loadMessages(activeConversationId);
        }, 3000);
    });

    // Handle Form Submit (Kirim Pesan)
    $('#message-form').on('submit', function(e) {
        e.preventDefault();
        const input = $('#message-input');
        const text = input.val().trim();
        
        if (text === '' || !activeConversationId) return;

        $.ajax({
            url: '<?= site_url('admin/api/chat/send') ?>',
            method: 'POST',
            data: {
                'conversation_id': activeConversationId,
                'message': text,
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            },
            success: function(response) {
                if (response.status === true) {
                    input.val('');
                    loadMessages(activeConversationId);
                } else {
                    alert('Gagal mengirim pesan.');
                }
            }
        });
    });
});
</script>
<?php
echo $this->endSection();
?>