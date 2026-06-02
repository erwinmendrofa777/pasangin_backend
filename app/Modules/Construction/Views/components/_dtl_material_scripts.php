<script>
  // Load real database data dynamically from PHP
  const demo = <?= json_encode(array_map(function ($sub) {
    $description = trim($sub['description'] ?? '');
    $items = [];
    $decoded = json_decode($description, true);
    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
      $items = $decoded;
    } else {
      $jsonLike = str_replace("'", '"', $description);
      $decodedJsonLike = json_decode($jsonLike, true);
      if (json_last_error() === JSON_ERROR_NONE && is_array($decodedJsonLike)) {
        $items = $decodedJsonLike;
      } else {
        if (strpos($description, '[') === 0 && strrpos($description, ']') === strlen($description) - 1) {
          $cleanDesc = trim($description, '[]');
          preg_match_all('/\'([^\']*)\'|"([^"]*)"|([^,]+)/', $cleanDesc, $matches);
          $combined = array_merge(array_filter($matches[1]), array_filter($matches[2]));
          if (!empty($combined)) {
            $items = $combined;
          } else {
            $items = array_map(function ($val) {
              return trim($val, " '\"");
            }, explode(',', $cleanDesc));
          }
        } else {
          if (strpos($description, "\n") !== false) {
            $items = explode("\n", $description);
          } else {
            $items = [$description];
          }
        }
      }
    }
    return [
      'id' => (int) $sub['id'],
      'type' => $sub['type'],
      'items' => array_filter(array_map('trim', $items)),
      'date' => $sub['created_at'],
      'status' => $sub['status'],
      'title' => $sub['title'] ?? '',
      'comment' => $sub['comment'] ?? '',
      'photo_url' => !empty($sub['photo']) ? base_url('uploads/construction/material_submissions/' . $sub['photo']) : null,
      'job_applications_id' => $sub['job_applications_id'] ?? null,
      'tukang_name' => $sub['tukang_name'] ?? null
    ];
  }, $material_submissions ?? [])) ?>;

  let data = demo.map(d => ({ ...d }));
  let filter = 'all', search = '', page = 1;
  const PER_PAGE = 5;

  const statusConfig = {
    pending: { icon: 'ti-clock', label: 'PENDING', cls: 'pending' },
    approved: { icon: 'ti-circle-check', label: 'APPROVED', cls: 'approved' },
    rejected: { icon: 'ti-circle-x', label: 'REJECTED', cls: 'rejected' },
  };
  const typeConfig = {
    bahan: { icon: 'ti-hammer', label: 'BAHAN' },
    alat: { icon: 'ti-tool', label: 'ALAT' },
  };

  function filtered() {
    return data.filter(r => {
      const matchType = filter === 'all' || (r.type || '').toLowerCase() === filter.toLowerCase();
      const q = search.toLowerCase();
      const matchSearch = !q ||
        r.items.some(i => i.toLowerCase().includes(q)) ||
        (r.type || '').toLowerCase().includes(q) ||
        (r.title || '').toLowerCase().includes(q) ||
        (r.tukang_name || '').toLowerCase().includes(q) ||
        (r.status || '').toLowerCase().includes(q);
      return matchType && matchSearch;
    });
  }

  function fmtDate(str) {
    if (!str) return '-';
    const d = new Date(str.replace(' ', 'T'));
    if (isNaN(d.getTime())) return str;
    return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
  }
  function fmtTime(str) {
    if (!str) return '';
    return str.split(' ')[1] || '';
  }

  function renderBadges() {
    document.getElementById('cnt-bahan').textContent = data.filter(d => (d.type || '').toLowerCase() === 'bahan').length;
    document.getElementById('cnt-alat').textContent = data.filter(d => (d.type || '').toLowerCase() === 'alat').length;
    document.getElementById('cnt-total').textContent = data.length;
  }

  function renderTable() {
    const rows = filtered();
    const total = rows.length;
    const totalPages = Math.max(1, Math.ceil(total / PER_PAGE));
    if (page > totalPages) page = totalPages;
    const start = (page - 1) * PER_PAGE;
    const slice = rows.slice(start, start + PER_PAGE);

    const tbody = document.getElementById('ms-tbody');
    const empty = document.getElementById('ms-empty');

    if (slice.length === 0) {
      tbody.innerHTML = '';
      empty.style.display = '';
    } else {
      empty.style.display = 'none';
      tbody.innerHTML = slice.map((r, i) => {
        const statusKey = (r.status || 'pending').toLowerCase();
        const sc = statusConfig[statusKey] || statusConfig.pending;

        const typeKey = (r.type || 'bahan').toLowerCase();
        const tc = typeConfig[typeKey] || typeConfig.bahan;

        const globalIdx = start + i + 1;
        return `<tr>
        <td class="row-no"><span>${globalIdx}</span></td>
        <td style="font-weight: 600; color: #1a1a2e; word-break: break-word;">${r.tukang_name || 'Admin'}</td>
        <td style="font-weight: 500; font-size: 13px; color: #1b1b2f; word-break: break-word;">${r.title || 'Pengajuan Tanpa Judul'}</td>
        <td>
          <div class="time-date">${fmtDate(r.date)}</div>
          <div class="time-clock"><i class="ti ti-clock" aria-hidden="true"></i>${fmtTime(r.date)}</div>
        </td>
        <td style="text-align: center;">
          <span class="type-badge ${typeKey}"><i class="ti ${tc.icon}" aria-hidden="true"></i>${tc.label}</span>
        </td>
        <td class="status-cell">
          <div class="dd-wrap" id="dd-${r.id}">
            <button class="status-pill ${sc.cls}" onclick="toggleDd(${r.id})" aria-label="Ubah status" aria-haspopup="true" aria-expanded="false">
              <i class="ti ${sc.icon}" aria-hidden="true"></i>${sc.label}
              <i class="ti ti-chevron-down" style="font-size:10px;opacity:0.6;" aria-hidden="true"></i>
            </button>
            <div class="dd-menu" role="menu">
              <div class="dd-item pending"  role="menuitem" onclick="setStatus(${r.id},'pending')"><i class="ti ti-clock" aria-hidden="true"></i>Pending</div>
              <div class="dd-item approved" role="menuitem" onclick="setStatus(${r.id},'approved')"><i class="ti ti-circle-check" aria-hidden="true"></i>Approved</div>
              <div class="dd-item rejected" role="menuitem" onclick="setStatus(${r.id},'rejected')"><i class="ti ti-circle-x" aria-hidden="true"></i>Rejected</div>
            </div>
          </div>
        </td>
        <td style="text-align: center; white-space: nowrap;">
          <button class="btn-action detail" onclick="openDetailModal(${r.id})" title="Detail"><i class="ti ti-eye" aria-hidden="true"></i></button>
          <button class="btn-action edit" onclick="openEditModal(${r.id})" title="Edit"><i class="ti ti-edit" aria-hidden="true"></i></button>
          <button class="btn-action delete" onclick="deleteSubmission(${r.id})" title="Hapus"><i class="ti ti-trash" aria-hidden="true"></i></button>
        </td>
      </tr>`;

      }).join('');
    }

    const infoEl = document.getElementById('ms-info-text');
    infoEl.textContent = total === 0
      ? 'Tidak ada data'
      : `Menampilkan ${start + 1}–${Math.min(start + PER_PAGE, total)} dari ${total} data`;

    const pagEl = document.getElementById('pag-btns');
    let h = `<button class="pag-btn" onclick="goPage(${page - 1})" ${page === 1 ? 'disabled' : ''} aria-label="Sebelumnya"><i class="ti ti-chevron-left" aria-hidden="true"></i></button>`;
    for (let p = 1; p <= totalPages; p++) {
      h += `<button class="pag-btn ${p === page ? 'active' : ''}" onclick="goPage(${p})">${p}</button>`;
    }
    h += `<button class="pag-btn" onclick="goPage(${page + 1})" ${page === totalPages ? 'disabled' : ''} aria-label="Berikutnya"><i class="ti ti-chevron-right" aria-hidden="true"></i></button>`;
    pagEl.innerHTML = h;
  }

  function goPage(p) {
    const total = Math.max(1, Math.ceil(filtered().length / PER_PAGE));
    if (p < 1 || p > total) return;
    page = p; renderTable();
  }

  function toggleDd(id) {
    document.querySelectorAll('.dd-wrap').forEach(el => {
      if (el.id !== 'dd-' + id) {
        el.classList.remove('open', 'dropup');
        el.querySelector('button')?.setAttribute('aria-expanded', 'false');
      }
    });

    const el = document.getElementById('dd-' + id);
    if (!el) return;

    // Calculate remaining space below button to determine dropup direction
    const rect = el.getBoundingClientRect();
    const spaceBelow = window.innerHeight - rect.bottom;
    const menuHeight = 160; // Estimated dropdown menu height

    if (spaceBelow < menuHeight) {
      el.classList.add('dropup');
    } else {
      el.classList.remove('dropup');
    }

    const open = el.classList.toggle('open');
    el.querySelector('button').setAttribute('aria-expanded', String(open));
  }

  function setStatus(id, status) {
    const row = data.find(r => r.id === id);
    if (!row || (row.status || '').toLowerCase() === status.toLowerCase()) { closeAll(); return; }

    let title = '';
    let confirmText = '';
    let color = '';
    let desc = '';

    const typeLabel = (row.type || 'pengajuan').toLowerCase();

    if (status === 'approved') {
      title = 'Setujui Pengajuan?';
      confirmText = 'Ya, Setujui!';
      color = '#47c363';
      desc = `Pengajuan ${typeLabel} ini akan disetujui and tukang akan menerima notifikasinya.`;
    } else if (status === 'rejected') {
      title = 'Tolak Pengajuan?';
      confirmText = 'Ya, Tolak!';
      color = '#fc544b';
      desc = `Pengajuan ${typeLabel} ini akan ditolak and tukang akan menerima notifikasinya.`;
    } else {
      title = 'Kembalikan ke Pending?';
      confirmText = 'Ya, Ubah!';
      color = '#ffa426';
      desc = `Status pengajuan ${typeLabel} ini akan dikembalikan ke pending.`;
    }

    // Gunakan Stisla SweetAlert2 jika ada, atau fallback native confirm
    if (typeof Swal !== 'undefined') {
      Swal.fire({
        title: title,
        text: desc,
        icon: 'question',
        input: 'text',
        inputPlaceholder: 'Tambahkan catatan admin (opsional)...',
        showCancelButton: true,
        confirmButtonColor: color,
        cancelButtonColor: '#6c757d',
        confirmButtonText: `<i class="fas fa-check me-1"></i> ${confirmText}`,
        cancelButtonText: 'Batal',
      }).then((result) => {
        if (result.isConfirmed) {
          const actionUrl = "<?= base_url('admin/construction/update-material-status') ?>/" + id;
          const form = document.getElementById('formUpdateStatusMaterial');
          form.action = actionUrl;
          document.getElementById('inputStatusMaterial').value = status;
          
          let commentInput = form.querySelector('input[name="comment"]');
          if (!commentInput) {
            commentInput = document.createElement('input');
            commentInput.type = 'hidden';
            commentInput.name = 'comment';
            form.appendChild(commentInput);
          }
          commentInput.value = result.value || '';

          form.submit();
        } else {
          closeAll();
        }
      });
    } else {
      const comment = prompt(`${title}\n\n${desc}\n\nTambahkan catatan admin (opsional):`);
      if (comment !== null) {
        const actionUrl = "<?= base_url('admin/construction/update-material-status') ?>/" + id;
        const form = document.getElementById('formUpdateStatusMaterial');
        form.action = actionUrl;
        document.getElementById('inputStatusMaterial').value = status;
        
        let commentInput = form.querySelector('input[name="comment"]');
        if (!commentInput) {
          commentInput = document.createElement('input');
          commentInput.type = 'hidden';
          commentInput.name = 'comment';
          form.appendChild(commentInput);
        }
        commentInput.value = comment;

        form.submit();
      } else {
        closeAll();
      }
    }
  }

  function closeAll() {
    document.querySelectorAll('.dd-wrap').forEach(el => {
      el.classList.remove('open', 'dropup');
      el.querySelector('button')?.setAttribute('aria-expanded', 'false');
    });
  }

  document.getElementById('ms-search-input').addEventListener('input', e => { search = e.target.value; page = 1; renderTable(); });
  function toggleFilterDd() {
    const el = document.getElementById('dd-filter-type');
    if (!el) return;
    const isOpen = el.classList.contains('open');
    closeAll();
    if (!isOpen) {
      el.classList.add('open');
      el.querySelector('button').setAttribute('aria-expanded', 'true');
    }
  }

  function setFilterType(type) {
    filter = type;
    page = 1;
    const btn = document.getElementById('filter-dropdown-btn');
    const labels = { all: 'Semua', bahan: 'Bahan', alat: 'Alat' };
    btn.innerHTML = `<i class="ti ti-filter" aria-hidden="true"></i> Filter: ${labels[type]} <i class="ti ti-chevron-down" style="font-size:10px;opacity:0.6;" aria-hidden="true"></i>`;
    const el = document.getElementById('dd-filter-type');
    if (el) {
      el.classList.remove('open');
      el.querySelector('button').setAttribute('aria-expanded', 'false');
    }
    renderTable();
  }

  // Global click event to close dropdown when clicking outside
  document.addEventListener('click', e => {
    if (!e.target.closest('.dd-wrap')) {
      closeAll();
    }
    if (!e.target.closest('.custom-select-wrapper')) {
      closeCustomSelect();
    }
  });

  function toggleCustomSelect(e) {
    if (e) e.stopPropagation();
    const list = document.getElementById('custom-select-options-list');
    const arrow = document.getElementById('custom-select-arrow');
    if (!list || !arrow) return;
    const isOpen = list.style.display === 'block';
    
    // Close other dropdowns
    closeAll();
    
    if (!isOpen) {
      list.style.display = 'block';
      arrow.style.transform = 'rotate(180deg)';
    } else {
      list.style.display = 'none';
      arrow.style.transform = 'rotate(0deg)';
    }
  }

  function selectCustomOption(value, labelText) {
    document.getElementById('submission-job-app').value = value;
    document.getElementById('custom-select-label').textContent = labelText;
    
    document.querySelectorAll('.custom-select-option').forEach(opt => {
      opt.style.background = 'transparent';
      opt.style.color = '#1a1a2e';
    });
    
    if (value) {
      const selectedOpt = document.querySelector(`.custom-select-option[data-value="${value}"]`);
      if (selectedOpt) {
        selectedOpt.style.background = '#ede9fe';
        selectedOpt.style.color = '#5b21b6';
      }
    }
    
    closeCustomSelect();
  }

  function closeCustomSelect() {
    const list = document.getElementById('custom-select-options-list');
    const arrow = document.getElementById('custom-select-arrow');
    if (list) list.style.display = 'none';
    if (arrow) arrow.style.transform = 'rotate(0deg)';
  }

  function handleFileChange(input) {
    const label = document.getElementById('photo-file-label');
    const preview = document.getElementById('edit-photo-preview');
    const previewWrap = document.getElementById('edit-photo-preview-wrap');
    
    if (input.files && input.files[0]) {
      const file = input.files[0];
      label.textContent = file.name;
      
      const reader = new FileReader();
      reader.onload = function(e) {
        preview.src = e.target.result;
        previewWrap.style.display = 'block';
      };
      reader.readAsDataURL(file);
    } else {
      label.textContent = 'Pilih Foto atau Ambil Gambar';
      previewWrap.style.display = 'none';
      preview.src = '';
    }
  }

  function openAddModal() {
    document.getElementById('modalMaterialSubmissionLabel').textContent = 'Tambah Pengajuan';
    document.getElementById('formMaterialSubmission').action = '<?= base_url("admin/construction/add-material-submission") ?>';
    document.getElementById('type-bahan').checked = true;
    document.getElementById('submission-title').value = '';
    
    // Reset custom select
    document.getElementById('submission-job-app').value = '';
    document.getElementById('custom-select-label').textContent = '-- Pilih Tukang (Opsional) --';
    document.querySelectorAll('.custom-select-option').forEach(opt => {
      opt.style.background = 'transparent';
      opt.style.color = '#1a1a2e';
    });

    document.getElementById('submission-description').value = '';
    document.getElementById('submission-photo').value = '';
    document.getElementById('photo-file-label').textContent = 'Pilih Foto atau Ambil Gambar';
    document.getElementById('edit-photo-preview-wrap').style.display = 'none';
    document.getElementById('edit-photo-preview').src = '';

    if (typeof bootstrap !== 'undefined') {
      const modalEl = document.getElementById('modalMaterialSubmission');
      const modalInstance = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
      modalInstance.show();
    } else {
      $('#modalMaterialSubmission').modal('show');
    }
  }

  function openDetailModal(id) {
    const row = data.find(r => r.id === id);
    if (!row) return;

    // Populate the detail modal fields
    document.getElementById('detail-title').textContent = row.title || 'Pengajuan Tanpa Judul';
    document.getElementById('detail-tukang').textContent = row.tukang_name || 'Admin';
    
    // Type badge
    const typeEl = document.getElementById('detail-type');
    const typeKey = (row.type || 'bahan').toLowerCase();
    const tc = typeConfig[typeKey] || typeConfig.bahan;
    typeEl.className = `type-badge ${typeKey}`;
    typeEl.innerHTML = `<i class="ti ${tc.icon}"></i> ${tc.label}`;

    // Status pill
    const statusEl = document.getElementById('detail-status');
    const statusKey = (row.status || 'pending').toLowerCase();
    const sc = statusConfig[statusKey] || statusConfig.pending;
    statusEl.className = `status-pill ${sc.cls}`;
    statusEl.innerHTML = `<i class="ti ${sc.icon}"></i> ${sc.label}`;

    // Date and time
    document.getElementById('detail-date').innerHTML = `<i class="ti ti-calendar" style="margin-right: 4px;"></i>${fmtDate(row.date)} <i class="ti ti-clock" style="margin-left: 12px; margin-right: 4px;"></i>${fmtTime(row.date)}`;

    // Items list
    const itemsContainer = document.getElementById('detail-items-container');
    itemsContainer.innerHTML = '';
    if (row.items && row.items.length > 0) {
      row.items.forEach(item => {
        const itemEl = document.createElement('div');
        itemEl.className = 'detail-item-card';
        itemEl.innerHTML = `<i class="ti ti-cube"></i> <span>${item}</span>`;
        itemsContainer.appendChild(itemEl);
      });
    } else {
      itemsContainer.innerHTML = '<div style="color: #9ca3af; font-style: italic; font-size: 13px; padding: 8px;">Tidak ada deskripsi item</div>';
    }

    // Photo preview
    const photoContainer = document.getElementById('detail-photo-container');
    const photoImg = document.getElementById('detail-photo-img');
    const photoLink = document.getElementById('detail-photo-link');
    if (row.photo_url) {
      photoImg.src = row.photo_url;
      photoLink.href = row.photo_url;
      photoContainer.style.display = 'block';
    } else {
      photoContainer.style.display = 'none';
      photoImg.src = '';
      photoLink.href = '';
    }

    // Admin comment box
    const commentWrap = document.getElementById('detail-comment-wrap');
    const commentText = document.getElementById('detail-comment-text');
    if (row.comment) {
      commentText.textContent = row.comment;
      commentWrap.style.display = 'block';
    } else {
      commentWrap.style.display = 'none';
      commentText.textContent = '';
    }

    if (typeof bootstrap !== 'undefined') {
      const modalEl = document.getElementById('modalMaterialDetail');
      const modalInstance = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
      modalInstance.show();
    } else {
      $('#modalMaterialDetail').modal('show');
    }
  }

  function openEditModal(id) {
    const row = data.find(r => r.id === id);
    if (!row) return;

    document.getElementById('modalMaterialSubmissionLabel').textContent = 'Edit Pengajuan';
    document.getElementById('formMaterialSubmission').action = '<?= base_url("admin/construction/update-material-submission") ?>/' + id;

    document.getElementById('submission-title').value = row.title || '';
    
    // Populate custom select
    document.getElementById('submission-job-app').value = row.job_applications_id || '';
    const label = row.tukang_name ? row.tukang_name : '-- Pilih Tukang (Opsional) --';
    document.getElementById('custom-select-label').textContent = label;
    document.querySelectorAll('.custom-select-option').forEach(opt => {
      opt.style.background = 'transparent';
      opt.style.color = '#1a1a2e';
    });
    if (row.job_applications_id) {
      const selectedOpt = document.querySelector(`.custom-select-option[data-value="${row.job_applications_id}"]`);
      if (selectedOpt) {
        selectedOpt.style.background = '#ede9fe';
        selectedOpt.style.color = '#5b21b6';
      }
    }
    
    const type = (row.type || 'bahan').toLowerCase();
    if (type === 'alat') {
      document.getElementById('type-alat').checked = true;
    } else {
      document.getElementById('type-bahan').checked = true;
    }

    document.getElementById('submission-description').value = row.items.join('\n');
    document.getElementById('submission-photo').value = '';

    if (row.photo_url) {
      document.getElementById('photo-file-label').textContent = 'Ganti Foto Bukti';
      document.getElementById('edit-photo-preview-wrap').style.display = 'block';
      document.getElementById('edit-photo-preview').src = row.photo_url;
    } else {
      document.getElementById('photo-file-label').textContent = 'Pilih Foto atau Ambil Gambar';
      document.getElementById('edit-photo-preview-wrap').style.display = 'none';
      document.getElementById('edit-photo-preview').src = '';
    }

    if (typeof bootstrap !== 'undefined') {
      const modalEl = document.getElementById('modalMaterialSubmission');
      const modalInstance = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
      modalInstance.show();
    } else {
      $('#modalMaterialSubmission').modal('show');
    }
  }

  function deleteSubmission(id) {
    const row = data.find(r => r.id === id);
    if (!row) return;

    const typeLabel = (row.type || 'pengajuan').toLowerCase();

    if (typeof Swal !== 'undefined') {
      Swal.fire({
        title: 'Hapus Pengajuan?',
        text: `Apakah Anda yakin ingin menghapus pengajuan ${typeLabel} ini? Tindakan ini tidak dapat dibatalkan.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#fc544b',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-trash me-1"></i> Ya, Hapus!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = '<?= base_url("admin/construction/delete-material-submission") ?>/' + id;
        }
      });
    } else {
      if (confirm(`Apakah Anda yakin ingin menghapus pengajuan ${typeLabel} ini?`)) {
        window.location.href = '<?= base_url("admin/construction/delete-material-submission") ?>/' + id;
      }
    }
  }

  renderBadges();
  renderTable();
</script>
