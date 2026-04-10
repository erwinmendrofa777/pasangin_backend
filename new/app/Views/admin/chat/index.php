<?php
// FILE: app/Views/admin/chat/index.php
// KODE FULL FINAL - Menggunakan struktur HTML, CSS, dan URL AJAX Anda yang benar.

// Menggunakan layout Anda
echo $this->extend('layout/app');

echo $this->section('title');
?>
Pusat Pesan
<?php
echo $this->endSection();

echo $this->section('style');
?>
<!-- CSS Anda (tidak diubah) -->
<style>
  .chat-box { height: 75vh; display: flex; flex-direction: column; }
  .chat-content { flex: 1; overflow-y: auto; padding: 10px 20px; }
  .chat-list-user { cursor: pointer; border-bottom: 1px solid #f9f9f9; }
  .chat-list-user:hover { background-color: #f8f9fa; }
  .chat-list-user.active { background-color: #6777ef; color: #fff; }
  .chat-list-user.active .text-muted, .chat-list-user.active .font-weight-bold { color: #fff !important; }
  .chat-item { display: flex; margin-bottom: 20px; max-width: 80%; }
  .chat-avatar img { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; }
  .chat-item-body { display: flex; flex-direction: column; }
  .chat-text { padding: 10px 15px; border-radius: 18px; word-wrap: break-word; font-size: 14px; }
  .chat-time { font-size: 11px; color: #999; margin-top: 4px; }
  .chat-item.chat-left { align-self: flex-start; }
  .chat-item.chat-left .chat-avatar { margin-right: 15px; }
  .chat-item.chat-left .chat-text { background: #e4e6eb; color: #050505; border-top-left-radius: 5px; }
  .chat-item.chat-left .chat-time { align-self: flex-start; }
  .chat-item.chat-right { align-self: flex-end; flex-direction: row-reverse; }
  .chat-item.chat-right .chat-avatar { margin-left: 15px; }
  .chat-item.chat-right .chat-text { background: #6777ef; color: #fff; border-top-right-radius: 5px; }
  .chat-item.chat-right .chat-time { align-self: flex-end; }
</style>
<?php
echo $this->endSection();

echo $this->section('content');
?>
<!-- HTML Anda (tidak diubah) -->
<section class="section">
  <div class="section-header"><h1>Pusat Pesan</h1></div>
  <div class="section-body">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-12 col-sm-12 col-md-4">
                <div class="card">
                  <div class="card-header"><h4>Daftar Percakapan</h4></div>
                  <div class="card-body" id="chat-list" style="height: 75vh; overflow-y: auto; padding: 0;">
                    <ul class="list-unstyled list-unstyled-border">
                      <?php if (!empty($conversations)) : ?>
                        <?php foreach ($conversations as $convo) : ?>
                          <li class="media chat-list-user p-3" data-id="<?= $convo['id'] ?>" data-name="<?= esc($convo['client_name']) ?>">
                            <img alt="image" class="mr-3 rounded-circle" width="50" height="50" src="https://ui-avatars.com/api/?name=<?= urlencode($convo['client_name'] ?? 'K') ?>&background=random">
                            <div class="media-body">
                                <div class="nt-0 mb-1 font-weight-bold">
                                    <?= esc($convo['client_name'] ?? ('Client ID: ' . $convo['client_id'])) ?>
                                </div>
                              <div class="text-small font-weight-600 text-muted">
                                <span class="preview-text"><?= esc(substr($convo['last_message_preview'] ?? '...', 0, 30)) ?></span>
                              </div>
                            </div>
                          </li>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <li class="text-center p-3">Tidak ada percakapan.</li>
                      <?php endif; ?>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-12 col-sm-12 col-md-8">
                <div class="card chat-box card-success" id="chat-window">
                  <div class="card-header"><h4 id="chat-with-name">Pilih percakapan untuk memulai</h4></div>
                  <div class="card-body chat-content" id="chat-messages">
                      <div class="text-center mt-5" id="chat-placeholder">
                        <i class="fas fa-comments fa-3x text-muted"></i>
                        <p class="text-muted mt-2">Pesan akan muncul di sini.</p>
                      </div>
                  </div>
                  <div class="card-footer chat-form" style="display: none;">
                    <form id="message-form" class="d-flex">
                      <input type="text" id="message-input" class="form-control" placeholder="Ketik balasan..." autocomplete="off">
                      <button id="send-btn" type="submit" class="btn btn-primary ml-2"><i class="far fa-paper-plane"></i></button>
                    </form>
                  </div>
                </div>
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

    function scrollToBottom() {
        const chatContent = $("#chat-messages");
        chatContent.scrollTop(chatContent[0].scrollHeight);
    }

    function loadMessages(conversationId) {
        if (!conversationId) return;

        // ==========================================================
        // === URL DIPERBAIKI SESUAI STRUKTUR API ANDA (GET) ===
        // ==========================================================
        const url = `<?= site_url('admin/api/chat/') ?>${conversationId}/messages`;

        $.ajax({
            url: url,
            method: 'GET',
            dataType: 'json',
            beforeSend: function() {
                $('#chat-messages').html('<div class="text-center mt-5"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></div>');
            },
            success: function(response) {
                $('#chat-messages').html('');
                if (response.status === 'success' && response.messages.length > 0) {
                    response.messages.forEach(function(msg) {
                        const messageClass = (msg.sender_type === 'admin') ? 'chat-right' : 'chat-left';
                        const avatarName = (msg.sender_type === 'admin') ? 'Admin' : '<?= esc($convo['client_name'] ?? 'Client') ?>';
                        const avatarUrl = `https://ui-avatars.com/api/?name=${encodeURIComponent(avatarName)}&background=random`;

                        const messageHtml = `
                            <div class="chat-item ${messageClass}">
                                <div class="chat-avatar"><img src="${avatarUrl}" alt="Avatar"></div>
                                <div class="chat-item-body">
                                    <div class="chat-text">${msg.body}</div>
                                    <div class="chat-time">${new Date(msg.created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}</div>
                                </div>
                            </div>`;
                        $('#chat-messages').append(messageHtml);
                    });
                } else {
                     $('#chat-messages').html('<div class="text-center mt-5"><p>Belum ada pesan dalam percakapan ini.</p></div>');
                }
                scrollToBottom();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("AJAX GAGAL (getMessages):", textStatus, errorThrown, jqXHR.responseText);
                alert('Gagal memuat pesan. Pastikan rute di app/Config/Routes.php sudah benar. Cek console (F12).');
            }
        });
    }

    $('body').on('click', '.chat-list-user', function() {
        const conversationId = $(this).data('id');
        if (activeConversationId === conversationId) return;

        activeConversationId = conversationId;
        const clientName = $(this).data('name');

        $('#chat-placeholder').hide();
        $('.chat-form').show();
        $('.chat-list-user').removeClass('active');
        $(this).addClass('active');
        $('#chat-with-name').text('Chat dengan ' + clientName);

        loadMessages(activeConversationId);
    });

    $('#message-form').on('submit', function(e) {
        e.preventDefault();
        const messageInput = $('#message-input');
        const messageBody = messageInput.val().trim();
        if (messageBody === '' || activeConversationId === null) return;

        $.ajax({
            // ==========================================================
            // === URL DIPERBAIKI SESUAI STRUKTUR API ANDA (POST) ===
            // ==========================================================
            url: '<?= site_url('admin/api/chat/send') ?>',
            method: 'POST',
            data: {
                'conversation_id': activeConversationId,
                'body': messageBody,
                // PENTING: Keamanan CSRF untuk CodeIgniter 4
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            },
            dataType: 'json',
            beforeSend: function(){
                $('#send-btn').addClass('btn-progress');
            },
            success: function(response) {
                if (response.status === 'success') {
                    messageInput.val('');
                    loadMessages(activeConversationId); // Muat ulang untuk menampilkan pesan balasan
                } else {
                    alert('Gagal mengirim pesan: ' + response.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("AJAX GAGAL (sendMessage):", textStatus, errorThrown, jqXHR.responseText);
                alert('Terjadi kesalahan koneksi saat mengirim pesan. Cek console (F12).');
            },
            complete: function() {
                $('#send-btn').removeClass('btn-progress');
            }
        });
    });
});
</script>
<?php
echo $this->endSection();
?>
