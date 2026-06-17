<div class="py-2">
    <!-- Info List -->
    <p class="section-title mb-4"><i class="fas fa-id-card me-2"></i>Informasi Profil Lengkap</p>

    <div class="info-list row g-3">
        <div class="col-md-6">
            <div class="info-item border-0 bg-light p-3 rounded-3 h-100">
                <div class="info-icon mb-2"><i class="fas fa-user"></i></div>
                <div>
                    <div class="info-label text-uppercase text-muted fw-bold" style="font-size:0.68rem; letter-spacing:0.5px;">Nama Lengkap</div>
                    <div class="info-value text-dark fw-semibold" style="font-size:0.95rem;"><?= esc($user['full_name'] ?? '-') ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="info-item border-0 bg-light p-3 rounded-3 h-100">
                <div class="info-icon mb-2"><i class="fas fa-id-card"></i></div>
                <div>
                    <div class="info-label text-uppercase text-muted fw-bold" style="font-size:0.68rem; letter-spacing:0.5px;">NIK</div>
                    <div class="info-value text-dark fw-semibold" style="font-size:0.95rem;"><?= esc($user['nik'] ?? '-') ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="info-item border-0 bg-light p-3 rounded-3 h-100">
                <div class="info-icon mb-2"><i class="fas fa-envelope"></i></div>
                <div>
                    <div class="info-label text-uppercase text-muted fw-bold" style="font-size:0.68rem; letter-spacing:0.5px;">Email</div>
                    <div class="info-value text-dark fw-semibold" style="font-size:0.95rem;"><?= esc($user['email'] ?? '-') ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="info-item border-0 bg-light p-3 rounded-3 h-100">
                <div class="info-icon mb-2"><i class="fab fa-whatsapp"></i></div>
                <div>
                    <div class="info-label text-uppercase text-muted fw-bold" style="font-size:0.68rem; letter-spacing:0.5px;">Nomor WhatsApp</div>
                    <div class="info-value text-dark fw-semibold" style="font-size:0.95rem;"><?= esc($user['phone_number'] ?? '-') ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="info-item border-0 bg-light p-3 rounded-3 h-100">
                <div class="info-icon mb-2"><i class="fas fa-venus-mars"></i></div>
                <div>
                    <div class="info-label text-uppercase text-muted fw-bold" style="font-size:0.68rem; letter-spacing:0.5px;">Jenis Kelamin</div>
                    <div class="info-value text-dark fw-semibold" style="font-size:0.95rem;">
                        <?php 
                        $gender = strtolower($user['gender'] ?? '');
                        if ($gender === 'l' || $gender === 'laki-laki' || $gender === 'laki_laki') {
                            echo 'Laki-laki';
                        } elseif ($gender === 'p' || $gender === 'perempuan') {
                            echo 'Perempuan';
                        } else {
                            echo esc($user['gender'] ?: '-');
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="info-item border-0 bg-light p-3 rounded-3 h-100">
                <div class="info-icon mb-2"><i class="fas fa-birthday-cake"></i></div>
                <div>
                    <div class="info-label text-uppercase text-muted fw-bold" style="font-size:0.68rem; letter-spacing:0.5px;">Tanggal Lahir</div>
                    <div class="info-value text-dark fw-semibold" style="font-size:0.95rem;">
                        <?= !empty($user['birth_date']) ? date('d M Y', strtotime($user['birth_date'])) : '-' ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="info-item border-0 bg-light p-3 rounded-3 h-100">
                <div class="info-icon mb-2"><i class="fas fa-map-marker-alt"></i></div>
                <div>
                    <div class="info-label text-uppercase text-muted fw-bold" style="font-size:0.68rem; letter-spacing:0.5px;">Alamat</div>
                    <div class="info-value text-dark fw-semibold" style="font-size:0.95rem;"><?= esc($user['address'] ?? '-') ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
