<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel - Super Admin Console')</title>
    
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
    
    <style>
        :root {
            /* Purple Theme variables */
            --primary-color: #7c3aed;
            --primary-hover: #6d28d9;
            --primary-glow: rgba(124, 58, 237, 0.15);
            --secondary-color: #a78bfa;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #06b6d4;

            /* Light mode colors */
            --bg-color: #f5f6fa;
            --card-bg: #ffffff;
            --card-border: #e2e8f0;
            --text-dark: #0f172a;
            --text-muted: #64748b;
            --navbar-bg: rgba(255, 255, 255, 0.8);
            --sidebar-bg: #ffffff;
            --sidebar-border: #cbd5e1;
            --sidebar-hover-bg: rgba(124, 58, 237, 0.05);
            --input-bg: #ffffff;
            --input-border: #cbd5e1;
            --input-color: #0f172a;
            --input-focus-border: #7c3aed;
            --input-focus-shadow: rgba(124, 58, 237, 0.1);
            --table-thead-bg: #f8fafc;
            --badge-bg-subtle: #f3e8ff;
            --badge-text-subtle: #7c3aed;

            --shadow-sm: 0 1px 2px 0 rgba(15, 23, 42, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(15, 23, 42, 0.05), 0 2px 4px -2px rgba(15, 23, 42, 0.05);
            --shadow-lg: 0 20px 25px -5px rgba(15, 23, 42, 0.08), 0 8px 10px -6px rgba(15, 23, 42, 0.08);
            
            --radius-lg: 16px;
            --radius-md: 12px;
            --radius-sm: 8px;
            --transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        html.dark {
            /* Dark mode colors */
            --bg-color: #090d16;
            --card-bg: #111625;
            --card-border: #1f293d;
            --text-dark: #f8fafc;
            --text-muted: #94a3b8;
            --navbar-bg: rgba(17, 22, 37, 0.8);
            --sidebar-bg: #111625;
            --sidebar-border: #1f293d;
            --sidebar-hover-bg: rgba(124, 58, 237, 0.1);
            --input-bg: #1e293b;
            --input-border: #334155;
            --input-color: #f8fafc;
            --input-focus-border: #a78bfa;
            --input-focus-shadow: rgba(167, 139, 250, 0.2);
            --table-thead-bg: #1e293b;
            --badge-bg-subtle: rgba(124, 58, 237, 0.15);
            --badge-text-subtle: #c084fc;

            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.2);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.2), 0 2px 4px -2px rgba(0, 0, 0, 0.2);
            --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 8px 10px -6px rgba(0, 0, 0, 0.3);
        }

        body {
            font-family: 'Inter', 'Jost', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-dark);
            min-height: 100vh;
            overflow-x: hidden;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Layout Architecture */
        .admin-layout {
            display: flex;
            min-height: 100vh;
            position: relative;
        }

        /* Fixed Sidebar */
        .admin-sidebar {
            width: 260px;
            background-color: var(--sidebar-bg);
            border-right: 1px solid var(--sidebar-border);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1040;
            display: flex;
            flex-direction: column;
            transition: var(--transition);
        }

        .admin-sidebar-header {
            padding: 1.5rem 1.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--sidebar-border);
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .sidebar-brand-icon {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, #7c3aed 0%, #ec4899 100%);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: white;
            font-size: 1.1rem;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.25);
        }

        .sidebar-brand-text {
            font-weight: 800;
            font-size: 1.1rem;
            color: var(--text-dark);
            line-height: 1;
            letter-spacing: -0.3px;
        }

        .sidebar-brand-sub {
            font-size: 0.65rem;
            font-weight: 600;
            color: var(--text-muted);
            letter-spacing: 0.06em;
            text-transform: uppercase;
            margin-top: 2px;
        }

        .admin-sidebar-menu-wrapper {
            flex-grow: 1;
            overflow-y: auto;
            padding: 1.25rem 0.85rem;
        }

        .admin-sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .admin-sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.72rem 1rem;
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.92rem;
            border-radius: var(--radius-md);
            transition: var(--transition);
            border: none;
            background: transparent;
            width: 100%;
            text-align: left;
        }

        .admin-sidebar-link i {
            font-size: 1.15rem;
            transition: var(--transition);
        }

        .admin-sidebar-link:hover {
            background-color: var(--sidebar-hover-bg);
            color: var(--primary-color);
        }

        .admin-sidebar-link:hover i {
            transform: scale(1.1);
        }

        .admin-sidebar-link.active {
            background-color: var(--primary-color) !important;
            color: #ffffff !important;
            box-shadow: 0 4px 12px var(--primary-glow);
        }

        /* Main Workspace */
        .admin-main {
            flex-grow: 1;
            margin-left: 260px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: var(--transition);
            min-width: 0;
            overflow-x: hidden;
        }

        /* Glassmorphism Header */
        .admin-header {
            height: 70px;
            background-color: var(--navbar-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--card-border);
            display: flex;
            align-items: center;
            padding: 0 2rem;
            z-index: 1030;
            position: sticky;
            top: 0;
        }

        /* Main Content Container */
        .admin-body {
            padding: 2.25rem 2.25rem;
            flex-grow: 1;
        }

        /* Quick Actions Menu button styling */
        .btn-quick-actions {
            background-color: var(--badge-bg-subtle);
            color: var(--badge-text-subtle);
            border: 1px solid transparent;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 0.5rem 1rem;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: var(--transition);
        }

        .btn-quick-actions:hover {
            background-color: var(--primary-color);
            color: #ffffff;
            box-shadow: 0 4px 10px var(--primary-glow);
        }

        /* UI Overlay for mobile menu open state */
        .sidebar-backdrop {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(4px);
            z-index: 1035;
        }

        /* Responsive Breakpoints */
        @media (max-width: 991.98px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }
            .admin-main {
                margin-left: 0;
            }
            .show-sidebar .admin-sidebar {
                transform: translateX(0);
            }
            .show-sidebar .sidebar-backdrop {
                display: block;
            }
            .admin-header {
                padding: 0 1.25rem;
            }
            .admin-body {
                padding: 1.5rem 1.25rem;
            }
        }

        /* Search input bar styling */
        .admin-search-wrapper {
            position: relative;
            max-width: 320px;
            width: 100%;
        }

        .admin-search-wrapper i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
        }

        .admin-search-input {
            padding-left: 2.5rem !important;
            font-size: 0.9rem;
            border-radius: var(--radius-md);
        }

        /* Footer styling */
        .admin-footer {
            padding: 1.25rem 2rem;
            border-top: 4px solid var(--primary-color);
            color: var(--text-muted);
            font-size: 0.85rem;
            background-color: var(--card-bg);
            margin-top: auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 -4px 10px rgba(0,0,0,0.02);
        }

        .admin-footer-center {
            font-weight: 800;
            color: var(--text-dark);
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-family: 'Jost', sans-serif;
        }

        .admin-footer-dot {
            color: #c084fc;
            font-size: 1.5rem;
            line-height: 0;
        }

        @media (max-width: 767.98px) {
            .admin-footer {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
        }

        /* Dropdowns & Modals Custom Styling */
        .dropdown-menu {
            border: 1px solid var(--card-border) !important;
            background-color: var(--card-bg) !important;
            box-shadow: var(--shadow-lg) !important;
            border-radius: var(--radius-md) !important;
            padding: 0.5rem !important;
        }

        .dropdown-item {
            color: var(--text-dark) !important;
            border-radius: var(--radius-sm) !important;
            padding: 0.5rem 1rem !important;
            font-size: 0.88rem !important;
            font-weight: 500 !important;
            transition: var(--transition) !important;
        }

        .dropdown-item:hover {
            background-color: var(--sidebar-hover-bg) !important;
            color: var(--primary-color) !important;
        }

        .avatar-img {
            width: 38px;
            height: 38px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid var(--primary-color);
        }

        .avatar-img-placeholder {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background-color: var(--primary-color);
            color: #ffffff;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--primary-color);
        }

        /* Modals & Dialogs */
        .modal-content {
            background-color: var(--card-bg) !important;
            border: 1px solid var(--card-border) !important;
            border-radius: var(--radius-lg) !important;
            box-shadow: var(--shadow-lg) !important;
        }

        .modal-header {
            border-bottom: 1px solid var(--card-border) !important;
        }

        .modal-footer {
            border-top: 1px solid var(--card-border) !important;
        }

        .form-control, .form-select {
            background-color: var(--input-bg);
            border-color: var(--input-border);
            color: var(--input-color);
            border-radius: var(--radius-md);
            transition: var(--transition);
        }

        .form-control:focus, .form-select:focus {
            background-color: var(--input-bg);
            border-color: var(--input-focus-border);
            color: var(--input-color);
            box-shadow: 0 0 0 3px var(--input-focus-shadow);
        }

        .alert-dismissible {
            border-radius: var(--radius-md) !important;
        }

        .portal-card {
            background-color: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            padding: 2.25rem;
            transition: var(--transition);
            overflow: visible;
        }

        /* Global scrollbar styling - appears on hover */
        .admin-sidebar-menu-wrapper {
            scrollbar-width: thin;
            scrollbar-color: transparent transparent;
        }
        .admin-sidebar-menu-wrapper:hover {
            scrollbar-color: rgba(124, 58, 237, 0.4) transparent;
        }
        .admin-sidebar-menu-wrapper::-webkit-scrollbar {
            width: 5px;
        }
        .admin-sidebar-menu-wrapper::-webkit-scrollbar-track {
            background: transparent;
        }
        .admin-sidebar-menu-wrapper::-webkit-scrollbar-thumb {
            background: transparent;
            border-radius: 5px;
        }
        .admin-sidebar-menu-wrapper:hover::-webkit-scrollbar-thumb {
            background: rgba(124, 58, 237, 0.4);
        }

        /* Custom Modern Pagination */
        .pagination-custom .page-item .page-link {
            color: var(--text-muted);
            background-color: var(--card-bg);
            border: 1px solid var(--card-border);
            font-weight: 500;
            font-size: 0.85rem;
            padding: 0.4rem 0.8rem;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .pagination-custom .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: #ffffff;
            box-shadow: 0 4px 10px var(--primary-glow);
        }
        .pagination-custom .page-item:not(.active):not(.disabled) .page-link:hover {
            background-color: var(--sidebar-hover-bg);
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateY(-1px);
        }
        .pagination-custom .page-item.disabled .page-link {
            color: var(--text-muted);
            opacity: 0.5;
            background-color: var(--bg-color);
            border-color: var(--card-border);
        }
        .pagination-custom .page-link.rounded-circle {
            width: 34px;
            height: 34px;
            padding: 0;
        }

        /* ── Unified User Menu Pill ── */
        .user-menu-pill {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 5px 6px 5px 14px;
            border-radius: 50px;
            border: 1.5px solid var(--card-border);
            background: var(--card-bg);
            cursor: pointer;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            outline: none;
            box-shadow: var(--shadow-sm);
            white-space: nowrap;
        }
        .user-menu-pill:hover,
        .user-menu-pill:focus,
        .user-menu-pill[aria-expanded="true"] {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px var(--primary-glow);
        }
        .user-menu-pill .pill-name {
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--text-dark);
            max-width: 130px;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1;
        }
        .user-menu-pill .pill-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px; height: 30px;
            border-radius: 50%;
            background: linear-gradient(135deg, #7c3aed 0%, #ec4899 100%);
            color: #fff;
            font-size: 1rem;
            flex-shrink: 0;
            transition: transform 0.25s ease;
        }
        .user-menu-pill[aria-expanded="true"] .pill-icon {
            transform: rotate(90deg);
        }
        .user-dropdown-menu {
            border-radius: var(--radius-md) !important;
            border: 1px solid var(--card-border) !important;
            background: var(--card-bg) !important;
            box-shadow: var(--shadow-lg) !important;
            padding: 6px !important;
            min-width: 230px !important;
            margin-top: 10px !important;
            animation: dropdownSlideIn 0.2s cubic-bezier(0.4,0,0.2,1);
        }
        @keyframes dropdownSlideIn {
            from { opacity:0; transform: scale(0.95) translateY(-6px); }
            to   { opacity:1; transform: scale(1) translateY(0); }
        }
        .user-dropdown-menu .dropdown-item {
            border-radius: 8px !important;
            padding: 8px 12px !important;
            font-size: 0.875rem !important;
            font-weight: 500 !important;
            display: flex !important;
            align-items: center !important;
            gap: 8px !important;
        }
        .user-dropdown-menu .dropdown-item i { width: 18px; text-align: center; opacity: 0.75; }
        .user-dropdown-menu .dropdown-item:hover i { opacity: 1; }
        .user-dropdown-menu .dropdown-item.text-danger:hover {
            background: rgba(239,68,68,0.08) !important;
            color: #dc2626 !important;
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
        @media (max-width: 400px) {
            .user-menu-pill .pill-name { display: none; }
            .user-menu-pill { padding: 5px; border-radius: 50%; gap: 0; }
        }
    </style>
    @yield('styles')
    <link rel="stylesheet" href="{{ asset('css/theme-system.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dark-mode-overrides.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="admin-dashboard">

    <div class="admin-layout" id="adminLayout">
        <!-- Sidebar Backdrop for Mobile view -->
        <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

        <!-- Left Sidebar Navigation -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="admin-sidebar-header">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
                    <div class="sidebar-brand-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div>
                        <div class="sidebar-brand-text">Hire-a-Friend</div>
                        <div class="sidebar-brand-sub">Admin Portal</div>
                    </div>
                </a>
                <button class="btn d-lg-none p-0 text-theme-primary border-0" id="closeSidebarBtn" style="outline: none; box-shadow: none;">
                    <i class="bi bi-x-lg fs-5 text-muted"></i>
                </button>
            </div>

            <div class="admin-sidebar-menu-wrapper">
                <ul class="admin-sidebar-menu">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="admin-sidebar-link {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-speedometer2"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.users') }}" class="admin-sidebar-link {{ Route::is('admin.users') ? 'active' : '' }}">
                            <i class="bi bi-people"></i>
                            <span>Users</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.kyc') }}" class="admin-sidebar-link {{ Route::is('admin.kyc') ? 'active' : '' }}">
                            <i class="bi bi-person-badge"></i>
                            <span>Partners</span>
                        </a>
                    </li>
                    <li>
                        <a class="admin-sidebar-link d-flex align-items-center justify-content-between {{ Route::is('admin.homepage.*') ? 'active' : '' }}" data-bs-toggle="collapse" href="#homepageMenu" role="button" aria-expanded="{{ Route::is('admin.homepage.*') ? 'true' : 'false' }}">
                            <div>
                                <i class="bi bi-window-sidebar me-2"></i>
                                <span class="ms-1">Homepage Management</span>
                            </div>
                            <i class="bi bi-chevron-down" style="font-size: 0.8rem;"></i>
                        </a>
                        <div class="collapse {{ Route::is('admin.homepage.*') ? 'show' : '' }}" id="homepageMenu">
                            <ul class="list-unstyled ps-4 mt-2 mb-1 d-flex flex-column gap-1">
                                <li>
                                    <a href="{{ route('admin.homepage.recommended') }}" class="admin-sidebar-link py-1.5 {{ Route::is('admin.homepage.recommended') ? 'active' : '' }}" style="font-size: 0.85rem;">
                                        <i class="bi bi-star me-2"></i>Recommended Profiles
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.homepage.top') }}" class="admin-sidebar-link py-1.5 {{ Route::is('admin.homepage.top') ? 'active' : '' }}" style="font-size: 0.85rem;">
                                        <i class="bi bi-award me-2"></i>Top Profiles
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <a href="{{ route('admin.bookings') }}" class="admin-sidebar-link {{ Route::is('admin.bookings') ? 'active' : '' }}">
                            <i class="bi bi-calendar-event"></i>
                            <span>Bookings</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('conversations') }}" class="admin-sidebar-link {{ Route::is('conversations') || Route::is('conversations.show') ? 'active' : '' }}">
                            <i class="bi bi-chat-text"></i>
                            <span>Chat Moderation</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.locations') }}" class="admin-sidebar-link {{ Route::is('admin.locations') || Route::is('admin.cities') ? 'active' : '' }}">
                            <i class="bi bi-geo-alt"></i>
                            <span>Cities / Locations</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.marketing') }}" class="admin-sidebar-link {{ Route::is('admin.marketing') ? 'active' : '' }}">
                            <i class="bi bi-image"></i>
                            <span>Banners & Marketing</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.subscriptions') }}" class="admin-sidebar-link {{ Route::is('admin.subscriptions') ? 'active' : '' }}">
                            <i class="bi bi-card-checklist"></i>
                            <span>Subscriptions</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.cms') }}" class="admin-sidebar-link {{ Route::is('admin.cms') ? 'active' : '' }}">
                            <i class="bi bi-file-text"></i>
                            <span>CMS & Blogs</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.notifications') }}" class="admin-sidebar-link {{ Route::is('admin.notifications') ? 'active' : '' }}">
                            <i class="bi bi-bell"></i>
                            <span>Notifications</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.settings') }}" class="admin-sidebar-link {{ Route::is('admin.settings') ? 'active' : '' }}">
                            <i class="bi bi-gear"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.security') }}" class="admin-sidebar-link {{ Route::is('admin.security') ? 'active' : '' }}">
                            <i class="bi bi-shield-check"></i>
                            <span>Security & Audits</span>
                        </a>
                    </li>
                </ul>
            </div>
        </aside>

        <!-- Main Workspace Area -->
        <div class="admin-main" id="adminMain">
            
            <!-- Top Glassmorphism Navbar -->
            <header class="admin-header">
                <div class="d-flex align-items-center justify-content-between w-100">
                    
                    <!-- Search Bar & Toggle -->
                    <div class="d-flex align-items-center gap-3 flex-grow-1">
                        <button class="btn d-lg-none p-0 text-theme-primary border-0" id="hamburgerBtn" style="outline: none; box-shadow: none;">
                            <i class="bi bi-list fs-3 text-muted"></i>
                        </button>
                        
                        <div class="admin-search-wrapper d-none d-md-block">
                            <i class="bi bi-search"></i>
                            <input type="text" class="form-control admin-search-input border-0 bg-theme-secondary" placeholder="Global search console...">
                        </div>
                    </div>

                    <!-- Right Controls Menu -->
                    <div class="d-flex align-items-center gap-3">
                        
                        <!-- Quick Actions Button -->
                        <div class="dropdown d-none d-sm-block">
                            <button class="btn btn-quick-actions dropdown-toggle" type="button" id="quickActionsBtn" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-plus-lg"></i>
                                Quick Actions
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="quickActionsBtn">
                                <li><a class="dropdown-item" href="{{ route('admin.notifications') }}"><i class="bi bi-broadcast me-2"></i>Send Broadcast</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.locations') }}"><i class="bi bi-geo-alt me-2"></i>Add New City</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.marketing') }}"><i class="bi bi-tag me-2"></i>Create Coupon</a></li>
                            </ul>
                        </div>

                        <!-- Notification Bell Dropdown -->
                        <div class="dropdown">
                            <button class="btn btn-link p-2 rounded-circle hover-bg-gray text-theme-primary position-relative" type="button" id="adminNotifBtn" data-bs-toggle="dropdown" aria-expanded="false" style="background: transparent; outline: none; box-shadow: none;">
                                <i class="bi bi-bell fs-5 text-muted"></i>
                                <span class="position-absolute top-1.5 end-1.5 w-2 h-2 bg-danger rounded-circle"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2 mt-2" style="width: 300px;" aria-labelledby="adminNotifBtn">
                                <li class="dropdown-header fw-bold text-theme-primary">Notification Center</li>
                                <li><hr class="dropdown-divider"></li>
                                <li class="p-2 text-center text-muted small">No pending alerts in this session.</li>
                            </ul>
                        </div>

                        <!-- Messages Dropdown -->
                        <div class="dropdown">
                            <button class="btn btn-link p-2 rounded-circle hover-bg-gray text-theme-primary position-relative" type="button" id="adminMessagesBtn" data-bs-toggle="dropdown" aria-expanded="false" style="background: transparent; outline: none; box-shadow: none;">
                                <i class="bi bi-chat-text fs-5 text-muted"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2 mt-2" style="width: 300px;" aria-labelledby="adminMessagesBtn">
                                <li class="dropdown-header fw-bold text-theme-primary">Recent Messages</li>
                                <li><hr class="dropdown-divider"></li>
                                <li class="p-2 text-center text-muted small">No active chat sessions.</li>
                            </ul>
                        </div>

                        <!-- Dark Mode Toggle -->
                        <button class="btn btn-link text-theme-primary p-2 rounded-circle border-0" id="darkModeToggle" aria-label="Toggle Dark Mode" style="background: transparent; outline: none; box-shadow: none;">
                            <i class="bi bi-moon-fill fs-5 text-muted" id="darkModeIcon"></i>
                        </button>

                        <!-- Admin User Menu Pill -->
                        @auth
                            <div class="dropdown">
                                <button
                                    class="user-menu-pill"
                                    type="button"
                                    id="adminUserMenu"
                                    data-bs-toggle="dropdown"
                                    data-bs-auto-close="outside"
                                    aria-expanded="false"
                                    aria-label="Account menu"
                                >
                                    <span class="pill-name">{{ Auth::user()->name }}</span>
                                    <span class="pill-icon"><i class="bi bi-list"></i></span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end user-dropdown-menu" aria-labelledby="adminUserMenu">
                                    <li class="menu-header">
                                        <div class="fw-bold text-truncate" style="font-size:0.85rem;color:var(--text-dark);max-width:190px;">{{ Auth::user()->name }}</div>
                                        <div class="mt-1"><span class="badge rounded-pill" style="background:rgba(124,58,237,0.12);color:#7c3aed;font-size:0.7rem;">SUPER ADMIN</span></div>
                                    </li>

                                    <li><a class="dropdown-item" href="{{ route('admin.settings') }}"><i class="bi bi-gear"></i> Settings</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.security') }}"><i class="bi bi-shield-check"></i> Security</a></li>
                                    <li class="menu-footer">
                                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger w-100"><i class="bi bi-box-arrow-right"></i> Logout</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @endauth

                    </div>
                </div>
            </header>

            <!-- Main Scrollable Body Content -->
            <main class="admin-body">
                
                <!-- Display Alert Notifications if any -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-success border-start border-4 mb-4" role="alert" style="background-color: var(--card-bg); color: var(--text-dark);">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill text-success fs-4 me-2"></i>
                            <div>{{ session('success') }}</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show border-danger border-start border-4 mb-4" role="alert" style="background-color: var(--card-bg); color: var(--text-dark);">
                        <div class="d-flex">
                            <i class="bi bi-exclamation-triangle-fill text-danger fs-4 me-2"></i>
                            <div>
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

                @yield('content')
            </main>

            <!-- Page Footer Copyright -->
            <footer class="admin-footer">
                <div class="text-muted">
                    &copy; 2026 Hire-a-Friend. All rights reserved.
                </div>
                <div class="admin-footer-center">
                    <span class="admin-footer-dot">&bull;</span> Hire-a-Friend
                </div>
                <div class="text-muted d-flex align-items-center gap-2">
                    <i class="bi bi-shield-check fs-5"></i> Secure SSL Verification Enabled
                </div>
            </footer>

        </div>
    </div>

    <!-- Bootstrap 5 Bundle JS with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Dark Mode & Mobile Menu Handler -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const darkModeToggle = document.getElementById('darkModeToggle');
            const darkModeIcon = document.getElementById('darkModeIcon');
            const adminLayout = document.getElementById('adminLayout');
            
            // Hamburger Mobile Toggle
            const hamburgerBtn = document.getElementById('hamburgerBtn');
            const closeSidebarBtn = document.getElementById('closeSidebarBtn');
            const sidebarBackdrop = document.getElementById('sidebarBackdrop');

            if (hamburgerBtn && adminLayout) {
                hamburgerBtn.addEventListener('click', function() {
                    adminLayout.classList.add('show-sidebar');
                });
            }

            const closeSidebar = function() {
                if (adminLayout) {
                    adminLayout.classList.remove('show-sidebar');
                }
            };

            if (closeSidebarBtn) closeSidebarBtn.addEventListener('click', closeSidebar);
            if (sidebarBackdrop) sidebarBackdrop.addEventListener('click', closeSidebar);

            // Dark Mode Toggle
            if (darkModeToggle && darkModeIcon) {
                function updateDarkModeUI() {
                    const isDark = document.documentElement.classList.contains('dark');
                    if (isDark) {
                        darkModeIcon.classList.replace('bi-moon-fill', 'bi-sun-fill');
                        darkModeIcon.classList.add('text-warning');
                    } else {
                        darkModeIcon.classList.replace('bi-sun-fill', 'bi-moon-fill');
                        darkModeIcon.classList.remove('text-warning');
                    }
                }

                updateDarkModeUI();

                darkModeToggle.addEventListener('click', function () {
                    const isDark = document.documentElement.classList.toggle('dark');
                    localStorage.setItem('darkMode', isDark);
                    updateDarkModeUI();
                });
            }

            // --- Scroll Persistence & Navigation Fixes ---
            const sidebarWrapper = document.querySelector('.admin-sidebar-menu-wrapper');
            const currentPath = window.location.pathname;
            
            // Restore Sidebar Scroll
            const savedSidebarScroll = sessionStorage.getItem('adminSidebarScroll');
            if (sidebarWrapper && savedSidebarScroll) {
                sidebarWrapper.scrollTop = parseInt(savedSidebarScroll, 10);
            }
            
            // Save Sidebar Scroll on scroll
            if (sidebarWrapper) {
                sidebarWrapper.addEventListener('scroll', function() {
                    sessionStorage.setItem('adminSidebarScroll', sidebarWrapper.scrollTop);
                });
            }

            // Restore Main Window Scroll if remaining on the same path (refresh or internal navigate)
            const savedPath = sessionStorage.getItem('adminLastPath');
            const savedScroll = sessionStorage.getItem('adminMainScroll');
            
            if (savedPath === currentPath && savedScroll) {
                window.scrollTo(0, parseInt(savedScroll, 10));
            }

            // Save Main Window Scroll & Path before unload
            window.addEventListener('beforeunload', function() {
                sessionStorage.setItem('adminLastPath', currentPath);
                sessionStorage.setItem('adminMainScroll', window.scrollY);
            });

            // Prevent '#' links from jumping to the top of the page
            document.querySelectorAll('a[href="#"]').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                });
            });
        });
    </script>
    
    @yield('scripts')
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
