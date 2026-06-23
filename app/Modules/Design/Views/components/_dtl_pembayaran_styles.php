<style>
    .invoice-wrap,
    .invoice-wrap *:not(i):not([class*="fa"]) {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    /* ══ Hero Card ══ */
    .inv-hero {
        background: linear-gradient(135deg, var(--palette-primary) 0%, var(--palette-primary-hover) 100%);
        border-radius: 20px;
        padding: 28px 32px 24px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(255,92,92,.25);
    }
    .inv-hero::before {
        content: '';
        position: absolute;
        right: -60px; top: -60px;
        width: 220px; height: 220px;
        border-radius: 50%;
        background: rgba(255,255,255,.07);
        pointer-events: none;
    }
    .inv-hero::after {
        content: '';
        position: absolute;
        right: 80px; bottom: -80px;
        width: 160px; height: 160px;
        border-radius: 50%;
        background: rgba(255,255,255,.05);
        pointer-events: none;
    }
    .inv-hero-label {
        font-size: .68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: rgba(255,255,255,.75);
    }
    .inv-hero-amount {
        font-size: 2.4rem;
        font-weight: 800;
        color: #fff;
        letter-spacing: -.5px;
        line-height: 1.1;
    }
    .inv-hero-sub {
        font-size: .78rem;
        color: rgba(255,255,255,.7);
        margin: 0;
    }
    .inv-hero-progress-bar {
        height: 8px;
        background: rgba(255,255,255,.18);
        border-radius: 99px;
        overflow: hidden;
        margin-top: 12px;
    }
    .inv-hero-progress-bar .bar-fill {
        height: 100%;
        background: #fff;
        border-radius: 99px;
        transition: width .6s ease;
        box-shadow: 0 0 8px rgba(255,255,255,0.4);
    }
    .inv-progress-pct {
        font-size: .75rem;
        font-weight: 700;
        color: #fff;
        margin-top: 6px;
    }

    /* ── Stat cards ── */
    .inv-stat-card {
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 14px;
        padding: 10px 14px;
        display: flex;
        align-items: center;
        gap: 10px;
        backdrop-filter: blur(10px);
        transition: all 0.25s ease;
        flex: 1 1 calc(33.333% - 12px);
        min-width: 130px;
    }
    .inv-stat-card:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }
    .inv-stat-card .sc-icon {
        font-size: 1.3rem;
        color: rgba(255, 255, 255, 0.9);
        background: rgba(255, 255, 255, 0.15);
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .inv-stat-card .sc-info {
        display: flex;
        flex-direction: column;
    }
    .inv-stat-card .sc-label {
        font-size: 0.62rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: rgba(255, 255, 255, 0.75);
        line-height: 1;
    }
    .inv-stat-card .sc-val {
        font-size: 0.92rem;
        font-weight: 800;
        color: #fff;
        margin-top: 3px;
        line-height: 1.1;
    }

    @media (max-width: 991px) {
        .inv-stat-card {
            flex: 1 1 calc(50% - 8px);
        }
    }
    @media (max-width: 480px) {
        .inv-stat-card {
            flex: 1 1 100%;
        }
    }

    /* ── Add btn ── */
    .inv-btn-add {
        background: linear-gradient(135deg, var(--palette-primary), var(--palette-primary-hover));
        color: #fff !important;
        border: none;
        border-radius: 10px;
        padding: 7px 14px;
        font-size: .78rem;
        font-weight: 700;
        transition: transform .15s, box-shadow .15s;
        box-shadow: 0 4px 10px rgba(255,92,92,.2);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }
    .inv-btn-add:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 14px rgba(255,92,92,.35);
        color: #fff !important;
    }

    /* ══ Section header (pill) ══ */
    .inv-section-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
        font-size: .8rem;
        font-weight: 700;
        color: var(--palette-primary);
        background: #fff1f1;
        border: 1.5px solid #fecaca;
        border-radius: 12px;
        padding: .6rem 1.2rem;
        margin-bottom: .75rem;
    }

    /* ══ Target Row (accordion-style) ══ */
    .inv-target-row {
        background: #fff;
        border: 1.5px solid #f3f4f6;
        border-radius: 14px;
        margin-bottom: 10px;
        overflow: hidden;
        transition: box-shadow .2s, border-color .2s;
    }
    .inv-target-row:hover {
        box-shadow: 0 4px 16px rgba(255,92,92,.08);
        border-color: #fecaca;
    }
    .inv-target-row.needs-setup {
        border-color: #fde68a;
        background: #fffdf5;
    }
    .inv-target-header {
        display: flex;
        align-items: center;
        padding: 14px 20px;
        gap: 14px;
        cursor: default;
    }
    .inv-target-num {
        width: 32px; height: 32px;
        border-radius: 8px;
        background: linear-gradient(135deg, var(--palette-primary), var(--palette-primary-hover));
        color: #fff;
        font-size: .75rem;
        font-weight: 800;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .inv-target-num.warn {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }
    .inv-target-num.done {
        background: linear-gradient(135deg, #10b981, #059669);
    }
    .inv-target-name {
        font-size: .9rem;
        font-weight: 700;
        color: #1e293b;
        flex: 1;
    }
    .inv-target-amount {
        font-size: .9rem;
        font-weight: 800;
        color: var(--palette-primary);
    }
    .inv-target-amount.null-amount {
        font-size: .78rem;
        font-weight: 600;
        color: #f59e0b;
    }

    /* ── Status badges ── */
    .badge-paid {
        background: #d1fae5; color: #065f46;
        border-radius: 20px; padding: 4px 12px;
        font-size: .7rem; font-weight: 700; letter-spacing: .4px;
        display: inline-block;
    }
    .badge-unpaid {
        background: #fee2e2; color: #991b1b;
        border-radius: 20px; padding: 4px 12px;
        font-size: .7rem; font-weight: 700; letter-spacing: .4px;
        display: inline-block;
    }
    .badge-pending-pay {
        background: #fef3c7; color: #92400e;
        border-radius: 20px; padding: 4px 12px;
        font-size: .7rem; font-weight: 700; letter-spacing: .4px;
        display: inline-block;
    }
    .badge-needs-setup {
        background: #fef3c7; color: #92400e;
        border-radius: 6px; padding: 2px 8px;
        font-size: .64rem; font-weight: 700;
        display: inline-block;
    }
    .badge-target-status {
        border-radius: 6px; padding: 2px 8px;
        font-size: .64rem; font-weight: 700; display: inline-block;
    }



    /* ══ Amount display ══ */
    .inv-amount-final {
        font-size: .88rem; font-weight: 800;
        color: var(--palette-primary);
    }
    .inv-amount-old {
        font-size: .73rem; color: #9ca3af; text-decoration: line-through;
    }
    .inv-discount-tag {
        font-size: .66rem; font-weight: 700; color: #059669;
        background: #d1fae5; border-radius: 6px; padding: 2px 7px;
        display: inline-block;
    }

    /* ══ Action buttons ══ */
    .inv-edit-btn {
        border: none;
        background: linear-gradient(135deg, var(--palette-primary), var(--palette-primary-hover));
        color: #fff; border-radius: 8px; padding: 5px 10px;
        font-size: .75rem; transition: transform .15s, box-shadow .15s;
        box-shadow: 0 2px 8px rgba(255,92,92,.2);
    }
    .inv-edit-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(255,92,92,.35);
        color: #fff;
    }
    .inv-del-btn {
        border: 1.5px solid #fca5a5; color: #ef4444;
        background: transparent; border-radius: 8px;
        padding: 5px 9px; font-size: .75rem;
        transition: background .15s, color .15s;
    }
    .inv-del-btn:hover { background: #ef4444; color: #fff; border-color: #ef4444; }

    /* ══ Empty ══ */
    .inv-empty {
        padding: 48px 24px;
        display: flex; flex-direction: column;
        align-items: center; gap: 8px; text-align: center;
    }
    .inv-empty-icon {
        width: 64px; height: 64px; border-radius: 50%;
        background: #fff1f1;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.6rem; color: #fca5a5; margin-bottom: 6px;
    }

    /* ══ Form controls (modal) ══ */
    .inv-label {
        font-size: .7rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .8px;
        color: #64748b; margin-bottom: 6px; display: block;
    }
    .inv-control {
        border: 1.5px solid #e2e8f0; border-radius: 10px;
        padding: 10px 14px; font-size: .875rem; color: #1e293b;
        transition: border-color .2s, box-shadow .2s; background: #fafbfc;
    }
    .inv-control:focus {
        border-color: var(--palette-primary);
        box-shadow: 0 0 0 3px rgba(255,92,92,.12);
        background: #fff; outline: none;
    }
    .inv-submit-btn {
        background: linear-gradient(135deg, var(--palette-primary), var(--palette-primary-hover));
        border: none; border-radius: 10px; padding: 12px;
        font-weight: 700; font-size: .875rem; color: #fff;
        transition: transform .15s, box-shadow .15s;
        box-shadow: 0 4px 14px rgba(255,92,92,.25);
    }
    .inv-submit-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(255,92,92,.35); color: #fff;
    }
    .inv-modal-header {
        background: linear-gradient(135deg, var(--palette-primary), var(--palette-primary-hover));
        border-radius: 16px 16px 0 0; padding: 1.25rem 1.5rem; position: relative;
    }
    .inv-modal-content {
        border-radius: 16px; border: none;
        box-shadow: 0 24px 64px rgba(0,0,0,.15); overflow: hidden;
    }
    .inv-help-text { font-size: .73rem; color: #94a3b8; margin-top: 5px; }


</style>
