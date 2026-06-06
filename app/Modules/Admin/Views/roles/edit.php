<?= $this->extend('layout/app') ?>

<?= $this->section('title') ?>Edit Role - <?= esc($role['role_name'] ?? '') ?><?= $this->endSection() ?>
<?= $this->section('page_title') ?>Edit Role<?= $this->endSection() ?>

<?= $this->section('style') ?>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
    rel="stylesheet">
<style>
    * {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    :root {
        --indigo: var(--palette-primary);
        --indigo-light: #fff5f5;
        --indigo-mid: #ffb3b3;
        --slate-50: #f8fafc;
        --slate-100: #f1f5f9;
        --slate-200: #e2e8f0;
        --slate-400: #94a3b8;
        --slate-500: #64748b;
        --slate-700: #334155;
        --slate-900: #0f172a;
    }

    .cr-wrapper {
        max-width: 920px;
        margin: 0 auto;
    }

    .cr-back {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: .78rem;
        font-weight: 600;
        color: var(--slate-500);
        background: #fff;
        border: 1.5px solid var(--slate-200);
        border-radius: 8px;
        padding: 6px 14px;
        text-decoration: none;
        transition: all .18s;
        margin-bottom: 18px;
    }

    .cr-back:hover {
        color: var(--indigo);
        border-color: var(--indigo-mid);
        background: var(--indigo-light);
    }

    .cr-card {
        background: #fff;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 20px 60px -10px rgba(255, 92, 92, 0.11);
    }

    /* Banner */
    .cr-banner {
        background: linear-gradient(130deg, var(--palette-primary-hover) 0%, var(--palette-primary-hover) 55%, var(--palette-primary) 100%);
        padding: 30px 36px 36px;
        position: relative;
        overflow: hidden;
    }

    .cr-banner::before,
    .cr-banner::after {
        content: '';
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.05);
    }

    .cr-banner::before {
        width: 260px;
        height: 260px;
        top: -120px;
        right: -60px;
    }

    .cr-banner::after {
        width: 180px;
        height: 180px;
        bottom: -90px;
        left: 40px;
    }

    .cr-banner-inner {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .cr-banner-ico {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.12);
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .cr-banner-ico i {
        color: rgba(255, 255, 255, .85);
        font-size: 1.2rem;
    }

    .cr-banner h4 {
        color: #fff;
        font-weight: 800;
        font-size: 1.2rem;
        margin: 0 0 2px;
    }

    .cr-banner p {
        color: rgba(255, 255, 255, .75);
        font-size: .8rem;
        margin: 0;
    }

    .cr-body {
        padding: 28px 32px 32px;
    }

    .sec-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: .7rem;
        font-weight: 700;
        color: var(--indigo);
        text-transform: uppercase;
        letter-spacing: .8px;
        margin-bottom: 12px;
    }

    .sec-label::after {
        content: '';
        flex: 1;
        height: 1px;
        background: linear-gradient(90deg, #ffd3d3, transparent);
    }

    .cr-label {
        font-size: .78rem;
        font-weight: 700;
        color: var(--slate-700);
        margin-bottom: 8px;
        display: block;
    }

    .cr-label span {
        color: #ef4444;
    }

    .cr-input-wrap {
        display: flex;
        align-items: center;
        background: var(--slate-50);
        border: 1.5px solid var(--slate-200);
        border-radius: 12px;
        overflow: hidden;
        transition: all .2s;
    }

    .cr-input-wrap:focus-within {
        border-color: var(--indigo);
        background: #fff;
        box-shadow: 0 0 0 3px rgba(255, 92, 92, .1);
    }

    .cr-input-wrap .ico {
        padding: 0 14px;
        color: var(--slate-400);
        font-size: .85rem;
    }

    .cr-input-wrap:focus-within .ico {
        color: var(--indigo);
    }

    .cr-input-wrap input {
        flex: 1;
        border: none;
        background: transparent;
        outline: none;
        padding: 11px 14px 11px 0;
        font-size: .88rem;
        font-weight: 500;
        color: var(--slate-900);
    }

    .cr-input-wrap input::placeholder {
        color: var(--slate-400);
        font-weight: 400;
    }

    .cr-hint {
        font-size: .72rem;
        color: var(--slate-400);
        margin-top: 6px;
    }

    .cr-divider {
        height: 1px;
        background: var(--slate-100);
        margin: 24px 0;
    }

    /* Global counter bar */
    .cr-global-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: var(--slate-50);
        border: 1.5px solid var(--slate-200);
        border-radius: 10px;
        padding: 9px 16px;
        margin-bottom: 14px;
    }

    .cr-global-bar .gcb-label {
        font-size: .75rem;
        font-weight: 600;
        color: var(--slate-500);
    }

    .cr-global-bar .gcb-count {
        font-size: .78rem;
        font-weight: 800;
        color: var(--indigo);
    }

    /* Permission Panel */
    .perm-panel {
        border: 1.5px solid var(--slate-200);
        border-radius: 14px;
        overflow: hidden;
        display: flex;
        min-height: 380px;
    }

    /* Sidebar */
    .perm-sidebar {
        width: 195px;
        flex-shrink: 0;
        background: var(--slate-50);
        border-right: 1.5px solid var(--slate-200);
        display: flex;
        flex-direction: column;
    }

    .perm-sidebar-head {
        padding: 13px 16px 10px;
        font-size: .65rem;
        font-weight: 700;
        color: var(--slate-400);
        text-transform: uppercase;
        letter-spacing: .8px;
        border-bottom: 1px solid var(--slate-200);
    }

    .perm-tab {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 11px 16px;
        cursor: pointer;
        border-left: 3px solid transparent;
        transition: all .15s;
    }

    .perm-tab:hover {
        background: var(--indigo-light);
    }

    .perm-tab.active {
        background: #fff;
        border-left-color: var(--indigo);
        box-shadow: inset -1px 0 0 var(--slate-200);
    }

    .perm-tab .tab-icon {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        background: var(--slate-200);
        color: var(--slate-500);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .75rem;
        flex-shrink: 0;
        transition: all .15s;
    }

    .perm-tab.active .tab-icon {
        background: var(--indigo-light);
        color: var(--indigo);
    }

    .perm-tab .tab-info {
        flex: 1;
        min-width: 0;
    }

    .perm-tab .tab-name {
        font-size: .77rem;
        font-weight: 700;
        color: var(--slate-700);
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .perm-tab.active .tab-name {
        color: var(--indigo);
    }

    .perm-tab .tab-badge {
        font-size: .62rem;
        font-weight: 700;
        color: var(--slate-400);
        display: block;
        margin-top: 1px;
    }

    .perm-tab.active .tab-badge {
        color: var(--indigo);
    }

    .perm-sidebar-footer {
        margin-top: auto;
        padding: 12px 14px;
        border-top: 1px solid var(--slate-200);
    }

    .btn-select-all {
        width: 100%;
        font-size: .72rem;
        font-weight: 700;
        color: var(--indigo);
        background: var(--indigo-light);
        border: none;
        border-radius: 8px;
        padding: 7px 10px;
        cursor: pointer;
        transition: all .15s;
    }

    .btn-select-all:hover {
        background: var(--indigo);
        color: #fff;
    }

    /* Content panes */
    .perm-content {
        flex: 1;
        overflow-y: auto;
        padding: 20px 22px;
        max-height: 420px;
    }

    .tab-pane {
        display: none;
    }

    .tab-pane.active {
        display: block;
    }

    .pane-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }

    .pane-title {
        font-size: .78rem;
        font-weight: 800;
        color: var(--slate-700);
        text-transform: uppercase;
        letter-spacing: .5px;
    }

    .pane-counter {
        font-size: .68rem;
        font-weight: 700;
        color: var(--indigo);
        background: var(--indigo-light);
        padding: 2px 9px;
        border-radius: 20px;
    }

    /* Parent card */
    .perm-parent-card {
        border: 1.5px solid var(--slate-200);
        border-radius: 12px;
        margin-bottom: 10px;
        overflow: hidden;
        transition: border-color .15s;
        background: #fff;
    }

    .perm-parent-card.has-checked {
        border-color: var(--indigo-mid);
    }

    .perm-parent-row {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 11px 14px;
    }

    .perm-parent-row label {
        font-size: .82rem;
        font-weight: 700;
        color: var(--slate-700);
        cursor: pointer;
        flex: 1;
        margin: 0;
    }

    .perm-children {
        padding: 0 14px 13px 42px;
        display: flex;
        flex-wrap: wrap;
        gap: 7px;
    }

    .perm-standalone {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 14px;
    }

    /* Checkbox */
    .cr-check {
        position: relative;
        width: 18px;
        height: 18px;
        flex-shrink: 0;
    }

    .cr-check input {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }

    .cr-check .cm {
        position: absolute;
        inset: 0;
        border: 2px solid #cbd5e1;
        border-radius: 5px;
        background: #fff;
        cursor: pointer;
        transition: all .15s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .cr-check input:checked~.cm {
        background: var(--indigo);
        border-color: var(--indigo);
    }

    .cr-check .cm::after {
        content: '';
        width: 4px;
        height: 7px;
        border: 2px solid #fff;
        border-top: none;
        border-left: none;
        transform: rotate(45deg) translate(-1px, -1px);
        opacity: 0;
        transition: opacity .12s;
    }

    .cr-check input:checked~.cm::after {
        opacity: 1;
    }

    /* Pill */
    .perm-pill {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 5px 11px;
        background: var(--slate-50);
        border: 1.5px solid var(--slate-200);
        border-radius: 8px;
        cursor: pointer;
        transition: all .14s;
        user-select: none;
    }

    .perm-pill:hover {
        border-color: var(--indigo-mid);
        background: var(--indigo-light);
    }

    .perm-pill input {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
        pointer-events: none;
    }

    .perm-pill .pi {
        width: 13px;
        height: 13px;
        border-radius: 4px;
        border: 1.5px solid #cbd5e1;
        background: #fff;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all .14s;
        position: relative;
    }

    .perm-pill .pi::after {
        content: '';
        width: 3px;
        height: 5px;
        border: 1.5px solid transparent;
        border-top: none;
        border-left: none;
        transform: rotate(45deg) translate(-1px, -1px);
        transition: all .12s;
    }

    .perm-pill.on {
        background: var(--indigo-light);
        border-color: var(--indigo-mid);
    }

    .perm-pill.on .pi {
        background: var(--indigo);
        border-color: var(--indigo);
    }

    .perm-pill.on .pi::after {
        border-color: #fff;
    }

    .perm-pill span {
        font-size: .75rem;
        font-weight: 600;
        color: var(--slate-500);
    }

    .perm-pill.on span {
        color: var(--palette-primary-hover);
    }

    .parent-count {
        font-size: .65rem;
        font-weight: 700;
        background: var(--slate-100);
        color: var(--slate-500);
        padding: 2px 8px;
        border-radius: 20px;
        flex-shrink: 0;
    }

    .perm-parent-card.has-checked .parent-count {
        background: var(--indigo-light);
        color: var(--indigo);
    }

    /* Actions */
    .cr-actions {
        display: flex;
        gap: 12px;
        padding-top: 8px;
    }

    .btn-cr-save {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: linear-gradient(135deg, var(--palette-primary-hover), var(--palette-primary));
        color: #fff;
        font-weight: 700;
        font-size: .88rem;
        border: none;
        border-radius: 12px;
        padding: 13px 24px;
        cursor: pointer;
        transition: all .2s;
        box-shadow: 0 4px 14px rgba(255, 92, 92, .35);
    }

    .btn-cr-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 22px rgba(255, 92, 92, .45);
        color: #fff;
    }

    .btn-cr-cancel {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: #fff;
        color: var(--slate-500);
        font-weight: 700;
        font-size: .88rem;
        border: 1.5px solid var(--slate-200);
        border-radius: 12px;
        padding: 13px 24px;
        cursor: pointer;
        text-decoration: none;
        transition: all .2s;
        min-width: 130px;
    }

    .btn-cr-cancel:hover {
        border-color: #fda4af;
        color: #e11d48;
        background: #fff1f2;
    }

    .perm-content::-webkit-scrollbar {
        width: 5px;
    }

    .perm-content::-webkit-scrollbar-track {
        background: transparent;
    }

    .perm-content::-webkit-scrollbar-thumb {
        background: var(--slate-200);
        border-radius: 4px;
    }

    .perm-content::-webkit-scrollbar-thumb:hover {
        background: var(--indigo-mid);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
// Pre-calculate total
$grandTotal = 0;
foreach ($available_menus as $menus) {
    foreach ($menus as $k => $v) {
        $grandTotal++;
        if (is_array($v))
            $grandTotal += count($v['actions']);
    }
}
$groupIcons = [
    'MANAJEMEN' => 'fa-users-cog',
    'PROYEK' => 'fa-hard-hat',
    'KONTEN' => 'fa-layer-group',
    'AKSES' => 'fa-sliders-h',
];
$groupKeys = array_keys($available_menus);
$firstGroup = $groupKeys[0] ?? '';

$rolePermissions = [];
if (!empty($role['permissions'])) {
    $rolePermissions = json_decode($role['permissions'], true);
    if (!is_array($rolePermissions))
        $rolePermissions = [];
}
?>

<div class="cr-wrapper">
    <?= $this->include('App\Modules\Admin\Views\roles\components\_edit_form') ?>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= $this->include('App\Modules\Admin\Views\roles\components\_edit_scripts') ?>
<?= $this->endSection() ?>