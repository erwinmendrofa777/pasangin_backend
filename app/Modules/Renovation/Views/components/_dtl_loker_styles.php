<style>
    /* ── Loker Canvas Design System (Primary Theme) ── */
    :root {
        --canvas-primary: var(--palette-primary);
        --canvas-primary-dark: var(--palette-primary-hover);
        --canvas-success: #47c363;
        --canvas-warning: #ffa426;
        --canvas-danger: #fc544b;
        --canvas-dark: #1e293b;
        --canvas-text: #64748b;
        --canvas-bg: #f8fafc;
        --canvas-radius: 20px;
    }

    .canvas-wrapper {
        font-family: 'Inter', 'Nunito', sans-serif;
        color: var(--canvas-dark);
        animation: canvasFadeIn 0.6s ease-out;
    }

    @keyframes canvasFadeIn {
        from {
            opacity: 0;
            transform: translateY(15px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Hero Card (Primary BG) */
    .hero-primary-card {
        background: linear-gradient(135deg, var(--canvas-primary) 0%, var(--canvas-primary-dark) 100%);
        border-radius: var(--canvas-radius);
        border: none;
        box-shadow: 0 10px 25px rgba(255, 92, 92, 0.25);
        color: #fff;
        margin-bottom: 35px;
    }

    .stat-divider {
        width: 1px;
        height: 50px;
        background: rgba(255, 255, 255, 0.2);
    }

    .hero-stat-label {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        opacity: 0.8;
        margin-bottom: 5px;
        display: block;
    }

    .hero-stat-value {
        font-size: 1.5rem;
        font-weight: 800;
        margin: 0;
    }

    /* Main Grid */
    .canvas-grid {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 30px;
    }

    .canvas-card {
        background: #fff;
        border-radius: var(--canvas-radius);
        border: 1px solid #edf2f7;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.03);
        margin-bottom: 30px;
        overflow: hidden;
    }

    .canvas-card-header {
        padding: 20px 25px;
        background: #fff;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .header-accent {
        width: 4px;
        height: 20px;
        background: var(--canvas-primary);
        border-radius: 10px;
        margin-right: 12px;
    }

    /* Info Display */
    .info-group {
        margin-bottom: 22px;
    }

    .info-label {
        font-size: 0.72rem;
        font-weight: 800;
        color: var(--canvas-primary);
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 6px;
        display: block;
    }

    .info-content {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--canvas-dark);
        line-height: 1.6;
    }

    /* Form Elements */
    .canvas-input {
        width: 100%;
        padding: 12px 16px;
        border-radius: 12px;
        border: 2px solid #edf2f7;
        background: #fdfdff;
        font-size: 0.9rem;
        transition: all 0.2s;
    }

    .canvas-input:focus {
        border-color: var(--canvas-primary);
        background: #fff;
        outline: none;
        box-shadow: 0 0 0 4px rgba(255, 92, 92, 0.1);
    }

    .canvas-btn-primary {
        background: var(--canvas-primary);
        color: #fff;
        border: none;
        padding: 16px 24px;
        border-radius: 14px;
        font-weight: 800;
        font-size: 1rem;
        width: 100%;
        transition: all 0.3s;
        box-shadow: 0 5px 15px rgba(255, 92, 92, 0.3);
    }

    .canvas-btn-primary:hover {
        background: var(--canvas-primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(255, 92, 92, 0.4);
    }

    /* Radar List */
    .radar-item {
        padding: 20px;
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.2s;
    }

    .radar-item:last-child {
        border-bottom: none;
    }

    .radar-item:hover {
        background: #fcfcfd;
    }

    /* Mobile */
    @media (max-width: 1100px) {
        .canvas-grid {
            grid-template-columns: 1fr;
        }

        .stat-divider {
            display: none;
        }

        .hero-stat-item {
            text-align: center;
            margin-bottom: 15px;
        }

        .hero-stat-item:last-child {
            margin-bottom: 0;
        }
    }
</style>
