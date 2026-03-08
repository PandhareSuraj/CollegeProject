<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" id="html-root">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Campus Store Management')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    <style>
        /* =============================================
           LIGHT THEME VARIABLES (default)
           ============================================= */
        :root {
            --bg-body:        #f1f5f9;
            --bg-sidebar:     #1e40af;
            --bg-sidebar-end: #1d4ed8;
            --bg-navbar:      #ffffff;
            --bg-card:        #ffffff;
            --bg-hover:       rgba(255,255,255,0.15);
            --bg-active:      rgba(255,255,255,0.25);

            --text-primary:   #0f172a;
            --text-secondary: #475569;
            --text-tertiary:  #94a3b8;
            --text-sidebar:   #ffffff;

            --border-color:   #e2e8f0;
            --shadow:         0 1px 3px rgba(0,0,0,0.10);

            --input-bg:       #ffffff;
            --input-border:   #cbd5e1;
            --input-text:     #0f172a;

            --badge-bg:       #dbeafe;
            --badge-text:     #1e40af;

            /* Table */
            --table-header-bg:  #f8fafc;
            --table-header-text:#0f172a;
            --table-row-bg:     #ffffff;
            --table-row-alt:    #f8fafc;
            --table-row-hover:  #f1f5f9;
            --table-border:     #e2e8f0;
            --table-text:       #0f172a;
            --table-text-muted: #475569;

            /* Status badges — light */
            --badge-pending-bg:    rgba(245,158,11,0.12);
            --badge-pending-c:     #d97706;
            --badge-approved-bg:   rgba(34,197,94,0.12);
            --badge-approved-c:    #16a34a;
            --badge-rejected-bg:   rgba(239,68,68,0.12);
            --badge-rejected-c:    #dc2626;
            --badge-completed-bg:  rgba(99,102,241,0.12);
            --badge-completed-c:   #6366f1;
            --badge-supplied-bg:   rgba(249,115,22,0.12);
            --badge-supplied-c:    #ea580c;

            /* Sidebar width — JS updates this single var; everything else reads it */
            --sb-w: 72px;
        }

        /* =============================================
           DARK THEME VARIABLES
           ============================================= */
        html.dark {
            --bg-body:        #18181b;
            --bg-sidebar:     #0f172a;
            --bg-sidebar-end: #1e1b4b;
            --bg-navbar:      #1e293b;
            --bg-card:        #1e293b;
            --bg-hover:       rgba(255,255,255,0.10);
            --bg-active:      rgba(255,255,255,0.18);

            --text-primary:   #f1f5f9;
            --text-secondary: #94a3b8;
            --text-tertiary:  #64748b;
            --text-sidebar:   #e2e8f0;

            --border-color:   #334155;
            --shadow:         0 1px 3px rgba(0,0,0,0.35);

            --input-bg:       #1e293b;
            --input-border:   #475569;
            --input-text:     #f1f5f9;

            --badge-bg:       #1e3a8a;
            --badge-text:     #93c5fd;

            /* Table */
            --table-header-bg:  #262630;
            --table-header-text:#f1f5f9;
            --table-row-bg:     #1e293b;
            --table-row-alt:    #232c3d;
            --table-row-hover:  #2d3748;
            --table-border:     #334155;
            --table-text:       #f1f5f9;
            --table-text-muted: #94a3b8;

            /* Status badges — dark */
            --badge-pending-bg:    rgba(245,158,11,0.18);
            --badge-pending-c:     #fbbf24;
            --badge-approved-bg:   rgba(34,197,94,0.18);
            --badge-approved-c:    #4ade80;
            --badge-rejected-bg:   rgba(239,68,68,0.18);
            --badge-rejected-c:    #f87171;
            --badge-completed-bg:  rgba(99,102,241,0.18);
            --badge-completed-c:   #a5b4fc;
            --badge-supplied-bg:   rgba(249,115,22,0.18);
            --badge-supplied-c:    #fb923c;
        }

        /* =============================================
           RESETS
           ============================================= */
        *, *::before, *::after { box-sizing: border-box; }
        html, body { height: 100%; margin: 0; padding: 0; }
        body {
            background-color: var(--bg-body);
            color: var(--text-primary);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            overflow-x: hidden;
            transition: background-color 0.25s, color 0.25s;
        }

        /* =============================================
           SIDEBAR — width = var(--sb-w)
           ============================================= */
        #sidebar {
            position: fixed;
            left: 0; top: 0;
            height: 100vh;
            width: var(--sb-w);
            background: linear-gradient(160deg, var(--bg-sidebar), var(--bg-sidebar-end));
            color: var(--text-sidebar);
            box-shadow: 4px 0 20px rgba(0,0,0,0.18);
            z-index: 50;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: width 0.28s cubic-bezier(0.4,0,0.2,1);
        }

        .sidebar-header {
            height: 72px;
            display: flex; align-items: center;
            padding: 0 22px;
            border-bottom: 1px solid rgba(255,255,255,0.12);
            flex-shrink: 0; gap: 12px; overflow: hidden;
        }
        .sidebar-logo-icon { flex-shrink: 0; width: 28px; height: 28px; }

        .sidebar-brand {
            font-size: 1.05rem; font-weight: 700;
            letter-spacing: 0.02em; white-space: nowrap;
            flex: 1; color: #fff; overflow: hidden;
            opacity: 0; transform: translateX(-6px);
            transition: opacity 0.2s 0.06s, transform 0.2s 0.06s;
        }
        #sidebar.is-open .sidebar-brand { opacity: 1; transform: translateX(0); }

        #sidebarPinBtn {
            flex-shrink: 0;
            width: 28px; height: 28px;
            border: 1px solid rgba(255,255,255,0.25);
            border-radius: 7px;
            background: rgba(255,255,255,0.10);
            color: rgba(255,255,255,0.80);
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            padding: 0;
            opacity: 0; pointer-events: none;
            transition: opacity 0.2s, background 0.18s, color 0.18s;
        }
        #sidebarPinBtn svg { width: 14px; height: 14px; pointer-events: none; }
        #sidebar.is-open  #sidebarPinBtn { opacity: 1; pointer-events: auto; }
        #sidebarPinBtn:hover { background: rgba(255,255,255,0.22); color: #fff; }
        #sidebar.is-pinned #sidebarPinBtn {
            background: rgba(255,255,255,0.22); color: #fff;
            border-color: rgba(255,255,255,0.5);
        }

        .sidebar-nav {
            padding: 14px 10px;
            display: flex; flex-direction: column; gap: 3px;
            overflow-y: auto; overflow-x: hidden; flex: 1;
            scrollbar-width: none;
        }
        .sidebar-nav::-webkit-scrollbar { display: none; }

        .nav-item {
            display: flex; align-items: center; gap: 14px;
            padding: 11px 14px; border-radius: 10px;
            color: rgba(255,255,255,0.82);
            text-decoration: none;
            font-size: 0.9rem; font-weight: 500;
            white-space: nowrap; position: relative;
            background: none; border: none;
            cursor: pointer; width: 100%; text-align: left;
            transition: background 0.18s, color 0.18s;
        }
        .nav-icon { flex-shrink: 0; width: 22px; height: 22px; }
        .nav-label {
            opacity: 0; transform: translateX(-5px); flex: 1;
            transition: opacity 0.18s 0.05s, transform 0.18s 0.05s;
        }
        #sidebar.is-open .nav-label { opacity: 1; transform: translateX(0); }
        .nav-item:hover  { background: var(--bg-hover); color: #fff; }
        .nav-item.active {
            background: var(--bg-active); color: #fff;
            box-shadow: inset 0 0 0 1px rgba(255,255,255,0.15);
        }

        /* Tooltip for collapsed sidebar */
        .nav-item::after {
            content: attr(data-label);
            position: absolute;
            left: 64px; top: 50%; transform: translateY(-50%);
            background: #1e293b; color: #f1f5f9;
            font-size: 0.78rem; padding: 5px 10px;
            border-radius: 6px; white-space: nowrap;
            pointer-events: none; opacity: 0;
            transition: opacity 0.15s;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3); z-index: 999;
        }
        #sidebar:not(.is-open) .nav-item:hover::after { opacity: 1; }

        .nav-arrow {
            width: 16px; height: 16px; flex-shrink: 0;
            transition: transform 0.25s; opacity: 0;
        }
        #sidebar.is-open .nav-arrow { opacity: 1; }

        /* =============================================
           TOPBAR — left tracks --sb-w
           ============================================= */
        #topbar {
            position: fixed;
            top: 0; right: 0;
            left: var(--sb-w);
            height: 72px;
            background: var(--bg-navbar);
            border-bottom: 1px solid var(--border-color);
            box-shadow: var(--shadow);
            z-index: 40;
            display: flex; align-items: center;
            padding: 0 24px;
            justify-content: space-between;
            transition: left 0.28s cubic-bezier(0.4,0,0.2,1),
                        background 0.25s, border-color 0.25s;
        }

        .navbar-title-section {
            display: flex; align-items: center; gap: 14px;
            flex: 1; min-width: 0;
        }
        .navbar-title {
            font-size: 1rem; font-weight: 600;
            color: var(--text-primary); white-space: nowrap;
            overflow: hidden; text-overflow: ellipsis;
        }
        .topbar-right { display: flex; align-items: center; gap: 8px; }

        /* ─── Icon button (search, notif, theme) ─── */
        .icon-btn {
            width: 40px; height: 40px; border: none;
            background: transparent; border-radius: 10px;
            cursor: pointer; display: flex; align-items: center; justify-content: center;
            color: var(--text-secondary);
            transition: background 0.18s, color 0.18s;
            position: relative; padding: 8px;
        }
        .icon-btn:hover { background: var(--border-color); color: var(--text-primary); }
        .icon-btn svg { width: 22px; height: 22px; }

        /* ─── Theme icon: NO CSS controlling display.
               JS alone sets style.display on #icon-sun and #icon-moon.
               Rule of thumb:
                 Light mode → show MOON  (click to go dark)
                 Dark mode  → show SUN   (click to go light)          ─── */
        .notif-dot {
            position: absolute; top: 7px; right: 7px;
            width: 9px; height: 9px; background: #ef4444;
            border-radius: 50%; border: 2px solid var(--bg-navbar);
        }

        /* ─── User menu ─── */
        .user-menu { position: relative; }
        .user-btn {
            display: flex; align-items: center; gap: 10px;
            background: var(--border-color); border: 1px solid var(--border-color);
            border-radius: 12px; padding: 6px 12px;
            cursor: pointer; color: var(--text-primary);
            font-size: 0.875rem; font-weight: 500;
            transition: background 0.18s;
        }
        .user-btn:hover                  { background: rgba(0,0,0,0.05); }
        html.dark .user-btn:hover        { background: rgba(255,255,255,0.08); }

        .avatar {
            width: 32px; height: 32px;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: 700; font-size: 0.82rem; flex-shrink: 0;
        }

        .dropdown {
            position: absolute; right: 0; top: calc(100% + 8px);
            width: 200px; background: var(--bg-card);
            border: 1px solid var(--border-color); border-radius: 14px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.15);
            overflow: hidden; opacity: 0; pointer-events: none;
            transform: translateY(-6px);
            transition: opacity 0.2s, transform 0.2s; z-index: 100;
        }
        .user-menu:hover .dropdown,
        .user-menu:focus-within .dropdown { opacity: 1; pointer-events: auto; transform: translateY(0); }
        .dropdown a, .dropdown button {
            display: block; width: 100%; padding: 12px 16px;
            background: transparent; border: none; text-align: left;
            cursor: pointer; font-size: 0.875rem; color: var(--text-primary);
            text-decoration: none; transition: background 0.15s;
        }
        .dropdown a:hover, .dropdown button:hover { background: var(--border-color); }
        .dropdown-divider { height: 1px; background: var(--border-color); margin: 0; }

        /* =============================================
           LAYOUT
           ============================================= */
        .app-shell {
            display: flex; min-height: 100vh;
            background-color: var(--bg-body);
        }
        .main-col {
            margin-left: var(--sb-w);
            flex: 1; min-width: 0;
            display: flex; flex-direction: column;
            background-color: var(--bg-body);
            transition: margin-left 0.28s cubic-bezier(0.4,0,0.2,1),
                        background-color 0.25s;
        }
        .content-area {
            flex: 1; overflow-x: hidden;
            background: var(--bg-body);
            padding-top: 72px; min-height: 100vh;
            transition: background 0.25s;
        }
        .content-inner {
            width: 100%; max-width: 100%;
            padding: 28px 32px;
        }
        @media (max-width: 768px) { .content-inner { padding: 20px 16px; } }

        /* =============================================
           UTILITY CLASSES — theme-aware
           ============================================= */
        .page-title { font-size: 2rem; font-weight: 700; color: var(--text-primary); margin: 0 0 4px; }
        .page-desc  { font-size: 0.925rem; color: var(--text-secondary); margin: 0 0 28px; }

        /* Card */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 16px; padding: 24px;
            box-shadow: var(--shadow);
            transition: background 0.25s, border-color 0.25s;
        }

        /* Section eyebrow label */
        .section-eyebrow {
            font-size: 0.7rem; font-weight: 700;
            letter-spacing: 0.1em; text-transform: uppercase;
            color: var(--text-secondary);
            margin: 0 0 14px;
        }

        /* ── Table helpers ── */
        table { border-collapse: collapse; width: 100%; }
        th {
            background-color: var(--table-header-bg);
            color: var(--table-header-text);
            font-size: 0.72rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.06em;
            padding: 12px 20px; text-align: left;
        }
        td {
            color: var(--table-text);
            padding: 14px 20px; font-size: 0.875rem;
        }
        tr { border-bottom: 1px solid var(--table-border); }
        tbody tr { background-color: var(--table-row-bg); transition: background 0.15s; }
        tbody tr:hover { background-color: var(--table-row-hover) !important; }

        /* Table wrapper card */
        .dash-table-wrap {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 14px;
            box-shadow: var(--shadow);
            overflow: hidden;
            transition: background 0.25s, border-color 0.25s;
        }
        .dash-table-head {
            padding: 18px 24px;
            border-bottom: 1px solid var(--border-color);
            display: flex; align-items: center; gap: 10px;
            background-color: var(--bg-card);
        }
        .dash-table-title {
            font-size: 0.95rem; font-weight: 700;
            color: var(--text-primary);
        }
        .dash-table-count {
            margin-left: auto;
            font-size: 0.72rem; font-weight: 700;
            padding: 3px 10px; border-radius: 9999px;
            background: var(--badge-pending-bg);
            color: var(--badge-pending-c);
        }

        /* ── Status badges — use CSS vars, switch with theme ── */
        .status-badge {
            display: inline-block;
            font-size: 0.75rem; font-weight: 600;
            padding: 3px 10px; border-radius: 9999px;
        }
        .status-pending   { background: var(--badge-pending-bg);   color: var(--badge-pending-c); }
        .status-approved  { background: var(--badge-approved-bg);  color: var(--badge-approved-c); }
        .status-rejected  { background: var(--badge-rejected-bg);  color: var(--badge-rejected-c); }
        .status-completed { background: var(--badge-completed-bg); color: var(--badge-completed-c); }
        .status-supplied  { background: var(--badge-supplied-bg);  color: var(--badge-supplied-c); }

        /* Alerts */
        .alert {
            display: flex; align-items: flex-start; gap: 14px;
            padding: 14px 16px; border-radius: 12px;
            margin-bottom: 20px; font-size: 0.9rem;
        }
        .alert svg        { flex-shrink: 0; width: 20px; height: 20px; margin-top: 1px; }
        .alert-close      { margin-left: auto; background: transparent; border: none; cursor: pointer; padding: 0; opacity: 0.6; }
        .alert-close:hover{ opacity: 1; }
        .alert-error      { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
        .alert-success    { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
        html.dark .alert-error   { background: rgba(127,29,29,0.2);  border-color: #7f1d1d; color: #fca5a5; }
        html.dark .alert-success { background: rgba(20,83,45,0.2);   border-color: #14532d; color: #86efac; }
        .alert-error   svg { color: #dc2626; }
        .alert-success svg { color: #16a34a; }
        html.dark .alert-error   svg { color: #f87171; }
        html.dark .alert-success svg { color: #4ade80; }
        .alert-title { font-weight: 600; margin-bottom: 6px; }
        .alert ul { margin: 0; padding: 0 0 0 4px; list-style: none; }
        .alert li { margin-bottom: 2px; }

        /* Input */
        .theme-input {
            background: var(--input-bg);
            border: 1px solid var(--input-border);
            color: var(--input-text);
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 0.875rem;
            transition: border-color 0.2s;
        }
        .theme-input:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.15); }
    </style>
</head>

<body>
    {{-- ─── Anti-flash: apply correct theme + sidebar width BEFORE first paint ─── --}}
    <script>
    (function(){
        var root  = document.getElementById('html-root');
        var t     = localStorage.getItem('theme');
        var dark  = t ? t === 'dark' : window.matchMedia('(prefers-color-scheme:dark)').matches;
        if (dark) root.classList.add('dark');
        var pinned = localStorage.getItem('sidebarPinned') === 'true';
        document.documentElement.style.setProperty('--sb-w', pinned ? '240px' : '72px');
    })();
    </script>

    @auth
    <div class="app-shell">

        {{-- ═══════════════ SIDEBAR ═══════════════ --}}
        <aside id="sidebar">
            <div class="sidebar-header">
                <svg class="sidebar-logo-icon" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96C5 14.1 5.9 15 7 15h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63H17c.75 0 1.41-.41 1.75-1.03l3.58-6.49A1 1 0 0017 6H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/>
                </svg>
                <span class="sidebar-brand">Campus Store</span>

                <button id="sidebarPinBtn" aria-label="Pin sidebar" aria-pressed="false">
                    <svg id="icon-pin-off" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M16 12V4h1V2H7v2h1v8l-2 2v2h5.2v6h1.6v-6H18v-2l-2-2z"/>
                    </svg>
                    <svg id="icon-pin-on" viewBox="0 0 24 24" fill="currentColor" style="display:none;">
                        <path d="M14 4v5.793l1.707 1.707H17v2h-5v6h-2v-6H5v-2h1.293L8 9.793V4H7V2h10v2h-3z"/>
                    </svg>
                </button>
            </div>

            <nav class="sidebar-nav">

                <a href="{{ route('dashboard') }}" data-label="Dashboard"
                   class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="currentColor" viewBox="0 0 24 24"><path d="M3 13h8v8H3zm0-10h8v8H3zM13 3v8h8V3zm0 10v8h8v-8z"/></svg>
                    <span class="nav-label">Dashboard</span>
                </a>

                @if(Auth::user()->isTeacher() || Auth::user()->isAdmin())
                <a href="{{ route('requests.index') }}" data-label="Requests"
                   class="nav-item {{ request()->routeIs('requests.*') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/></svg>
                    <span class="nav-label">Requests</span>
                </a>
                @endif

                @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.products.index') }}" data-label="Products"
                   class="nav-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="currentColor" viewBox="0 0 24 24"><path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49A1 1 0 0017 6H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/></svg>
                    <span class="nav-label">Products</span>
                </a>

                <a href="{{ route('admin.orders.index') }}" data-label="Orders"
                   class="nav-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="currentColor" viewBox="0 0 24 24"><path d="M19 6h-2.5l-.71-2.04A1 1 0 0 0 14.86 3H5.14a1 1 0 0 0-.93.64L3.5 6H1a2 2 0 0 0-2 2v11c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2z"/></svg>
                    <span class="nav-label">Orders</span>
                </a>

                <a href="{{ route('admin.users.index') }}" data-label="Users"
                   class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                    <span class="nav-label">Users</span>
                </a>

                <a href="{{ route('admin.vendors.index') }}" data-label="Vendors"
                   class="nav-item {{ request()->routeIs('admin.vendors.*') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4v2l8 5 8-5V4zM4 13v7h16v-7l-8 5-8-5z"/></svg>
                    <span class="nav-label">Vendors</span>
                </a>

                <a href="{{ route('admin.order-reports.index') }}" data-label="Reports"
                   class="nav-item {{ request()->routeIs('admin.order-reports.*') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/></svg>
                    <span class="nav-label">Reports</span>
                </a>

                <a href="{{ route('admin.departments.index') }}" data-label="Departments"
                   class="nav-item {{ request()->routeIs('admin.departments.*') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="currentColor" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                    <span class="nav-label">Departments</span>
                </a>

                <button id="collegeBtn"
                        class="nav-item {{ request()->routeIs('admin.college-section.*') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3L1 9l4 2.18V15l7 4 7-4v-3.82L23 9zm0 2.27L19.52 9 12 12.73 4.48 9zm6 6.43L12 15.73 6 11.7V12.9l6 3.27 6-3.27z"/></svg>
                    <span class="nav-label" style="flex:1;text-align:left;">College Section</span>
                    <svg id="collegeArrow" class="nav-arrow" fill="currentColor" viewBox="0 0 24 24"><path d="M7 10l5 5 5-5z"/></svg>
                </button>

                <div id="collegeMenu" style="display:none;flex-direction:column;gap:3px;padding-left:18px;margin-top:2px;border-left:2px solid rgba(255,255,255,0.1);margin-left:8px;">
                    <a href="{{ route('admin.college-section.sanstha') }}" data-label="Sanstha"
                       class="nav-item {{ request()->routeIs('admin.college-section.sanstha') ? 'active' : '' }}" style="font-size:.85rem;padding:8px 10px;">
                        <svg class="nav-icon" fill="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px;"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/></svg>
                        <span class="nav-label">Sanstha</span>
                    </a>
                    <a href="{{ route('admin.college-section.college') }}" data-label="College"
                       class="nav-item {{ request()->routeIs('admin.college-section.college') ? 'active' : '' }}" style="font-size:.85rem;padding:8px 10px;">
                        <svg class="nav-icon" fill="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px;"><path d="M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82M12 3L1 9l11 6.18L23 9 12 3z"/></svg>
                        <span class="nav-label">College</span>
                    </a>
                    <a href="{{ route('admin.college-section.department') }}" data-label="Department"
                       class="nav-item {{ request()->routeIs('admin.college-section.department') ? 'active' : '' }}" style="font-size:.85rem;padding:8px 10px;">
                        <svg class="nav-icon" fill="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px;"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                        <span class="nav-label">Department</span>
                    </a>
                    <a href="{{ route('admin.college-section.department-users') }}" data-label="Dept. Users"
                       class="nav-item {{ request()->routeIs('admin.college-section.department-users') ? 'active' : '' }}" style="font-size:.85rem;padding:8px 10px;">
                        <svg class="nav-icon" fill="currentColor" viewBox="0 0 24 24" style="width:18px;height:18px;"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                        <span class="nav-label">Dept. Users</span>
                    </a>
                </div>
                @endif

            </nav>
        </aside>

        {{-- ═══════════════ MAIN ═══════════════ --}}
        <div class="main-col">

            <nav id="topbar">
                <div class="navbar-title-section">
                    <button id="mobileSidebarToggle" class="icon-btn" style="display:none" aria-label="Toggle sidebar">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    @yield('navbar-title')
                </div>

                <div class="topbar-right">

                    <button class="icon-btn" aria-label="Search">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>

                    <button class="icon-btn" aria-label="Notifications">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span class="notif-dot"></span>
                    </button>

                    {{-- ══════════════════════════════════════════
                         THEME TOGGLE
                         Light mode → moon icon visible  (click → go dark)
                         Dark  mode → sun  icon visible  (click → go light)
                         JS sets style.display. NO CSS rules touch these icons.
                    ═══════════════════════════════════════════ --}}
                    <button id="themeToggle" class="icon-btn" aria-label="Toggle theme" title="Switch light / dark">
                        {{-- MOON: default visible (starts in light mode) --}}
                        <svg id="icon-moon" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                        </svg>
                        {{-- SUN: default hidden --}}
                        <svg id="icon-sun" fill="currentColor" viewBox="0 0 20 20" style="display:none;">
                            <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
                        </svg>
                    </button>

                    <div class="user-menu">
                        <button class="user-btn" type="button" aria-haspopup="true">
                            <div class="avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
                            <span class="hidden sm:inline">{{ Auth::user()->name }}</span>
                            <svg style="width:14px;height:14px;flex-shrink:0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <div class="dropdown" role="menu">
                            <a href="{{ route('dashboard') }}" role="menuitem">My Profile</a>
                            <div class="dropdown-divider"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" role="menuitem">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <main class="content-area">
                <div class="content-inner">

                    @if(View::hasSection('page_title'))
                    <div>
                        <h1 class="page-title">@yield('page_title')</h1>
                        <p class="page-desc">@yield('page_description', '')</p>
                    </div>
                    @endif

                    @if($errors->any())
                    <div class="alert alert-error">
                        <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                        <div>
                            <div class="alert-title">Validation Errors</div>
                            <ul>@foreach($errors->all() as $error)<li>• {{ $error }}</li>@endforeach</ul>
                        </div>
                    </div>
                    @endif

                    @if(session('success'))
                    <div class="alert alert-success" id="successAlert">
                        <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <div style="flex:1">{{ session('success') }}</div>
                        <button class="alert-close" onclick="document.getElementById('successAlert').remove()">
                            <svg style="width:18px;height:18px" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-error" id="errorAlert">
                        <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                        <div style="flex:1">{{ session('error') }}</div>
                        <button class="alert-close" onclick="document.getElementById('errorAlert').remove()">
                            <svg style="width:18px;height:18px" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                        </button>
                    </div>
                    @endif

                    @yield('content')

                </div>
            </main>
        </div>
    </div>

    @else
    {{-- Guest / auth pages --}}
    <style>
        .auth-wrap { min-height:100vh; display:flex; align-items:center; justify-content:center; background:linear-gradient(135deg,#1e40af 0%,#1e3a8a 50%,#312e81 100%); padding:24px; }
        .auth-card  { width:100%; max-width:420px; }
    </style>
    <main class="auth-wrap"><div class="auth-card">@yield('content')</div></main>
    @endauth

    <script>
    document.addEventListener('DOMContentLoaded', function () {

        var html    = document.getElementById('html-root');
        var root    = document.documentElement;
        var sidebar = document.getElementById('sidebar');
        var pinBtn  = document.getElementById('sidebarPinBtn');
        var iconOff = document.getElementById('icon-pin-off');
        var iconOn  = document.getElementById('icon-pin-on');

        var COLLAPSED = '72px';
        var EXPANDED  = '240px';

        function setWidth(w) { root.style.setProperty('--sb-w', w); }
        function openSidebar()  { sidebar.classList.add('is-open'); setWidth(EXPANDED); }
        function closeSidebar() {
            if (!sidebar.classList.contains('is-pinned')) {
                sidebar.classList.remove('is-open');
                setWidth(COLLAPSED);
            }
        }

        var pinned = localStorage.getItem('sidebarPinned') === 'true';

        function applyPin(p) {
            pinned = p;
            localStorage.setItem('sidebarPinned', p ? 'true' : 'false');
            if (p) {
                sidebar.classList.add('is-pinned', 'is-open');
                pinBtn.setAttribute('aria-pressed', 'true');
                iconOff.style.display = 'none';
                iconOn.style.display  = 'block';
                setWidth(EXPANDED);
            } else {
                sidebar.classList.remove('is-pinned', 'is-open');
                pinBtn.setAttribute('aria-pressed', 'false');
                iconOff.style.display = 'block';
                iconOn.style.display  = 'none';
                setWidth(COLLAPSED);
            }
        }

        if (pinned) {
            sidebar.classList.add('is-pinned', 'is-open');
            iconOff.style.display = 'none';
            iconOn.style.display  = 'block';
            if (pinBtn) pinBtn.setAttribute('aria-pressed', 'true');
        }

        if (sidebar) {
            sidebar.addEventListener('mouseenter', function () { if (!pinned) openSidebar(); });
            sidebar.addEventListener('mouseleave', function () { if (!pinned) closeSidebar(); });
        }
        if (pinBtn) {
            pinBtn.addEventListener('click', function (e) { e.stopPropagation(); applyPin(!pinned); });
        }

        /* ═══════════════════════════════════════════════
           THEME TOGGLE — single source of truth
           ───────────────────────────────────────────────
           Convention:
             Light mode → MOON visible  (click to go dark)
             Dark  mode → SUN  visible  (click to go light)

           NO CSS rules control these icons.
           JS is the ONLY thing that sets their display.
        ═══════════════════════════════════════════════ */
        var themeBtn  = document.getElementById('themeToggle');
        var moonIcon  = document.getElementById('icon-moon');
        var sunIcon   = document.getElementById('icon-sun');

        function applyThemeIcons(isDark) {
            if (isDark) {
                // Dark mode — show sun so user can go back to light
                moonIcon.style.display = 'none';
                sunIcon.style.display  = 'block';
            } else {
                // Light mode — show moon so user can go to dark
                moonIcon.style.display = 'block';
                sunIcon.style.display  = 'none';
            }
        }

        // Sync icon with current theme on load
        applyThemeIcons(html.classList.contains('dark'));

        if (themeBtn) {
            themeBtn.addEventListener('click', function () {
                var nowDark = html.classList.toggle('dark');
                localStorage.setItem('theme', nowDark ? 'dark' : 'light');
                applyThemeIcons(nowDark);
            });
        }

        /* ─── Mobile toggle ─── */
        var mobileBtn = document.getElementById('mobileSidebarToggle');
        if (mobileBtn && window.innerWidth < 1024) {
            mobileBtn.style.display = 'flex';
            mobileBtn.addEventListener('click', function () {
                sidebar.classList.contains('is-open') ? closeSidebar() : openSidebar();
            });
        }

        /* ─── College submenu ─── */
        var collegeBtn   = document.getElementById('collegeBtn');
        var collegeMenu  = document.getElementById('collegeMenu');
        var collegeArrow = document.getElementById('collegeArrow');
        if (collegeBtn && collegeMenu) {
            collegeBtn.addEventListener('click', function (e) {
                e.preventDefault();
                var open = collegeMenu.style.display === 'flex';
                collegeMenu.style.display = open ? 'none' : 'flex';
                if (collegeArrow) collegeArrow.style.transform = open ? 'rotate(0deg)' : 'rotate(180deg)';
            });
            if (window.location.pathname.includes('college-section')) {
                collegeMenu.style.display = 'flex';
                if (collegeArrow) collegeArrow.style.transform = 'rotate(180deg)';
            }
        }
    });
    </script>

    @stack('scripts')
</body>
</html>