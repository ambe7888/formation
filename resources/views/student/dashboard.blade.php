<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Espace Étudiant - Success Business Training</title>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/style.css') }}?v=1.0.2">
    <style>
        :root {
            --primary: #0f766e;
            --primary-dark: #0b5b55;
            --primary-soft: rgba(15, 118, 110, 0.1);
            --dark: #1e1b18;
            --light-bg: #f4efe7;
            --border-color: rgba(30, 27, 24, 0.12);
            --text-main: #6f665f;
            --text-dark: #1e1b18;
            --success: #0b5b55;
            --warning: #D97706;
            --danger: #DC2626;
            --bg-surface: #fffaf2;
            --text-muted: #9a8f85;
        }

        body {
            font-family: 'Manrope', sans-serif;
            background-color: var(--light-bg);
            color: var(--text-main);
            margin: 0;
            padding: 0;
        }

        /* Top navigation header */
        .dashboard-header {
            background: var(--bg-surface);
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 100;
            padding: 1.25rem 0;
        }

        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-weight: 800;
            font-size: 1.2rem;
            color: var(--text-dark);
            text-decoration: none;
        }

        .logo span {
            color: var(--primary);
        }

        .user-nav {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .user-name {
            font-weight: 700;
            color: var(--text-dark);
            font-size: 0.95rem;
        }

        .logout-btn {
            background: none;
            border: 1px solid #cbd5e1;
            color: var(--text-main);
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .logout-btn:hover {
            background: var(--primary-soft);
            color: var(--danger);
            border-color: var(--danger);
        }

        /* Sidebar Styles */
        .student-sidebar {
            width: 260px;
            background: var(--bg-surface);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 200;
            transition: transform 0.3s ease;
        }
        .sidebar-brand { padding: 1.5rem; border-bottom: 1px solid var(--border-color); }
        .sidebar-nav { flex: 1; padding: 1rem 0.75rem; overflow-y: auto; }
        .sidebar-link {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 0.75rem 1rem; color: var(--text-main);
            text-decoration: none; border-radius: 0.5rem;
            font-weight: 600; margin-bottom: 0.25rem;
            transition: all 0.2s;
        }
        .sidebar-link:hover, .sidebar-link.active {
            background: var(--primary-soft);
            color: var(--primary);
        }
        .sidebar-footer { padding: 1rem; border-top: 1px solid var(--border-color); }

        .sidebar-overlay {
            display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.5); z-index: 150;
        }

        @media (max-width: 991px) {
            .student-sidebar { transform: translateX(-100%); }
            .student-sidebar.open { transform: translateX(0); }
            .sidebar-overlay.active { display: block; }
            .student-main { margin-left: 0 !important; }
        }

        /* Main Workspace container */
        .student-main {
            margin-left: 260px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: var(--light-bg);
            transition: margin-left 0.3s ease;
        }
        .dashboard-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1.5rem;
            width: 100%;
            box-sizing: border-box;
        }

        /* Financial summary tiles */
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        @media (max-width: 768px) {
            .mobile-scroll-row {
                display: flex !important;
                flex-wrap: nowrap !important;
                overflow-x: auto !important;
                scroll-snap-type: x mandatory;
                padding-bottom: 1rem;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: none;
                -ms-overflow-style: none;
            }
            .mobile-scroll-row::-webkit-scrollbar {
                display: none;
            }
            .mobile-scroll-row > * {
                flex: 0 0 85% !important;
                scroll-snap-align: center;
            }
        }

        .summary-card {
            background: var(--bg-card);
            border-radius: 1.25rem;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.02);
            border: 1px solid var(--border-color);
            padding: 1.75rem;
            position: relative;
            overflow: hidden;
        }

        .summary-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--primary);
        }

        .summary-card.success::before { background: var(--success); }
        .summary-card.warning::before { background: var(--warning); }
        .summary-card.danger::before { background: var(--danger); }

        .summary-label {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.03em;
            margin-bottom: 0.5rem;
        }

        .summary-value {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--text-dark);
        }

        /* Formations listings */
        .dashboard-section-title {
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 1.5rem;
        }

        .registration-item {
            background: #ffffff;
            border-radius: 1.25rem;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.03);
            border: 1px solid var(--border-color);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .registration-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        @media(min-width: 768px) {
            .registration-grid {
                grid-template-columns: 2fr 1fr 1fr;
                align-items: center;
            }
        }

        .training-info h3 {
            margin: 0 0 0.5rem 0;
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--text-dark);
        }

        .training-meta {
            font-size: 0.85rem;
            color: #64748b;
            display: flex;
            gap: 1.5rem;
            font-weight: 600;
        }

        .financial-stat {
            display: flex;
            flex-direction: column;
        }

        .stat-lbl {
            font-size: 0.8rem;
            color: #64748b;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 0.25rem;
        }

        .stat-val {
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--text-dark);
        }

        /* Progress indicator bar */
        .progress-container {
            margin-top: 1.5rem;
            padding-top: 1.25rem;
            border-top: 1px solid #f1f5f9;
        }

        .progress-bar-wrapper {
            background: #f1f5f9;
            height: 8px;
            border-radius: 99px;
            overflow: hidden;
            margin-bottom: 0.5rem;
        }

        .progress-bar-fill {
            height: 100%;
            border-radius: 99px;
            transition: width 0.4s ease;
        }

        .progress-meta {
            display: flex;
            justify-content: space-between;
            font-size: 0.85rem;
            font-weight: 600;
            color: #64748b;
        }

        /* Payments log */
        .payments-log {
            margin-top: 1.5rem;
            background: #f8fafc;
            border-radius: 0.75rem;
            padding: 1.25rem;
            border: 1px solid #f1f5f9;
        }

        .log-title {
            font-size: 0.9rem;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 0.75rem;
        }

        .log-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
        }

        .log-table th {
            text-align: left;
            padding: 0.5rem;
            color: #64748b;
            border-bottom: 1px solid var(--border-color);
        }

        .log-table td {
            padding: 0.5rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .log-table tr:last-child td {
            border-bottom: none;
        }

        /* Declaration Form */
        .declaration-box {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px dashed var(--border-color);
        }

        .btn-declare {
            background: var(--primary-soft);
            color: var(--primary);
            border: 1px solid rgba(79, 70, 229, 0.15);
            padding: 0.6rem 1.25rem;
            border-radius: 0.5rem;
            font-size: 0.85rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-declare:hover {
            background: var(--primary);
            color: #ffffff;
        }

        .declaration-form-wrapper {
            display: none;
            background: #f8fafc;
            border-radius: 1rem;
            border: 1px solid var(--border-color);
            padding: 1.5rem;
            margin-top: 1rem;
        }

        .declaration-form-wrapper.active {
            display: block;
        }

        .form-row-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .lbl-custom {
            display: block;
            font-weight: 700;
            font-size: 0.8rem;
            color: var(--text-dark);
            margin-bottom: 0.35rem;
        }

        .ctrl-custom {
            width: 100%;
            padding: 0.65rem 0.85rem;
            border-radius: 0.5rem;
            border: 1px solid var(--border-color);
            font-family: inherit;
            box-sizing: border-box;
            background: var(--bg-card);
            color: var(--text-dark);
        }

        .ctrl-custom:focus {
            outline: none;
            border-color: var(--primary);
        }

        .btn-submit-payment {
            background: var(--primary);
            color: #ffffff;
            border: none;
            padding: 0.65rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 700;
            cursor: pointer;
            font-size: 0.85rem;
            transition: all 0.2s ease;
        }

        .btn-submit-payment:hover {
            background: var(--primary-dark);
        }

        .btn-cancel {
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            font-weight: 600;
            font-size: 0.85rem;
            margin-left: 1rem;
        }

        .btn-cancel:hover {
            color: var(--danger);
        }

        .alert-success {
            background-color: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #065f46;
            padding: 1rem;
            border-radius: 0.75rem;
            margin-bottom: 2rem;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
            animation: fadeIn 0.3s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ─── Dashboard Tables ────────────────────────────── */
        .dashboard-table-container {
            background: var(--bg-surface);
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.02);
            border: 1px solid var(--border-color);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .dashboard-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.85rem;
        }

        .dashboard-table th {
            background: rgba(30, 27, 24, 0.03);
            padding: 1rem 1.25rem;
            font-weight: 700;
            color: var(--text-dark);
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid var(--border-color);
        }

        .dashboard-table td {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-dark);
            vertical-align: middle;
        }

        .dashboard-table tr:last-child td {
            border-bottom: none;
        }

        .dashboard-table tr {
            transition: background 0.2s ease;
        }

        .dashboard-table tr:hover {
            background: rgba(30, 27, 24, 0.015);
        }

        .dashboard-table .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.6rem;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .dashboard-table .btn-outline {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.4rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.78rem;
            font-weight: 700;
            border: 1px solid var(--border-color);
            background: transparent;
            color: var(--text-main);
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .dashboard-table .btn-outline:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--primary-soft);
        }

        /* ─── Modals ────────────────────────────────────────── */
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.5); backdrop-filter: blur(4px);
            z-index: 1000; display: flex; align-items: center; justify-content: center;
            opacity: 0; visibility: hidden; transition: all 0.2s ease;
        }
        .modal-overlay.active { opacity: 1; visibility: visible; }
        .modal-container {
            background: var(--bg-surface); border: 1px solid var(--border-color);
            border-radius: 1rem; box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            width: 90%; max-width: 600px; max-height: 90vh; overflow-y: auto;
            transform: translateY(20px); transition: all 0.3s ease;
            position: relative;
        }
        .modal-overlay.active .modal-container { transform: translateY(0); }
        .modal-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; }
        .modal-title { font-size: 1.1rem; font-weight: 700; color: var(--text-dark); margin: 0; }
        .modal-close { background: none; border: none; color: var(--text-muted); cursor: pointer; transition: color 0.2s; display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 50%; }
        .modal-close:hover { background: var(--border-color); color: var(--text-dark); }
        .modal-body { padding: 1.5rem; }
    </style>
</head>
<body style="margin: 0; padding: 0;">

    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <aside class="student-sidebar" id="studentSidebar">
        <div class="sidebar-brand">
            <a href="{{ url('/') }}" class="logo" style="font-size: 1.1rem;">
                <span>Success</span> Training
            </a>
        </div>
        <nav class="sidebar-nav">
            <a href="#" class="sidebar-link active" onclick="switchTab(event, 'overview')">📊 Vue d'ensemble</a>
            <a href="#" class="sidebar-link" onclick="switchTab(event, 'formations')">📚 Mes Formations</a>
            <a href="#" class="sidebar-link" onclick="switchTab(event, 'catalogue')">💳 Voir le Catalogue</a>
        </nav>
        <div class="sidebar-footer">
            <form action="{{ route('student.logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn" style="width: 100%;">Déconnexion</button>
            </form>
        </div>
    </aside>

    <div class="student-main">
        <!-- Header navigation bar -->
        <header class="dashboard-header">
            <div class="header-container" style="justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <button class="d-lg-none" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; display: none;" id="sidebarToggleBtn" onclick="toggleSidebar()">☰</button>
                    <div style="font-weight: 700; color: var(--text-dark);">Espace Étudiant</div>
                </div>
                
                <div class="user-nav">
                    <!-- Notifications -->
                    <div style="position:relative; margin-right:1rem;">
                        <button id="notifToggle" title="Notifications" aria-label="Notifications" style="background:transparent;border:none;cursor:pointer;color:inherit;position:relative;display:flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:50%;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                                <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                            </svg>
                            @php
                                $unreadCount = Auth::guard('client')->user()->unreadNotifications->count();
                            @endphp
                            @if($unreadCount > 0)
                                <span style="position:absolute;top:-2px;right:-2px;background:var(--danger);color:#fff;font-size:10px;font-weight:700;padding:2px 5px;border-radius:10px;line-height:1;">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </button>
                        <div id="notifDropdown" style="display:none;position:absolute;top:calc(100% + 10px);right:-10px;width:300px;background:var(--bg-card);border:1px solid var(--border-color);border-radius:12px;box-shadow:0 10px 25px rgba(0,0,0,0.1);z-index:100;overflow:hidden;">
                            <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 16px;border-bottom:1px solid var(--border-color);background:var(--bg-surface);">
                                <strong style="font-size:14px;color:var(--text-dark);">Notifications</strong>
                                @if($unreadCount > 0)
                                    <form action="{{ route('student.notifications.readAll') }}" method="POST" style="margin:0;">
                                        @csrf
                                        <button type="submit" style="background:none;border:none;color:var(--primary);cursor:pointer;font-size:12px;text-decoration:none;font-weight:600;">Tout marquer lu</button>
                                    </form>
                                @endif
                            </div>
                            <div style="max-height:300px;overflow-y:auto;background:var(--bg-card);">
                                @forelse(Auth::guard('client')->user()->unreadNotifications as $notification)
                                    <a href="{{ $notification->data['url'] ?? '#' }}" style="display:block;padding:12px 16px;border-bottom:1px solid var(--border-color);text-decoration:none;color:inherit;">
                                        <div style="display:flex;justify-content:space-between;margin-bottom:4px;">
                                            <strong style="font-size:13px;color:var(--text-dark);">
                                                {{ $notification->data['title'] ?? 'Notification' }}
                                            </strong>
                                            <span style="font-size:11px;color:var(--text-muted);">{{ $notification->created_at->diffForHumans() }}</span>
                                        </div>
                                        <div style="font-size:13px;color:var(--text-muted);line-height:1.4;">
                                            {{ $notification->data['message'] ?? '' }}
                                        </div>
                                    </a>
                                @empty
                                    <div style="padding:20px;text-align:center;color:var(--text-muted);font-size:13px;">
                                        Aucune nouvelle notification
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <span class="user-name">🧑‍🎓 {{ $client->name }}</span>
                </div>
            </div>
        </header>

        <!-- Main Workspace container -->
        <main class="dashboard-container">
        
        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <h1 class="dashboard-section-title" style="font-size: 1.75rem;">Mon Tableau de bord Étudiant</h1>
        <p class="text-muted" style="margin-top: -1rem; margin-bottom: 2.5rem;">Suivi financier et administratif de vos programmes de formations professionnelles.</p>

        <!-- Calculations variables for total view summary tiles -->
        @php
            $totalDû = $registrations->sum('amount');
            $totalPayé = $registrations->sum(fn($reg) => $reg->amount_paid);
            $totalReste = max(0, $totalDû - $totalPayé);
            $totalInscriptions = $registrations->count();
        @endphp

        <!-- TAB 1: OVERVIEW -->
        <div id="overview" class="tab-content active">
            <!-- Educational/Progress tiles -->
            <h3 style="font-size: 1.1rem; color: var(--text-dark); margin-bottom: 1rem;">Mon Parcours</h3>
            <div class="summary-grid mobile-scroll-row" style="margin-bottom: 2rem;">
                <div class="summary-card">
                    <div class="summary-label">Formations en cours</div>
                    <div class="summary-value">{{ $stats['en_cours'] }}</div>
                </div>
                <div class="summary-card" style="border-left-color: var(--accent);">
                    <div class="summary-label">Formations à venir</div>
                    <div class="summary-value">{{ $stats['a_venir'] }}</div>
                </div>
                <div class="summary-card warning">
                    <div class="summary-label">Paiements en attente</div>
                    <div class="summary-value">{{ $stats['paiements_attente'] }}</div>
                </div>
                <div class="summary-card danger">
                    <div class="summary-label">Paiements en retard</div>
                    <div class="summary-value">{{ $stats['paiements_retard'] }}</div>
                </div>
            </div>

            <!-- Financial tiles -->
            <h3 style="font-size: 1.1rem; color: var(--text-dark); margin-bottom: 1rem;">Ma Facturation</h3>
            <div class="summary-grid mobile-scroll-row">
                <div class="summary-card">
                    <div class="summary-label">Formations Inscrites</div>
                    <div class="summary-value">{{ $totalInscriptions }}</div>
                </div>
                <div class="summary-card success">
                    <div class="summary-label">Total versé (CFA)</div>
                    <div class="summary-value">{{ number_format($totalPayé, 0, ',', ' ') }} CFA</div>
                </div>
                <div class="summary-card warning">
                    <div class="summary-label">Total restant dû (CFA)</div>
                    <div class="summary-value">{{ number_format($totalReste, 0, ',', ' ') }} CFA</div>
                </div>
                <div class="summary-card danger">
                    <div class="summary-label">Solde global de facturation</div>
                    <div class="summary-value">{{ number_format($totalDû, 0, ',', ' ') }} CFA</div>
                </div>
            </div>

            <!-- Suivi des inscriptions -->
            <h3 style="font-size: 1.1rem; color: var(--text-dark); margin-top: 2rem; margin-bottom: 1rem;">Suivi de mes Formations</h3>
            <div class="dashboard-table-container">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Formation / Pack</th>
                            <th>Tarif</th>
                            <th>Payé</th>
                            <th>Reste à payer</th>
                            <th>Statut</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($registrations as $reg)
                            @php
                                $paid = $reg->amount_paid;
                                $total = $reg->amount;
                                $balance = $reg->balance_due;
                                $percentage = $total > 0 ? min(100, round(($paid / $total) * 100)) : 0;
                                $status = $reg->payment_status; // unpaid, partial, paid

                                $resources = collect();
                                if ($reg->training) {
                                    foreach ($reg->training->resources as $res) {
                                        $arr = $res->toArray();
                                        $arr['training_title'] = $reg->training->title;
                                        $resources->push($arr);
                                    }
                                } elseif ($reg->bundle) {
                                    foreach ($reg->bundle->trainings as $bt) {
                                        foreach ($bt->resources as $res) {
                                            $arr = $res->toArray();
                                            $arr['training_title'] = $bt->title;
                                            $resources->push($arr);
                                        }
                                    }
                                }
                                $isConfirmed = $reg->status === 'confirmed';
                            @endphp
                            <tr>
                                <td>
                                    @if($reg->bundle_id)
                                        <div style="font-weight: 700; color: var(--primary);">🎁 Pack : {{ optional($reg->bundle)->name ?? 'N/A' }}</div>
                                        <div style="font-size: 0.75rem; color: var(--text-main); margin-top: 0.25rem;">
                                            {{ optional($reg->bundle)->trainings->pluck('title')->implode(', ') }}
                                        </div>
                                    @else
                                        <div style="font-weight: 700;">{{ optional($reg->training)->title ?? 'N/A' }}</div>
                                        <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">
                                            📅 Début : {{ optional($reg->training)->start_date ? \Carbon\Carbon::parse($reg->training->start_date)->format('d F Y') : 'N/A' }}
                                            | 📍 Lieu : {{ optional($reg->training)->location ?? 'Bingerville / En ligne' }}
                                        </div>
                                    @endif
                                </td>
                                <td class="fw-bold">{{ number_format($total, 0, ',', ' ') }} CFA</td>
                                <td>
                                    <div style="font-weight: 700;">{{ number_format($paid, 0, ',', ' ') }} CFA</div>
                                    <div style="width: 100px; background: #e2e8f0; height: 6px; border-radius: 99px; margin-top: 0.35rem; overflow: hidden;">
                                        <div style="width: {{ $percentage }}%; height: 100%; background: @if($status==='paid') var(--success) @elseif($status==='partial') var(--warning) @else var(--danger) @endif;"></div>
                                    </div>
                                    <div style="font-size: 0.7rem; color: var(--text-muted); margin-top: 0.2rem;">{{ $percentage }}% versé</div>
                                </td>
                                <td class="fw-bold" style="color: @if($balance > 0) var(--danger) @else var(--success) @endif;">
                                    {{ number_format($balance, 0, ',', ' ') }} CFA
                                </td>
                                <td>
                                    <span class="badge" style="background-color: 
                                        @if($status === 'paid') #d1fae5; color: #065f46;
                                        @elseif($status === 'partial') #fef3c7; color: #92400e;
                                        @else #fee2e2; color: #991b1b;
                                        @endif
                                    ">
                                        @if($status === 'paid') Payé entièrement
                                        @elseif($status === 'partial') Payé partiellement
                                        @else Non payé
                                        @endif
                                    </span>
                                </td>
                                <td style="text-align: right; white-space: nowrap;">
                                    <!-- Actions button group -->
                                    <button type="button" class="btn-outline" 
                                            onclick="openResourcesModal(this)"
                                            data-title="{{ $reg->bundle ? $reg->bundle->name : $reg->training->title }}"
                                            data-confirmed="{{ $isConfirmed ? '1' : '0' }}"
                                            data-resources='@json($resources)'>
                                        📁 Supports
                                    </button>

                                    <button type="button" class="btn-outline" 
                                            onclick="openHistoryModal(this)"
                                            data-title="{{ $reg->bundle ? $reg->bundle->name : $reg->training->title }}"
                                            data-payments='@json($reg->payments)'>
                                        🕒 Historique
                                    </button>

                                    @if($status !== 'paid')
                                        <button type="button" class="btn-outline" 
                                                style="border-color: var(--primary); color: var(--primary);"
                                                onclick="openDeclareModal(this)"
                                                data-id="{{ $reg->id }}"
                                                data-title="{{ $reg->bundle ? $reg->bundle->name : $reg->training->title }}"
                                                data-balance="{{ $balance }}">
                                            💳 Payer
                                        </button>
                                    @endif

                                    @if($reg->payments()->whereIn('status', ['completed', 'pending'])->count() === 0)
                                        <form action="{{ route('student.registrations.destroy', $reg->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler et supprimer cette inscription ?')" style="display: inline-block; margin: 0;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-outline" style="color: var(--danger); border-color: rgba(220, 38, 38, 0.2);">
                                                ❌ Annuler
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 3rem 1rem;">
                                    Vous n'êtes inscrit à aucune formation pour le moment.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div> <!-- End Overview Tab -->

        <!-- TAB 2: FORMATIONS -->
        <div id="formations" class="tab-content">
            <!-- Inscriptions listings -->
            <h2 class="dashboard-section-title">Mes Formations</h2>

            <div class="dashboard-table-container">
                <table class="dashboard-table">
                    <thead>
                        <tr>
                            <th>Formation / Pack</th>
                            <th>Tarif</th>
                            <th>Payé</th>
                            <th>Reste à payer</th>
                            <th>Statut</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($registrations as $reg)
                            @php
                                $paid = $reg->amount_paid;
                                $total = $reg->amount;
                                $balance = $reg->balance_due;
                                $percentage = $total > 0 ? min(100, round(($paid / $total) * 100)) : 0;
                                $status = $reg->payment_status; // unpaid, partial, paid

                                $resources = collect();
                                if ($reg->training) {
                                    foreach ($reg->training->resources as $res) {
                                        $arr = $res->toArray();
                                        $arr['training_title'] = $reg->training->title;
                                        $resources->push($arr);
                                    }
                                } elseif ($reg->bundle) {
                                    foreach ($reg->bundle->trainings as $bt) {
                                        foreach ($bt->resources as $res) {
                                            $arr = $res->toArray();
                                            $arr['training_title'] = $bt->title;
                                            $resources->push($arr);
                                        }
                                    }
                                }
                                $isConfirmed = $reg->status === 'confirmed';
                            @endphp
                            <tr>
                                <td>
                                    @if($reg->bundle_id)
                                        <div style="font-weight: 700; color: var(--primary);">🎁 Pack : {{ optional($reg->bundle)->name ?? 'N/A' }}</div>
                                        <div style="font-size: 0.75rem; color: var(--text-main); margin-top: 0.25rem;">
                                            {{ optional($reg->bundle)->trainings->pluck('title')->implode(', ') }}
                                        </div>
                                    @else
                                        <div style="font-weight: 700;">{{ optional($reg->training)->title ?? 'N/A' }}</div>
                                        <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem;">
                                            📅 Début : {{ optional($reg->training)->start_date ? \Carbon\Carbon::parse($reg->training->start_date)->format('d F Y') : 'N/A' }}
                                            | 📍 Lieu : {{ optional($reg->training)->location ?? 'Bingerville / En ligne' }}
                                        </div>
                                    @endif
                                </td>
                                <td class="fw-bold">{{ number_format($total, 0, ',', ' ') }} CFA</td>
                                <td>
                                    <div style="font-weight: 700;">{{ number_format($paid, 0, ',', ' ') }} CFA</div>
                                    <div style="width: 100px; background: #e2e8f0; height: 6px; border-radius: 99px; margin-top: 0.35rem; overflow: hidden;">
                                        <div style="width: {{ $percentage }}%; height: 100%; background: @if($status==='paid') var(--success) @elseif($status==='partial') var(--warning) @else var(--danger) @endif;"></div>
                                    </div>
                                    <div style="font-size: 0.7rem; color: var(--text-muted); margin-top: 0.2rem;">{{ $percentage }}% versé</div>
                                </td>
                                <td class="fw-bold" style="color: @if($balance > 0) var(--danger) @else var(--success) @endif;">
                                    {{ number_format($balance, 0, ',', ' ') }} CFA
                                </td>
                                <td>
                                    <span class="badge" style="background-color: 
                                        @if($status === 'paid') #d1fae5; color: #065f46;
                                        @elseif($status === 'partial') #fef3c7; color: #92400e;
                                        @else #fee2e2; color: #991b1b;
                                        @endif
                                    ">
                                        @if($status === 'paid') Payé entièrement
                                        @elseif($status === 'partial') Payé partiellement
                                        @else Non payé
                                        @endif
                                    </span>
                                </td>
                                <td style="text-align: right; white-space: nowrap;">
                                    <!-- Actions button group -->
                                    <button type="button" class="btn-outline" 
                                            onclick="openResourcesModal(this)"
                                            data-title="{{ $reg->bundle ? $reg->bundle->name : $reg->training->title }}"
                                            data-confirmed="{{ $isConfirmed ? '1' : '0' }}"
                                            data-resources='@json($resources)'>
                                        📁 Supports
                                    </button>

                                    <button type="button" class="btn-outline" 
                                            onclick="openHistoryModal(this)"
                                            data-title="{{ $reg->bundle ? $reg->bundle->name : $reg->training->title }}"
                                            data-payments='@json($reg->payments)'>
                                        🕒 Historique
                                    </button>

                                    @if($status !== 'paid')
                                        <button type="button" class="btn-outline" 
                                                style="border-color: var(--primary); color: var(--primary);"
                                                onclick="openDeclareModal(this)"
                                                data-id="{{ $reg->id }}"
                                                data-title="{{ $reg->bundle ? $reg->bundle->name : $reg->training->title }}"
                                                data-balance="{{ $balance }}">
                                            💳 Payer
                                        </button>
                                    @endif

                                    @if($reg->payments()->whereIn('status', ['completed', 'pending'])->count() === 0)
                                        <form action="{{ route('student.registrations.destroy', $reg->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler et supprimer cette inscription ?')" style="display: inline-block; margin: 0;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-outline" style="color: var(--danger); border-color: rgba(220, 38, 38, 0.2);">
                                                ❌ Annuler
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 3rem 1rem;">
                                    Vous n'êtes inscrit à aucune formation pour le moment.
                                    <div style="margin-top: 1.5rem;">
                                        <a href="#" onclick="switchTab(event, 'catalogue')" class="btn-submit-payment" style="text-decoration: none; padding: 0.65rem 1.5rem;">Consulter notre catalogue</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div> <!-- End Formations Tab -->

        <!-- TAB 3: CATALOGUE -->
        <div id="catalogue" class="tab-content">
            <h2 class="dashboard-section-title">Catalogue des Formations</h2>
            
            <div class="mobile-scroll-row" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                @foreach($allTrainings as $training)
                    <div class="summary-card" style="padding: 1.25rem; border-left: 4px solid var(--primary); display: flex; flex-direction: column; gap: 1rem;">
                        <div>
                            <h3 style="font-size: 1.1rem; color: var(--text-dark); margin: 0 0 0.5rem 0;">{{ $training->title }}</h3>
                            <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ strip_tags($training->description) }}</p>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: auto; padding-top: 1rem; border-top: 1px solid var(--border-color);">
                            <span style="font-weight: 800; color: var(--primary); font-size: 1.1rem;">{{ number_format($training->price, 0, ',', ' ') }} CFA</span>
                            <a href="{{ url('/formations/'.$training->id) }}" target="_blank" class="btn-submit-payment" style="text-decoration: none; padding: 0.5rem 1rem;">Détails</a>
                        </div>
                    </div>
                @endforeach
                
                @foreach($allBundles as $bundle)
                    <div class="summary-card" style="padding: 1.25rem; border-left: 4px solid var(--accent); display: flex; flex-direction: column; gap: 1rem;">
                        <div>
                            <div style="font-size: 0.7rem; font-weight: 800; color: var(--accent); text-transform: uppercase; margin-bottom: 0.3rem;">Pack Spécial</div>
                            <h3 style="font-size: 1.1rem; color: var(--text-dark); margin: 0 0 0.5rem 0;">{{ $bundle->name }}</h3>
                            <p style="font-size: 0.85rem; color: var(--text-muted); margin: 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ strip_tags($bundle->description) }}</p>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: auto; padding-top: 1rem; border-top: 1px solid var(--border-color);">
                            <span style="font-weight: 800; color: var(--primary); font-size: 1.1rem;">{{ number_format($bundle->price, 0, ',', ' ') }} CFA</span>
                            <a href="{{ url('/packs/'.$bundle->id) }}" target="_blank" class="btn-submit-payment" style="background: var(--accent); text-decoration: none; padding: 0.5rem 1rem;">Détails</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div> <!-- End Catalogue Tab -->

        <!-- ── MODAL: DECLARE PAYMENT ── -->
        <div class="modal-overlay" id="declarePaymentModal" onclick="closeDeclareModal(event)">
            <div class="modal-container" onclick="event.stopPropagation()">
                <div class="modal-header">
                    <h3 class="modal-title" id="declareModalTitle">Déclarer un versement</h3>
                    <button class="modal-close" onclick="closeDeclareModal()">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('student.payments.declare') }}" method="POST">
                        @csrf
                        <input type="hidden" name="registration_id" id="declare_registration_id">

                        <div class="mb-3">
                            <label class="lbl-custom" for="declare_amount">Montant versé (CFA) *</label>
                            <input type="number" id="declare_amount" name="amount" class="ctrl-custom" required placeholder="ex: 15000">
                            <div style="font-size:0.75rem;color:var(--text-muted);margin-top:0.3rem;">Reste à payer maximum : <strong id="declare_max_balance">0</strong> CFA</div>
                        </div>

                        <div class="mb-3">
                            <label class="lbl-custom" for="declare_method">Méthode utilisée *</label>
                            <select id="declare_method" name="method" class="ctrl-custom" required>
                                <option value="Orange Money">Orange Money</option>
                                <option value="Wave">Wave</option>
                                <option value="MTN Mobile Money">MTN MoMo</option>
                                <option value="Moov Money">Moov Flooz</option>
                                <option value="Virement bancaire">Virement bancaire</option>
                                <option value="Espèces">Espèces</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="lbl-custom" for="declare_reference">N° de transaction / Référence</label>
                            <input type="text" id="declare_reference" name="reference" class="ctrl-custom" placeholder="ex: Ref: OM_89712">
                        </div>

                        <div class="d-flex justify-content-end mt-4 pt-3 border-top" style="gap:0.5rem; display:flex; justify-content:flex-end;">
                            <button type="button" class="logout-btn" onclick="closeDeclareModal()">Annuler</button>
                            <button type="submit" class="btn-submit-payment">Soumettre la déclaration</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ── MODAL: PAYMENT HISTORY ── -->
        <div class="modal-overlay" id="paymentHistoryModal" onclick="closeHistoryModal(event)">
            <div class="modal-container" style="max-width: 650px;" onclick="event.stopPropagation()">
                <div class="modal-header">
                    <h3 class="modal-title" id="historyModalTitle">Historique des versements</h3>
                    <button class="modal-close" onclick="closeHistoryModal()">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
                <div class="modal-body" id="historyModalBody">
                    <!-- Table dynamically injected via JS -->
                </div>
            </div>
        </div>

        <!-- ── MODAL: COURSE RESOURCES ── -->
        <div class="modal-overlay" id="courseResourcesModal" onclick="closeResourcesModal(event)">
            <div class="modal-container" style="max-width: 650px;" onclick="event.stopPropagation()">
                <div class="modal-header">
                    <h3 class="modal-title" id="resourcesModalTitle">Supports de cours</h3>
                    <button class="modal-close" onclick="closeResourcesModal()">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>
                <div class="modal-body" id="resourcesModalBody">
                    <!-- Resources list dynamically injected via JS -->
                </div>
            </div>
        </div>

    </main>
    </div> <!-- End student-main -->

    <script>
        // Modal management
        const declareModal = document.getElementById('declarePaymentModal');
        const historyModal = document.getElementById('paymentHistoryModal');
        const resourcesModal = document.getElementById('courseResourcesModal');

        function openDeclareModal(btn) {
            const id = btn.getAttribute('data-id');
            const title = btn.getAttribute('data-title');
            const balance = btn.getAttribute('data-balance');

            document.getElementById('declareModalTitle').innerText = "Déclarer un versement — " + title;
            document.getElementById('declare_registration_id').value = id;
            document.getElementById('declare_amount').value = '';
            document.getElementById('declare_amount').max = balance;
            document.getElementById('declare_max_balance').innerText = Number(balance).toLocaleString('fr-FR');
            document.getElementById('declare_reference').value = '';

            declareModal.classList.add('active');
        }

        function closeDeclareModal(e) {
            if (e && e.target !== declareModal && !e.target.closest('.modal-close') && !e.target.closest('.logout-btn')) return;
            declareModal.classList.remove('active');
        }

        function openHistoryModal(btn) {
            const title = btn.getAttribute('data-title');
            const payments = JSON.parse(btn.getAttribute('data-payments') || '[]');

            document.getElementById('historyModalTitle').innerText = "Historique des versements — " + title;

            let html = '';
            if (payments.length === 0) {
                html = '<p class="text-muted text-center" style="padding: 2rem 0;">Aucune transaction enregistrée pour le moment.</p>';
            } else {
                html = `
                    <table class="log-table" style="width:100%;border-collapse:collapse;font-size:0.85rem;">
                        <thead>
                            <tr style="border-bottom: 1px solid var(--border-color);">
                                <th style="padding: 0.75rem 0.5rem; text-align: left; color:#64748b;">Montant</th>
                                <th style="padding: 0.75rem 0.5rem; text-align: left; color:#64748b;">Méthode</th>
                                <th style="padding: 0.75rem 0.5rem; text-align: left; color:#64748b;">Référence</th>
                                <th style="padding: 0.75rem 0.5rem; text-align: left; color:#64748b;">Statut</th>
                                <th style="padding: 0.75rem 0.5rem; text-align: left; color:#64748b;">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                `;
                payments.forEach(p => {
                    let statusBg = '#fef3c7', statusColor = '#92400e', statusText = 'en cours';
                    if (p.status === 'completed') { statusBg = '#d1fae5'; statusColor = '#065f46'; statusText = 'validé'; }
                    else if (p.status === 'failed') { statusBg = '#fee2e2'; statusColor = '#991b1b'; statusText = 'échoué'; }

                    const dateStr = p.created_at ? new Date(p.created_at).toLocaleDateString('fr-FR') : '-';

                    html += `
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.75rem 0.5rem;"><strong>${Number(p.amount).toLocaleString('fr-FR')} CFA</strong></td>
                            <td style="padding: 0.75rem 0.5rem;">${p.method}</td>
                            <td style="padding: 0.75rem 0.5rem;"><code>${p.reference || '-'}</code></td>
                            <td style="padding: 0.75rem 0.5rem;">
                                <span class="badge" style="background-color: ${statusBg}; color: ${statusColor}; font-size: 0.75rem; padding: 0.15rem 0.4rem;">
                                    ${statusText}
                                </span>
                            </td>
                            <td style="padding: 0.75rem 0.5rem;">${dateStr}</td>
                        </tr>
                    `;
                });
                html += '</tbody></table>';
            }

            document.getElementById('historyModalBody').innerHTML = html;
            historyModal.classList.add('active');
        }

        function closeHistoryModal(e) {
            if (e && e.target !== historyModal && !e.target.closest('.modal-close')) return;
            historyModal.classList.remove('active');
        }

        function openResourcesModal(btn) {
            const title = btn.getAttribute('data-title');
            const resources = JSON.parse(btn.getAttribute('data-resources') || '[]');
            const confirmed = btn.getAttribute('data-confirmed') === '1';

            document.getElementById('resourcesModalTitle').innerText = "Supports de cours — " + title;

            let html = '';
            if (!confirmed) {
                html = `
                    <div style="background-color: var(--bg-surface); border: 1px dashed var(--border-color); padding: 1.5rem; border-radius: 0.5rem; text-align: center;">
                        <p style="color: var(--danger); font-size: 1.5rem; margin-bottom: 0.5rem;">🔒</p>
                        <p class="text-muted small" style="margin: 0; line-height: 1.5;">
                            Vos supports de cours (PDF, liens de visioconférence) seront débloqués automatiquement dès la validation de votre versement et la confirmation de votre inscription par l'administration.
                        </p>
                    </div>
                `;
            } else if (resources.length === 0) {
                html = '<p class="text-muted text-center" style="padding: 2rem 0;">Aucune ressource n\'a encore été ajoutée pour ce cours. Restez connecté(e) !</p>';
            } else {
                // Group resources by training title
                const grouped = {};
                resources.forEach(r => {
                    const groupKey = r.training_title || title;
                    if (!grouped[groupKey]) {
                        grouped[groupKey] = [];
                    }
                    grouped[groupKey].push(r);
                });

                Object.keys(grouped).forEach(tName => {
                    html += `
                        <div style="font-weight: 700; color: var(--primary); font-size: 0.92rem; margin-top: 1rem; margin-bottom: 0.6rem; padding-bottom: 0.4rem; border-bottom: 2px solid var(--border-color); display: flex; align-items: center; gap: 8px;">
                            <span>🎓</span> <span>${tName}</span>
                        </div>
                        <ul style="list-style: none; padding: 0; margin: 0 0 1rem 0;">
                    `;

                    grouped[tName].forEach(r => {
                        let icon = '🔗';
                        let actionText = 'Accéder';
                        let fileUrl = r.url;

                        if (r.type === 'file') {
                            icon = '📄';
                            actionText = 'Télécharger';
                            fileUrl = r.url.startsWith('http') ? r.url : '{{ asset("storage") }}/' + r.url;
                        } else if (r.type === 'video') {
                            icon = '🎥';
                            actionText = 'Regarder';
                            fileUrl = r.url.startsWith('http') ? r.url : '{{ asset("storage") }}/' + r.url;
                        }

                        html += `
                            <li style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 1rem; background: var(--bg-card); border-radius: 0.5rem; margin-bottom: 0.5rem; box-shadow: 0 2px 4px rgba(15, 23, 42, 0.02); border: 1px solid var(--border-color);">
                                <div>
                                    <strong style="display: block; color: var(--text-dark); font-size: 0.9rem;">
                                        ${icon} ${r.title}
                                    </strong>
                                    ${r.description ? `<small style="color: var(--text-muted); display: block; margin-top: 0.25rem;">${r.description}</small>` : ''}
                                </div>
                                <a href="${fileUrl}" target="_blank" class="btn-declare" style="text-decoration: none; font-size: 0.8rem; padding: 0.4rem 0.8rem;">
                                    ${actionText}
                                </a>
                            </li>
                        `;
                    });

                    html += '</ul>';
                });
            }

            document.getElementById('resourcesModalBody').innerHTML = html;
            resourcesModal.classList.add('active');
        }

        function closeResourcesModal(e) {
            if (e && e.target !== resourcesModal && !e.target.closest('.modal-close')) return;
            resourcesModal.classList.remove('active');
        }

        // Sidebar logic
        function toggleSidebar() {
            const sidebar = document.getElementById('studentSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
        }

        // Show hamburger on mobile
        if (window.innerWidth <= 991) {
            document.getElementById('sidebarToggleBtn').style.display = 'block';
        }
        window.addEventListener('resize', () => {
            if (window.innerWidth <= 991) {
                document.getElementById('sidebarToggleBtn').style.display = 'block';
            } else {
                document.getElementById('sidebarToggleBtn').style.display = 'none';
                document.getElementById('studentSidebar').classList.remove('open');
                document.getElementById('sidebarOverlay').classList.remove('active');
            }
        });

        document.getElementById('notifToggle').addEventListener('click', function(e) {
            e.stopPropagation();
            const dropdown = document.getElementById('notifDropdown');
            dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
        });

        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('notifDropdown');
            if (dropdown && dropdown.style.display === 'block' && !e.target.closest('#notifDropdown') && !e.target.closest('#notifToggle')) {
                dropdown.style.display = 'none';
            }
        });

        function switchTab(event, tabId) {
            event.preventDefault();

            // 1. Remove active class from all links
            const links = document.querySelectorAll('.sidebar-link');
            links.forEach(link => {
                // Ignore the catalogue link which doesn't have an onclick with switchTab
                if(link.getAttribute('href') === '#') {
                    link.classList.remove('active');
                }
            });

            // 2. Add active class to clicked link
            event.currentTarget.classList.add('active');

            // 3. Hide all tabs
            const tabs = document.querySelectorAll('.tab-content');
            tabs.forEach(tab => tab.classList.remove('active'));

            // 4. Show the selected tab
            document.getElementById(tabId).classList.add('active');

            // 5. If on mobile, close the sidebar after clicking
            if (window.innerWidth <= 1024) {
                toggleSidebar();
            }
        }
    </script>
</body>
</html>
