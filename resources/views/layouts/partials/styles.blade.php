<style>
    /* Background Styles */
    .gradient-bg {
        background: #3C8F3A;
    }
    .dark .gradient-bg {
        background: #3C8F3A;
    }
    .card-gradient {
        background: linear-gradient(135deg, #ffffff 0%, #f7fafc 100%);
    }
    .dark .card-gradient {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    }

    /* Card Animations */
    .metric-card {
        transition: all 0.3s ease;
    }
    .metric-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }

    /* Table Hover Effects */
    .table-row-hover:hover {
        background: linear-gradient(90deg, #f7fafc 0%, #edf2f7 100%);
    }
    .dark .table-row-hover:hover {
        background: linear-gradient(90deg, #1e293b 0%, #0f172a 100%);
    }

    /* Progress Bar Styles */
    .progress-bar {
        width: 100%;
        height: 6px;
        background-color: #e2e8f0;
        border-radius: 9999px;
        overflow: hidden;
    }
    .progress-bar-fill {
        height: 100%;
        border-radius: 9999px;
        transition: width 0.3s ease;
    }
    .dark .progress-bar {
        background-color: #334155;
    }

    /* Modal Overlay */
    .modal-overlay {
        backdrop-filter: blur(4px);
    }

    /* Dropdown Styles */
    .dropdown-content {
        min-width: 12rem;
        transform-origin: top right;
    }

    /* Transition Effects */
    .transition-all {
        transition-property: all;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 150ms;
    }

    /* Focus States */
    .focus\:ring-2:focus {
        --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);
        --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(2px + var(--tw-ring-offset-width)) var(--tw-ring-color);
        box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000);
    }

    /* Custom Colors */
    .text-emerald-800 {
        color: rgb(6, 95, 70);
    }
    .dark .text-emerald-800 {
        color: rgb(4, 120, 87);
    }

    /* Responsive Adjustments */
    @media (max-width: 640px) {
        .sm\:flex-col {
            flex-direction: column;
        }
        .sm\:space-y-4 > * + * {
            margin-top: 1rem;
        }
    }
</style>