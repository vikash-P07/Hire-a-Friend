<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Companion Booking Platform')</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Dark Mode Initializer -->
    <script>
        (function() {
            const isDark = localStorage.getItem('darkMode') === 'true' || 
                (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);
            if (isDark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>
    
    <!-- Custom Premium SaaS Styling -->
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-hover: #1d4ed8;
            --secondary-color: #7c3aed;
            --secondary-hover: #6d28d9;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;

            /* Light mode color space */
            --bg-color: #f8fafc;
            --card-bg: #ffffff;
            --card-border: #e2e8f0;
            --text-dark: #0f172a;
            --text-muted: #64748b;
            --navbar-bg: rgba(255, 255, 255, 0.85);
            --dropdown-glass-bg: rgba(255, 255, 255, 0.45);
            --footer-bg: #ffffff;
            --footer-border: #e2e8f0;
            --input-bg: #ffffff;
            --input-border: #cbd5e1;
            --input-color: #0f172a;
            --input-focus-border: #2563eb;
            --input-focus-shadow: rgba(37, 99, 235, 0.1);
            --table-thead-bg: #f8fafc;
            --badge-bg-subtle: #eff6ff;
            --badge-text-subtle: #2563eb;

            --shadow-sm: 0 1px 2px 0 rgba(15, 23, 42, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(15, 23, 42, 0.05), 0 2px 4px -2px rgba(15, 23, 42, 0.05);
            --shadow-lg: 0 20px 25px -5px rgba(15, 23, 42, 0.08), 0 8px 10px -6px rgba(15, 23, 42, 0.08);
            --radius-lg: 20px;
            --radius-md: 12px;
            --radius-sm: 8px;
            --transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        html.dark {
            /* Dark mode color space */
            --bg-color: #0b111e;
            --card-bg: #141c2f;
            --card-border: #1e293b;
            --text-dark: #f8fafc;
            --text-muted: #94a3b8;
            --navbar-bg: rgba(11, 17, 30, 0.85);
            --dropdown-glass-bg: rgba(11, 17, 30, 0.45);
            --footer-bg: #0b111e;
            --footer-border: #1e293b;
            --input-bg: #1e293b;
            --input-border: #334155;
            --input-color: #f8fafc;
            --input-focus-border: #3b82f6;
            --input-focus-shadow: rgba(59, 130, 246, 0.2);
            --table-thead-bg: #1e293b;
            --badge-bg-subtle: rgba(37, 99, 235, 0.15);
            --badge-text-subtle: #60a5fa;

            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.2);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.2), 0 2px 4px -2px rgba(0, 0, 0, 0.2);
            --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 8px 10px -6px rgba(0, 0, 0, 0.3);
        }

        body {
            font-family: 'Jost', 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Glassmorphism Cards */
        .glass-card {
            background-color: var(--card-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }

        .glass-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
            border-color: rgba(37, 99, 235, 0.2);
        }

        .glass-card-static {
            background-color: var(--card-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
        }

        /* Navbar Styling */
        .navbar {
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            background-color: var(--navbar-bg) !important;
            border-bottom: 1px solid var(--card-border);
            transition: var(--transition);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-weight: 800;
            font-family: 'Jost', sans-serif;
            color: #0b1530 !important;
            font-size: 1.65rem;
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }

        html.dark .navbar-brand {
            color: #ffffff !important;
        }

        .footer-logo {
            color: var(--text-primary);
            transition: color 0.3s ease;
        }

        html.dark .footer-logo {
            color: #ffffff;
        }

        .nav-link {
            font-weight: 500;
            color: var(--text-muted);
            transition: var(--transition);
            padding: 0.5rem 1rem;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--primary-color) !important;
        }

        /* Buttons Styling */
        .btn-gradient {
            background: var(--bg-inverse);
            color: #ffffff !important;
            border: 1px solid #000000;
            font-weight: 600;
            padding: 0.65rem 1.5rem;
            border-radius: 30px;
            transition: var(--transition);
        }

        .btn-gradient:hover {
            background: #222222;
            border-color: #222222;
            transform: translateY(-2px);
        }

        .btn-gradient-secondary {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #db2777 100%);
            color: #ffffff !important;
            border: none;
            font-weight: 600;
            padding: 0.65rem 1.5rem;
            border-radius: 30px;
            box-shadow: 0 4px 14px rgba(124, 58, 237, 0.2);
            transition: var(--transition);
        }

        .btn-gradient-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(124, 58, 237, 0.35);
        }

        .btn-outline-secondary {
            border-color: var(--input-border);
            color: var(--text-dark);
            transition: var(--transition);
            border-radius: 30px;
        }

        .btn-outline-secondary:hover {
            background-color: var(--card-border);
            color: var(--text-dark);
            border-color: var(--input-border);
        }

        /* Form elements */
        .form-control, .form-select {
            background-color: var(--input-bg);
            border-color: var(--input-border);
            color: var(--input-color);
            border-radius: var(--radius-md);
            padding: 0.6rem 1rem;
            transition: var(--transition);
        }

        .form-control:focus, .form-select:focus {
            background-color: var(--input-bg);
            border-color: var(--input-focus-border);
            color: var(--input-color);
            box-shadow: 0 0 0 3px var(--input-focus-shadow);
        }

        /* Dropdowns */
        .dropdown-menu {
            background-color: var(--card-bg);
            border-color: var(--card-border);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-lg);
        }

        .dropdown-item {
            color: var(--text-dark);
            transition: var(--transition);
            padding: 0.5rem 1.25rem;
        }

        .dropdown-item:hover {
            background-color: var(--table-thead-bg);
            color: var(--primary-color);
        }

        /* Clean Table Design */
        .table {
            color: var(--text-dark);
            border-color: var(--card-border);
        }

        .table-light {
            --bs-table-bg: var(--table-thead-bg);
            --bs-table-border-color: var(--card-border);
            color: var(--text-dark);
        }

        /* Status Badges */
        .badge-status {
            font-size: 0.75rem;
            padding: 0.35em 0.75em;
            border-radius: 20px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
        }

        .badge-pending {
            background-color: rgba(245, 158, 11, 0.15);
            color: #d97706;
        }

        .badge-approved {
            background-color: rgba(16, 185, 129, 0.15);
            color: #059669;
        }

        .badge-completed {
            background-color: rgba(37, 99, 235, 0.15);
            color: #2563eb;
        }

        .badge-rejected, .badge-danger {
            background-color: rgba(239, 68, 68, 0.15);
            color: #dc2626;
        }

        .badge-cancelled {
            background-color: rgba(100, 116, 139, 0.15);
            color: #475569;
        }

        /* ── Footer – Premium Purple Theme ──────────────────── */
        footer {
            margin-top: auto;
        }

        .footer-top {
            background: linear-gradient(135deg, #56358A 0%, #4A2E78 100%);
            color: rgba(255,255,255,0.82);
            transition: var(--transition);
            box-shadow: 0 -4px 40px rgba(74, 46, 120, 0.25);
        }

        .footer-top a {
            color: rgba(255,255,255,0.75);
            transition: var(--transition);
        }

        .footer-top a:hover {
            color: #ffffff;
            text-decoration: none;
        }

        /* Purple accent helpers */
        .footer-top .hover-primary:hover {
            color: #ffffff !important;
            opacity: 1;
        }
        .footer-top .text-indigo {
            color: rgba(255,255,255,0.65) !important;
        }

        /* Override Bootstrap text utilities inside footer-top */
        .footer-top .text-muted {
            color: rgba(255,255,255,0.70) !important;
        }
        .footer-top .text-dark {
            color: #ffffff !important;
        }
        .footer-top .text-secondary {
            color: rgba(255,255,255,0.60) !important;
        }
        .footer-top .footer-logo {
            color: #ffffff !important;
        }
        .footer-top h5, .footer-top h6 {
            color: #ffffff !important;
            letter-spacing: 0.02em;
        }

        /* Footer Bottom Styling */
        .footer-bottom {
            background-color: var(--card-bg);
            border-top: 1px solid var(--card-border);
            color: var(--text-muted);
            transition: var(--transition);
        }

        .avatar-img {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid var(--primary-color);
        }

        .avatar-lg {
            width: 90px;
            height: 90px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid var(--primary-color);
            box-shadow: var(--shadow-md);
        }

        .avatar-card {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: var(--radius-lg);
            transition: var(--transition);
        }

        /* Skeleton Loaders */
        .skeleton {
            background: linear-gradient(90deg, var(--card-border) 25%, var(--table-thead-bg) 50%, var(--card-border) 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
            border-radius: var(--radius-sm);
        }

        @keyframes loading {
            0% {
                background-position: 200% 0;
            }
            100% {
                background-position: -200% 0;
            }
        }

        .star-filled {
            color: #f59e0b;
        }

        .star-empty {
            color: #cbd5e1;
        }

        /* ── User Account Menu Pill ──────────────────────── */
        .user-menu-pill {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 5px 6px 5px 14px;
            border-radius: 50px;
            border: 1.5px solid var(--card-border);
            background: var(--card-bg);
            cursor: pointer;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
            text-decoration: none;
            outline: none;
            box-shadow: var(--shadow-sm);
            white-space: nowrap;
        }

        .user-menu-pill:hover,
        .user-menu-pill:focus,
        .user-menu-pill[aria-expanded="true"] {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
            background: var(--card-bg);
            text-decoration: none;
        }

        .user-menu-pill .pill-name {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-dark);
            letter-spacing: -0.1px;
            line-height: 1;
            max-width: 120px;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-menu-pill .pill-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: linear-gradient(135deg, #7c3aed 0%, #ec4899 100%);
            color: #ffffff;
            font-size: 1rem;
            flex-shrink: 0;
            transition: transform 0.25s ease;
        }

        .user-menu-pill[aria-expanded="true"] .pill-icon {
            transform: rotate(90deg);
        }

        /* Dropdown animation */
        .user-dropdown-menu {
            border-radius: var(--radius-md) !important;
            border: 1px solid var(--card-border) !important;
            background: var(--card-bg) !important;
            box-shadow: var(--shadow-lg) !important;
            padding: 6px !important;
            min-width: 230px !important;
            margin-top: 10px !important;
            animation: dropdownSlideIn 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            transform-origin: top right;
        }

        @keyframes dropdownSlideIn {
            from {
                opacity: 0;
                transform: scale(0.95) translateY(-6px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .user-dropdown-menu .dropdown-item {
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background 0.15s ease, color 0.15s ease;
        }

        .user-dropdown-menu .dropdown-item i {
            font-size: 1rem;
            width: 18px;
            text-align: center;
            opacity: 0.75;
        }

        .user-dropdown-menu .dropdown-item:hover {
            background: var(--table-thead-bg);
            color: var(--primary-color);
        }

        .user-dropdown-menu .dropdown-item:hover i {
            opacity: 1;
        }

        .user-dropdown-menu .dropdown-item.text-danger:hover {
            background: rgba(239, 68, 68, 0.08);
            color: #dc2626;
        }

        .user-dropdown-menu .menu-header {
            padding: 8px 12px 6px;
            border-bottom: 1px solid var(--card-border);
            margin-bottom: 4px;
        }

        .user-dropdown-menu .menu-footer {
            padding-top: 4px;
            border-top: 1px solid var(--card-border);
            margin-top: 4px;
        }

        /* Hide name on very small screens, show only icon */
        @media (max-width: 400px) {
            .user-menu-pill .pill-name {
                display: none;
            }
            .user-menu-pill {
                padding: 5px;
                border-radius: 50%;
                gap: 0;
            }
        }

        /* Custom Mobile Responsive Styles */
        .brand-logo-container {
            width: 36px;
            height: 36px;
            font-size: 1.1rem;
        }
        .brand-title {
            font-size: 1.25rem;
        }
        .brand-subtitle {
            font-size: 0.65rem;
        }
        
        @media (max-width: 991.98px) {
            .navbar {
                padding-top: 0.25rem !important;
                padding-bottom: 0.25rem !important;
            }
            .brand-logo-container {
                width: 28px !important;
                height: 28px !important;
                font-size: 0.85rem !important;
                border-radius: 8px !important;
            }
            .brand-title {
                font-size: 1.05rem !important;
            }
            .brand-subtitle {
                font-size: 0.52rem !important;
            }
            #navbarLocationSelectorMobile {
                padding: 0.18rem 0.45rem !important;
                border-radius: 15px !important;
                font-size: 0.72rem !important;
                max-width: 120px !important;
            }
            #navbarLocationTextMobile {
                max-width: 80px !important;
                font-size: 0.72rem !important;
            }
            main.py-4 {
                padding-top: 0.5rem !important;
                padding-bottom: 1rem !important;
            }
        }
        
        @media (max-width: 360px) {
            .brand-title {
                font-size: 0.95rem !important;
            }
            .brand-subtitle {
                display: none !important;
            }
            #navbarLocationTextMobile {
                max-width: 65px !important;
            }
        }
    </style>
    @yield('styles')
    
    <!-- Premium Toast Styles -->
    <style>
        .custom-toast-container {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 12px;
            pointer-events: none;
        }
        .custom-toast {
            background: var(--surface, #ffffff);
            color: var(--text-dark, #0f172a);
            border: 1px solid var(--card-border, rgba(0,0,0,0.08));
            border-radius: 12px;
            padding: 12px 20px;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 280px;
            max-width: 400px;
            font-weight: 500;
            font-size: 0.9rem;
            pointer-events: auto;
            opacity: 0;
            transform: translateY(20px) scale(0.9);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .custom-toast.show {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
        .custom-toast-icon {
            font-size: 1.2rem;
        }
        .custom-toast.success {
            border-left: 4px solid var(--success-color, #10b981);
        }
        .custom-toast.success .custom-toast-icon {
            color: var(--success-color, #10b981);
        }
        .custom-toast.warning {
            border-left: 4px solid var(--warning-color, #f59e0b);
        }
        .custom-toast.warning .custom-toast-icon {
            color: var(--warning-color, #f59e0b);
        }
        .custom-toast.error {
            border-left: 4px solid var(--danger-color, #ef4444);
        }
        .custom-toast.error .custom-toast-icon {
            color: var(--danger-color, #ef4444);
        }
        html.dark .custom-toast {
            background: #1e293b;
            color: #f8fafc;
            border-color: #334155;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/theme-system.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dark-mode-overrides.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

    <!-- Header Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top py-2 py-lg-3">
        <div class="container d-flex align-items-center justify-content-between">
            <!-- Left Side: Logo & Brand -->
            <a class="navbar-brand text-theme-primary d-flex align-items-center m-0" href="{{ route('home') }}" style="gap: 8px; text-decoration: none;">
                <div class="brand-logo-container" style="width: 36px; height: 36px; background: linear-gradient(135deg, #7c3aed 0%, #ec4899 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white !important; font-size: 1.1rem; flex-shrink: 0; box-shadow: 0 4px 12px rgba(124, 58, 237, 0.25);">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div style="display: flex; flex-direction: column; line-height: 1.1; text-align: left;">
                    <div class="brand-title" style="font-weight: 800; font-family: 'Jost', sans-serif; font-size: 1.25rem; color: var(--text-dark) !important; letter-spacing: -0.3px;">Hire-a-Friend</div>
                    <div class="brand-subtitle" style="font-size: 0.65rem; font-weight: 600; color: var(--text-muted); letter-spacing: 0.06em; text-transform: uppercase; margin-top: 1px;">Companion Platform</div>
                </div>
            </a>
            
            <!-- Desktop Location Selector Widget -->
            <div class="d-none d-lg-flex align-items-center cursor-pointer ms-3 me-auto" id="navbarLocationSelector" style="cursor: pointer; padding: 0.25rem 0.6rem; border-radius: 30px; border: 1px solid var(--card-border); background-color: var(--card-bg);" data-bs-toggle="modal" data-bs-target="#locationSelectorModal">
                <span class="text-primary me-1"><i class="bi bi-geo-alt-fill" style="font-size: 1.1rem; color: var(--primary-color) !important;"></i></span>
                <div class="d-flex flex-column text-start" style="line-height: 1.2;">
                    <span class="text-muted fw-semibold" style="font-size: 0.68rem; letter-spacing: 0.02em;">Location</span>
                    <span class="fw-bold small text-truncate" id="navbarLocationText" style="font-size: 0.8rem; color: var(--text-dark); max-width: 140px;">
                        {{ session('user_location.city') ? session('user_location.city') : 'Select Location' }}
                    </span>
                </div>
                <span class="ms-1 text-muted" style="font-size: 0.72rem;"><i class="bi bi-chevron-down"></i></span>
            </div>

            <!-- Mobile Stack: Compact Location + Hamburger (directly below location) -->
            <div class="d-flex flex-column align-items-end justify-content-center d-lg-none" style="gap: 4px; min-width: 100px;">
                <!-- Compact Location Selector -->
                <div class="d-flex align-items-center cursor-pointer" id="navbarLocationSelectorMobile" style="cursor: pointer; padding: 0.18rem 0.45rem; border-radius: 15px; border: 1px solid var(--card-border); background-color: var(--card-bg); line-height: 1;" data-bs-toggle="modal" data-bs-target="#locationSelectorModal">
                    <span class="text-primary me-1"><i class="bi bi-geo-alt-fill" style="font-size: 0.9rem; color: var(--primary-color) !important;"></i></span>
                    <span class="fw-bold small text-truncate" id="navbarLocationTextMobile" style="font-size: 0.72rem; color: var(--text-dark); max-width: 80px;">
                        {{ session('user_location.city') ? session('user_location.city') : 'Select Location' }}
                    </span>
                    <span class="ms-1 text-muted" style="font-size: 0.65rem;"><i class="bi bi-chevron-down"></i></span>
                </div>
                
                <!-- Action Icons + Hamburger Menu (Directly below location) -->
                <div class="d-flex align-items-center gap-3 mt-1">
                    <!-- Notifications Trigger Mobile -->
                    <div class="dropdown">
                        <button class="btn btn-link p-0 text-theme-primary position-relative border-0" type="button" id="notifBtnMobile" data-bs-toggle="dropdown" aria-expanded="false" style="outline: none; box-shadow: none; text-decoration: none;">
                            <i class="bi bi-bell fs-5"></i>
                            <span class="position-absolute top-0 end-0 p-1 bg-danger border border-light rounded-circle" style="transform: translate(25%, -25%);">
                                <span class="visually-hidden">New alerts</span>
                            </span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2 mt-2" style="width: 280px;" aria-labelledby="notifBtnMobile">
                            <li class="dropdown-header fw-bold text-theme-primary">Notification Center</li>
                            <li><hr class="dropdown-divider"></li>
                            <li class="p-2 text-center text-muted small">No unread notifications in this sandbox session.</li>
                        </ul>
                    </div>

                    <!-- Dark Mode Toggle Button Mobile -->
                    <button class="btn btn-link p-0 text-theme-primary border-0 darkModeToggle" aria-label="Toggle Dark Mode" style="outline: none; box-shadow: none; text-decoration: none;">
                        <i class="bi bi-moon-fill fs-5 darkModeIcon"></i>
                    </button>

                    <!-- Hamburger Dropdown Menu -->
                    <div class="dropdown">
                        <button class="navbar-toggler p-0 border-0" type="button" id="mobileMenuDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="outline: none; box-shadow: none;">
                            <i class="bi bi-list fs-3" style="color: var(--text-dark); line-height: 1;"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-1 mt-1" aria-labelledby="mobileMenuDropdown" style="min-width: 120px; border-radius: 12px; background: var(--dropdown-glass-bg); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);">
                            @auth
                                <li>
                                    @if(Auth::user()->role === 'admin')
                                        <a href="{{ route('admin.dashboard') }}" class="dropdown-item py-1 px-2 fw-semibold text-primary rounded-3 text-end" style="font-size: 0.8rem;">
                                            <i class="bi bi-speedometer2 me-1"></i>Dashboard
                                        </a>
                                    @elseif(Auth::user()->role === 'partner')
                                        <a href="{{ route('partner.dashboard') }}" class="dropdown-item py-1 px-2 fw-semibold text-primary rounded-3 text-end" style="font-size: 0.8rem;">
                                            <i class="bi bi-speedometer2 me-1"></i>Dashboard
                                        </a>
                                    @else
                                        <a href="{{ route('customer.dashboard') }}" class="dropdown-item py-1 px-2 fw-semibold text-primary rounded-3 text-end" style="font-size: 0.8rem;">
                                            <i class="bi bi-speedometer2 me-1"></i>Dashboard
                                        </a>
                                    @endif
                                </li>
                                <li><hr class="dropdown-divider my-1"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="dropdown-item py-1 px-2 fw-semibold text-danger rounded-3 w-100 text-end" style="font-size: 0.8rem; border: none; background: transparent;">
                                            <i class="bi bi-box-arrow-right me-1"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            @else
                                <li>
                                    <a href="{{ route('login') }}" class="dropdown-item py-1 px-2 fw-semibold text-theme-primary rounded-3 text-end" style="font-size: 0.8rem;">
                                        <i class="bi bi-box-arrow-in-right me-1"></i>Login
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('register') }}" class="dropdown-item py-1 px-2 fw-semibold text-theme-primary rounded-3 text-end" style="font-size: 0.8rem;">
                                        <i class="bi bi-person-plus me-1"></i>Register
                                    </a>
                                </li>
                            @endauth
                        </ul>
                    </div>
                </div>
            </div>

            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Desktop Navigation Menu -->
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 d-none d-lg-flex">
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('companions.*') ? 'active' : '' }}" href="{{ route('companions.index') }}">Find Companions</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::is('cms.page') && request()->route('slug') === 'about-us' ? 'active' : '' }}" href="{{ route('cms.page', 'about-us') }}">About Us</a>
                    </li>
                </ul>

                <!-- Desktop Actions -->
                <div class="d-none d-lg-flex align-items-center gap-3">
                    <!-- Filters Drawer Trigger -->
                    <button class="btn btn-link p-2 rounded-circle hover-bg-gray text-theme-primary border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#filterDrawer" aria-controls="filterDrawer" title="Filter Listings" style="background: transparent; outline: none; box-shadow: none;">
                        <i class="bi bi-sliders fs-5"></i>
                    </button>
                    
                    <!-- Notifications Trigger -->
                    <div class="dropdown">
                        <button class="btn btn-link p-2 rounded-circle hover-bg-gray text-theme-primary position-relative" type="button" id="notifBtn" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-bell fs-5"></i>
                            <span class="position-absolute top-1.5 end-1.5 w-2 h-2 bg-danger rounded-circle"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2 mt-2" style="width: 280px;" aria-labelledby="notifBtn">
                            <li class="dropdown-header fw-bold text-theme-primary">Notification Center</li>
                            <li><hr class="dropdown-divider"></li>
                            <li class="p-2 text-center text-muted small">No unread notifications in this sandbox session.</li>
                        </ul>
                    </div>

                    <!-- Dark Mode Toggle Button -->
                    <button class="btn btn-link text-theme-primary p-2 rounded-circle border-0 darkModeToggle" id="darkModeToggle" aria-label="Toggle Dark Mode" style="background: transparent; outline: none; box-shadow: none;">
                        <i class="bi bi-moon-fill fs-5 darkModeIcon" id="darkModeIcon"></i>
                    </button>

                    @auth
                        {{-- Desktop Authenticated Pill --}}
                        <div class="dropdown">
                            <button
                                class="user-menu-pill"
                                type="button"
                                id="userMenu"
                                data-bs-toggle="dropdown"
                                data-bs-auto-close="outside"
                                aria-expanded="false"
                                aria-label="Account menu"
                            >
                                <span class="pill-name">{{ Auth::user()->name }}</span>
                                <span class="pill-icon">
                                    <i class="bi bi-list"></i>
                                </span>
                            </button>

                            <ul class="dropdown-menu dropdown-menu-end user-dropdown-menu" aria-labelledby="userMenu">
                                <li class="menu-header">
                                    <div class="fw-bold text-truncate" style="font-size:0.85rem; color:var(--text-dark); max-width:190px;">{{ Auth::user()->name }}</div>
                                    <div class="mt-1">
                                        <span class="badge rounded-pill" style="background:rgba(124,58,237,0.12); color:#7c3aed; font-size:0.7rem; letter-spacing:0.04em;">{{ strtoupper(Auth::user()->role) }}</span>
                                    </div>
                                </li>

                                @if(Auth::user()->role === 'admin')
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="bi bi-person-lines-fill"></i> Profile</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="bi bi-gear"></i> Settings</a></li>
                                @elseif(Auth::user()->role === 'partner')
                                    <li><a class="dropdown-item" href="{{ route('partner.dashboard') }}"><i class="bi bi-person-lines-fill"></i> Profile</a></li>
                                    <li><a class="dropdown-item" href="{{ route('partner.dashboard') }}"><i class="bi bi-gear"></i> Settings</a></li>
                                @else
                                    <li><a class="dropdown-item" href="{{ route('customer.dashboard') }}"><i class="bi bi-person-lines-fill"></i> Profile</a></li>
                                    <li><a class="dropdown-item" href="{{ route('customer.dashboard') }}"><i class="bi bi-gear"></i> Settings</a></li>
                                @endif

                                <li class="menu-footer">
                                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger w-100">
                                            <i class="bi bi-box-arrow-right"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        {{-- Desktop Guest Pill --}}
                        <div class="dropdown">
                            <button
                                class="user-menu-pill"
                                type="button"
                                id="loginDropdown"
                                data-bs-toggle="dropdown"
                                aria-expanded="false"
                                aria-label="Login menu"
                            >
                                <span class="pill-name">Sign In</span>
                                <span class="pill-icon">
                                    <i class="bi bi-list"></i>
                                </span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end user-dropdown-menu" aria-labelledby="loginDropdown">
                                <li><a class="dropdown-item" href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right"></i> Log In</a></li>
                                <li><a class="dropdown-item" href="{{ route('register') }}"><i class="bi bi-person-plus"></i> Register</a></li>
                            </ul>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="py-4">
        <div class="container">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show glass-card-static border-success border-start border-4 mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill text-success fs-4 me-2"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show glass-card-static border-danger border-start border-4 mb-4" role="alert">
                    <div class="d-flex">
                        <i class="bi bi-exclamation-triangle-fill text-danger fs-4 me-2"></i>
                        <div>
                            <h6 class="alert-heading fw-bold mb-1">Please fix the following:</h6>
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-auto">
        <!-- Footer Top (Purple Gradient) -->
        <div class="footer-top pt-5 pb-4">
            <div class="container">
                <div class="row">
                    <div class="col-md-5 mb-4">
                        <a href="{{ route('home') }}" class="footer-logo text-decoration-none d-inline-flex align-items-center gap-2 mb-3" style="font-family: 'Jost', sans-serif; text-decoration: none;">
                            <div style="width: 32px; height: 32px; background: linear-gradient(135deg, #7c3aed 0%, #ec4899 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white !important; font-size: 0.95rem; flex-shrink: 0; box-shadow: 0 3px 8px rgba(124, 58, 237, 0.2);">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <div style="display: flex; flex-direction: column; line-height: 1.1; text-align: left;">
                                <div style="font-weight: 800; font-family: 'Jost', sans-serif; font-size: 1.15rem; color: var(--text-dark); letter-spacing: -0.3px;">Hire-a-Friend</div>
                                <div style="font-size: 0.6rem; font-weight: 600; color: var(--text-muted); letter-spacing: 0.06em; text-transform: uppercase; margin-top: 1px;">Companion Platform</div>
                            </div>
                        </a>
                        <p class="text-muted">A premium booking marketplace connecting users with verified companions for social events, study activities, fitness sessions, and local touring.</p>
                        <div class="d-flex gap-3 mt-3">
                            <a href="#" class="text-secondary fs-5 hover-primary"><i class="bi bi-twitter-x"></i></a>
                            <a href="#" class="text-secondary fs-5 hover-primary"><i class="bi bi-facebook"></i></a>
                            <a href="#" class="text-secondary fs-5 hover-primary"><i class="bi bi-instagram"></i></a>
                            <a href="#" class="text-secondary fs-5 hover-primary"><i class="bi bi-linkedin"></i></a>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4 ms-auto">
                        <h6 class="fw-bold mb-3">Legal</h6>
                        <ul class="list-unstyled">
                            <li><a href="{{ route('cms.page', 'terms-of-service') }}" class="text-muted">Terms of Service</a></li>
                            <li><a href="{{ route('cms.page', 'privacy-policy') }}" class="text-muted">Privacy Policy</a></li>
                        </ul>
                    </div>
                    <div class="col-md-4 mb-4">
                        <h6 class="fw-bold mb-3">Contact Support</h6>
                        <ul class="list-unstyled text-muted">
                            <li class="mb-2"><i class="bi bi-envelope-fill me-2 text-indigo"></i> support@companion.com</li>
                            <li><i class="bi bi-telephone-fill me-2 text-indigo"></i> +1 (555) 019-2834</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Bottom (White/Light Background) -->
        <div class="footer-bottom py-3">
            <div class="container">
                <div class="row align-items-center">
                    <!-- Copyright – left -->
                    <div class="col-12 col-md-4 mb-2 mb-md-0 text-center text-md-start">
                        <p class="mb-0 text-muted small">&copy; {{ date('Y') }} Hire-a-Friend. All rights reserved.</p>
                    </div>

                    <!-- Logo – center -->
                    <div class="col-12 col-md-4 mb-2 mb-md-0 text-center">
                        <a href="{{ route('home') }}" class="footer-logo text-decoration-none d-inline-flex align-items-center gap-2 justify-content-center"
                           style="font-family: 'Jost', sans-serif; text-decoration: none; display: inline-flex !important;">
                            <div style="width: 28px; height: 28px; background: linear-gradient(135deg, #7c3aed 0%, #ec4899 100%); border-radius: 7px; display: flex; align-items: center; justify-content: center; color: white !important; font-size: 0.85rem; flex-shrink: 0;">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <div style="display: flex; flex-direction: column; line-height: 1.1; text-align: left;">
                                <div style="font-weight: 800; font-family: 'Jost', sans-serif; font-size: 1rem; color: var(--text-dark); letter-spacing: -0.3px;">Hire-a-Friend</div>
                                <div style="font-size: 0.55rem; font-weight: 600; color: var(--text-muted); letter-spacing: 0.06em; text-transform: uppercase; margin-top: 1px;">Companion Platform</div>
                            </div>
                        </a>
                    </div>

                    <!-- SSL Badge – right -->
                    <div class="col-12 col-md-4 text-center text-md-end">
                        <div class="d-inline-flex align-items-center text-muted">
                            <i class="bi bi-shield-check fs-5 me-2"></i>
                            <span class="small">Secure SSL Verification Enabled</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 Bundle JS with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const darkModeToggles = document.querySelectorAll('.darkModeToggle');
            const userMenuToggle = document.getElementById('userMenu');

            if (darkModeToggles.length > 0) {
                function updateDarkModeUI() {
                    const isDark = document.documentElement.classList.contains('dark');
                    darkModeToggles.forEach(toggle => {
                        const icon = toggle.querySelector('.darkModeIcon');
                        if (isDark) {
                            if(icon) icon.classList.replace('bi-moon-fill', 'bi-sun-fill');
                            toggle.classList.replace('text-dark', 'text-light');
                        } else {
                            if(icon) icon.classList.replace('bi-sun-fill', 'bi-moon-fill');
                            toggle.classList.replace('text-light', 'text-dark');
                        }
                    });
                    if (userMenuToggle) {
                        if (isDark) {
                            userMenuToggle.classList.replace('text-dark', 'text-light');
                        } else {
                            userMenuToggle.classList.replace('text-light', 'text-dark');
                        }
                    }
                }

                updateDarkModeUI();

                darkModeToggles.forEach(toggle => {
                    toggle.addEventListener('click', function () {
                        const isDark = document.documentElement.classList.toggle('dark');
                        localStorage.setItem('darkMode', isDark);
                        updateDarkModeUI();
                    });
                });
            }
        });
    </script>

    <!-- Filter Drawer -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="filterDrawer" aria-labelledby="filterDrawerLabel" style="width: 380px; border-left: 1px solid var(--card-border); background-color: var(--card-bg);">
        <div class="offcanvas-header border-bottom py-3">
            <h5 class="offcanvas-title fw-bold" id="filterDrawerLabel" style="font-family: 'Jost', sans-serif;">Filter Options</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-4">
            <form action="{{ route('companions.index') }}" method="GET" id="drawerFilterForm">
                <!-- Keyword Search -->
                <div class="mb-4">
                    <label class="form-label fw-bold text-muted text-uppercase mb-2" style="font-size: 0.75rem; letter-spacing: 0.05em;">Search by Name</label>
                    <input type="text" name="search" class="form-control" placeholder="Search by name..." value="{{ request('search') }}">
                </div>

                <!-- Location City -->
                <div class="mb-4">
                    <label class="form-label fw-bold text-muted text-uppercase mb-2" style="font-size: 0.75rem; letter-spacing: 0.05em;">Location</label>
                    <select name="city_id" class="form-select">
                        <option value="">All Locations</option>
                        @if(isset($globalCities))
                            @foreach($globalCities as $city)
                                <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Category -->
                <div class="mb-4">
                    <label class="form-label fw-bold text-muted text-uppercase mb-2" style="font-size: 0.75rem; letter-spacing: 0.05em;">Category</label>
                    <select name="category_id" class="form-select">
                        <option value="">All Categories</option>
                        @if(isset($globalCategories))
                            @foreach($globalCategories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Gender -->
                <div class="mb-4">
                    <label class="form-label fw-bold text-muted text-uppercase mb-2" style="font-size: 0.75rem; letter-spacing: 0.05em;">Gender</label>
                    <div class="d-flex gap-2">
                        <input type="radio" class="btn-check" name="gender" id="drawer_gender_all" value="" {{ !request('gender') ? 'checked' : '' }}>
                        <label class="btn btn-outline-secondary btn-sm px-3 rounded-pill" for="drawer_gender_all">All</label>

                        <input type="radio" class="btn-check" name="gender" id="drawer_gender_male" value="male" {{ request('gender') === 'male' ? 'checked' : '' }}>
                        <label class="btn btn-outline-secondary btn-sm px-3 rounded-pill" for="drawer_gender_male">Male</label>

                        <input type="radio" class="btn-check" name="gender" id="drawer_gender_female" value="female" {{ request('gender') === 'female' ? 'checked' : '' }}>
                        <label class="btn btn-outline-secondary btn-sm px-3 rounded-pill" for="drawer_gender_female">Female</label>
                    </div>
                </div>

                <!-- Price Range -->
                <div class="mb-4">
                    <label class="form-label fw-bold text-muted text-uppercase mb-2" style="font-size: 0.75rem; letter-spacing: 0.05em;">Hourly Rate (₹)</label>
                    <div class="row g-2">
                        <div class="col-6">
                            <input type="number" name="min_price" class="form-control" placeholder="Min" value="{{ request('min_price') }}">
                        </div>
                        <div class="col-6">
                            <input type="number" name="max_price" class="form-control" placeholder="Max" value="{{ request('max_price') }}">
                        </div>
                    </div>
                </div>

                <!-- Action buttons -->
                <div class="d-flex gap-2 mt-5">
                    <a href="{{ route('companions.index') }}" class="btn btn-outline-secondary w-50 py-2.5 rounded-pill text-center d-flex align-items-center justify-content-center">Reset</a>
                    <button type="submit" class="btn btn-gradient w-50 py-2.5">Apply</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}',
            isAuthenticated: {{ auth()->check() ? 'true' : 'false' }},
            loginRoute: '{{ route('login') }}'
        };

        function showToast(message, type = 'success') {
            let container = document.querySelector('.custom-toast-container');
            if (!container) {
                container = document.createElement('div');
                container.className = 'custom-toast-container';
                document.body.appendChild(container);
            }
            
            const toast = document.createElement('div');
            toast.className = `custom-toast ${type}`;
            
            let iconClass = 'bi-check-circle-fill';
            if (type === 'warning') iconClass = 'bi-exclamation-triangle-fill';
            if (type === 'error') iconClass = 'bi-x-circle-fill';
            
            toast.innerHTML = `
                <i class="bi ${iconClass} custom-toast-icon"></i>
                <div class="custom-toast-message">${message}</div>
            `;
            
            container.appendChild(toast);
            
            // Force reflow
            toast.offsetHeight;
            
            toast.classList.add('show');
            
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 3000);
        }

        function toggleFavorite(companionId, btnEl) {
            if (!window.Laravel.isAuthenticated) {
                showToast('Please login to add companions to your favorites', 'warning');
                setTimeout(() => {
                    window.location.href = window.Laravel.loginRoute;
                }, 1500);
                return;
            }

            if (btnEl.disabled) return;
            btnEl.disabled = true;

            fetch('/customer/favorites/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.Laravel.csrfToken
                },
                body: JSON.stringify({ companion_id: companionId })
            })
            .then(response => {
                if (response.status === 401) {
                    window.location.href = window.Laravel.loginRoute;
                    return;
                }
                return response.json();
            })
            .then(data => {
                btnEl.disabled = false;
                if (data && data.success) {
                    const querySelector = `[data-companion-id="${companionId}"]`;
                    const buttons = document.querySelectorAll(querySelector);
                    
                    buttons.forEach(btn => {
                        const icon = btn.querySelector('i');
                        if (data.status === 'added') {
                            btn.classList.add('active');
                            if (icon) {
                                icon.className = 'bi bi-heart-fill text-danger';
                                icon.style.color = '#ef4444';
                            }
                        } else {
                            btn.classList.remove('active');
                            if (icon) {
                                icon.className = 'bi bi-heart';
                                icon.style.color = '';
                            }
                            
                            // Favorites page element handling
                            if (window.location.pathname.includes('/customer/favorites')) {
                                const cardCol = btn.closest('.col-12, .col-sm-6, .col-md-4, .col-lg-3');
                                if (cardCol) {
                                    cardCol.style.transition = 'all 0.4s ease';
                                    cardCol.style.opacity = '0';
                                    cardCol.style.transform = 'scale(0.8)';
                                    setTimeout(() => {
                                        cardCol.remove();
                                        const grid = document.querySelector('.row.g-4');
                                        if (grid && grid.querySelectorAll('.col-12, .col-sm-6, .col-md-4, .col-lg-3').length === 0) {
                                            window.location.reload();
                                        }
                                    }, 400);
                                }
                            }
                        }
                    });

                    showToast(data.message, 'success');
                } else {
                    showToast(data.message || 'Something went wrong', 'error');
                }
            })
            .catch(err => {
                btnEl.disabled = false;
                console.error(err);
                showToast('Connection error. Please try again.', 'error');
            });
        }
    </script>

    @yield('scripts')

    @include('partials.location-modal')
    @include('partials.location-scripts')
    <!-- Global Chart.js Dark Mode Handler -->
    <script>
        if (typeof Chart !== 'undefined') {
            const isDarkMode = document.documentElement.classList.contains('dark');
            Chart.defaults.color = isDarkMode ? '#f8fafc' : '#64748b';
            Chart.defaults.borderColor = isDarkMode ? '#334155' : '#e2e8f0';
            
            // Add observer to watch for dark mode changes
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.attributeName === 'class') {
                        const isDark = document.documentElement.classList.contains('dark');
                        Chart.defaults.color = isDark ? '#f8fafc' : '#64748b';
                        Chart.defaults.borderColor = isDark ? '#334155' : '#e2e8f0';
                        // Rerender all charts
                        for (let id in Chart.instances) {
                            Chart.instances[id].update();
                        }
                    }
                });
            });
            observer.observe(document.documentElement, { attributes: true });
        }
    </script>
    @include('partials.chatbot')
</body>
</html>
