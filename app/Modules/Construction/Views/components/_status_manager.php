<!-- ======================== RIGHT: UPDATE STATUS ======================== -->
<div class="card action-card">
    <!-- Card Header -->
    <div class="card-header">
        <h6 class="text-white mb-0 fw-bold">
            <i class="fas fa-sliders-h mr-2"></i>Kelola Status Proyek
        </h6>
    </div>

    <div class="card-body p-2 pt-2">
        <form id="updateStatusFormDirect"
            action="<?= base_url('admin/construction/update-status') ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= $construction['id'] ?>">
            <input type="hidden" name="status" id="selectedStatusInput"
                value="<?= $conStatus ?>">

            <div class="d-flex flex-column" style="gap: 10px;">
                <?php foreach ($conStatusMeta as $key => $act):
                    $isActive = ($conStatus === $key);
                    ?>
                    <button type="button"
                        class="btn <?= $isActive ? 'btn-' . $act['color'] . ' btn-current-status' : 'btn-outline-' . $act['color'] ?> status-action-btn text-left w-100"
                        data-status="<?= $key ?>" data-color="<?= $act['color'] ?>"
                        data-is-active="<?= $isActive ? 'true' : 'false' ?>">
                        <div
                            class="d-flex align-items-center justify-content-between w-100">
                            <div class="d-flex align-items-center gap-2">
                                <i class="<?= $act['icon'] ?>"
                                    style="width:20px; text-align:center;"></i>
                                <div class="ml-2">
                                    <div
                                        style="font-size:0.88rem; font-weight:700; line-height:1.2; text-align: left;">
                                        <?= $act['label'] ?>
                                    </div>
                                    <div
                                        style="font-size:0.72rem; font-weight:400; opacity:0.75; text-align: left;">
                                        <?= $act['desc'] ?>
                                    </div>
                                </div>
                            </div>
                            <?php if ($isActive): ?>
                                <i class="fas fa-check-circle status-icon ml-2"
                                    style="font-size:1rem;"></i>
                            <?php else: ?>
                                <i class="fas fa-chevron-right status-icon ml-2"
                                    style="font-size:0.75rem; opacity:0.6;"></i>
                            <?php endif; ?>
                        </div>
                    </button>
                <?php endforeach; ?>
            </div>

            <div class="mt-4 pt-3 border-top text-center">
                <button type="submit"
                    class="btn btn-primary btn-block btn-lg ladda-button shadow-sm"
                    data-style="zoom-in" style="border-radius: 8px; font-weight: bold;">
                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                </button>
            </div>
        </form>

        <div class="mt-3 pt-3 border-top">
            <p class="text-muted mb-0" style="font-size:0.78rem;">
                <i class="fas fa-info-circle text-primary mr-1"></i>
                Pilih status baru lalu klik tombol Simpan. Tombol berwarna solid
                adalah
                status saat ini.
            </p>
        </div>
    </div>
</div>
