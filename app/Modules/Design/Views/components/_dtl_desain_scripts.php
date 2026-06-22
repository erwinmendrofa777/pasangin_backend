<script>
    let uploadedFiles = [];
    const designFileInput = document.getElementById('designFileInput');
    const dropzoneArea = document.getElementById('dropzoneArea');
    const previewContainer = document.getElementById('modalUploadPreviewList');
    const previewList = previewContainer ? previewContainer.querySelector('.preview-files-list') : null;
    const objectNameInput = document.getElementById('3dObjectNameInput');

    // Trigger click on file input when dropzone is clicked
    if (dropzoneArea && designFileInput) {
        dropzoneArea.addEventListener('click', function (e) {
            designFileInput.click();
        });
        designFileInput.addEventListener('click', function (e) {
            e.stopPropagation();
        });
    }

    // Drag and Drop Event Listeners
    if (dropzoneArea) {
        ['dragenter', 'dragover'].forEach(eventName => {
            dropzoneArea.addEventListener(eventName, function (e) {
                e.preventDefault();
                e.stopPropagation();
                dropzoneArea.classList.add('dragover');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropzoneArea.addEventListener(eventName, function (e) {
                e.preventDefault();
                e.stopPropagation();
                dropzoneArea.classList.remove('dragover');
            }, false);
        });

        dropzoneArea.addEventListener('drop', function (e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files && files.length > 0) {
                addFilesToQueue(files);
            }
        }, false);
    }

    function addFilesToQueue(files) {
        Array.from(files).forEach(file => {
            uploadedFiles.push(file);
        });
        renderUploadPreviews();
    }

    function formatBytes(bytes, decimals = 1) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }

    function renderUploadPreviews() {
        if (!previewList || !previewContainer) return;
        
        previewList.innerHTML = '';
        const objectVal = objectNameInput ? objectNameInput.value.trim() : '';

        let hasItems = false;

        // Render 3D object name if present
        if (objectVal.length > 0) {
            hasItems = true;
            const itemHtml = `
                <div class="preview-item">
                    <div class="preview-item-info">
                        <div class="preview-thumb-container file-icon-3d">
                            <i class="fas fa-cubes" style="font-size: 16px;"></i>
                        </div>
                        <div class="preview-file-details">
                            <div class="preview-file-name" title="${objectVal}">3D: ${objectVal}</div>
                            <small class="preview-file-size">Objek Unity / String</small>
                        </div>
                    </div>
                    <button type="button" class="btn-remove-preview" onclick="clear3dInput()" title="Hapus Objek 3D">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            previewList.insertAdjacentHTML('beforeend', itemHtml);
        }

        // Render all files
        uploadedFiles.forEach((file, index) => {
            hasItems = true;
            const ext = file.name.split('.').pop().toLowerCase();
            
            let badgeClass = 'file-icon-general';
            let iconClass = 'far fa-file-alt';
            let isImage = false;
            let thumbUrl = '';

            if (file.type.startsWith('image/')) {
                isImage = true;
                thumbUrl = URL.createObjectURL(file);
                badgeClass = 'file-icon-image';
            } else if (file.type === 'application/pdf' || ext === 'pdf') {
                badgeClass = 'file-icon-pdf';
                iconClass = 'far fa-file-pdf';
            } else if (file.type.startsWith('video/') || ['mp4', 'mov', 'avi', 'webm', 'mkv'].includes(ext)) {
                badgeClass = 'file-icon-video';
                iconClass = 'far fa-file-video';
            } else if (['obj', 'fbx', 'glb', 'gltf', 'dwg', 'rvt'].includes(ext)) {
                badgeClass = 'file-icon-3d';
                iconClass = 'fas fa-cubes';
            }

            const visualBlock = isImage 
                ? `<img src="${thumbUrl}" class="preview-thumb-img" onload="URL.revokeObjectURL('${thumbUrl}')">` 
                : `<i class="${iconClass}" style="font-size: 16px;"></i>`;

            const itemHtml = `
                <div class="preview-item">
                    <div class="preview-item-info">
                        <div class="preview-thumb-container ${badgeClass}">
                            ${visualBlock}
                        </div>
                        <div class="preview-file-details">
                            <div class="preview-file-name" title="${file.name}">${file.name}</div>
                            <small class="preview-file-size">${formatBytes(file.size)}</small>
                        </div>
                    </div>
                    <button type="button" class="btn-remove-preview" onclick="removeUploadedFile(${index})" title="Hapus Berkas">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            previewList.insertAdjacentHTML('beforeend', itemHtml);
        });

        const placeholder = document.getElementById('previewPlaceholder');
        if (hasItems) {
            if (placeholder) placeholder.classList.add('d-none');
            previewList.classList.remove('d-none');
            previewList.classList.add('d-flex');
        } else {
            if (placeholder) placeholder.classList.remove('d-none');
            previewList.classList.add('d-none');
            previewList.classList.remove('d-flex');
        }
    }

    if (designFileInput) {
        designFileInput.addEventListener('change', function (e) {
            if (e.target.files && e.target.files.length > 0) {
                addFilesToQueue(e.target.files);
                e.target.value = ''; // Reset to enable same-file selecting
            }
        });
    }

    if (objectNameInput) {
        objectNameInput.addEventListener('input', function () {
            renderUploadPreviews();
        });
    }

    window.removeUploadedFile = function (index) {
        uploadedFiles.splice(index, 1);
        renderUploadPreviews();
    };

    window.clear3dInput = function () {
        if (objectNameInput) {
            objectNameInput.value = '';
        }
        renderUploadPreviews();
    };

    // Modal Reset on hidden
    const modalEl = document.getElementById('modalUploadDesign');
    if (modalEl) {
        modalEl.addEventListener('hidden.bs.modal', function () {
            uploadedFiles = [];
            const form = document.getElementById('uploadDesignForm');
            if (form) form.reset();
            renderUploadPreviews();
        });
    }

    const uploadForm = document.getElementById('uploadDesignForm');
    if (uploadForm) {
        uploadForm.addEventListener('submit', function (e) {
            const objectInput = document.getElementById('3dObjectNameInput');
            const hasFiles = uploadedFiles.length > 0;
            const hasObject = objectInput && objectInput.value.trim().length > 0;
            
            if (!hasFiles && !hasObject) {
                e.preventDefault();
                if (typeof iziToast !== 'undefined') {
                    iziToast.error({
                        title: 'Validasi Gagal',
                        message: 'Wajib mengunggah file desain atau mengisi nama objek 3D!',
                        position: 'topRight'
                    });
                } else {
                    alert('Wajib mengunggah file desain atau mengisi nama objek 3D!');
                }
                return;
            }

            // Sync internal uploadedFiles array back to HTML input files before submission!
            if (designFileInput) {
                const dt = new DataTransfer();
                uploadedFiles.forEach(file => dt.items.add(file));
                designFileInput.files = dt.files;
            }
        });
    }
</script>
