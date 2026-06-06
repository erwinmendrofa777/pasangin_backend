<!-- Load Tabler Icons CDN so that user's ti classes render beautifully! -->


<h2 class="sr-only">Daftar pengajuan bahan dan alat dari tukang di lapangan</h2>

<div class="ms-root" style="position:relative; padding-bottom: 60px;">
  <div class="ms-header">
    <div class="ms-header-left">
      <div class="ms-header-icon"><i class="ti ti-package" aria-hidden="true"></i></div>
      <div>
        <div class="ms-header-title">Daftar Pengajuan Bahan &amp; Alat</div>
        <div class="ms-header-sub">Pengajuan tambahan dari Tukang / Pekerja di lapangan</div>
      </div>
    </div>
    <div class="ms-header-badges">
      <span class="ms-badge"><i class="ti ti-hammer" aria-hidden="true"></i> Bahan: <strong
          id="cnt-bahan">0</strong></span>
      <span class="ms-badge"><i class="ti ti-tool" aria-hidden="true"></i> Alat: <strong id="cnt-alat">0</strong></span>
      <span class="ms-badge total"><i class="ti ti-list" aria-hidden="true"></i> <strong id="cnt-total">0</strong>
        Total</span>
    </div>
  </div>

  <div class="ms-toolbar">
    <div class="ms-search-wrap">
      <i class="ti ti-search" aria-hidden="true"></i>
      <input class="ms-search" type="text" id="ms-search-input" placeholder="Cari pengajuan...">
    </div>
    <div class="ms-filter-group">
      <!-- Dropdown Filter Tipe -->
      <div class="dd-wrap" id="dd-filter-type">
        <button class="ms-filter-dd-btn" onclick="toggleFilterDd()" id="filter-dropdown-btn"
          aria-label="Filter tipe pengajuan" aria-haspopup="true" aria-expanded="false">
          <i class="ti ti-filter" aria-hidden="true"></i> Filter: Semua <i class="ti ti-chevron-down"
            style="font-size:10px;opacity:0.6;" aria-hidden="true"></i>
        </button>
        <div class="dd-menu filter-menu" role="menu">
          <div class="dd-item all-item" role="menuitem" onclick="setFilterType('all')"><i class="ti ti-list"
              aria-hidden="true"></i>Semua</div>
          <div class="dd-item bahan-item" role="menuitem" onclick="setFilterType('bahan')"><i class="ti ti-hammer"
              aria-hidden="true"></i>Bahan</div>
          <div class="dd-item alat-item" role="menuitem" onclick="setFilterType('alat')"><i class="ti ti-tool"
              aria-hidden="true"></i>Alat</div>
        </div>
      </div>
      <button class="ms-add-btn" onclick="openAddModal()">
        <i class="ti ti-plus" aria-hidden="true"></i> Tambah Pengajuan
      </button>
    </div>
  </div>

  <div class="ms-table-wrap">
    <table>
      <thead>
        <tr>
          <th style="width: 46px; text-align: center;">No</th>
          <th style="width: 180px;">Nama Tukang</th>
          <th>Title</th>
          <th style="width: 150px;">Tanggal</th>
          <th style="width: 110px; text-align: center;">Tipe</th>
          <th style="width: 145px; text-align: center;">Status</th>
          <th style="width: 150px; text-align: center;">Aksi</th>
        </tr>
      </thead>
      <tbody id="ms-tbody"></tbody>
    </table>
    <div class="empty-state" id="ms-empty" style="display:none;">
      <i class="ti ti-package-off" aria-hidden="true"></i>
      <p>Tidak ada data yang cocok</p>
    </div>
  </div>

  <div class="ms-footer">
    <span id="ms-info-text" style="color:#4b5563;">Memuat data...</span>
    <div class="pag-btns" id="pag-btns"></div>
  </div>

</div>

<!-- Form tersembunyi untuk posting pembaruan status -->
<form id="formUpdateStatusMaterial" action="" method="post" style="display:none;">
  <?= csrf_field() ?>
  <input type="hidden" name="status" id="inputStatusMaterial">
</form>

<!-- Modal Bootstrap 5 untuk Tambah / Edit Pengajuan -->
<div class="modal fade" id="modalMaterialSubmission" tabindex="-1" aria-labelledby="modalMaterialSubmissionLabel"
  aria-hidden="true" style="z-index: 1060;">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content ms-modal-content ms-root">
      <div class="ms-modal-header">
        <h5 class="modal-title" id="modalMaterialSubmissionLabel">Tambah Pengajuan</h5>
        <button type="button" class="btn-close-custom" data-bs-dismiss="modal" aria-label="Close">
          <i class="ti ti-x"></i>
        </button>
      </div>
      <form id="formMaterialSubmission" action="" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" name="renovation_id" value="<?= $renovation['id'] ?? '' ?>">
        <div class="modal-body" style="padding: 24px;">
          <!-- Judul Pengajuan -->
          <div class="mb-3">
            <label class="form-label" style="font-weight: 600; font-size: 13px; color: #4b5563; display: block; margin-bottom: 8px;">Judul Pengajuan</label>
            <input type="text" class="form-control" name="title" id="submission-title" placeholder="Contoh: Pengajuan Semen Cor Lantai 2" style="border: 1px solid #d1d5db; border-radius: 8px; padding: 8px 12px; font-size: 13px; color: #1a1a2e; width: 100%; outline: none; background: #ffffff;">
          </div>

          <!-- Tukang yang Mengajukan -->
          <div class="mb-3">
            <label class="form-label" style="font-weight: 600; font-size: 13px; color: #4b5563; display: block; margin-bottom: 8px;">Tukang Yang Mengajukan</label>
            <div class="custom-select-wrapper" style="position: relative; width: 100%;">
              <input type="hidden" name="job_applications_id" id="submission-job-app" value="">
              <button type="button" class="custom-select-trigger" onclick="toggleCustomSelect(event)" style="display: flex; align-items: center; justify-content: space-between; width: 100%; height: 42px; padding: 0 14px; font-size: 13px; font-weight: 500; color: #1a1a2e; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 8px; outline: none; cursor: pointer; transition: all 0.2s ease;">
                <span style="display: flex; align-items: center; gap: 8px;">
                  <i class="ti ti-user" style="font-size: 16px; color: #6b7280;" aria-hidden="true"></i>
                  <span id="custom-select-label">-- Pilih Tukang (Opsional) --</span>
                </span>
                <i class="ti ti-chevron-down" style="font-size: 14px; color: #6b7280; transition: transform 0.2s; pointer-events: none;" id="custom-select-arrow" aria-hidden="true"></i>
              </button>
              <div class="custom-select-options" id="custom-select-options-list" style="display: none; position: absolute; top: calc(100% + 6px); left: 0; right: 0; background: #ffffff; border: 1px solid #e2e4e9; border-radius: 10px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08); z-index: 1080; max-height: 200px; overflow-y: auto; padding: 6px;">
                <div class="custom-select-option" onclick="selectCustomOption('', '-- Pilih Tukang (Opsional) --')" style="padding: 10px 12px; font-size: 13px; color: #4b5563; border-radius: 6px; cursor: pointer; transition: background 0.15s; display: flex; align-items: center; gap: 8px;">
                  <i class="ti ti-user-x" style="font-size: 14px; color: #9ca3af;" aria-hidden="true"></i>
                  <span>-- Pilih Tukang (Opsional) --</span>
                </div>
                <?php if (!empty($applicants)): ?>
                  <?php foreach ($applicants as $app): ?>
                    <div class="custom-select-option" data-value="<?= $app['id'] ?>" onclick="selectCustomOption('<?= $app['id'] ?>', '<?= esc($app['tukang_name']) ?>')" style="padding: 10px 12px; font-size: 13px; color: #1a1a2e; border-radius: 6px; cursor: pointer; transition: background 0.15s; display: flex; align-items: center; justify-content: space-between;">
                      <span style="display: flex; align-items: center; gap: 8px;">
                        <i class="ti ti-user" style="font-size: 14px; color: var(--palette-primary-hover);" aria-hidden="true"></i>
                        <span style="font-weight: 500;"><?= esc($app['tukang_name']) ?></span>
                      </span>
                      <span style="font-size: 11px; padding: 2px 8px; border-radius: 12px; background: #f3f4f6; color: #6b7280; font-weight: 600;"><?= esc($app['status']) ?></span>
                    </div>
                  <?php endforeach; ?>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <!-- Tipe Pengajuan Selector -->
          <div class="mb-3">
            <label class="form-label"
              style="font-weight: 600; font-size: 13px; color: #4b5563; display: block; margin-bottom: 8px;">Tipe
              Pengajuan</label>
            <div class="ms-type-selector">
              <input type="radio" name="type" id="type-bahan" value="bahan" checked style="display: none;">
              <label for="type-bahan" class="ms-type-card bahan">
                <i class="ti ti-hammer" aria-hidden="true"></i>
                <div class="card-text">
                  <span class="title">Bahan</span>
                </div>
              </label>

              <input type="radio" name="type" id="type-alat" value="alat" style="display: none;">
              <label for="type-alat" class="ms-type-card alat">
                <i class="ti ti-tool" aria-hidden="true"></i>
                <div class="card-text">
                  <span class="title">Alat</span>
                </div>
              </label>
            </div>
          </div>

          <!-- Deskripsi Textarea -->
          <div class="mb-3">
            <label class="form-label"
              style="font-weight: 600; font-size: 13px; color: #4b5563; display: block; margin-bottom: 8px;">Deskripsi /
              Daftar Item</label>
            <div class="ms-textarea-container">
              <textarea class="form-control" name="description" id="submission-description" rows="5" required
                style="border: none; outline: none; box-shadow: none; padding: 12px; font-size: 13px; font-weight: 500; resize: none; background: transparent; width: 100%; height: 100%; min-height: 120px; color: #1a1a2e;"></textarea>
              <div class="ms-textarea-hint">
                <i class="ti ti-info-circle" aria-hidden="true"></i> Pisahkan setiap item dengan baris baru (Enter)
              </div>
            </div>
          </div>

          <!-- Foto Bukti/Kebutuhan -->
          <div class="mb-3">
            <label class="form-label" style="font-weight: 600; font-size: 13px; color: #4b5563; display: block; margin-bottom: 8px;">Foto Bukti/Kebutuhan</label>
            <div class="ms-file-upload-wrap" style="position: relative; border: 2px dashed #d1d5db; border-radius: 8px; padding: 16px; text-align: center; background: #f9fafb; cursor: pointer; transition: all 0.2s ease;">
              <input type="file" name="photo" id="submission-photo" accept="image/*" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; z-index: 2;" onchange="handleFileChange(this)">
              <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 6px; z-index: 1;">
                <i class="ti ti-camera" style="font-size: 26px; color: var(--palette-primary);" aria-hidden="true"></i>
                <div style="font-size: 13px; font-weight: 600; color: var(--palette-primary-hover);" id="photo-file-label">Pilih Foto atau Ambil Gambar</div>
                <div style="font-size: 11px; color: #6b7280;">Format: JPG, PNG, WebP (Maks. 5MB)</div>
              </div>
            </div>
            <div id="edit-photo-preview-wrap" style="display:none; margin-top: 12px; text-align: center;">
              <div style="font-size: 11px; color: #6b7280; margin-bottom: 4px;">Preview Foto:</div>
              <img id="edit-photo-preview" src="" style="max-height: 120px; border-radius: 8px; border: 1px solid #e2e4e9; box-shadow: 0 4px 10px rgba(0,0,0,0.08);">
            </div>
          </div>
        </div>
        <div class="modal-footer" style="border-top: 1px solid #f0f0f5; padding: 1rem 1.5rem; gap: 8px;">
          <button type="button" class="ms-btn-secondary" data-bs-dismiss="modal">
            <i class="ti ti-x" aria-hidden="true"></i> Batal
          </button>
          <button type="submit" class="ms-btn-primary">
            <i class="ti ti-device-floppy" aria-hidden="true"></i> Simpan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Bootstrap 5 untuk Detail Pengajuan -->
<div class="modal fade" id="modalMaterialDetail" tabindex="-1" aria-labelledby="modalMaterialDetailLabel"
  aria-hidden="true" style="z-index: 1060;">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content ms-modal-content ms-root">
      <div class="ms-modal-header">
        <h5 class="modal-title" id="modalMaterialDetailLabel">Detail Pengajuan Bahan & Alat</h5>
        <button type="button" class="btn-close-custom" data-bs-dismiss="modal" aria-label="Close">
          <i class="ti ti-x"></i>
        </button>
      </div>
      <div class="modal-body" style="padding: 24px;">
        <!-- Header Info (Judul) -->
        <div style="margin-bottom: 20px;">
          <h4 id="detail-title" style="font-weight: 700; color: #1a1a2e; margin-bottom: 8px; font-size: 18px; word-break: break-word;">Judul Pengajuan</h4>
          <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
            <span id="detail-type" class="type-badge"></span>
            <span id="detail-status" class="status-pill" style="cursor: default; pointer-events: none;"></span>
          </div>
        </div>

        <!-- Info Grid -->
        <div class="detail-info-grid">
          <div class="detail-info-item">
            <span class="detail-info-label">Diajukan Oleh</span>
            <span id="detail-tukang" class="detail-info-value">-</span>
          </div>
          <div class="detail-info-item">
            <span class="detail-info-label">Tanggal Pengajuan</span>
            <span id="detail-date" class="detail-info-value">-</span>
          </div>
        </div>

        <div class="row">
          <!-- Left Column: Items List -->
          <div class="col-md-6 mb-3">
            <div class="detail-section-title">
              <i class="ti ti-list-check"></i> Daftar Item
            </div>
            <div id="detail-items-container" style="max-height: 250px; overflow-y: auto; padding-right: 4px;">
              <!-- Items populated dynamically -->
            </div>
          </div>

          <!-- Right Column: Photo Attachment -->
          <div class="col-md-6 mb-3" id="detail-photo-container">
            <div class="detail-section-title">
              <i class="ti ti-photo"></i> Foto Bukti / Kebutuhan
            </div>
            <div class="detail-photo-wrapper">
              <a id="detail-photo-link" href="" target="_blank" title="Buka gambar di tab baru">
                <img id="detail-photo-img" src="" alt="Foto Bukti">
              </a>
            </div>
          </div>
        </div>

        <!-- Catatan Admin (Feedback) -->
        <div id="detail-comment-wrap" style="display: none; margin-top: 15px;">
          <div class="detail-section-title" style="color: #b45309;">
            <i class="ti ti-message" style="color: #b45309;"></i> Catatan dari Admin
          </div>
          <div class="detail-comment-box" id="detail-comment-text">
            Catatan...
          </div>
        </div>
      </div>
      <div class="modal-footer" style="border-top: 1px solid #f0f0f5; padding: 1rem 1.5rem;">
        <button type="button" class="ms-btn-secondary" data-bs-dismiss="modal">
          <i class="ti ti-x" aria-hidden="true"></i> Tutup
        </button>
      </div>
    </div>
  </div>
</div>
