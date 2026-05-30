<?php
// FILE: app/Views/admin/chat/index.php
// VERSI PREMIUM — Bootstrap 5 + DM Sans + DM Mono

echo $this->extend('layout/app');

echo $this->section('title');
?>
Pusat Pesan – Pasangin
<?php
echo $this->endSection();

echo $this->section('style');
?>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

<style>
/* ── Reset & Base ──────────────────────────────── */
.chat-root *,
.chat-root *::before,
.chat-root *::after { box-sizing: border-box; }

.chat-root {
    font-family: 'DM Sans', sans-serif;
}

/* ── Wrapper Card ──────────────────────────────── */
.chat-card {
    border-radius: 16px;
    overflow: hidden;
    border: 0.5px solid #e2e8f0;
    background: #fff;
    box-shadow: 0 4px 24px rgba(0,0,0,0.04);
    display: flex;
    height: 80vh;
    min-height: 560px;
}

/* ── SIDEBAR ───────────────────────────────────── */
.chat-sidebar {
    width: 300px;
    min-width: 300px;
    display: flex;
    flex-direction: column;
    border-right: 0.5px solid #e2e8f0;
    background: #fff;
}

.sidebar-header {
    padding: 18px 16px 0;
}

.sidebar-title {
    font-size: 15px;
    font-weight: 600;
    color: #0f172a;
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
}

.badge-conv {
    font-size: 11px;
    font-weight: 500;
    padding: 2px 8px;
    border-radius: 20px;
    background: #EAF3DE;
    color: #3B6D11;
}

.badge-unread-total {
    font-size: 11px;
    font-weight: 600;
    padding: 2px 8px;
    border-radius: 20px;
    background: #FCEBEB;
    color: #A32D2D;
}

/* Search */
.search-box {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #f8fafc;
    border: 0.5px solid #e2e8f0;
    border-radius: 10px;
    padding: 0 12px;
    height: 36px;
    transition: border-color .2s;
    margin-bottom: 10px;
}
.search-box:focus-within { border-color: #94a3b8; }
.search-box input {
    border: none;
    background: transparent;
    outline: none;
    font-family: 'DM Sans', sans-serif;
    font-size: 13px;
    color: #0f172a;
    width: 100%;
}
.search-box input::placeholder { color: #94a3b8; }
.search-icon { color: #94a3b8; font-size: 12px; flex-shrink: 0; }
.clear-btn { color: #cbd5e1; font-size: 12px; cursor: pointer; display: none; }
.clear-btn:hover { color: #64748b; }

/* Filter chips */
.filter-row {
    display: flex;
    gap: 6px;
    padding-bottom: 12px;
}
.fchip {
    font-family: 'DM Sans', sans-serif;
    font-size: 11px;
    font-weight: 500;
    border: 0.5px solid #e2e8f0;
    background: transparent;
    color: #64748b;
    border-radius: 20px;
    padding: 4px 12px;
    cursor: pointer;
    transition: all .2s;
}
.fchip:hover { background: #f8fafc; }
.fchip.active {
    background: #3C3489;
    border-color: #3C3489;
    color: #EEEDFE;
}

/* Contact list */
.contact-scroll {
    flex: 1;
    overflow-y: auto;
}
.contact-scroll::-webkit-scrollbar { width: 3px; }
.contact-scroll::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }

.contact-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 11px 16px;
    cursor: pointer;
    border-bottom: 0.5px solid #f1f5f9;
    border-left: 3px solid transparent;
    transition: background .15s;
    text-decoration: none;
}
.contact-item:hover { background: #f8fafc; }
.contact-item.active {
    background: #f8fafc;
    border-left-color: #534AB7;
}
.contact-item.active .contact-name { color: #534AB7; }

/* Avatar color variants */
.av { width: 38px; height: 38px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 12px; flex-shrink: 0; font-family: 'DM Mono', monospace; letter-spacing: -0.5px; }
.av-purple { background: #EEEDFE; color: #3C3489; }
.av-teal   { background: #E1F5EE; color: #085041; }
.av-coral  { background: #FAECE7; color: #712B13; }
.av-blue   { background: #E6F1FB; color: #0C447C; }
.av-amber  { background: #FAEEDA; color: #633806; }

.contact-info { flex: 1; min-width: 0; }
.contact-name { font-size: 13px; font-weight: 500; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 2px; }
.contact-preview { font-size: 11.5px; color: #94a3b8; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.contact-meta { display: flex; flex-direction: column; align-items: flex-end; gap: 4px; flex-shrink: 0; }
.contact-time { font-size: 10px; color: #94a3b8; font-family: 'DM Mono', monospace; }

.role-pill { font-size: 9px; font-weight: 600; padding: 2px 6px; border-radius: 4px; text-transform: uppercase; letter-spacing: .4px; }
.rp-tukang { background: #FAEEDA; color: #633806; }
.rp-klien  { background: #E6F1FB; color: #0C447C; }

.unread-badge {
    width: 16px; height: 16px; border-radius: 50%;
    background: #E24B4A; color: #fff;
    font-size: 9px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
}

/* ── MAIN PANEL ────────────────────────────────── */
.chat-main {
    flex: 1;
    display: flex;
    flex-direction: column;
    min-width: 0;
    background: #fff;
}

/* Header */
.chat-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 20px;
    border-bottom: 0.5px solid #e2e8f0;
    flex-shrink: 0;
}
.header-av-wrap { display: flex; align-items: center; gap: 10px; }
.header-name { font-size: 14px; font-weight: 600; color: #0f172a; }
.header-sub { font-size: 11.5px; color: #94a3b8; display: flex; align-items: center; gap: 5px; margin-top: 1px; }
.online-dot { width: 7px; height: 7px; border-radius: 50%; background: #639922; flex-shrink: 0; }

.icon-btn {
    width: 32px; height: 32px; border-radius: 8px;
    border: 0.5px solid #e2e8f0;
    background: transparent; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    color: #64748b; font-size: 13px;
    transition: all .2s;
}
.icon-btn:hover { background: #f8fafc; color: #0f172a; }

/* Messages */
.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 4px;
    background-color: #f8fafc;
    background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
    background-size: 22px 22px;
}
.chat-messages::-webkit-scrollbar { width: 4px; }
.chat-messages::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }

/* Date separator */
.date-sep {
    display: flex; align-items: center; justify-content: center;
    position: relative; margin: 12px 0;
}
.date-sep::before { content: ''; position: absolute; left: 0; right: 0; height: 0.5px; background: #e2e8f0; }
.date-pill {
    background: #fff;
    border: 0.5px solid #e2e8f0;
    color: #64748b;
    font-size: 10px; font-weight: 500;
    padding: 3px 12px; border-radius: 20px; z-index: 1;
    letter-spacing: .3px;
}

/* Bubble rows */
.msg-row { display: flex; align-items: flex-end; gap: 8px; animation: fadeUp .2s ease-out; }
@keyframes fadeUp { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
.msg-row.me   { justify-content: flex-end; }
.msg-row.them { justify-content: flex-start; }

.msg-av { width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: 600; flex-shrink: 0; font-family: 'DM Mono', monospace; }

.bubble {
    max-width: 65%;
    padding: 10px 14px;
    font-size: 13px; line-height: 1.55;
    word-break: break-word;
}
.bubble-them {
    background: #fff;
    border: 0.5px solid #e2e8f0;
    border-radius: 14px 14px 14px 4px;
    color: #0f172a;
}
.bubble-me {
    background: #534AB7;
    border-radius: 14px 14px 4px 14px;
    color: #EEEDFE;
}
.bubble-me.failed { background: #E24B4A; }

.msg-time { font-size: 10px; color: #94a3b8; margin-top: 4px; font-family: 'DM Mono', monospace; }
.msg-row.me .msg-time { text-align: right; }
.tick-icon { font-size: 9px; color: #AFA9EC; }
.tick-read { color: #818cf8; }

/* Media inside bubble */
.bubble img.chat-img {
    max-width: 220px; max-height: 220px;
    object-fit: cover; border-radius: 8px;
    cursor: pointer; display: block;
}
.bubble video.chat-vid {
    max-width: 220px; max-height: 220px;
    border-radius: 8px; display: block;
}
.bubble .file-link {
    display: flex; align-items: center; gap: 8px;
    background: rgba(255,255,255,.12);
    border: 0.5px solid rgba(255,255,255,.2);
    border-radius: 8px; padding: 8px 12px;
    text-decoration: none; color: inherit;
    font-size: 12px; font-weight: 500;
    max-width: 220px;
}
.bubble-them .file-link {
    background: #f8fafc;
    border: 0.5px solid #e2e8f0;
    color: #0f172a;
}

/* Placeholder / empty */
.chat-placeholder {
    flex: 1;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    gap: 12px; padding: 40px;
}
.ph-icon {
    width: 60px; height: 60px; border-radius: 18px;
    background: #EEEDFE;
    display: flex; align-items: center; justify-content: center;
    font-size: 26px; color: #534AB7;
}
.ph-title { font-size: 15px; font-weight: 500; color: #0f172a; margin-top: 4px; }
.ph-sub { font-size: 12.5px; color: #94a3b8; text-align: center; line-height: 1.7; max-width: 240px; }

/* No messages inside chat */
.no-messages {
    flex: 1; display: flex; align-items: center; justify-content: center;
    font-size: 12.5px; color: #94a3b8;
}

/* Footer input */
.chat-footer {
    padding: 12px 16px;
    border-top: 0.5px solid #e2e8f0;
    flex-shrink: 0;
}
.attach-preview {
    display: flex; align-items: center; justify-content: space-between;
    background: #f8fafc; border: 0.5px solid #e2e8f0;
    border-radius: 10px; padding: 7px 14px;
    margin-bottom: 8px; animation: fadeUp .2s ease-out;
}
.input-row {
    display: flex; align-items: center; gap: 8px;
    background: #f8fafc;
    border: 0.5px solid #e2e8f0;
    border-radius: 12px;
    padding: 6px 8px 6px 14px;
    transition: border-color .2s;
}
.input-row:focus-within { border-color: #94a3b8; }
.msg-input {
    flex: 1; border: none; background: transparent; outline: none;
    font-family: 'DM Sans', sans-serif; font-size: 13px; color: #0f172a;
    min-height: 28px; resize: none; line-height: 1.5;
}
.msg-input::placeholder { color: #94a3b8; }
.attach-btn, .send-btn {
    width: 32px; height: 32px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    border: none; cursor: pointer; font-size: 14px; flex-shrink: 0;
    transition: all .2s;
}
.attach-btn { background: transparent; color: #64748b; }
.attach-btn:hover { background: #f1f5f9; color: #0f172a; }
.send-btn { background: #534AB7; color: #EEEDFE; }
.send-btn:hover { background: #3C3489; }

/* ── Responsive ─────────────────────────────────── */
@media (max-width: 768px) {
    .chat-sidebar { width: 100%; min-width: unset; }
    .chat-card    { flex-direction: column; height: auto; min-height: 100vh; }
}
</style>
<?php echo $this->endSection(); ?>

<?php echo $this->section('content'); ?>

<div class="chat-root">
    <div class="section-header mb-3">
        <h1 class="h4 fw-semibold mb-0">Pusat Pesan Real-time</h1>
    </div>

    <div class="chat-card">

        <!-- ══ SIDEBAR ══════════════════════════════════ -->
        <aside class="chat-sidebar">
            <div class="sidebar-header">
                <div class="sidebar-title">
                    Obrolan Aktif
                    <div class="d-flex gap-1 align-items-center">
                        <?php
                        $totalUnread = 0;
                        foreach (($conversations ?? []) as $c) {
                            $totalUnread += intval($c['unread_by_admin_count'] ?? 0);
                        }
                        ?>
                        <span id="badge-unread-total" class="badge-unread-total" <?= $totalUnread > 0 ? '' : 'style="display:none"' ?>>
                            <?= $totalUnread ?> belum dibaca
                        </span>
                        <span class="badge-conv"><?= count($conversations ?? []) ?> chat</span>
                    </div>
                </div>

                <div class="search-box">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" id="chat-search" placeholder="Cari klien atau tukang…" autocomplete="off">
                    <i class="fas fa-times clear-btn" id="clear-search"></i>
                </div>

                <div class="filter-row">
                    <button class="fchip active" data-filter="all">Semua</button>
                    <button class="fchip" data-filter="client">Klien</button>
                    <button class="fchip" data-filter="tukang">Tukang</button>
                </div>
            </div>

            <div class="contact-scroll" id="contact-scroll">
                <div id="contact-list">
                    <?php if (!empty($conversations)): ?>
                        <?php
                        $avClasses = ['av-purple','av-teal','av-coral','av-blue','av-amber'];
                        $i = 0;
                        foreach ($conversations as $convo):
                            $isTukang = ($convo['client_type'] === 'tukang');
                            $avClass  = $avClasses[$i % count($avClasses)];
                            $initials = implode('', array_map(fn($w) => strtoupper($w[0]), array_slice(explode(' ', $convo['client_name']), 0, 2)));
                            $unread   = intval($convo['unread_by_admin_count'] ?? 0);

                            $timeStr = '';
                            if (!empty($convo['last_message_at'])) {
                                $ts = strtotime($convo['last_message_at']);
                                $timeStr = date('Y-m-d', $ts) === date('Y-m-d')
                                    ? date('H:i', $ts)
                                    : date('d M', $ts);
                            }
                            $i++;
                        ?>
                        <div class="contact-item"
                             data-id="<?= $convo['id'] ?>"
                             data-name="<?= esc($convo['client_name']) ?>"
                             data-type="<?= esc($convo['client_type']) ?>">
                            <div class="av <?= $avClass ?>"><?= esc($initials) ?></div>
                            <div class="contact-info">
                                <div class="contact-name"><?= esc($convo['client_name']) ?></div>
                                <div class="contact-preview"><?= esc($convo['last_message_preview'] ?? 'Belum ada pesan') ?></div>
                            </div>
                            <div class="contact-meta">
                                <span class="contact-time"><?= $timeStr ?></span>
                                <div class="d-flex align-items-center gap-1">
                                    <span class="unread-badge" id="unread-badge-<?= $convo['id'] ?>" <?= $unread > 0 ? '' : 'style="display:none"' ?>><?= $unread ?></span>
                                    <span class="role-pill <?= $isTukang ? 'rp-tukang' : 'rp-klien' ?>"><?= $isTukang ? 'Tukang' : 'Klien' ?></span>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-5 text-muted" style="font-size:13px">
                            <i class="fas fa-inbox fa-2x mb-3 d-block opacity-25"></i>
                            Belum ada percakapan masuk.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </aside>

        <!-- ══ MAIN PANEL ═══════════════════════════════ -->
        <main class="chat-main">

            <!-- Placeholder (no chat selected) -->
            <div class="chat-placeholder" id="chat-placeholder">
                <div class="ph-icon"><i class="fas fa-comments"></i></div>
                <p class="ph-title mb-0">Pilih percakapan</p>
                <p class="ph-sub mb-0">Klik nama klien atau tukang di panel kiri untuk membuka riwayat pesan dan membalas secara langsung.</p>
            </div>

            <!-- Chat active panel (hidden initially) -->
            <div id="chat-active" style="display:none;flex-direction:column;flex:1;min-height:0;">

                <!-- Header -->
                <div class="chat-header">
                    <div class="header-av-wrap">
                        <div class="av av-purple" id="hdr-av">AD</div>
                        <div>
                            <div class="header-name" id="hdr-name">—</div>
                            <div class="header-sub">
                                <span class="online-dot"></span>
                                <span id="hdr-role">—</span>
                                <span>· Online</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="icon-btn" title="Profil"><i class="fas fa-user-circle"></i></button>
                        <button class="icon-btn" title="Lainnya"><i class="fas fa-ellipsis-h"></i></button>
                    </div>
                </div>

                <!-- Messages -->
                <div class="chat-messages" id="chat-messages">
                    <div class="no-messages">Memuat pesan…</div>
                </div>

                <!-- Footer -->
                <div class="chat-footer">
                    <form id="message-form" enctype="multipart/form-data">
                        <div id="attach-preview" class="attach-preview" style="display:none;">
                            <div class="d-flex align-items-center gap-2" style="min-width:0">
                                <i class="fas fa-file-alt text-primary" id="attach-icon"></i>
                                <span id="attach-name" class="text-truncate" style="font-size:12.5px;font-weight:500;color:#0f172a;max-width:240px"></span>
                            </div>
                            <button type="button" id="clear-attach" style="border:none;background:transparent;color:#E24B4A;cursor:pointer;font-size:13px;padding:0;flex-shrink:0;">
                                <i class="fas fa-times-circle"></i>
                            </button>
                        </div>

                        <div class="input-row">
                            <button type="button" class="attach-btn" id="attach-trigger" title="Lampiran">
                                <i class="fas fa-paperclip"></i>
                            </button>
                            <input type="file" id="file-input" name="file" style="display:none">
                            <textarea class="msg-input" id="msg-input" placeholder="Tulis pesan…" rows="1"></textarea>
                            <button type="submit" class="send-btn" title="Kirim"><i class="fas fa-paper-plane"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div><!-- .chat-card -->
</div><!-- .chat-root -->

<?php echo $this->endSection(); ?>

<?php echo $this->section('script'); ?>
<script>
(function () {
    /* ── State ───────────────────────────────── */
    let activeConvoId = null;
    let activeClientName = '';
    let activeFilter = 'all';
    let searchQuery = '';
    let csrfName = '<?= csrf_token() ?>';
    let csrfHash = '<?= csrf_hash() ?>';

    /* ── Helpers ─────────────────────────────── */
    const $ = id => document.getElementById(id);
    const esc = str => String(str ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    const fmt = body => esc(body).replace(/\n/g,'<br>');

    function fmtTime(dateStr) {
        try {
            const d = new Date(dateStr.replace(' ','T'));
            return ('0'+d.getHours()).slice(-2)+':'+('0'+d.getMinutes()).slice(-2);
        } catch(e) { return ''; }
    }

    function fmtDateSep(dateStr) {
        try {
            const d = new Date(dateStr.replace(' ','T'));
            const today = new Date(), yest = new Date();
            yest.setDate(today.getDate() - 1);
            if (d.toDateString() === today.toDateString()) return 'Hari ini';
            if (d.toDateString() === yest.toDateString())  return 'Kemarin';
            return d.toLocaleDateString('id-ID', {day:'numeric',month:'long',year:'numeric'});
        } catch(e) { return dateStr.split(' ')[0]; }
    }

    function scrollBottom() {
        const el = $('chat-messages');
        if (el) el.scrollTop = el.scrollHeight;
    }

    /* ── Sound ───────────────────────────────── */
    function playChime() {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            [[523.25, 0], [659.25, 0.09]].forEach(([freq, delay]) => {
                const osc = ctx.createOscillator();
                const g   = ctx.createGain();
                osc.type = 'sine';
                osc.frequency.setValueAtTime(freq, ctx.currentTime + delay);
                g.gain.setValueAtTime(0.1, ctx.currentTime + delay);
                g.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + delay + 0.2);
                osc.connect(g); g.connect(ctx.destination);
                osc.start(ctx.currentTime + delay);
                osc.stop(ctx.currentTime + delay + 0.2);
            });
        } catch(e) {}
    }

    /* ── Update total unread badge ───────────── */
    function updateTotalUnread() {
        let total = 0;
        document.querySelectorAll('[id^="unread-badge-"]').forEach(el => {
            total += parseInt(el.textContent) || 0;
        });
        const badge = $('badge-unread-total');
        if (!badge) return;
        badge.textContent = total + ' belum dibaca';
        badge.style.display = total > 0 ? '' : 'none';
    }

    /* ── Filter contacts in sidebar ──────────── */
    function filterContacts() {
        document.querySelectorAll('.contact-item').forEach(el => {
            const name = (el.dataset.name || '').toLowerCase();
            const type = el.dataset.type;
            const matchSearch = name.includes(searchQuery);
            const matchFilter = activeFilter === 'all' || type === activeFilter;
            el.style.display = matchSearch && matchFilter ? '' : 'none';
        });
    }

    /* ── Render message bubbles ──────────────── */
    function renderBubbleContent(msg) {
        const body = fmt(msg.body);
        const base = '<?= base_url() ?>/';

        if (msg.message_type === 'image') {
            return `<img src="${base}${esc(msg.file_url)}" class="chat-img mb-1" onclick="window.open(this.src,'_blank')" alt="Gambar">${body ? '<div class="mt-1">'+body+'</div>' : ''}`;
        }
        if (msg.message_type === 'video') {
            return `<video src="${base}${esc(msg.file_url)}" controls class="chat-vid mb-1"></video>${body ? '<div class="mt-1">'+body+'</div>' : ''}`;
        }
        if (msg.message_type === 'file') {
            return `<a href="${base}${esc(msg.file_url)}" target="_blank" class="file-link"><i class="fas fa-file-download"></i><span class="text-truncate">${body || 'Unduh Berkas'}</span></a>`;
        }
        if (msg.message_type === 'location') {
            return `<a href="https://www.google.com/maps?q=${msg.latitude},${msg.longitude}" target="_blank" class="file-link"><i class="fas fa-map-marker-alt text-danger"></i><span>Buka Lokasi</span></a>${body ? '<div class="mt-1">'+body+'</div>' : ''}`;
        }
        return `<div>${body}</div>`;
    }

    function loadMessages(convoId) {
        const url = `<?= site_url('admin/api/chat/') ?>${convoId}/messages`;
        $('chat-messages').innerHTML = '<div class="no-messages"><i class="fas fa-spinner fa-spin me-2"></i>Memuat pesan…</div>';

        fetch(url)
            .then(r => r.json())
            .then(response => {
                if (!response.status) return;
                const msgs = response.data || [];
                const container = $('chat-messages');
                container.innerHTML = '';

                if (!msgs.length) {
                    container.innerHTML = '<div class="no-messages">Belum ada riwayat pesan.</div>';
                    return;
                }

                let lastDate = null;
                msgs.forEach(msg => {
                    const isAdmin = msg.sender_type === 'admin';
                    const dateKey = msg.created_at.split(' ')[0];

                    if (dateKey !== lastDate) {
                        lastDate = dateKey;
                        const sep = document.createElement('div');
                        sep.className = 'date-sep';
                        sep.innerHTML = `<span class="date-pill">${fmtDateSep(msg.created_at)}</span>`;
                        container.appendChild(sep);
                    }

                    const row = document.createElement('div');
                    row.className = 'msg-row ' + (isAdmin ? 'me' : 'them');

                    const tick = isAdmin
                        ? (msg.is_read_by_client == 1
                            ? '<i class="fas fa-check-double tick-icon tick-read ms-1"></i>'
                            : '<i class="fas fa-check tick-icon ms-1"></i>')
                        : '';

                    const content = renderBubbleContent(msg);
                    const time    = fmtTime(msg.created_at);
                    const avColor = isAdmin ? 'av-purple' : 'av-teal';
                    const avText  = isAdmin ? 'AD' : (activeClientName.substring(0,2).toUpperCase() || 'CL');

                    if (isAdmin) {
                        row.innerHTML = `
                            <div>
                                <div class="bubble bubble-me">${content}</div>
                                <div class="msg-time">${time} ${tick}</div>
                            </div>
                            <div class="msg-av ${avColor}" style="background:#EEEDFE;color:#3C3489">${avText}</div>`;
                    } else {
                        row.innerHTML = `
                            <div class="msg-av ${avColor}" style="background:#E1F5EE;color:#085041">${avText}</div>
                            <div>
                                <div class="bubble bubble-them">${content}</div>
                                <div class="msg-time">${time}</div>
                            </div>`;
                    }
                    container.appendChild(row);
                });
                scrollBottom();
            })
            .catch(console.error);
    }

    /* ── Open conversation ───────────────────── */
    function openConvo(item) {
        const id   = item.dataset.id;
        const name = item.dataset.name;
        const type = item.dataset.type;

        activeConvoId    = id;
        activeClientName = name;

        document.querySelectorAll('.contact-item').forEach(el => el.classList.remove('active'));
        item.classList.add('active');

        // Reset unread
        const badge = $(`unread-badge-${id}`);
        if (badge) { badge.textContent = '0'; badge.style.display = 'none'; }
        updateTotalUnread();

        // Update header
        const initials = name.split(' ').slice(0,2).map(w => w[0].toUpperCase()).join('');
        $('hdr-av').textContent = initials;
        $('hdr-name').textContent = name;
        $('hdr-role').textContent = type === 'tukang' ? 'Tukang' : 'Klien';

        // Show chat panel
        $('chat-placeholder').style.display = 'none';
        const panel = $('chat-active');
        panel.style.display = 'flex';

        loadMessages(id);
    }

    /* ── Click on contact ────────────────────── */
    document.getElementById('contact-list').addEventListener('click', function(e) {
        const item = e.target.closest('.contact-item');
        if (item) openConvo(item);
    });

    /* ── Search ──────────────────────────────── */
    $('chat-search').addEventListener('input', function() {
        searchQuery = this.value.toLowerCase().trim();
        $('clear-search').style.display = searchQuery ? 'inline' : 'none';
        filterContacts();
    });
    $('clear-search').addEventListener('click', function() {
        $('chat-search').value = '';
        searchQuery = '';
        this.style.display = 'none';
        filterContacts();
    });

    /* ── Filter chips ────────────────────────── */
    document.querySelectorAll('.fchip').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.fchip').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            activeFilter = this.dataset.filter;
            filterContacts();
        });
    });

    /* ── Attachment ──────────────────────────── */
    $('attach-trigger').addEventListener('click', () => $('file-input').click());

    $('file-input').addEventListener('change', function() {
        if (!this.files.length) { $('attach-preview').style.display = 'none'; return; }
        const file = this.files[0];
        if (file.size > 20 * 1024 * 1024) {
            alert('Ukuran berkas terlalu besar (maks. 20 MB).');
            this.value = '';
            return;
        }
        const icon = $('attach-icon');
        icon.className = file.type.startsWith('image/') ? 'fas fa-file-image text-success'
            : file.type.startsWith('video/')  ? 'fas fa-file-video text-warning'
            : 'fas fa-file-alt text-primary';
        $('attach-name').textContent = file.name;
        $('attach-preview').style.display = 'flex';
    });

    $('clear-attach').addEventListener('click', () => {
        $('file-input').value = '';
        $('attach-preview').style.display = 'none';
    });

    /* ── Auto-grow textarea ──────────────────── */
    $('msg-input').addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 100) + 'px';
    });
    $('msg-input').addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); submitMessage(); }
    });

    /* ── Send message ────────────────────────── */
    $('message-form').addEventListener('submit', function(e) {
        e.preventDefault();
        submitMessage();
    });

    function submitMessage() {
        const input   = $('msg-input');
        const text    = input.value.trim();
        const fileEl  = $('file-input');
        const hasFile = fileEl.files.length > 0;

        if ((!text && !hasFile) || !activeConvoId) return;

        input.value = '';
        input.style.height = 'auto';

        const container = $('chat-messages');
        const empty     = container.querySelector('.no-messages');
        if (empty) empty.remove();

        // Date separator
        const pills = container.querySelectorAll('.date-pill');
        if (!pills.length || pills[pills.length-1].textContent.trim().toUpperCase() !== 'HARI INI') {
            const sep = document.createElement('div');
            sep.className = 'date-sep';
            sep.innerHTML = '<span class="date-pill">Hari ini</span>';
            container.appendChild(sep);
        }

        const now      = new Date();
        const timeStr  = ('0'+now.getHours()).slice(-2)+':'+('0'+now.getMinutes()).slice(-2);
        const tempId   = 'tmp-' + Date.now();
        const initials = activeClientName.substring(0,2).toUpperCase() || 'AD';

        // Optimistic bubble
        let contentHtml = `<div>${fmt(text)}</div>`;
        let objectUrl   = null;

        if (hasFile) {
            const file = fileEl.files[0];
            if (file.type.startsWith('image/')) {
                objectUrl   = URL.createObjectURL(file);
                contentHtml = `<img src="${objectUrl}" class="chat-img mb-1" id="oi-${tempId}">${text ? '<div class="mt-1">'+fmt(text)+'</div>' : ''}`;
            } else if (file.type.startsWith('video/')) {
                objectUrl   = URL.createObjectURL(file);
                contentHtml = `<video src="${objectUrl}" controls class="chat-vid mb-1" id="oi-${tempId}"></video>${text ? '<div class="mt-1">'+fmt(text)+'</div>' : ''}`;
            } else {
                contentHtml = `<div class="file-link"><i class="fas fa-file"></i><span class="text-truncate">${esc(file.name)}</span></div>${text ? '<div class="mt-1">'+fmt(text)+'</div>' : ''}`;
            }
        }

        const row = document.createElement('div');
        row.className = 'msg-row me';
        row.id = tempId;
        row.style.opacity = '0.7';
        row.innerHTML = `
            <div>
                <div class="bubble bubble-me">${contentHtml}</div>
                <div class="msg-time">${timeStr} <span id="ind-${tempId}"><i class="fas fa-spinner fa-spin" style="font-size:9px;color:#c7d2fe"></i></span></div>
            </div>
            <div class="msg-av av-purple" style="background:#EEEDFE;color:#3C3489">AD</div>`;
        container.appendChild(row);
        scrollBottom();

        // Update sidebar preview
        const activeItem = document.querySelector(`.contact-item[data-id="${activeConvoId}"]`);
        if (activeItem) {
            const prev = activeItem.querySelector('.contact-preview');
            const time = activeItem.querySelector('.contact-time');
            if (prev) prev.textContent = text || (hasFile ? '📎 Lampiran' : '');
            if (time) time.textContent = timeStr;
            const list = document.getElementById('contact-list');
            list.prepend(activeItem);
        }

        // AJAX
        let body, processData = true, contentType = 'application/x-www-form-urlencoded; charset=UTF-8';
        if (hasFile) {
            body = new FormData();
            body.append('conversation_id', activeConvoId);
            body.append('message', text);
            body.append('file', fileEl.files[0]);
            body.append(csrfName, csrfHash);
            processData = false;
            contentType = false;
        } else {
            body = { conversation_id: activeConvoId, message: text };
            body[csrfName] = csrfHash;
        }

        $('file-input').value = '';
        $('attach-preview').style.display = 'none';

        const ajaxOpts = {
            url: '<?= site_url('admin/api/chat/send') ?>',
            method: 'POST',
            success(res) {
                if (res.csrf_hash) csrfHash = res.csrf_hash;
                const bubble = document.getElementById(tempId);
                const ind    = document.getElementById('ind-' + tempId);
                if (res.status) {
                    bubble.style.opacity = '1';
                    if (ind) ind.innerHTML = '<i class="fas fa-check tick-icon" style="color:#c7d2fe;margin-left:2px"></i>';
                    if (objectUrl) URL.revokeObjectURL(objectUrl);
                } else {
                    markFailed(bubble, ind, objectUrl);
                }
            },
            error(xhr) {
                if (xhr.responseJSON?.csrf_hash) csrfHash = xhr.responseJSON.csrf_hash;
                const bubble = document.getElementById(tempId);
                const ind    = document.getElementById('ind-' + tempId);
                markFailed(bubble, ind, objectUrl);
            }
        };

        if (hasFile) {
            ajaxOpts.processData = false;
            ajaxOpts.contentType = false;
            ajaxOpts.data = body;
        } else {
            ajaxOpts.data = body;
        }

        $.ajax(ajaxOpts);
    }

    function markFailed(bubble, ind, objectUrl) {
        bubble.style.opacity = '1';
        bubble.querySelector('.bubble').classList.add('failed');
        if (ind) ind.innerHTML = '<span style="color:#fca5a5;font-size:9px;margin-left:2px"><i class="fas fa-exclamation-circle"></i> Gagal</span>';
        if (objectUrl) URL.revokeObjectURL(objectUrl);
    }

    /* ── Load conversations (sidebar refresh) ── */
    function loadConversations() {
        fetch('<?= site_url('admin/api/chat/conversations') ?>')
            .then(r => r.json())
            .then(res => {
                if (!res.status) return;
                if (res.csrf_hash) csrfHash = res.csrf_hash;
                renderConversations(res.data || []);
            })
            .catch(console.error);
    }

    function renderConversations(list) {
        const avClasses = ['av-purple','av-teal','av-coral','av-blue','av-amber'];
        const container = $('contact-list');
        container.innerHTML = '';

        if (!list.length) {
            container.innerHTML = '<div class="text-center py-5" style="font-size:13px;color:#94a3b8"><i class="fas fa-inbox fa-2x mb-3 d-block opacity-25"></i>Belum ada percakapan masuk.</div>';
            return;
        }

        list.forEach((c, idx) => {
            const isTukang  = c.client_type === 'tukang';
            const avClass   = avClasses[idx % avClasses.length];
            const initials  = (c.client_name || '??').split(' ').slice(0,2).map(w=>w[0].toUpperCase()).join('');
            const unread    = parseInt(c.unread_by_admin_count) || 0;
            const isActive  = String(c.id) === String(activeConvoId);

            let timeStr = '';
            if (c.last_message_at) {
                const ts    = new Date(c.last_message_at.replace(' ','T'));
                const today = new Date();
                timeStr = ts.toDateString() === today.toDateString()
                    ? ('0'+ts.getHours()).slice(-2)+':'+('0'+ts.getMinutes()).slice(-2)
                    : ts.getDate()+' '+['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'][ts.getMonth()];
            }

            const item = document.createElement('div');
            item.className = 'contact-item' + (isActive ? ' active' : '');
            item.dataset.id   = c.id;
            item.dataset.name = c.client_name;
            item.dataset.type = c.client_type;
            item.innerHTML = `
                <div class="av ${avClass}">${initials}</div>
                <div class="contact-info">
                    <div class="contact-name">${esc(c.client_name)}</div>
                    <div class="contact-preview">${esc(c.last_message_preview || 'Belum ada pesan')}</div>
                </div>
                <div class="contact-meta">
                    <span class="contact-time">${timeStr}</span>
                    <div class="d-flex align-items-center gap-1">
                        <span id="unread-badge-${c.id}" class="unread-badge" ${unread>0?'':'style="display:none"'}>${unread}</span>
                        <span class="role-pill ${isTukang?'rp-tukang':'rp-klien'}">${isTukang?'Tukang':'Klien'}</span>
                    </div>
                </div>`;
            container.appendChild(item);
        });

        filterContacts();
        updateTotalUnread();
    }

    /* ── FCM real-time listener ──────────────── */
    window.addEventListener('fcm_chat_received', function(e) {
        const payload = e.detail;
        if (payload?.data?.type === 'chat') {
            const incomingId = payload.data.conversation_id;
            if (incomingId == activeConvoId) {
                loadMessages(activeConvoId);
                if (document.hidden) playChime();
            } else {
                playChime();
            }
            loadConversations();
        }
    });

    /* ── Init ────────────────────────────────── */
    updateTotalUnread();

    /* jQuery shim for $.ajax if still using jQuery elsewhere */
    if (typeof $ === 'undefined' || typeof $.ajax === 'undefined') {
        window.$ = { ajax(opts) {
            const isFormData = opts.data instanceof FormData;
            const url  = opts.url;
            const init = { method: opts.method || 'GET' };
            if (isFormData) {
                init.body = opts.data;
            } else if (opts.data && opts.method !== 'GET') {
                init.headers = { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' };
                init.body = new URLSearchParams(opts.data).toString();
            }
            fetch(url, init)
                .then(r => r.json())
                .then(opts.success)
                .catch(err => opts.error && opts.error({ responseJSON: null, statusText: err.message }));
        }};
    }
})();
</script>

<?php echo $this->endSection(); ?>
