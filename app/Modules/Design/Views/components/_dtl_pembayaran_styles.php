<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

    .invoice-wrap,
    .invoice-wrap *:not(i):not([class*="fa"]) {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    /* ── Form Card ── */
    .inv-form-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .06), 0 8px 24px rgba(99, 102, 241, .08);
        overflow: hidden;
    }

    .inv-form-header {
        background: linear-gradient(135deg, #4f46e5 0%, #6366f1 60%, #818cf8 100%);
        padding: 20px 24px;
        position: relative;
        overflow: hidden;
    }

    .inv-form-header::before {
        content: '';
        position: absolute;
        right: -24px;
        top: -24px;
        width: 96px;
        height: 96px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .10);
    }

    .inv-form-header::after {
        content: '';
        position: absolute;
        right: 20px;
        bottom: -32px;
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .07);
    }

    .inv-form-header h6 {
        font-size: .9rem;
        font-weight: 700;
        letter-spacing: .3px;
    }

    /* ── Form controls ── */
    .inv-label {
        font-size: .72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .8px;
        color: #6b7280;
        margin-bottom: 6px;
    }

    .inv-control {
        border: 1.5px solid #e5e7eb;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: .875rem;
        color: #111827;
        transition: border-color .2s, box-shadow .2s;
        background: #fafafa;
    }

    .inv-control:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, .12);
        background: #fff;
        outline: none;
    }

    .inv-control option {
        background: #fff;
    }

    .inv-submit-btn {
        background: linear-gradient(135deg, #4f46e5, #6366f1);
        border: none;
        border-radius: 10px;
        padding: 12px;
        font-weight: 700;
        font-size: .875rem;
        letter-spacing: .3px;
        color: #fff;
        transition: transform .15s, box-shadow .15s, opacity .15s;
        box-shadow: 0 4px 14px rgba(99, 102, 241, .35);
    }

    .inv-submit-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(99, 102, 241, .45);
        opacity: .95;
    }

    .inv-submit-btn:active {
        transform: translateY(0);
    }

    /* ── List Card ── */
    .inv-list-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .06), 0 8px 24px rgba(99, 102, 241, .08);
        overflow: hidden;
        min-height: 510px;
    }

    .inv-list-header {
        background: linear-gradient(135deg, #4f46e5 0%, #6366f1 60%, #818cf8 100%);
        padding: 20px 24px 14px;
        position: relative;
        overflow: hidden;
    }

    .inv-list-header::before {
        content: '';
        position: absolute;
        right: -16px;
        top: -32px;
        width: 110px;
        height: 110px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .08);
    }

    .inv-list-header h6 {
        font-size: .9rem;
        font-weight: 700;
        letter-spacing: .3px;
    }

    /* ── Table rows ── */
    .inv-table tbody tr {
        transition: background .15s;
        border-bottom: 1px solid #f3f4f6;
    }

    .inv-table tbody tr:hover {
        background: #f5f3ff;
    }

    .inv-table tbody td {
        vertical-align: middle;
    }

    /* ── Invoice icon ── */
    .inv-icon-wrap {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: #ede9fe;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6366f1;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    /* ── Status badges ── */
    .badge-paid {
        background: #d1fae5;
        color: #065f46;
        border-radius: 20px;
        padding: 5px 12px;
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .4px;
    }

    .badge-unpaid {
        background: #fee2e2;
        color: #991b1b;
        border-radius: 20px;
        padding: 5px 12px;
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .4px;
    }

    /* ── Amount display ── */
    .inv-amount-final {
        font-size: .95rem;
        font-weight: 700;
        color: #4f46e5;
    }

    .inv-amount-old {
        font-size: .75rem;
        color: #9ca3af;
        text-decoration: line-through;
    }

    .inv-discount-tag {
        font-size: .68rem;
        font-weight: 700;
        color: #059669;
        background: #d1fae5;
        border-radius: 6px;
        padding: 2px 7px;
        display: inline-block;
    }

    /* ── Delete btn ── */
    .inv-del-btn {
        border: 1.5px solid #fca5a5;
        color: #ef4444;
        background: transparent;
        border-radius: 8px;
        padding: 6px 10px;
        font-size: .8rem;
        transition: background .15s, color .15s;
    }

    .inv-del-btn:hover {
        background: #ef4444;
        color: #fff;
        border-color: #ef4444;
    }

    /* ── Empty state ── */
    .inv-empty {
        padding: 64px 24px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
    }

    .inv-empty-icon {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        color: #d1d5db;
        margin-bottom: 8px;
    }

    /* ── Mobile card ── */
    .inv-mobile-card {
        border: 1.5px solid #f3f4f6;
        border-radius: 14px;
        padding: 14px;
        background: #fff;
        transition: box-shadow .2s;
    }

    .inv-mobile-card:hover {
        box-shadow: 0 4px 16px rgba(99, 102, 241, .12);
    }

    .inv-divider {
        border-top: 1.5px dashed #e5e7eb;
        margin-top: 12px;
        padding-top: 12px;
    }

    /* ── Form helper text ── */
    .inv-help-text {
        font-size: .73rem;
        color: #9ca3af;
        margin-top: 5px;
    }
</style>
