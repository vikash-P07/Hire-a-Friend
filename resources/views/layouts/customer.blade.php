<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard | Hire-a-Friend')</title>
    <meta name="description" content="@yield('meta_description', 'Your personal Hire-a-Friend dashboard')">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">

    <!-- Dark Mode Initializer -->
    <script>
        (function() {
            if (localStorage.getItem('darkMode') === 'true') {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    <style>
        /* ═══════════════════════════════════════════
           DESIGN SYSTEM TOKENS
        ═══════════════════════════════════════════ */
        :root {
            --brand-purple: #7c3aed;
            --brand-purple-light: #8b5cf6;
            --brand-pink: #ec4899;
            --brand-gradient: linear-gradient(135deg, #7c3aed 0%, #ec4899 100%);
            --brand-glow: rgba(124, 58, 237, 0.25);

            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #06b6d4;

            /* Light mode */
            --bg: #f1f5f9;
            --surface: #ffffff;
            --surface-2: #f8fafc;
            --border: #e2e8f0;
            --border-light: rgba(0,0,0,0.06);
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-muted: #94a3b8;
            --sidebar-bg: #ffffff;
            --sidebar-border: #f1f5f9;
            --sidebar-link-hover: #f3e8ff;
            --sidebar-link-active-bg: var(--brand-gradient);
            --navbar-bg: rgba(255,255,255,0.9);
            --input-bg: #f8fafc;
            --input-border: #e2e8f0;
            --card-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.04);
            --card-shadow-hover: 0 4px 12px rgba(0,0,0,0.08), 0 16px 40px rgba(0,0,0,0.06);
            --radius-xl: 24px;
            --radius-lg: 16px;
            --radius-md: 12px;
            --radius-sm: 8px;
            --transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1);
        }

        html.dark {
            --bg: #080d1a;
            --surface: #0f172a;
            --surface-2: #1e293b;
            --border: #1e293b;
            --border-light: rgba(255,255,255,0.06);
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --text-muted: #64748b;
            --sidebar-bg: #0f172a;
            --sidebar-border: #1e293b;
            --sidebar-link-hover: rgba(124, 58, 237, 0.15);
            --navbar-bg: rgba(15, 23, 42, 0.9);
            --input-bg: #1e293b;
            --input-border: #334155;
            --card-shadow: 0 1px 3px rgba(0,0,0,0.3), 0 4px 16px rgba(0,0,0,0.2);
            --card-shadow-hover: 0 4px 12px rgba(0,0,0,0.4), 0 16px 40px rgba(0,0,0,0.3);
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text-primary);
            min-height: 100vh;
            overflow-x: hidden;
            transition: background 0.3s, color 0.3s;
        }

        /* ═══════════════════════════════════════════
           LAYOUT
        ═══════════════════════════════════════════ */
        .c-layout {
            display: flex;
            min-height: 100vh;
        }

        /* ═══════════════════════════════════════════
           SIDEBAR
        ═══════════════════════════════════════════ */
        .c-sidebar {
            width: 264px;
            background: var(--sidebar-bg);
            border-right: 1px solid var(--sidebar-border);
            position: fixed;
            top: 0; bottom: 0; left: 0;
            z-index: 1050;
            display: flex;
            flex-direction: column;
            overflow-y: hidden;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), var(--transition);
        }

        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: var(--border); border-radius: 99px; }
        .sidebar-scroll:hover::-webkit-scrollbar-thumb { background: var(--brand-purple); }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 1.4rem 1.5rem;
            text-decoration: none;
            border-bottom: 1px solid var(--sidebar-border);
        }

        .sidebar-brand-icon {
            width: 36px; height: 36px;
            background: var(--brand-gradient);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: white;
            font-size: 1.1rem;
            flex-shrink: 0;
            box-shadow: 0 4px 12px var(--brand-glow);
        }

        .sidebar-brand-text {
            font-weight: 800;
            font-size: 1.1rem;
            color: var(--text-primary);
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

        .sidebar-scroll {
            flex: 1;
            padding: 1.25rem 1rem;
            overflow-y: auto;
        }

        .sidebar-section-label {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--text-muted);
            padding: 0 0.5rem;
            margin-bottom: 0.5rem;
            margin-top: 1.25rem;
        }

        .sidebar-section-label:first-child { margin-top: 0; }

        .sidebar-nav { list-style: none; padding: 0; margin: 0 0 0.5rem; }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 0.85rem;
            border-radius: var(--radius-sm);
            text-decoration: none;
            color: var(--text-secondary);
            font-weight: 500;
            font-size: 0.92rem;
            transition: var(--transition);
            position: relative;
            border: none;
            background: transparent;
            width: 100%;
            text-align: left;
            cursor: pointer;
        }

        .sidebar-link i { font-size: 1.1rem; flex-shrink: 0; transition: var(--transition); }

        .sidebar-link:hover {
            background: var(--sidebar-link-hover);
            color: var(--brand-purple);
        }

        .sidebar-link:hover i { color: var(--brand-purple); }

        .sidebar-link.active {
            background: var(--brand-gradient);
            color: #fff !important;
            box-shadow: 0 4px 16px var(--brand-glow);
        }

        .sidebar-link.active i { color: #fff !important; }

        .sidebar-badge {
            margin-left: auto;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 0.25em 0.6em;
            border-radius: 99px;
        }

        /* User Card at bottom of sidebar */
        .sidebar-user {
            padding: 1rem;
            border-top: 1px solid var(--sidebar-border);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sidebar-user-avatar {
            width: 38px; height: 38px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--brand-purple-light);
            flex-shrink: 0;
        }

        .sidebar-user-avatar-placeholder {
            width: 38px; height: 38px;
            border-radius: 50%;
            background: var(--brand-gradient);
            display: flex; align-items: center; justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 0.9rem;
            flex-shrink: 0;
        }

        /* ═══════════════════════════════════════════
           MAIN
        ═══════════════════════════════════════════ */
        .c-main {
            flex: 1;
            margin-left: 264px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: margin-left 0.3s;
        }

        /* ═══════════════════════════════════════════
           TOPBAR
        ═══════════════════════════════════════════ */
        .c-topbar {
            height: 66px;
            background: var(--navbar-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border-light);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 1030;
            flex-shrink: 0;
        }

        .topbar-search {
            position: relative;
            max-width: 340px;
            width: 100%;
        }

        .topbar-search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 0.95rem;
            pointer-events: none;
        }

        .topbar-search-input {
            background: var(--input-bg);
            border: 1px solid var(--input-border);
            border-radius: 50px;
            padding: 0.55rem 1rem 0.55rem 2.75rem;
            font-size: 0.9rem;
            color: var(--text-primary);
            width: 100%;
            transition: var(--transition);
        }

        .topbar-search-input:focus {
            outline: none;
            border-color: var(--brand-purple);
            box-shadow: 0 0 0 3px var(--brand-glow);
            background: var(--surface);
        }

        .topbar-search-input::placeholder { color: var(--text-muted); }

        .topbar-actions { display: flex; align-items: center; gap: 0.5rem; }

        .topbar-btn {
            width: 40px; height: 40px;
            border-radius: 50%;
            border: 1px solid var(--border);
            background: var(--surface);
            color: var(--text-secondary);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            position: relative;
        }

        .topbar-btn:hover {
            border-color: var(--brand-purple);
            color: var(--brand-purple);
            background: var(--sidebar-link-hover);
        }

        .topbar-btn-notif-dot {
            position: absolute;
            top: 6px; right: 6px;
            width: 8px; height: 8px;
            background: #ef4444;
            border-radius: 50%;
            border: 2px solid var(--surface);
        }

        .topbar-avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--brand-purple);
            cursor: pointer;
        }

        .topbar-avatar-placeholder {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: var(--brand-gradient);
            display: flex; align-items: center; justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 0.85rem;
            cursor: pointer;
            border: 2px solid var(--brand-purple);
        }

        /* ═══════════════════════════════════════════
           PAGE BODY
        ═══════════════════════════════════════════ */
        .c-body {
            flex: 1;
            padding: 2rem;
            overflow-x: hidden;
        }

        /* ═══════════════════════════════════════════
           CARDS
        ═══════════════════════════════════════════ */
        .card-glass {
            background: var(--surface);
            border: 1px solid var(--border-light);
            border-radius: var(--radius-lg);
            box-shadow: var(--card-shadow);
            transition: var(--transition);
        }

        .card-glass:hover {
            box-shadow: var(--card-shadow-hover);
            transform: translateY(-2px);
        }

        .card-glass-static {
            background: var(--surface);
            border: 1px solid var(--border-light);
            border-radius: var(--radius-lg);
            box-shadow: var(--card-shadow);
        }

        /* ═══════════════════════════════════════════
           STAT CARDS
        ═══════════════════════════════════════════ */
        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border-light);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            cursor: default;
        }

        .stat-card:hover {
            box-shadow: var(--card-shadow-hover);
            transform: translateY(-3px);
        }

        .stat-icon {
            width: 48px; height: 48px;
            border-radius: var(--radius-md);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
            margin-bottom: 1rem;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 800;
            color: var(--text-primary);
            line-height: 1;
            margin-bottom: 0.4rem;
        }

        .stat-label {
            font-size: 0.85rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .stat-change {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.78rem;
            font-weight: 600;
            padding: 0.2em 0.6em;
            border-radius: 99px;
            margin-top: 0.5rem;
        }

        /* ═══════════════════════════════════════════
           BOOKING BADGES
        ═══════════════════════════════════════════ */
        .badge-pending   { background: rgba(245,158,11,0.12); color: #d97706; }
        .badge-approved  { background: rgba(16,185,129,0.12); color: #059669; }
        .badge-rejected  { background: rgba(239,68,68,0.12); color: #dc2626; }
        .badge-completed { background: rgba(124,58,237,0.12); color: #7c3aed; }
        .badge-cancelled { background: rgba(100,116,139,0.12); color: #475569; }
        .badge-ongoing   { background: rgba(6,182,212,0.12); color: #0891b2; }

        .booking-badge {
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.3em 0.9em;
            border-radius: 99px;
            text-transform: capitalize;
        }

        /* ═══════════════════════════════════════════
           FORM ELEMENTS
        ═══════════════════════════════════════════ */
        .form-control, .form-select {
            background: var(--input-bg);
            border: 1px solid var(--input-border);
            border-radius: var(--radius-md);
            color: var(--text-primary);
            padding: 0.7rem 1rem;
            font-size: 0.92rem;
            transition: var(--transition);
        }

        .form-control:focus, .form-select:focus {
            background: var(--surface);
            border-color: var(--brand-purple);
            box-shadow: 0 0 0 3px var(--brand-glow);
            color: var(--text-primary);
            outline: none;
        }

        .form-control::placeholder { color: var(--text-muted); }

        .form-label {
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.4rem;
        }

        /* ═══════════════════════════════════════════
           BUTTONS
        ═══════════════════════════════════════════ */
        .btn-brand {
            background: var(--brand-gradient);
            color: #fff !important;
            border: none;
            border-radius: var(--radius-md);
            padding: 0.65rem 1.5rem;
            font-weight: 600;
            font-size: 0.9rem;
            transition: var(--transition);
            box-shadow: 0 4px 14px var(--brand-glow);
            cursor: pointer;
        }

        .btn-brand:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(124,58,237,0.4);
        }

        .btn-outline-brand {
            background: transparent;
            color: var(--brand-purple) !important;
            border: 1.5px solid var(--brand-purple);
            border-radius: var(--radius-md);
            padding: 0.65rem 1.5rem;
            font-weight: 600;
            font-size: 0.9rem;
            transition: var(--transition);
            cursor: pointer;
        }

        .btn-outline-brand:hover {
            background: var(--brand-gradient);
            color: #fff !important;
            border-color: transparent;
            box-shadow: 0 4px 14px var(--brand-glow);
        }

        .btn-surface {
            background: var(--surface-2);
            color: var(--text-secondary);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            padding: 0.65rem 1.5rem;
            font-weight: 600;
            font-size: 0.9rem;
            transition: var(--transition);
        }

        .btn-surface:hover {
            background: var(--surface);
            color: var(--brand-purple);
            border-color: var(--brand-purple);
        }

        /* ═══════════════════════════════════════════
           ALERTS
        ═══════════════════════════════════════════ */
        .alert-brand {
            background: rgba(124,58,237,0.08);
            border: 1px solid rgba(124,58,237,0.2);
            border-left: 4px solid var(--brand-purple);
            color: var(--text-primary);
            border-radius: var(--radius-md);
            padding: 1rem 1.25rem;
        }

        .alert-success {
            background: rgba(16,185,129,0.08);
            border: 1px solid rgba(16,185,129,0.2);
            border-left: 4px solid var(--success);
            color: var(--text-primary);
            border-radius: var(--radius-md);
            padding: 1rem 1.25rem;
        }

        .alert-danger {
            background: rgba(239,68,68,0.08);
            border: 1px solid rgba(239,68,68,0.2);
            border-left: 4px solid var(--danger);
            color: var(--text-primary);
            border-radius: var(--radius-md);
            padding: 1rem 1.25rem;
        }

        /* ═══════════════════════════════════════════
           TABLE
        ═══════════════════════════════════════════ */
        .c-table { width: 100%; border-collapse: separate; border-spacing: 0; }
        .c-table thead th {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--text-muted);
            padding: 0.85rem 1rem;
            background: var(--surface-2);
            border-bottom: 1px solid var(--border);
        }
        .c-table thead th:first-child { border-radius: var(--radius-sm) 0 0 0; }
        .c-table thead th:last-child { border-radius: 0 var(--radius-sm) 0 0; }
        .c-table tbody td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-light);
            font-size: 0.9rem;
            color: var(--text-primary);
            vertical-align: middle;
        }
        .c-table tbody tr:last-child td { border-bottom: none; }
        .c-table tbody tr:hover td { background: var(--surface-2); }

        /* ═══════════════════════════════════════════
           MOBILE BACKDROP
        ═══════════════════════════════════════════ */
        .sidebar-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            backdrop-filter: blur(4px);
            z-index: 1049;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        /* ═══════════════════════════════════════════
           RESPONSIVE
        ═══════════════════════════════════════════ */
        @media (max-width: 991.98px) {
            .c-sidebar { transform: translateX(-100%); transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
            .c-main { margin-left: 0; }
            .c-body { padding: 1.25rem; }
            .c-topbar { padding: 0 1.25rem; }

            .show-sidebar .c-sidebar { transform: translateX(0); }
            .show-sidebar .sidebar-backdrop { opacity: 1; pointer-events: auto; }
        }

        /* Generic Mobile Responsive Enhancements */
        @media (max-width: 575.98px) {
            .c-body {
                padding: 0.85rem !important;
            }
            .c-topbar {
                padding: 0 0.85rem !important;
            }
            .card-glass-static, .card-glass {
                border-radius: var(--radius-md) !important;
                padding: 1.15rem !important;
            }
            .page-title {
                font-size: 1.35rem !important;
            }
            .page-subtitle {
                font-size: 0.8rem !important;
            }
            .c-dropdown {
                width: calc(100vw - 32px) !important;
                max-width: 340px !important;
            }
        }

        /* Table responsiveness wrapper */
        .table-responsive {
            scrollbar-width: thin;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* ═══════════════════════════════════════════
           DROPDOWN MENUS
        ═══════════════════════════════════════════ */
        .c-dropdown {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            box-shadow: var(--card-shadow-hover);
            padding: 0.5rem;
            min-width: 200px;
        }

        .c-dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.6rem 0.85rem;
            border-radius: var(--radius-sm);
            color: var(--text-secondary);
            font-size: 0.9rem;
            font-weight: 500;
            text-decoration: none;
            transition: var(--transition);
            border: none;
            background: transparent;
            width: 100%;
            cursor: pointer;
        }

        .c-dropdown-item:hover {
            background: var(--sidebar-link-hover);
            color: var(--brand-purple);
        }

        .c-dropdown-item i { width: 18px; text-align: center; }
        
        /* ═══════════════════════════════════════════
           PAGE HEADER
        ═══════════════════════════════════════════ */
        .page-header {
            margin-bottom: 1.75rem;
        }

        .page-title {
            font-size: 1.65rem;
            font-weight: 800;
            color: var(--text-primary);
            margin: 0 0 0.25rem;
            letter-spacing: -0.5px;
        }

        .page-subtitle {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin: 0;
        }

        /* ═══════════════════════════════════════════
           AVATAR
        ═══════════════════════════════════════════ */
        .avatar {
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
        }

        .avatar-placeholder {
            border-radius: 50%;
            background: var(--brand-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            flex-shrink: 0;
        }

        /* ── Unified User Menu Pill ── */
        .user-menu-pill {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 5px 6px 5px 14px;
            border-radius: 50px;
            border: 1.5px solid var(--border);
            background: var(--surface);
            cursor: pointer;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            outline: none;
            box-shadow: var(--card-shadow);
            white-space: nowrap;
        }
        .user-menu-pill:hover,
        .user-menu-pill:focus,
        .user-menu-pill[aria-expanded="true"] {
            border-color: var(--brand-purple);
            box-shadow: 0 0 0 3px var(--brand-glow);
        }
        .user-menu-pill .pill-name {
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--text-primary);
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
            background: var(--brand-gradient);
            color: #fff;
            font-size: 1rem;
            flex-shrink: 0;
            transition: transform 0.25s ease;
        }
        .user-menu-pill[aria-expanded="true"] .pill-icon {
            transform: rotate(90deg);
        }
        .user-dropdown-menu {
            background: var(--surface) !important;
            border: 1px solid var(--border) !important;
            border-radius: var(--radius-lg) !important;
            box-shadow: var(--card-shadow-hover) !important;
            padding: 6px !important;
            min-width: 230px !important;
            margin-top: 10px !important;
            animation: pillDropIn 0.2s cubic-bezier(0.4,0,0.2,1);
        }
        @keyframes pillDropIn {
            from { opacity:0; transform: scale(0.95) translateY(-6px); }
            to   { opacity:1; transform: scale(1) translateY(0); }
        }
        .user-dropdown-menu .c-dropdown-item {
            border-radius: var(--radius-sm);
            padding: 8px 12px;
        }
        .user-dropdown-menu .menu-header {
            padding: 8px 12px 6px;
            border-bottom: 1px solid var(--border);
            margin-bottom: 4px;
        }
        .user-dropdown-menu .menu-footer {
            padding-top: 4px;
            border-top: 1px solid var(--border);
            margin-top: 4px;
        }
        @media (max-width: 400px) {
            .user-menu-pill .pill-name { display: none; }
            .user-menu-pill { padding: 5px; border-radius: 50%; gap: 0; }
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
            color: var(--text-primary, #0f172a);
            border: 1px solid var(--border, rgba(0,0,0,0.08));
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

@php
    $unreadNotifs = Auth::check() ? \DB::table('notifications')->where('notifiable_id', Auth::id())->whereNull('read_at')->count() : 0;
@endphp

<div class="c-layout" id="dashLayout">
    <!-- Sidebar Backdrop -->
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <!-- ══ SIDEBAR ═══════════════════════════════════ -->
    <aside class="c-sidebar" id="cSidebar">

        <a href="{{ route('customer.dashboard') }}" class="sidebar-brand">
            <div class="sidebar-brand-icon">
                <i class="bi bi-people-fill"></i>
            </div>
            <div>
                <div class="sidebar-brand-text">Hire-a-Friend</div>
                <div class="sidebar-brand-sub">Customer Portal</div>
            </div>
        </a>

        <div class="sidebar-scroll">
            <div class="sidebar-section-label">Main</div>
            <ul class="sidebar-nav">
                <li>
                    <a href="{{ route('customer.dashboard') }}"
                       class="sidebar-link {{ Route::is('customer.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('companions.index') }}"
                       class="sidebar-link {{ Route::is('companions.*') ? 'active' : '' }}">
                        <i class="bi bi-compass"></i>
                        <span>Discover Companions</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('customer.dashboard') }}#bookings"
                       class="sidebar-link {{ request()->is('customer/dashboard') && request()->get('tab') === 'bookings' ? 'active' : '' }}">
                        <i class="bi bi-calendar-check"></i>
                        <span>My Bookings</span>
                        @php $activeBookingsCount = \App\Models\Booking::where('customer_id', Auth::id())->whereIn('status',['pending','approved'])->count(); @endphp
                        @if($activeBookingsCount > 0)
                            <span class="sidebar-badge badge bg-warning text-theme-primary">{{ $activeBookingsCount }}</span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ route('customer.messages') }}"
                       class="sidebar-link {{ Route::is('customer.messages') ? 'active' : '' }}">
                        <i class="bi bi-chat-dots"></i>
                        <span>Messages</span>
                        <span class="sidebar-badge badge" id="globalChatUnread" style="background: var(--danger); color:#fff; display:none;"></span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('customer.favorites') }}"
                       class="sidebar-link {{ Route::is('customer.favorites') ? 'active' : '' }}">
                        <i class="bi bi-heart"></i>
                        <span>Favorites</span>
                    </a>
                </li>
            </ul>

            <div class="sidebar-section-label">Finance</div>
            <ul class="sidebar-nav">
                <li>
                    <a href="{{ route('customer.wallet') }}"
                       class="sidebar-link {{ Route::is('customer.wallet') ? 'active' : '' }}">
                        <i class="bi bi-wallet2"></i>
                        <span>Wallet</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('customer.reviews') }}"
                       class="sidebar-link {{ Route::is('customer.reviews') ? 'active' : '' }}">
                        <i class="bi bi-star"></i>
                        <span>Reviews & Ratings</span>
                    </a>
                </li>
            </ul>

            <div class="sidebar-section-label">Safety & Support</div>
            <ul class="sidebar-nav">
                <li>
                    <a href="{{ route('customer.safety') }}"
                       class="sidebar-link {{ Route::is('customer.safety') ? 'active' : '' }}">
                        <i class="bi bi-shield-check"></i>
                        <span>Safety Center</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('customer.notifications') }}"
                       class="sidebar-link {{ Route::is('customer.notifications') ? 'active' : '' }}">
                        <i class="bi bi-bell"></i>
                        <span>Notifications</span>
                        @if($unreadNotifs > 0)
                            <span class="sidebar-badge badge bg-danger">{{ $unreadNotifs }}</span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ route('customer.settings') }}"
                       class="sidebar-link {{ Route::is('customer.settings') ? 'active' : '' }}">
                        <i class="bi bi-gear"></i>
                        <span>Settings</span>
                    </a>
                </li>
            </ul>


        </div>

        <!-- User Card -->
        @auth
        <div class="sidebar-user">
            @if(Auth::user()->profile_picture)
                <img src="{{ Auth::user()->profile_picture_url }}" class="sidebar-user-avatar" alt="">
            @else
                <div class="sidebar-user-avatar-placeholder">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            @endif
            <div class="flex-1 overflow-hidden" style="min-width:0;">
                <div class="fw-semibold text-truncate" style="font-size:0.88rem; color:var(--text-primary);">
                    {{ Auth::user()->name }}
                </div>
                <div style="font-size:0.72rem; color:var(--text-muted);">
                    {{ Auth::user()->email }}
                </div>
            </div>
        </div>
        @else
        <div class="sidebar-user">
            <a href="{{ route('login') }}" class="btn-brand w-100 text-center text-white text-decoration-none py-2" style="border-radius:var(--radius-sm);">
                <i class="bi bi-box-arrow-in-right me-2"></i>Log In
            </a>
        </div>
        @endauth

    </aside>

    <!-- ══ MAIN ═══════════════════════════════════════ -->
    <div class="c-main">

        <!-- ══ TOPBAR ══════════════════════════════════ -->
        <header class="c-topbar">
            <div class="d-flex align-items-center gap-3">
                <!-- Mobile menu toggle -->
                <button class="topbar-btn d-lg-none border-0" id="sidebarToggle">
                    <i class="bi bi-list fs-5"></i>
                </button>

                <!-- Location Selector Widget -->
                <div class="d-flex align-items-center cursor-pointer" id="navbarLocationSelector" style="cursor: pointer; padding: 0.25rem 0.6rem; border-radius: 30px; border: 1px solid var(--border); background-color: var(--surface);" data-bs-toggle="modal" data-bs-target="#locationSelectorModal">
                    <span class="text-primary me-1"><i class="bi bi-geo-alt-fill" style="font-size: 1.1rem; color: var(--brand-purple) !important;"></i></span>
                    <div class="d-flex flex-column text-start" style="line-height: 1.2;">
                        <span class="text-muted fw-semibold" style="font-size: 0.68rem; letter-spacing: 0.02em;">Location</span>
                        <span class="fw-bold small text-truncate" id="navbarLocationText" style="font-size: 0.8rem; color: var(--text-primary); max-width: 140px;">
                            {{ session('user_location.city') ? session('user_location.city') : 'Select Location' }}
                        </span>
                    </div>
                    <span class="ms-1 text-muted" style="font-size: 0.72rem;"><i class="bi bi-chevron-down"></i></span>
                </div>

                <!-- Search -->
                <form action="{{ route('companions.index') }}" method="GET" class="topbar-search d-none d-md-block">
                    <i class="bi bi-search topbar-search-icon"></i>
                    <input type="text" name="search" class="topbar-search-input" value="{{ request('search') }}" placeholder="Search companions, bio...">
                </form>
            </div>

            <div class="topbar-actions">
                <!-- Dark mode toggle -->
                <button class="topbar-btn" id="darkToggle" title="Toggle dark mode">
                    <i class="bi bi-moon-fill" id="darkIcon"></i>
                </button>

                <!-- Notifications -->
                @auth
                <div class="dropdown">
                    <button class="topbar-btn" data-bs-toggle="dropdown">
                        <i class="bi bi-bell fs-5"></i>
                        @if($unreadNotifs > 0)
                            <span class="topbar-btn-notif-dot"></span>
                        @endif
                    </button>
                    <div class="dropdown-menu dropdown-menu-end c-dropdown p-0" style="width:320px; border-radius:var(--radius-lg);">
                        <div class="d-flex align-items-center justify-content-between px-4 pt-4 pb-2">
                            <span class="fw-bold" style="color:var(--text-primary);">Notifications</span>
                            @if($unreadNotifs > 0)
                                <span class="badge" style="background: var(--brand-gradient); color:#fff; border-radius:99px;">{{ $unreadNotifs }} new</span>
                            @endif
                        </div>
                        @php $topNotifs = \DB::table('notifications')->where('notifiable_id', Auth::id())->orderByDesc('created_at')->limit(5)->get(); @endphp
                        @forelse($topNotifs as $n)
                            @php $nd = json_decode($n->data, true); @endphp
                            <div class="px-4 py-3 border-top" style="border-color:var(--border-light)!important;">
                                <div class="d-flex gap-3">
                                    <div style="width:36px;height:36px;border-radius:50%;background:rgba(124,58,237,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i class="bi bi-bell-fill" style="color:var(--brand-purple);font-size:0.9rem;"></i>
                                    </div>
                                    <div>
                                        <p style="font-size:0.85rem;color:var(--text-primary);margin:0 0 4px;">{{ $nd['message'] ?? 'Notification' }}</p>
                                        <span style="font-size:0.75rem;color:var(--text-muted);">{{ \Carbon\Carbon::parse($n->created_at)->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 px-4" style="color:var(--text-muted);">
                                <i class="bi bi-bell-slash d-block mb-2 fs-4"></i>
                                <span style="font-size:0.85rem;">No notifications yet</span>
                            </div>
                        @endforelse
                    </div>
                </div>
                @endauth

                <!-- Profile dropdown - Unified Pill -->
                @auth
                <div class="dropdown">
                    <button
                        class="user-menu-pill"
                        type="button"
                        id="customerUserMenu"
                        data-bs-toggle="dropdown"
                        data-bs-auto-close="outside"
                        aria-expanded="false"
                        aria-label="Account menu"
                    >
                        <span class="pill-name">{{ Auth::user()->name }}</span>
                        <span class="pill-icon"><i class="bi bi-list"></i></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end user-dropdown-menu" aria-labelledby="customerUserMenu">
                        <li class="menu-header">
                            <div class="fw-bold text-truncate" style="font-size:0.85rem;color:var(--text-primary);max-width:190px;">{{ Auth::user()->name }}</div>
                            <div class="mt-1"><span class="badge rounded-pill" style="background:rgba(124,58,237,0.12);color:#7c3aed;font-size:0.7rem;letter-spacing:0.04em;">CUSTOMER</span></div>
                        </li>

                        <li><a class="c-dropdown-item" href="{{ route('customer.settings') }}"><i class="bi bi-person-lines-fill"></i> Profile</a></li>
                        <li><a class="c-dropdown-item" href="{{ route('customer.settings') }}"><i class="bi bi-gear"></i> Settings</a></li>
                        <li class="menu-footer">
                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="c-dropdown-item text-danger w-100"><i class="bi bi-box-arrow-right"></i> Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
                @else
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('login') }}" class="btn-outline-brand btn-sm px-3 py-2 text-decoration-none" style="border-radius:10px; font-size: 0.85rem;">Log In</a>
                    <a href="{{ route('register') }}" class="btn-brand btn-sm px-3 py-2 text-decoration-none text-white" style="border-radius:10px; font-size: 0.85rem;">Register</a>
                </div>
                @endauth
            </div>
        </header>

        <!-- ══ PAGE CONTENT ═════════════════════════════ -->
        <div class="c-body">
            @if(session('success'))
                <div class="alert-success d-flex align-items-center gap-3 mb-4" role="alert">
                    <i class="bi bi-check-circle-fill text-success fs-5"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="alert-danger d-flex align-items-start gap-3 mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill text-danger fs-5 mt-1"></i>
                    <div>
                        @foreach($errors->all() as $e)
                            <div>{{ $e }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            @yield('content')
        </div>

    </div>
</div>

    @include('partials.location-modal')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>

    document.addEventListener('DOMContentLoaded', function () {
        // ── Dark mode ──────────────────────────────
        const darkToggle = document.getElementById('darkToggle');
        const darkIcon   = document.getElementById('darkIcon');

        function updateDarkUI() {
            const isDark = document.documentElement.classList.contains('dark');
            if (isDark) {
                darkIcon.classList.replace('bi-moon-fill','bi-sun-fill');
            } else {
                darkIcon.classList.replace('bi-sun-fill','bi-moon-fill');
            }
        }
        updateDarkUI();
        darkToggle && darkToggle.addEventListener('click', () => {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('darkMode', isDark);
            updateDarkUI();
        });

        // ── Mobile sidebar ─────────────────────────
        const sidebarToggle  = document.getElementById('sidebarToggle');
        const sidebarBackdrop = document.getElementById('sidebarBackdrop');
        const cSidebar = document.getElementById('cSidebar');
        const layout = document.getElementById('dashLayout');

        function openSidebar()  { layout.classList.add('show-sidebar'); document.body.style.overflow='hidden'; }
        function closeSidebar() { layout.classList.remove('show-sidebar'); document.body.style.overflow=''; }

        sidebarToggle  && sidebarToggle.addEventListener('click', (e) => { e.stopPropagation(); openSidebar(); });
        sidebarBackdrop && sidebarBackdrop.addEventListener('click', closeSidebar);

        // Close sidebar when clicking outside
        document.addEventListener('click', function (e) {
            if (layout && layout.classList.contains('show-sidebar')) {
                const clickInsideSidebar = cSidebar && cSidebar.contains(e.target);
                const clickOnToggle = sidebarToggle && sidebarToggle.contains(e.target);
                if (!clickInsideSidebar && !clickOnToggle) {
                    closeSidebar();
                }
            }
        });

        // ── Sidebar scroll persistence ────────────────
        const sidebarScroll = document.querySelector('.sidebar-scroll');
        const savedScroll = sessionStorage.getItem('customerSidebarScroll');
        if (sidebarScroll && savedScroll) {
            sidebarScroll.scrollTop = parseInt(savedScroll, 10);
        }
        if (sidebarScroll) {
            sidebarScroll.addEventListener('scroll', function() {
                sessionStorage.setItem('customerSidebarScroll', sidebarScroll.scrollTop);
            });
        }
    });

    // Coming soon toast
    function showComingSoon(feature) {
        const text  = document.getElementById('comingSoonText');
        const toast = document.getElementById('comingSoonToast');
        if (text) text.textContent = feature + ' — coming soon! 🚀';
        const bsToast = new bootstrap.Toast(toast, { delay: 3000 });
        bsToast.show();
    }

    // Open profile tab
    function openProfileTab() {
        window.location.href = '{{ route("customer.settings") }}';
    }
    </script>

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
            .catch(error => {
                console.error('Error in toggleFavorite:', error);
                btnEl.disabled = false;
                showToast('Failed to update favorites. Please try again.', 'error');
            });
        }

        // Global Chat Unread Polling
        if (window.Laravel.isAuthenticated) {
            function updateGlobalUnread() {
                fetch('{{ route("chat.unread") }}', { headers: { 'Accept': 'application/json' } })
                .then(res => res.json())
                .then(data => {
                    const badge = document.getElementById('globalChatUnread');
                    if(badge) {
                        if(data.unread_count > 0) {
                            badge.textContent = data.unread_count;
                            badge.style.display = 'inline-block';
                        } else {
                            badge.style.display = 'none';
                        }
                    }
                })
                .catch(e => console.error(e));
            }
            // Update immediately and then every 15 seconds
            updateGlobalUnread();
            setInterval(updateGlobalUnread, 15000);
        }
    </script>

    @yield('scripts')
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
