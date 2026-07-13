<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — Admin Formation</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&family=Fira+Code:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Apply theme BEFORE paint to avoid flash -->
    <script>
        (function() {
            var t = localStorage.getItem('admin_theme') || 'light';
            document.documentElement.setAttribute('data-theme', t);
        })();
    </script>

    <style>
        /* ─── Reset ────────────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        /* ══════════════════════════════════════════════════════
           LIGHT THEME (default)
        ══════════════════════════════════════════════════════ */
        :root,
        [data-theme="light"] {
            --bg-base:      #f4efe7;
            --bg-surface:   #fffaf2;
            --bg-card:      #ffffff;
            --bg-hover:     #efe7db;
            --border:       rgba(30, 27, 24, 0.12);
            --border-glow:  rgba(15, 118, 110, 0.35);
            --primary:      #0f766e;
            --primary-dim:  rgba(15, 118, 110, 0.1);
            --accent:       #f97316;
            --accent-dim:   rgba(249, 115, 22, 0.1);
            --warning:      #D97706;
            --warning-dim:  rgba(217,119,6,0.1);
            --danger:       #DC2626;
            --danger-dim:   rgba(220,38,38,0.08);
            --info:         #0284C7;
            --info-dim:     rgba(2,132,199,0.1);
            --text-1:       #1e1b18;
            --text-2:       #6f665f;
            --text-3:       #9a8f85;
            --shadow-card:  0 18px 60px rgba(40, 30, 20, 0.08), 0 4px 16px rgba(0,0,0,0.04);
            --topbar-bg:    rgba(255,250,242,0.9);
            /* badges light */
            --badge-success-text: #0b5b55;
            --badge-warning-text: #B45309;
            --badge-danger-text:  #B91C1C;
            --badge-info-text:    #0369A1;
            --badge-primary-text: #0b5b55;
            /* alert text */
            --alert-success-text: #0b5b55;
            --alert-danger-text:  #B91C1C;
        }

        /* ══════════════════════════════════════════════════════
           DARK THEME
        ══════════════════════════════════════════════════════ */
        [data-theme="dark"] {
            --bg-base:      #12100e;
            --bg-surface:   #1a1715;
            --bg-card:      #231f1c;
            --bg-hover:     #2c2724;
            --border:       rgba(255,255,255,0.08);
            --border-glow:  rgba(15, 118, 110, 0.4);
            --primary:      #14b8a6;
            --primary-dim:  rgba(20, 184, 166, 0.15);
            --accent:       #fdba74;
            --accent-dim:   rgba(253, 186, 116, 0.15);
            --warning:      #F59E0B;
            --warning-dim:  rgba(245,158,11,0.12);
            --danger:       #EF4444;
            --danger-dim:   rgba(239,68,68,0.12);
            --info:         #38BDF8;
            --info-dim:     rgba(56,189,248,0.12);
            --text-1:       #fcfaf8;
            --text-2:       #d0c8c0;
            --text-3:       #887d74;
            --shadow-card:  0 4px 32px rgba(0,0,0,0.45);
            --topbar-bg:    rgba(26,23,21,0.85);
            --badge-success-text: #5eead4;
            --badge-warning-text: #FCD34D;
            --badge-danger-text:  #FCA5A5;
            --badge-info-text:    #7DD3FC;
            --badge-primary-text: #5eead4;
            --alert-success-text: #5eead4;
            --alert-danger-text:  #FCA5A5;
        }

        /* ─── Shared vars ──────────────────────────────────── */
        :root {
            --sidebar-w:  260px;
            --radius:     12px;
            --radius-lg:  18px;
            --transition: 180ms ease;
        }

        /* ─── Smooth theme transition ──────────────────────── */
        html { transition: background var(--transition), color var(--transition); }
        body, .admin-sidebar, .admin-topbar, .card,
        .form-control, .form-select, .sidebar-link,
        .sidebar-logout-btn, .admin-page-content {
            transition: background var(--transition), color var(--transition),
                        border-color var(--transition), box-shadow var(--transition);
        }

        html, body {
            height: 100%;
            background: var(--bg-base);
            color: var(--text-1);
            font-family: 'Manrope', sans-serif;
            font-size: 15px;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        /* ─── Layout ───────────────────────────────────────── */
        .admin-shell { display: flex; min-height: 100vh; }

        /* ─── Sidebar ──────────────────────────────────────── */
        .admin-sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: var(--bg-surface);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            z-index: 100;
            overflow-y: auto;
        }
        .sidebar-brand {
            padding: 1.75rem 1.5rem 1.25rem;
            border-bottom: 1px solid var(--border);
        }
        .sidebar-brand-label {
            font-family: 'Fira Code', monospace;
            font-size: 0.68rem;
            font-weight: 500;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--primary);
            margin-bottom: 4px;
        }
        .sidebar-brand-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--text-1);
            letter-spacing: -0.02em;
        }
        .sidebar-nav { flex: 1; padding: 1rem 0.75rem; }
        .sidebar-section-label {
            font-family: 'Fira Code', monospace;
            font-size: 0.63rem;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--text-3);
            padding: 0.75rem 0.75rem 0.35rem;
        }
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.6rem 0.85rem;
            border-radius: 9px;
            color: var(--text-2);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            margin-bottom: 2px;
            border: 1px solid transparent;
            white-space: nowrap;
            overflow: hidden;
        }
        .sidebar-link svg { flex-shrink: 0; opacity: 0.65; transition: opacity var(--transition); }
        .sidebar-link:hover { background: var(--bg-hover); color: var(--text-1); }
        .sidebar-link:hover svg { opacity: 1; }
        .sidebar-link.active {
            background: var(--primary-dim);
            color: var(--primary);
            border-color: var(--border-glow);
        }
        .sidebar-link.active svg { opacity: 1; }

        .sidebar-visit-btn {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            margin: 0 0.75rem 0.75rem;
            padding: 0.65rem 1rem;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary) 0%, #18a39a 100%);
            color: #fff !important;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            box-shadow: 0 4px 18px rgba(15, 118, 110, 0.3);
            cursor: pointer;
            transition: opacity 200ms, transform 200ms, box-shadow 200ms !important;
        }
        .sidebar-visit-btn:hover { opacity: 0.88; transform: translateY(-1px); box-shadow: 0 6px 26px rgba(99,102,241,0.45); }
        .sidebar-visit-btn svg { flex-shrink: 0; }

        .sidebar-footer { padding: 0.75rem; border-top: 1px solid var(--border); }
        .sidebar-logout-btn {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            width: 100%;
            padding: 0.6rem 0.85rem;
            border-radius: 9px;
            border: 1px solid var(--border);
            background: transparent;
            color: var(--text-2);
            font-size: 0.85rem;
            font-family: 'Manrope', sans-serif;
            font-weight: 500;
            cursor: pointer;
        }
        .sidebar-logout-btn:hover { background: var(--danger-dim); color: var(--danger); border-color: color-mix(in srgb, var(--danger) 30%, transparent); }

        /* ─── Main ─────────────────────────────────────────── */
        .admin-main { margin-left: var(--sidebar-w); flex: 1; min-height: 100vh; display: flex; flex-direction: column; }

        /* ─── Topbar ───────────────────────────────────────── */
        .admin-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.1rem 2rem;
            border-bottom: 1px solid var(--border);
            background: var(--topbar-bg);
            backdrop-filter: blur(12px);
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .topbar-title  { font-size: 1.25rem; font-weight: 700; letter-spacing: -0.025em; color: var(--text-1); }
        .topbar-sub    { font-size: 0.75rem; color: var(--text-3); margin-top: 1px; }
        .topbar-right  { display: flex; align-items: center; gap: 0.65rem; }

        .topbar-visit-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.45rem 0.9rem;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--primary), #18a39a);
            color: #fff !important;
            font-size: 0.8rem;
            font-weight: 600;
            text-decoration: none;
            box-shadow: 0 3px 12px rgba(15, 118, 110, 0.3);
            transition: opacity 200ms, transform 200ms !important;
        }
        .topbar-visit-btn:hover { opacity: 0.85; transform: translateY(-1px); }

        /* ─── Theme Toggle Button ──────────────────────────── */
        .theme-toggle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: var(--bg-hover);
            color: var(--text-2);
            cursor: pointer;
            transition: background var(--transition), border-color var(--transition), color var(--transition), transform 200ms !important;
            position: relative;
            overflow: hidden;
        }
        .theme-toggle:hover { background: var(--primary-dim); border-color: var(--border-glow); color: var(--primary); transform: rotate(15deg); }
        .theme-toggle .icon-sun,
        .theme-toggle .icon-moon { position: absolute; transition: opacity 200ms, transform 200ms; }
        [data-theme="light"] .theme-toggle .icon-sun  { opacity: 1;  transform: rotate(0deg) scale(1); }
        [data-theme="light"] .theme-toggle .icon-moon { opacity: 0;  transform: rotate(90deg) scale(0.5); }
        [data-theme="dark"]  .theme-toggle .icon-sun  { opacity: 0;  transform: rotate(-90deg) scale(0.5); }
        [data-theme="dark"]  .theme-toggle .icon-moon { opacity: 1;  transform: rotate(0deg) scale(1); }

        /* ─── Page Content ─────────────────────────────────── */
        .admin-page-content { padding: 2rem; flex: 1; }

        /* ─── Cards ────────────────────────────────────────── */
        .card, .card-borderless {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-card);
        }

        /* ─── Tables ───────────────────────────────────────── */
        table { width: 100%; border-collapse: collapse; }
        thead { background: rgba(0, 0, 0, 0.04); }
        [data-theme="dark"] thead { background: rgba(255, 255, 255, 0.04); }
        thead th {
            font-size: 0.75rem; font-weight: 700; letter-spacing: 0.05em;
            text-transform: uppercase; color: #4b5563;
            padding: 0.85rem 1rem; border-bottom: 1px solid var(--border); text-align: left;
        }
        [data-theme="dark"] thead th { color: #9ca3af; }
        tbody td {
            padding: 0.8rem 1rem; border-bottom: 1px solid var(--border);
            color: var(--text-2); font-size: 0.875rem; vertical-align: middle;
        }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr { transition: background var(--transition); }
        tbody tr:hover td { background: var(--bg-hover); color: var(--text-1); }

        /* ─── Badges ───────────────────────────────────────── */
        .badge {
            display: inline-flex; align-items: center;
            padding: 0.2rem 0.6rem; border-radius: 999px;
            font-size: 0.72rem; font-weight: 600; letter-spacing: 0.03em;
        }
        .badge-primary { background: var(--primary-dim); color: var(--badge-primary-text); border: 1px solid rgba(99,102,241,0.2); }
        .badge-success { background: var(--accent-dim);  color: var(--badge-success-text); border: 1px solid rgba(34,197,94,0.2); }
        .badge-warning { background: var(--warning-dim); color: var(--badge-warning-text); border: 1px solid rgba(245,158,11,0.15); }
        .badge-danger  { background: var(--danger-dim);  color: var(--badge-danger-text);  border: 1px solid rgba(239,68,68,0.15); }
        .badge-info    { background: var(--info-dim);    color: var(--badge-info-text);    border: 1px solid rgba(56,189,248,0.15); }
        .badge-muted   { background: var(--bg-hover); color: var(--text-2); border: 1px solid var(--border); }
        .badge.bg-secondary { background: var(--bg-hover); color: var(--text-2); border-radius: 999px; padding: 0.2rem 0.55rem; font-size: 0.72rem; }
        .badge.rounded-pill { border-radius: 999px; }

        /* Status badges */
        .status-badge { display: inline-flex; align-items: center; justify-content: center; padding: 0.2rem 0.65rem; border-radius: 999px; font-size: 0.75rem; font-weight: 600; }
        .status-active   { background: var(--accent-dim); color: var(--badge-success-text); border: 1px solid rgba(34,197,94,0.2); }
        .status-inactive { background: var(--danger-dim); color: var(--badge-danger-text);  border: 1px solid rgba(239,68,68,0.15); }

        /* ─── Buttons ──────────────────────────────────────── */
        .btn {
            display: inline-flex; align-items: center; gap: 0.4rem;
            padding: 0.5rem 1rem; border-radius: 8px;
            font-size: 0.85rem; font-weight: 600;
            font-family: 'Manrope', sans-serif;
            cursor: pointer; text-decoration: none; border: none;
            transition: all 200ms ease; line-height: 1;
        }
        .btn-primary { background: var(--primary); color: #fff; box-shadow: 0 3px 14px rgba(15,118,110,0.3); }
        .btn-primary:hover { background: #128B82; color: #fff; box-shadow: 0 5px 20px rgba(15,118,110,0.45); transform: translateY(-1px); }
        .btn-success { background: var(--accent); color: #fff; box-shadow: 0 3px 12px rgba(249,115,22,0.25); }
        .btn-success:hover { opacity: 0.88; color: #fff; transform: translateY(-1px); }
        .btn-outline { background: transparent; border: 1px solid var(--border); color: var(--text-2); }
        .btn-outline:hover { border-color: var(--primary); color: var(--primary); background: var(--primary-dim); }
        .btn-danger-outline { background: transparent; border: 1px solid color-mix(in srgb, var(--danger) 35%, transparent); color: var(--badge-danger-text); }
        .btn-danger-outline:hover { background: var(--danger-dim); }
        .btn-sm { padding: 0.3rem 0.7rem; font-size: 0.78rem; border-radius: 6px; }
        .btn-outline-primary { background: transparent; border: 1px solid var(--primary); color: var(--primary); display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.45rem 0.9rem; border-radius: 8px; font-size: 0.85rem; font-weight: 600; cursor: pointer; text-decoration: none; font-family: 'Manrope', sans-serif; transition: all 200ms; }
        .btn-outline-primary:hover { background: var(--primary-dim); }
        .btn-outline-light { background: transparent; border: 1px solid var(--border); color: var(--text-2); display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.85rem; font-weight: 600; cursor: pointer; text-decoration: none; font-family: 'Manrope', sans-serif; transition: all 200ms; }
        .btn-outline-light:hover { background: var(--danger-dim); color: var(--danger); border-color: color-mix(in srgb, var(--danger) 30%, transparent); }
        .btn-secondary { background: var(--bg-hover); border: 1px solid var(--border); color: var(--text-2); display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.85rem; font-weight: 600; cursor: pointer; text-decoration: none; font-family: 'Manrope', sans-serif; transition: all 200ms; }
        .btn-secondary:hover { background: var(--border); color: var(--text-1); }
        .w-100 { width: 100%; justify-content: center; }

        /* ─── Forms ────────────────────────────────────────── */
        .form-label { display: block; font-size: 0.82rem; font-weight: 600; color: var(--text-2); margin-bottom: 0.4rem; }
        .form-control, .form-select {
            width: 100%; background: var(--bg-surface); border: 1px solid var(--border);
            border-radius: 8px; color: var(--text-1); padding: 0.6rem 0.9rem;
            font-size: 0.875rem; font-family: 'Manrope', sans-serif;
            outline: none; -webkit-appearance: none;
        }
        .form-control:focus, .form-select:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(15,118,110,0.15); }
        .form-control::placeholder { color: var(--text-3); }
        textarea.form-control { resize: vertical; min-height: 100px; }
        .form-check-input { width: 1em; height: 1em; background: var(--bg-surface); border: 1px solid var(--border); border-radius: 3px; cursor: pointer; accent-color: var(--primary); }
        .form-check-input:checked { background-color: var(--primary); border-color: var(--primary); }
        .form-text { font-size: 0.78rem; color: var(--text-3); margin-top: 0.3rem; }

        /* ─── Alerts ───────────────────────────────────────── */
        .alert { padding: 0.85rem 1.1rem; border-radius: var(--radius); margin-bottom: 1.25rem; border: 1px solid; }
        .alert-success { background: var(--accent-dim); border-color: color-mix(in srgb, var(--accent) 30%, transparent); color: var(--alert-success-text); }
        .alert-danger  { background: var(--danger-dim);  border-color: color-mix(in srgb, var(--danger) 20%, transparent); color: var(--alert-danger-text); }
        .alert-warning { background: var(--warning-dim); border-color: color-mix(in srgb, var(--warning) 25%, transparent); color: var(--badge-warning-text); }
        .alert ul { margin: 0.25rem 0 0 1.1rem; }

        /* ─── List groups ──────────────────────────────────── */
        .list-group { list-style: none; }
        .list-group-item { padding: 0.75rem 1rem; border-bottom: 1px solid var(--border); color: var(--text-2); font-size: 0.875rem; }
        .list-group-item:last-child { border-bottom: none; }
        .list-group-item-action { display: block; text-decoration: none; color: var(--text-2); transition: background var(--transition), color var(--transition); }
        .list-group-item-action:hover { background: var(--bg-hover); color: var(--text-1); }

        /* ─── Scrollbar ────────────────────────────────────── */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: var(--bg-base); }
        ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 8px; }

        /* ─── Utility helpers ──────────────────────────────── */
        .text-muted    { color: var(--text-2); }
        .text-sm       { font-size: 0.82rem; }
        .small         { font-size: 0.82rem; }
        .font-mono     { font-family: 'Fira Code', monospace; }
        .d-flex        { display: flex; }
        .flex          { display: flex; }
        .d-inline      { display: inline; }
        .d-block       { display: block; }
        .d-inline-flex { display: inline-flex; }
        .flex-wrap     { flex-wrap: wrap; }
        .items-center, .align-items-center { align-items: center; }
        .align-items-start  { align-items: flex-start; }
        .align-middle td, td.align-middle { vertical-align: middle; }
        .justify-content-between, .justify-between { justify-content: space-between; }
        .justify-content-end  { justify-content: flex-end; }
        .text-end   { text-align: right; }
        .text-center{ text-align: center; }
        .text-start { text-align: left; }
        .gap-1 { gap: 0.25rem; }
        .gap-2 { gap: 0.5rem; }
        .gap-3 { gap: 0.75rem; }
        /* Grid */
        .row    { display: flex; flex-wrap: wrap; gap: 1.25rem; }
        .g-4    { gap: 1.25rem; }
        .g-3    { gap: 1rem; }
        .col-md-6 { flex: 1 1 calc(50% - 0.625rem); min-width: 220px; }
        .col-md-3 { flex: 1 1 calc(25% - 0.95rem); min-width: 160px; }
        .col-md-4 { flex: 1 1 calc(33.33% - 0.84rem); min-width: 180px; }
        .col-md-8 { flex: 1 1 calc(66.66% - 0.42rem); min-width: 260px; }
        .col-12   { flex: 0 0 100%; width: 100%; }
        .col-6    { flex: 1 1 calc(50% - 0.625rem); min-width: 140px; }
        /* Spacing */
        .mb-0  { margin-bottom: 0; }
        .mb-1  { margin-bottom: 0.25rem; }
        .mb-2  { margin-bottom: 0.5rem; }
        .mb-3  { margin-bottom: 1rem; }
        .mb-4  { margin-bottom: 1.5rem; }
        .mt-2  { margin-top: 0.5rem; }
        .mt-3  { margin-top: 1rem; }
        .mt-4  { margin-top: 1.5rem; }
        .me-2  { margin-right: 0.5rem; }
        .ms-2  { margin-left: 0.5rem; }
        .py-2  { padding-top: 0.5rem; padding-bottom: 0.5rem; }
        .py-5  { padding-top: 3rem; padding-bottom: 3rem; }
        .px-3  { padding-left: 1rem; padding-right: 1rem; }
        .p-3   { padding: 1rem; }
        .p-4   { padding: 1.5rem; }
        .h-100 { height: 100%; }
        /* Backgrounds */
        .bg-light   { background: var(--bg-hover); }
        .bg-primary { background: var(--primary-dim) !important; color: var(--badge-primary-text) !important; }
        .bg-success { background: var(--accent-dim)  !important; color: var(--badge-success-text) !important; }
        .bg-info    { background: var(--info-dim)    !important; color: var(--badge-info-text)    !important; }
        .bg-warning { background: var(--warning-dim) !important; color: var(--badge-warning-text) !important; }
        .bg-danger  { background: var(--danger-dim)  !important; color: var(--badge-danger-text)  !important; }
        .bg-secondary { background: var(--bg-hover)  !important; color: var(--text-2) !important; }
        /* Text */
        .text-white { color: #fff !important; }
        .text-dark  { color: var(--text-1) !important; }
        /* Border radius */
        .rounded-3  { border-radius: var(--radius); }
        .rounded-pill { border-radius: 999px; }
        /* Table responsive wrapper */
        .table-responsive { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
        /* Misc */
        .d-none { display: none; }
        .table-actions a, .table-actions button { margin-right: 0.35rem; }
        strong { font-weight: 600; color: var(--text-1); }
        h1,h2,h3,h4,h5,h6 { color: var(--text-1); letter-spacing: -0.02em; }
        h5 { font-size: 1rem; font-weight: 600; }
        small { font-size: 0.8rem; }
        a { color: var(--primary); }
        /* btn-danger (missing) */
        .btn-danger {
            background: var(--danger);
            color: #fff;
            border: none;
            box-shadow: 0 3px 10px rgba(239,68,68,0.25);
        }
        .btn-danger:hover { opacity: 0.85; color: #fff; transform: translateY(-1px); }
        .fw-bold { font-weight: 700 !important; }
        .text-danger { color: var(--danger) !important; }
        .text-success { color: var(--accent) !important; }

        /* ─── Modal ────────────────────────────────────────── */
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.5); backdrop-filter: blur(4px);
            z-index: 1000; display: flex; align-items: center; justify-content: center;
            opacity: 0; visibility: hidden; transition: all 0.2s ease;
        }
        .modal-overlay.active { opacity: 1; visibility: visible; }
        .modal-container {
            background: var(--bg-card); border: 1px solid var(--border);
            border-radius: var(--radius-lg); box-shadow: var(--shadow-card);
            width: 90%; max-width: 600px; max-height: 90vh; overflow-y: auto;
            transform: translateY(20px); transition: all 0.3s ease;
            position: relative;
        }
        .modal-overlay.active .modal-container { transform: translateY(0); }
        .modal-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; }
        .modal-title { font-size: 1.1rem; font-weight: 700; color: var(--text-1); margin: 0; }
        .modal-close { background: none; border: none; color: var(--text-3); cursor: pointer; transition: color 0.2s; padding: 0.5rem; display: flex; border-radius: 6px; }
        .modal-close:hover { color: var(--danger); background: var(--danger-dim); }
        .modal-body { padding: 1.5rem; }

        /* ─── Responsive Sidebar ───────────────────────────── */
        @media (max-width: 991px) {
            :root { --sidebar-w: 75px; }
            .sidebar-brand { padding: 1.5rem 0.5rem 1rem; align-items: center; justify-content: center; text-align: center; }
            .sidebar-brand-label, .sidebar-brand-title { display: none; }
            .sidebar-brand::before { 
                content: 'F'; display: flex; align-items: center; justify-content: center; 
                width: 36px; height: 36px; border-radius: 10px;
                background: linear-gradient(135deg, var(--primary) 0%, #18a39a 100%);
                color: #fff; font-weight: 800; font-size: 1.2rem;
                box-shadow: 0 4px 12px rgba(15, 118, 110, 0.25);
            }
            .sidebar-section-label { text-align: center; color: transparent; height: 1px; margin: 0; padding: 0.5rem 0; }
            .sidebar-section-label::after { content: '•••'; color: var(--border); display: block; font-size: 1rem; line-height: 0; }
            
            .sidebar-link { padding: 0.75rem; justify-content: center; }
            .sidebar-link .link-text { display: none; }
            .sidebar-link svg { width: 20px; height: 20px; margin: 0; }
            
            .sidebar-visit-btn { padding: 0.75rem; justify-content: center; border-radius: 50%; width: 44px; height: 44px; margin: 0 auto 0.75rem; }
            .sidebar-visit-btn .link-text, .sidebar-visit-btn .visit-arrow { display: none; }
            .sidebar-visit-btn svg { width: 20px; height: 20px; margin: 0; }
            
            .sidebar-logout-btn { padding: 0.75rem; justify-content: center; border: none; }
            .sidebar-logout-btn .link-text { display: none; }
            .sidebar-logout-btn svg { width: 20px; height: 20px; margin: 0; }
            
            .admin-topbar { padding: 1rem; }
        }

        /* ─── JS Tooltip ───────────────────────────────────── */
        .sidebar-tooltip {
            position: fixed;
            background: var(--bg-card); color: var(--text-1);
            padding: 0.5rem 1rem; border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1); border: 1px solid var(--border);
            white-space: nowrap; z-index: 9999; font-size: 0.85rem; font-weight: 600;
            transform: translateY(-50%);
            opacity: 0; visibility: hidden; transition: opacity 0.2s;
            pointer-events: none;
        }
        .sidebar-tooltip.active { opacity: 1; visibility: visible; }

        @media (max-width: 768px) {
            .topbar-right span { display: none; }
        }

        /* ─── Page fade-in ─────────────────────────────────── */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .admin-page-content { animation: fadeInUp 250ms ease both; }

        @media (prefers-reduced-motion: reduce) {
            *, .admin-page-content { animation: none !important; transition: none !important; }
        }
    </style>
</head>
<body>
    <div class="admin-shell">
        <!-- ── Sidebar ─────────────────────────────────── -->
        <aside class="admin-sidebar">
            <div class="sidebar-brand">
                <div class="sidebar-brand-label">// admin panel</div>
                <div class="sidebar-brand-title">Formation Pro</div>
            </div>

            <nav class="sidebar-nav">
                <div class="sidebar-section-label">Navigation</div>

                <a href="{{ route('admin.dashboard') }}"
                   class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                        <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
                    </svg>
                    <span class="link-text">Tableau de bord</span>
                </a>

                <div class="sidebar-section-label" style="margin-top:0.5rem;">Contenu</div>

                <a href="{{ route('admin.categories') }}"
                   class="sidebar-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <span class="link-text">Catégories</span>
                </a>

                <a href="{{ route('admin.trainings') }}"
                   class="sidebar-link {{ request()->routeIs('admin.trainings*') ? 'active' : '' }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                    </svg>
                    <span class="link-text">Formations</span>
                </a>

                <a href="{{ route('admin.skills') }}"
                   class="sidebar-link {{ request()->routeIs('admin.skills*') ? 'active' : '' }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                    </svg>
                    <span class="link-text">Compétences</span>
                </a>

                <a href="{{ route('admin.bundles') }}"
                   class="sidebar-link {{ request()->routeIs('admin.bundles*') ? 'active' : '' }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/>
                        <path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/>
                    </svg>
                    <span class="link-text">Offres Packs</span>
                </a>

                <div class="sidebar-section-label" style="margin-top:0.5rem;">Clients</div>

                <a href="{{ route('admin.registrations') }}"
                   class="sidebar-link {{ request()->routeIs('admin.registrations') ? 'active' : '' }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    <span class="link-text">Inscriptions</span>
                </a>

                <a href="{{ route('admin.payments') }}"
                   class="sidebar-link {{ request()->routeIs('admin.payments') ? 'active' : '' }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/>
                    </svg>
                    <span class="link-text">Paiements</span>
                </a>

                <div class="sidebar-section-label" style="margin-top:0.5rem;">Système</div>

                <a href="{{ route('admin.accounts.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.accounts*') ? 'active' : '' }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    <span class="link-text">Comptes Admin</span>
                </a>

                <a href="{{ route('admin.students.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.students*') ? 'active' : '' }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    <span class="link-text">Étudiants</span>
                </a>
            </nav>

            <div style="padding:0 0.75rem;margin-bottom:0.5rem;">
                <a href="{{ url('/') }}" target="_blank" class="sidebar-visit-btn">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/>
                    </svg>
                    <span class="link-text">Voir le site</span>
                    <svg class="visit-arrow" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-left:auto;opacity:0.7">
                        <path d="M7 7h10v10"/><path d="M7 17 17 7"/>
                    </svg>
                </a>
            </div>

            <div class="sidebar-footer">
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="sidebar-logout-btn">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
                        </svg>
                        <span class="link-text">Se déconnecter</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- ── Main ───────────────────────────────────── -->
        <main class="admin-main">
            <div class="admin-topbar">
                <div>
                    <div class="topbar-title">@yield('title')</div>
                    <div class="topbar-sub">Espace d'administration · Formation Pro</div>
                </div>
                <div class="topbar-right" style="display:flex;align-items:center;">
                    <!-- Notifications -->
                    <div class="topbar-notifications" style="position:relative; margin-right:0.75rem;">
                        <button id="notifToggle" title="Notifications" aria-label="Notifications" style="background:transparent;border:none;cursor:pointer;color:inherit;position:relative;display:flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:50%;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                                <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                            </svg>
                            @php
                                $unreadCount = Auth::user()->unreadNotifications->count();
                            @endphp
                            @if($unreadCount > 0)
                                <span style="position:absolute;top:-2px;right:-2px;background:var(--danger);color:#fff;font-size:10px;font-weight:700;padding:2px 5px;border-radius:10px;line-height:1;">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </button>
                        <div id="notifDropdown" style="display:none;position:absolute;top:calc(100% + 10px);right:-10px;width:320px;background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow-card);z-index:100;overflow:hidden;">
                            <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 16px;border-bottom:1px solid var(--border);background:var(--bg-surface);">
                                <strong style="font-size:14px;color:var(--text-1);">Notifications</strong>
                                @if($unreadCount > 0)
                                    <form action="{{ route('admin.notifications.readAll') }}" method="POST" style="margin:0;">
                                        @csrf
                                        <button type="submit" style="background:none;border:none;color:var(--primary);cursor:pointer;font-size:12px;text-decoration:none;font-weight:600;">Tout marquer lu</button>
                                    </form>
                                @endif
                            </div>
                            <div style="max-height:300px;overflow-y:auto;background:var(--bg-card);">
                                @forelse(Auth::user()->unreadNotifications as $notification)
                                    <a href="{{ $notification->data['url'] ?? '#' }}" style="display:block;padding:12px 16px;border-bottom:1px solid var(--border);text-decoration:none;color:inherit;">
                                        <div style="display:flex;justify-content:space-between;margin-bottom:4px;">
                                            <strong style="font-size:13px;color:var(--text-1);">
                                                {{ $notification->data['title'] ?? 'Notification' }}
                                            </strong>
                                            <span style="font-size:11px;color:var(--text-3);">{{ $notification->created_at->diffForHumans() }}</span>
                                        </div>
                                        <div style="font-size:13px;color:var(--text-2);line-height:1.4;">
                                            {{ $notification->data['message'] ?? '' }}
                                        </div>
                                    </a>
                                @empty
                                    <div style="padding:20px;text-align:center;color:var(--text-3);font-size:13px;">
                                        Aucune nouvelle notification
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Theme Toggle -->
                    <button class="theme-toggle" id="themeToggle" title="Changer le thème" aria-label="Basculer le thème">
                        <!-- Sun icon (light mode) -->
                        <svg class="icon-sun" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/>
                        </svg>
                        <!-- Moon icon (dark mode) -->
                        <svg class="icon-moon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                        </svg>
                    </button>

                    <!-- Visit Site -->
                    <a href="{{ url('/') }}" target="_blank" class="topbar-visit-btn">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/>
                        </svg>
                        Voir le site
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="opacity:.75">
                            <path d="M7 7h10v10"/><path d="M7 17 17 7"/>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="admin-page-content">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- ── Modal Inscription ─────────────────────────── -->
    <div class="modal-overlay" id="registrationModal" onclick="closeRegistrationModal(event)">
        <div class="modal-container" onclick="event.stopPropagation()">
            <div class="modal-header">
                <h3 class="modal-title">Détails de l'inscription</h3>
                <button class="modal-close" onclick="closeRegistrationModal()">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="modal-body" id="registrationModalBody">
                <!-- Injected via JS -->
            </div>
        </div>
    </div>

    <script>
        // ── Theme Toggle Logic ─────────────────────────────
        const html    = document.documentElement;
        const btn     = document.getElementById('themeToggle');
        const STORAGE = 'admin_theme';

        function applyTheme(theme) {
            html.setAttribute('data-theme', theme);
            localStorage.setItem(STORAGE, theme);
        }

        btn.addEventListener('click', function () {
            const current = html.getAttribute('data-theme');
            applyTheme(current === 'dark' ? 'light' : 'dark');
        });

        // Keyboard: Space/Enter on the button
        btn.addEventListener('keydown', function (e) {
            if (e.key === ' ' || e.key === 'Enter') {
                e.preventDefault();
                btn.click();
            }
        });

        // ── Modal Logic ────────────────────────────────────
        const modal = document.getElementById('registrationModal');
        const modalBody = document.getElementById('registrationModalBody');

        function openRegistrationModal(data) {
            const content = `
                <div style="display:flex;gap:1.5rem;margin-bottom:1.5rem;flex-wrap:wrap;">
                    <div style="flex:1;min-width:200px;">
                        <div class="text-sm text-muted mb-1">Client</div>
                        <div class="fw-bold text-dark" style="font-size:1.1rem;">${data.clientName}</div>
                        <div class="text-sm mt-1">${data.clientEmail}</div>
                        ${data.clientPhone ? `<div class="text-sm">${data.clientPhone}</div>` : ''}
                    </div>
                    <div style="flex:1;min-width:200px;">
                        <div class="text-sm text-muted mb-1">Formation / Pack</div>
                        <div class="fw-bold text-dark" style="font-size:1.1rem;">${data.trainingTitle}</div>
                        <div class="text-sm mt-1">Date d'inscription : ${data.date}</div>
                        <div class="text-sm mt-1">Prix de base : <strong>${data.price}</strong></div>
                    </div>
                </div>
                <div style="background:var(--bg-hover);padding:1.25rem;border-radius:var(--radius);border:1px solid var(--border);">
                    <div style="display:flex;justify-content:space-between;margin-bottom:0.5rem;">
                        <span class="text-muted">Total payé</span>
                        <strong class="text-dark">${data.paid}</strong>
                    </div>
                    <div style="display:flex;justify-content:space-between;">
                        <span class="text-muted">Reste à payer</span>
                        <strong class="${data.remainingVal > 0 ? 'text-danger' : 'text-success'}">${data.remaining}</strong>
                    </div>
                </div>
            `;
            modalBody.innerHTML = content;
            modal.classList.add('active');
        }

        function closeRegistrationModal(e) {
            if (e && e.target !== modal) return;
            modal.classList.remove('active');
        }

        // ── Sidebar Responsive Tooltip ─────────────────────
        const sbTooltip = document.createElement('div');
        sbTooltip.className = 'sidebar-tooltip';
        document.body.appendChild(sbTooltip);

        document.querySelectorAll('.sidebar-link, .sidebar-visit-btn, .sidebar-logout-btn').forEach(el => {
            el.addEventListener('mouseenter', e => {
                if(window.innerWidth > 991) return;
                const textEl = el.querySelector('.link-text');
                if(!textEl) return;
                const rect = el.getBoundingClientRect();
                sbTooltip.innerText = textEl.innerText;
                sbTooltip.style.top = (rect.top + rect.height/2) + 'px';
                sbTooltip.style.left = (rect.right + 15) + 'px';
                sbTooltip.classList.add('active');
            });
            el.addEventListener('mouseleave', () => sbTooltip.classList.remove('active'));
            el.addEventListener('click', () => sbTooltip.classList.remove('active'));
        });

        // ── Notifications Toggle ───────────────────────────
        const notifToggle = document.getElementById('notifToggle');
        const notifDropdown = document.getElementById('notifDropdown');
        if (notifToggle && notifDropdown) {
            notifToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                notifDropdown.style.display = notifDropdown.style.display === 'none' ? 'block' : 'none';
            });
            document.addEventListener('click', (e) => {
                if (!notifDropdown.contains(e.target)) {
                    notifDropdown.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>
