<style>
    .animate-up {
        animation: pembayaranFadeUp 0.4s ease both;
    }

    @keyframes pembayaranFadeUp {
        from {
            opacity: 0;
            transform: translateY(15px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .rab-click-row:hover {
        background-color: #f0f7ff !important;
    }

    .rab-click-row.rab-selected {
        background-color: #cfe2ff !important;
        border-left: 4px solid #6777ef !important;
    }

    .shadow-primary {
        box-shadow: 0 4px 15px rgba(103, 119, 239, 0.4);
    }
</style>
