<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Quản Trị VietGo')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        /* ===== ADMIN LAYOUT ===== */
        :root {
            --sidebar-w: 260px;
            --header-h: 64px;
            --admin-bg: #0f1623;
            --admin-surface: #161d2e;
            --admin-border: rgba(255,255,255,0.07);
            --admin-text: rgba(255,255,255,0.75);
            --admin-text-muted: rgba(255,255,255,0.4);
            --accent-green: #10b981;
            --accent-green-glow: rgba(16,185,129,0.25);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: #f0f4f8; color: #1e293b; }

        .admin-layout { display: flex; min-height: 100vh; }

        /* SIDEBAR */
        .admin-sidebar {
            width: var(--sidebar-w);
            background: var(--admin-bg);
            position: fixed; top: 0; left: 0; height: 100vh;
            display: flex; flex-direction: column;
            border-right: 1px solid var(--admin-border);
            z-index: 100; overflow: hidden;
        }

        .sidebar-logo {
            padding: 22px 22px 18px;
            display: flex; align-items: center; gap: 10px;
            border-bottom: 1px solid var(--admin-border);
            text-decoration: none;
        }
        .sidebar-logo-icon {
            width: 36px; height: 36px; border-radius: 10px;
            background: linear-gradient(135deg, #10b981, #059669);
            display: flex; align-items: center; justify-content: center;
            font-size: 15px; color: white; flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(16,185,129,0.4);
        }
        .sidebar-logo-text { line-height: 1.1; }
        .sidebar-logo-text .brand { font-size: 18px; font-weight: 800; color: white; font-family: 'Outfit', sans-serif; }
        .sidebar-logo-text .brand span { color: #10b981; }
        .sidebar-logo-text .role-badge {
            font-size: 9px; background: rgba(16,185,129,0.2); color: #10b981;
            padding: 1px 6px; border-radius: 4px; font-weight: 600; letter-spacing: 1px;
        }

        .sidebar-menu { flex: 1; padding: 10px 0; overflow-y: auto; }
        .sidebar-section-title {
            font-size: 9px; font-weight: 700; letter-spacing: 1.5px;
            color: var(--admin-text-muted); padding: 12px 22px 6px;
            text-transform: uppercase;
        }

        .sidebar-item {
            display: flex; align-items: center; gap: 12px;
            padding: 11px 18px; color: var(--admin-text);
            text-decoration: none; font-size: 14px; font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
            position: relative; margin: 4px 14px; border-radius: 12px;
        }
        .sidebar-item:hover { 
            background: rgba(255,255,255,0.06); 
            color: white;
            transform: translateX(4px);
        }
        .sidebar-item.active {
            background: linear-gradient(135deg, rgba(16,185,129,0.18) 0%, rgba(16,185,129,0.05) 100%);
            color: #10b981;
            font-weight: 700;
        }
        .sidebar-item.active::before {
            content: "";
            position: absolute;
            left: 0;
            top: 25%;
            height: 50%;
            width: 4px;
            background: #10b981;
            border-radius: 0 10px 10px 0;
            box-shadow: 4px 0 15px rgba(16,185,129,0.6);
        }
        .sidebar-item .icon {
            width: 32px; height: 32px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; flex-shrink: 0; background: rgba(255,255,255,0.05);
            transition: all 0.3s;
        }
        .sidebar-item.active .icon { 
            background: #10b981; 
            color: white;
            box-shadow: 0 4px 10px rgba(16,185,129,0.3);
        }
        .sidebar-item:hover .icon { background: rgba(255,255,255,0.1); }

        .sidebar-footer {
            padding: 14px 14px;
            border-top: 1px solid var(--admin-border);
        }
        .sidebar-user {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; border-radius: 10px;
            background: rgba(255,255,255,0.04); margin-bottom: 8px;
        }
        .sidebar-user img { width: 36px; height: 36px; border-radius: 50%; object-fit: cover; border: 2px solid rgba(16,185,129,0.4); }
        .sidebar-user-info .name { font-size: 13px; font-weight: 600; color: white; }
        .sidebar-user-info .role { font-size: 11px; color: var(--admin-text-muted); }
        .btn-logout {
            width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px;
            padding: 9px; border-radius: 8px; border: 1px solid rgba(239,68,68,0.3);
            background: rgba(239,68,68,0.08); color: #f87171; font-size: 13px; font-weight: 600;
            cursor: pointer; transition: all 0.2s; font-family: inherit;
        }
        .btn-logout:hover { background: rgba(239,68,68,0.18); border-color: rgba(239,68,68,0.6); color: white; }

        /* MAIN */
        .admin-main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-height: 100vh; }

        .admin-topbar {
            height: var(--header-h); background: white; border-bottom: 1px solid #e2e8f0;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 28px; position: sticky; top: 0; z-index: 50;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04); gap: 10px;
        }
        .topbar-left { display: flex; align-items: center; gap: 12px; }
        #adminSidebarToggle { display: none; padding: 6px 10px; border-radius: 6px; border: 1px solid #d1d5db; background: white; color: #374151; cursor: pointer; }
        .topbar-title { font-size: 18px; font-weight: 700; color: #0f172a; }
        .topbar-right { display: flex; align-items: center; gap: 14px; }
        .topbar-badge {
            padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;
        }
        .badge-admin { background: #fee2e2; color: #b91c1c; }
        .badge-nv { background: #dbeafe; color: #1d4ed8; }
        .topbar-user { display: flex; align-items: center; gap: 8px; }
        .topbar-user img { width: 34px; height: 34px; border-radius: 50%; object-fit: cover; border: 2px solid #e2e8f0; }
        .topbar-user-name { font-size: 14px; font-weight: 600; color: #374151; }

        .admin-content { padding: 28px; flex: 1; }

        /* ALERTS */
        .alert {
            display: flex; align-items: flex-start; gap: 12px;
            padding: 14px 18px; border-radius: 10px; margin-bottom: 20px;
            font-size: 14px; font-weight: 500;
        }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
        .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        .alert i { margin-top: 2px; }

        /* CARDS */
        .card {
            background: white; border-radius: 14px;
            border: 1px solid #e8edf5; box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .card-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 18px 22px; border-bottom: 1px solid #f1f5f9;
        }
        .card-header h3 { font-size: 15px; font-weight: 700; color: #0f172a; display: flex; align-items: center; gap: 8px; }
        .card-header h3 i { color: #10b981; }
        .card-body { padding: 22px; }

        /* STATS */
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; margin-bottom: 24px; }
        .stat-card {
            background: white; border-radius: 14px; padding: 20px;
            border: 1px solid #e8edf5; box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            display: flex; align-items: center; gap: 16px;
        }
        .stat-icon {
            width: 52px; height: 52px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0;
        }
        .stat-icon.green { background: #d1fae5; color: #059669; }
        .stat-icon.blue { background: #dbeafe; color: #2563eb; }
        .stat-icon.orange { background: #fef3c7; color: #d97706; }
        .stat-icon.red { background: #fee2e2; color: #dc2626; }
        .stat-icon.purple { background: #ede9fe; color: #7c3aed; }
        .stat-info h4 { font-size: 12px; color: #64748b; font-weight: 500; margin-bottom: 4px; }
        .stat-info p { font-size: 26px; font-weight: 800; color: #0f172a; line-height: 1; }
        .stat-info .sub { font-size: 12px; color: #94a3b8; margin-top: 4px; }

        /* TABLE */
        .table-card { background: white; border-radius: 14px; border: 1px solid #e8edf5; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.04); }
        .table-header { display: flex; align-items: center; justify-content: space-between; padding: 18px 22px; border-bottom: 1px solid #f1f5f9; }
        .table-header h3 { font-size: 15px; font-weight: 700; color: #0f172a; }
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th {
            padding: 11px 16px; background: #f8fafc; font-size: 11px; font-weight: 700;
            color: #64748b; text-transform: uppercase; letter-spacing: 0.6px; text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }
        .data-table td { padding: 13px 16px; border-bottom: 1px solid #f1f5f9; font-size: 13.5px; vertical-align: middle; }
        .data-table tbody tr:last-child td { border-bottom: none; }
        .data-table tbody tr:hover { background: #f8fafc; }
        .overflow-x { overflow-x: auto; }
        .empty-row td { text-align: center; padding: 40px; color: #94a3b8; font-size: 14px; }

        /* BADGES */
        .badge {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 4px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 600;
        }
        .badge::before { content: ''; width: 5px; height: 5px; border-radius: 50%; background: currentColor; }
        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
        .badge-info { background: #dbeafe; color: #1e40af; }
        .badge-secondary { background: #f1f5f9; color: #64748b; }
        .badge-purple { background: #ede9fe; color: #6d28d9; }

        /* BUTTONS */
        .btn {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 10px 18px; border-radius: 9px; font-weight: 600; font-size: 13.5px;
            cursor: pointer; border: none; font-family: inherit; text-decoration: none;
            transition: all 0.2s; white-space: nowrap;
        }
        .btn-primary { background: #10b981; color: white; box-shadow: 0 3px 10px rgba(16,185,129,0.3); }
        .btn-primary:hover { background: #059669; color: white; transform: translateY(-1px); box-shadow: 0 5px 15px rgba(16,185,129,0.4); }
        .btn-secondary { background: white; color: #374151; border: 1px solid #d1d5db; }
        .btn-secondary:hover { background: #f9fafb; border-color: #9ca3af; color: #374151; }
        .btn-danger { background: #ef4444; color: white; }
        .btn-danger:hover { background: #dc2626; color: white; }
        .btn-warning { background: #f59e0b; color: white; }
        .btn-warning:hover { background: #d97706; color: white; }
        .btn-info { background: #3b82f6; color: white; }
        .btn-info:hover { background: #2563eb; color: white; }
        .btn-sm { padding: 6px 12px; font-size: 12px; border-radius: 7px; }
        .btn-icon { padding: 7px 9px; }
        .btn-full { width: 100%; justify-content: center; }

        /* FORM */
        .form-group { margin-bottom: 18px; }
        .form-label { display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px; }
        .form-label .req { color: #ef4444; }
        .form-control {
            width: 100%; padding: 10px 14px; border: 1.5px solid #e2e8f0; border-radius: 9px;
            font-family: inherit; font-size: 14px; color: #1e293b; background: white; transition: all 0.2s;
        }
        .form-control:focus { outline: none; border-color: #10b981; box-shadow: 0 0 0 3px rgba(16,185,129,0.1); }
        .form-control::placeholder { color: #94a3b8; }
        .form-hint { font-size: 12px; color: #64748b; margin-top: 5px; }
        .form-error { font-size: 12px; color: #ef4444; margin-top: 5px; }

        .filter-bar {
            display: flex; gap: 10px; align-items: center; flex-wrap: wrap;
            padding: 16px 22px; background: #f8fafc; border-bottom: 1px solid #e2e8f0;
        }
        .filter-bar .form-control { width: auto; min-width: 160px; }
        .filter-bar .btn { height: 40px; }
        .filter-bar .form-control { height: 40px; padding: 0 12px; }

        /* GRID LAYOUTS */
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; }
        .grid-detail { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; align-items: start; }

        /* DETAIL PAGE */
        .detail-row { display: flex; padding: 10px 0; border-bottom: 1px dashed #f1f5f9; font-size: 14px; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { width: 140px; flex-shrink: 0; font-weight: 600; color: #64748b; }
        .detail-value { color: #1e293b; flex: 1; }

        /* AVATAR */
        .avatar { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; }
        .avatar-sm { width: 32px; height: 32px; border-radius: 50%; object-fit: cover; }

        /* action buttons in table */
        .action-group { display: flex; gap: 6px; align-items: center; }

        /* PAGE HEADER */
        .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; }
        .page-header h2 { font-size: 22px; font-weight: 800; color: #0f172a; }
        .page-header p { font-size: 13px; color: #64748b; margin-top: 2px; }

        /* PAGINATION */
        .pagination-wrap { display: flex; justify-content: flex-end; padding: 16px 22px; border-top: 1px solid #f1f5f9; }
        .pagination-wrap nav ul { display: flex; gap: 4px; list-style: none; }
        .pagination-wrap nav a, .pagination-wrap nav span {
            display: flex; align-items: center; justify-content: center;
            width: 34px; height: 34px; border-radius: 8px; font-size: 13px; font-weight: 600;
            border: 1px solid #e2e8f0; background: white; color: #374151; text-decoration: none; transition: all 0.2s;
        }
        .pagination-wrap nav a:hover { background: #10b981; border-color: #10b981; color: white; }
        .pagination-wrap nav [aria-current="page"] > span { background: #10b981; border-color: #10b981; color: white; }

        /* STICKY ACTION BOX */
        .sticky-box { position: sticky; top: calc(var(--header-h) + 20px); }

        /* TOTAL ROW */
        .total-row { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; }
        .total-row.final { border-top: 2px solid #e2e8f0; margin-top: 8px; }
        .total-row.final .label { font-size: 16px; font-weight: 700; }
        .total-row.final .value { font-size: 20px; font-weight: 800; color: #ef4444; }

        /* SECTION DIVIDER */
        .divider { height: 1px; background: #e2e8f0; margin: 18px 0; }

        /* TOUR THUMBNAIL */
        .tour-thumb { width: 60px; height: 44px; border-radius: 8px; object-fit: cover; }

        /* ROLE BADGES */
        .role-admin { background: #fef3c7; color: #92400e; }
        .role-nv { background: #dbeafe; color: #1e40af; }
        .role-kh { background: #f0fdf4; color: #166534; }

        /* STATUS DOT */
        .status-dot { display: inline-flex; align-items: center; gap: 5px; font-size: 12.5px; font-weight: 600; }
        .status-dot::before { content: ''; width: 7px; height: 7px; border-radius: 50%; background: currentColor; }
        .status-active { color: #10b981; }
        .status-inactive { color: #94a3b8; }

        /* CHART CONTAINER */
        .chart-wrap { position: relative; height: 260px; }

        /* STAT CHANGE */
        .stat-change { font-size: 12px; display: flex; align-items: center; gap: 3px; }
        .stat-change.up { color: #10b981; }
        .stat-change.down { color: #ef4444; }

        /* ANIMATIONS */
        @keyframes fadeUp { from { opacity:0; transform:translateY(16px); } to { opacity:1; transform:translateY(0); } }
        .fade-up { animation: fadeUp 0.4s ease both; }
        .fade-up:nth-child(2) { animation-delay: 0.05s; }
        .fade-up:nth-child(3) { animation-delay: 0.1s; }
        .fade-up:nth-child(4) { animation-delay: 0.15s; }

        /* BACK HEADER */
        .back-header {
            display: flex; align-items: center; gap: 12px; margin-bottom: 24px; flex-wrap: wrap;
        }
        .back-header h2 { font-size: 20px; font-weight: 800; color: #0f172a; }
        @media (max-width: 480px) {
            .back-header { gap: 8px; margin-bottom: 16px; }
            .back-header h2 { font-size: 16px; }
        }

        /* OVERLAY FOR MOBILE SIDEBAR */
        .admin-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(15,23,42,0.5); z-index: 90;
            opacity: 0; transition: opacity 0.3s;
        }
        .admin-overlay.open { display: block; opacity: 1; }

        /* ===== RESPONSIVE ===== */

        /* Tablet landscape */
        @media (max-width: 1200px) {
            :root { --sidebar-w: 230px; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .grid-detail { grid-template-columns: 1fr; }
            .sticky-box { position: static; }
        }

        /* Tablet portrait */
        @media (max-width: 1024px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 14px; }
            .grid-3 { grid-template-columns: 1fr 1fr; }
            .admin-content { padding: 20px; }
        }

        /* Mobile / small tablet */
        @media (max-width: 768px) {
            /* Sidebar slides in */
            .admin-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s cubic-bezier(0.4,0,0.2,1);
                z-index: 100;
            }
            .admin-sidebar.open { transform: translateX(0); }
            .admin-main { margin-left: 0; }

            /* Topbar */
            #adminSidebarToggle { display: inline-flex; align-items: center; justify-content: center; }
            .admin-topbar { padding: 0 14px; gap: 8px; }
            .topbar-title { font-size: 15px; }
            .topbar-badge { display: none; }

            /* Content */
            .admin-content { padding: 14px; }

            /* Stats grid - 2 col on mobile */
            .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 10px; }
            .stat-card { padding: 14px; gap: 10px; }
            .stat-icon { width: 42px; height: 42px; font-size: 18px; border-radius: 10px; }
            .stat-info p { font-size: 20px; }
            .stat-info h4 { font-size: 11px; }

            /* Grid layouts */
            .grid-2 { grid-template-columns: 1fr; gap: 14px; }
            .grid-3 { grid-template-columns: 1fr; gap: 14px; }
            .grid-detail { grid-template-columns: 1fr; gap: 14px; }
            .sticky-box { position: static; }

            /* Filter bar */
            .filter-bar { flex-direction: column; align-items: stretch; gap: 8px; padding: 12px 14px; }
            .filter-bar .form-control { width: 100% !important; min-width: unset !important; }
            .filter-bar .btn { width: 100%; justify-content: center; }

            /* Page header */
            .page-header { flex-direction: column; align-items: flex-start; gap: 12px; margin-bottom: 16px; }
            .page-header h2 { font-size: 18px; }
            .page-header .btn { width: 100%; justify-content: center; }

            /* Tables - horizontal scroll */
            .overflow-x { overflow-x: auto; -webkit-overflow-scrolling: touch; }
            .data-table { min-width: 600px; }
            .data-table th, .data-table td { padding: 10px 12px; font-size: 13px; }

            /* Table header */
            .table-header { flex-direction: column; align-items: flex-start; gap: 10px; padding: 14px 16px; }

            /* Detail row */
            .detail-row { flex-direction: column; gap: 4px; }
            .detail-label { width: 100%; margin-bottom: 0; font-size: 12px; }
            .detail-value { font-size: 14px; }

            /* Cards */
            .card-header { padding: 14px 16px; flex-wrap: wrap; gap: 10px; }
            .card-body { padding: 14px 16px; }

            /* Action group - stack on very small screens */
            .action-group { flex-wrap: wrap; }

            /* Pagination */
            .pagination-wrap { padding: 12px 14px; justify-content: center; }
            .pagination-wrap nav a, .pagination-wrap nav span { width: 30px; height: 30px; font-size: 12px; }

            /* Alerts */
            .alert { padding: 12px 14px; font-size: 13px; }

            /* Form */
            .form-control { font-size: 16px; } /* Prevent iOS zoom */
        }

        /* Very small phones */
        @media (max-width: 480px) {
            .stats-grid { grid-template-columns: 1fr 1fr; gap: 8px; }
            .stat-card { padding: 12px 10px; gap: 8px; }
            .stat-icon { width: 36px; height: 36px; font-size: 16px; }
            .stat-info p { font-size: 18px; }
            .stat-info .sub { display: none; }
            .topbar-title { font-size: 14px; max-width: 180px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
            .admin-content { padding: 10px; }
            .card-header h3 { font-size: 13px; }
            .btn { font-size: 13px; padding: 9px 14px; }
            .btn-sm { padding: 5px 10px; font-size: 11.5px; }
        }
    </style>
    @yield('css')
</head>
<body>
    <div class="admin-layout">
        <!-- SIDEBAR -->
        <aside class="admin-sidebar">
            <a href="{{ route('quan-tri.dashboard') }}" class="sidebar-logo" style="text-decoration:none;">
                <div class="sidebar-logo-icon"><i class="fa-solid fa-plane-departure"></i></div>
                <div class="sidebar-logo-text">
                    <div class="brand">Viet<span>Go</span></div>
                    <div class="role-badge">ADMIN PANEL</div>
                </div>
            </a>

            <div class="sidebar-menu">
                <div class="sidebar-section-title">Tổng Quan</div>

                <a href="{{ route('quan-tri.dashboard') }}" class="sidebar-item {{ request()->routeIs('quan-tri.dashboard') ? 'active' : '' }}">
                    <div class="icon"><i class="fa-solid fa-chart-pie"></i></div>
                    Tổng Quan
                </a>

                <div class="sidebar-section-title">Quản Lý</div>

                <a href="{{ route('quan-tri.tour.danh-sach') }}" class="sidebar-item {{ request()->routeIs('quan-tri.tour.*') || request()->routeIs('quan-tri.lich-khoi-hanh.*') ? 'active' : '' }}">
                    <div class="icon"><i class="fa-solid fa-map-location-dot"></i></div>
                    Quản Lý Tour
                </a>

                <a href="{{ route('quan-tri.dat-tour.danh-sach') }}" class="sidebar-item {{ request()->routeIs('quan-tri.dat-tour.*') ? 'active' : '' }}">
                    <div class="icon"><i class="fa-solid fa-ticket-simple"></i></div>
                    Quản Lý Booking
                </a>

                <a href="{{ route('quan-tri.khuyen-mai.danh-sach') }}" class="sidebar-item {{ request()->routeIs('quan-tri.khuyen-mai.*') ? 'active' : '' }}">
                    <div class="icon"><i class="fa-solid fa-tags"></i></div>
                    Quản Lý Khuyến Mãi
                </a>

                <a href="{{ route('quan-tri.cam-nang.danh-sach') }}" class="sidebar-item {{ request()->routeIs('quan-tri.cam-nang.*') ? 'active' : '' }}">
                    <div class="icon"><i class="fa-solid fa-book-open-reader"></i></div>
                    Cẩm Nang Du Lịch
                </a>

                <a href="{{ route('quan-tri.nguoi-dung.danh-sach') }}" class="sidebar-item {{ request()->routeIs('quan-tri.nguoi-dung.*') ? 'active' : '' }}">
                    <div class="icon"><i class="fa-solid fa-users-gear"></i></div>
                    Người Dùng & Khách Hàng
                </a>

                <a href="{{ route('quan-tri.lich-su-dang-nhap.danh-sach') }}" class="sidebar-item {{ request()->routeIs('quan-tri.lich-su-dang-nhap.danh-sach') ? 'active' : '' }}">
                    <div class="icon"><i class="fa-solid fa-clock-rotate-left"></i></div>
                    Lịch Sử Đăng Nhập
                </a>

                @if(auth()->user()->laAdmin())
                <div class="sidebar-section-title">Phân Tích</div>

                <a href="{{ route('quan-tri.thong-ke') }}" class="sidebar-item {{ request()->routeIs('quan-tri.thong-ke') ? 'active' : '' }}">
                    <div class="icon"><i class="fa-solid fa-chart-line"></i></div>
                    Báo Cáo Thống Kê
                </a>

                <div class="sidebar-section-title">Hệ Thống</div>

                <a href="{{ route('quan-tri.he-thong') }}" class="sidebar-item {{ request()->routeIs('quan-tri.he-thong') ? 'active' : '' }}">
                    <div class="icon"><i class="fa-solid fa-gear"></i></div>
                    Cấu Hình Hệ Thống
                </a>
                @endif
            </div>

            <div class="sidebar-footer">
                <div class="sidebar-user">
                    <i class="fa-solid fa-circle-user" style="font-size: 32px; color: #94a3b8;"></i>
                    <div class="sidebar-user-info">
                        <div class="name">{{ auth()->user()->ho_ten }}</div>
                        <div class="role">{{ auth()->user()->ten_vai_tro }}</div>
                    </div>
                </div>
                <form action="{{ route('quan-tri.dang-xuat') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i> Đăng Xuất
                    </button>
                </form>
            </div>
        </aside>

        <!-- MAIN -->
        <main class="admin-main">
            <!-- OVERLAY FOR MOBILE -->
            <div class="admin-overlay" id="adminOverlay"></div>

            <header class="admin-topbar">
                <div class="topbar-left">
                    <button id="adminSidebarToggle"><i class="fa-solid fa-bars"></i></button>
                    <div class="topbar-title">@yield('page-title', 'Trang Quản Trị')</div>
                </div>
                <div class="topbar-right">
                    <!-- Removed topbar user info to avoid duplication -->
                </div>
            </header>

            <div class="admin-content">
                @if(session('thanh_cong') || session('loi') || $errors->any())
                    <!-- Giao diện Modal Thông báo Mới -->
                    <div id="systemAlertModal" style="position:fixed;inset:0;background:rgba(15,23,42,0.6);backdrop-filter:blur(4px);z-index:99999;display:flex;align-items:center;justify-content:center;opacity:0;animation:fadeIn 0.2s forwards;">
                        <!-- Modal Box -->
                        <div style="background:white;width:90%;max-width:400px;border-radius:16px;box-shadow:0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);transform:scale(0.95);animation:scaleIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;text-align:center;padding:1.5rem;overflow:hidden;position:relative;">
                            
                            @if(session('thanh_cong'))
                                <div style="width:60px;height:60px;border-radius:50%;background:#d1fae5;color:#10b981;font-size:30px;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                                <h3 style="font-size:1.25rem;font-weight:700;color:#0f172a;margin-bottom:0.5rem;font-family:var(--font-heading, 'Outfit', sans-serif);">Thành Công</h3>
                                <p style="color:#475569;font-size:0.95rem;line-height:1.5;margin-bottom:1.5rem;">{{ session('thanh_cong') }}</p>
                                <button onclick="document.getElementById('systemAlertModal').remove()" style="background:linear-gradient(135deg, #10b981, #059669);color:white;border:none;border-radius:10px;padding:10px 24px;font-weight:600;font-size:0.95rem;cursor:pointer;width:100%;transition:transform 0.1s;box-shadow:0 4px 12px rgba(16,185,129,0.3);" onmousedown="this.style.transform='scale(0.97)'" onmouseup="this.style.transform='scale(1)'">Xác nhận</button>
                            @endif
                            
                            @if(session('loi') || $errors->any())
                                <div style="width:60px;height:60px;border-radius:50%;background:#fee2e2;color:#ef4444;font-size:30px;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                </div>
                                <h3 style="font-size:1.25rem;font-weight:700;color:#0f172a;margin-bottom:0.5rem;font-family:var(--font-heading, 'Outfit', sans-serif);">Thông báo</h3>
                                <div style="color:#475569;font-size:0.95rem;line-height:1.5;margin-bottom:1.5rem;">
                                    @if(session('loi'))<p style="margin-bottom:4px;">{{ session('loi') }}</p>@endif
                                    @foreach ($errors->all() as $error)
                                        <p style="margin-bottom:4px;">{{ $error }}</p>
                                    @endforeach
                                </div>
                                <button onclick="document.getElementById('systemAlertModal').remove()" style="background:linear-gradient(135deg, #ef4444, #dc2626);color:white;border:none;border-radius:10px;padding:10px 24px;font-weight:600;font-size:0.95rem;cursor:pointer;width:100%;transition:transform 0.1s;box-shadow:0 4px 12px rgba(239,68,68,0.3);" onmousedown="this.style.transform='scale(0.97)'" onmouseup="this.style.transform='scale(1)'">Đóng lại</button>
                            @endif
                        </div>
                    </div>
                    <style>
                        @keyframes fadeIn { to { opacity: 1; } }
                        @keyframes scaleIn { to { transform: scale(1); } }
                    </style>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Intercept form onsubmit confirm
    document.querySelectorAll('form').forEach(form => {
        const onsubmitAttr = form.getAttribute('onsubmit');
        if (onsubmitAttr && onsubmitAttr.includes('confirm(')) {
            const match = onsubmitAttr.match(/confirm\(['"](.*?)['"]\)/);
            if (match && match[1]) {
                const message = match[1];
                form.removeAttribute('onsubmit');
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Xác nhận thao tác',
                        text: message,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#10b981',
                        cancelButtonColor: '#ef4444',
                        confirmButtonText: 'Đồng ý',
                        cancelButtonText: 'Hủy bỏ',
                        background: '#ffffff',
                        color: '#1e293b',
                        iconColor: '#f59e0b',
                        backdrop: 'rgba(15, 23, 42, 0.4)',
                        customClass: {
                            popup: 'rounded-2xl border border-slate-200'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            }
        }
    });

    // Intercept element onclick confirm
    document.querySelectorAll('[onclick*="confirm("]').forEach(el => {
        const onclickAttr = el.getAttribute('onclick');
        if (onclickAttr && onclickAttr.includes('confirm(')) {
            const match = onclickAttr.match(/confirm\(['"](.*?)['"]\)/);
            if (match && match[1]) {
                const message = match[1];
                el.removeAttribute('onclick');
                el.addEventListener('click', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Xác nhận thao tác',
                        text: message,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#10b981',
                        cancelButtonColor: '#ef4444',
                        confirmButtonText: 'Đồng ý',
                        cancelButtonText: 'Hủy bỏ',
                        background: '#ffffff',
                        color: '#1e293b',
                        iconColor: '#f59e0b',
                        backdrop: 'rgba(15, 23, 42, 0.4)',
                        customClass: {
                            popup: 'rounded-2xl border border-slate-200'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (el.tagName === 'A') {
                                window.location.href = el.href;
                            } else {
                                const form = el.closest('form');
                                if (form) {
                                    form.submit();
                                }
                            }
                        }
                    });
                });
            }
        }
    });
});
</script>

@yield('js')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'all 0.5s ease';
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-20px)';
                    setTimeout(() => alert.style.display = 'none', 500);
                }, 5000);
            });

            const sidebar = document.querySelector('.admin-sidebar');
            const toggleBtn = document.getElementById('adminSidebarToggle');
            const overlay = document.getElementById('adminOverlay');

            if (toggleBtn && sidebar && overlay) {
                toggleBtn.addEventListener('click', () => {
                    sidebar.classList.add('open');
                    overlay.classList.add('open');
                    document.body.style.overflow = 'hidden';
                });

                overlay.addEventListener('click', () => {
                    sidebar.classList.remove('open');
                    overlay.classList.remove('open');
                    document.body.style.overflow = '';
                });
            }
        });
    </script>
</body>
</html>
