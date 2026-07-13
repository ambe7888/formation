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

        </div> <!-- End Overview Tab -->

        <!-- TAB 2: FORMATIONS -->
        <div id="formations" class="tab-content">
            <!-- Inscriptions listings -->
        <h2 class="dashboard-section-title" id="formations">Mes Formations</h2>

        @forelse($registrations as $reg)
            @php
                $paid = $reg->amount_paid;
                $total = $reg->amount;
                $balance = $reg->balance_due;
                $percentage = $total > 0 ? min(100, round(($paid / $total) * 100)) : 0;
                $status = $reg->payment_status; // unpaid, partial, paid
            @endphp

            <div class="registration-item" style="position: relative;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; margin-bottom: 1.5rem; gap: 1rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 1.25rem;">
                    <!-- Training/Bundle General Parameters -->
                    <div class="training-info">
                        @if($reg->bundle_id)
                            <h3 style="color: var(--primary); margin: 0 0 0.5rem 0; font-size: 1.25rem; font-weight: 800;">🎁 Pack : {{ optional($reg->bundle)->name ?? 'N/A' }}</h3>
                            <div class="training-meta">
                                <span style="background-color: var(--primary-soft); color: var(--primary-dark); padding: 0.25rem 0.6rem; border-radius: 999px; font-size: 0.8rem; font-weight: 700;">📚 Inclus : {{ optional($reg->bundle)->trainings->pluck('title')->implode(', ') }}</span>
                            </div>
                        @else
                            <h3 style="margin: 0 0 0.5rem 0; font-size: 1.25rem; font-weight: 800;">{{ optional($reg->training)->title ?? 'N/A' }}</h3>
                            <div class="training-meta">
                                <span>📅 Début : {{ optional($reg->training)->start_date ? \Carbon\Carbon::parse($reg->training->start_date)->format('d F Y') : 'N/A' }}</span>
                                <span>📍 Lieu : {{ optional($reg->training)->location ?? 'Bingerville / En ligne' }}</span>
                            </div>
                        @endif
                    </div>
                    @if($reg->payments()->whereIn('status', ['completed', 'pending'])->count() === 0)
                        <form action="{{ route('student.registrations.destroy', $reg->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler et supprimer cette inscription ?')" style="margin: 0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="logout-btn" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; color: var(--danger); border-color: #fca5a5; font-weight: 700; background: #fff; cursor: pointer; border-radius: 0.5rem; transition: all 0.2s ease;">
                                ❌ Annuler l'inscription
                            </button>
                        </form>
                    @endif
                </div>

                <div class="registration-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); border-top: none; padding-top: 0; margin-top: 0;">
                    <!-- Costs parameters -->
                    <div class="financial-stat">
                        <span class="stat-lbl">Tarif total</span>
                        <span class="stat-val">{{ number_format($total, 0, ',', ' ') }} CFA</span>
                    </div>

                    <!-- Payment status badge & values -->
                    <div class="financial-stat">
                        <span class="stat-lbl">Statut du paiement</span>
                        <div>
                            <span class="badge" style="font-size: 0.8rem; padding: 0.35rem 0.65rem; background-color: 
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
                        </div>
                    </div>
                </div>

                <!-- Financial Progress indicator -->
                <div class="progress-container">
                    <div class="progress-bar-wrapper">
                        <div class="progress-bar-fill" style="width: {{ $percentage }}%; background-color: 
                            @if($status === 'paid') var(--success);
                            @elseif($status === 'partial') var(--warning);
                            @else var(--danger);
                            @endif
                        "></div>
                    </div>
                    <div class="progress-meta">
                        <span>{{ $percentage }}% versé</span>
                        <span>Payé : {{ number_format($paid, 0, ',', ' ') }} CFA | Reste : <strong>{{ number_format($balance, 0, ',', ' ') }} CFA</strong></span>
                    </div>
                </div>

                <!-- Log of Recorded payments -->
                @if($reg->payments->isNotEmpty())
                    <div class="payments-log">
                        <div class="log-title">Historique des transactions de versements</div>
                        <table class="log-table">
                            <thead>
                                <tr>
                                    <th>Montant</th>
                                    <th>Méthode</th>
                                    <th>Référence</th>
                                    <th>Statut</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reg->payments as $payment)
                                    <tr>
                                        <td><strong>{{ number_format($payment->amount, 0, ',', ' ') }} CFA</strong></td>
                                        <td>{{ $payment->method }}</td>
                                        <td><code>{{ $payment->reference ?? '-' }}</code></td>
                                        <td>
                                            <span class="badge" style="font-size: 0.75rem; padding: 0.15rem 0.4rem; background-color: 
                                                @if($payment->status === 'completed') #d1fae5; color: #065f46;
                                                @elseif($payment->status === 'failed') #fee2e2; color: #991b1b;
                                                @else #fef3c7; color: #92400e;
                                                @endif
                                            ">
                                                @if($payment->status === 'completed') validé
                                                @elseif($payment->status === 'failed') échoué
                                                @else en cours
                                                @endif
                                            </span>
                                        </td>
                                        <td>{{ $payment->created_at ? $payment->created_at->format('d/m/Y H:i') : '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <!-- Learning Resources Section (For Confirmed Students) -->
                @if($reg->status === 'confirmed')
                    @php
                        $resources = collect();
                        if ($reg->training) {
                            $resources = $reg->training->resources;
                        } elseif ($reg->bundle) {
                            foreach ($reg->bundle->trainings as $bt) {
                                $resources = $resources->concat($bt->resources);
                            }
                        }
                    @endphp
                    <div class="payments-log" style="background-color: var(--primary-soft); border: 1px solid var(--border-color); margin-top: 1.5rem;">
                        <div class="log-title" style="color: var(--primary); display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                            <span>📁</span> Supports & Ressources de cours
                        </div>
                        @if($resources->isNotEmpty())
                            <ul style="list-style: none; padding: 0; margin: 0;">
                                @foreach($resources as $resource)
                                    <li style="display: flex; justify-content: space-between; align-items: center; padding: 0.75rem 1rem; background: var(--bg-card); border-radius: 0.5rem; margin-bottom: 0.75rem; box-shadow: 0 2px 4px rgba(15, 23, 42, 0.02); border: 1px solid var(--border-color);">
                                        <div>
                                            <strong style="display: block; color: var(--text-dark); font-size: 0.9rem;">
                                                @if($resource->type === 'file') 📄 @else 🔗 @endif {{ $resource->title }}
                                            </strong>
                                            @if($resource->description)
                                                <small style="color: var(--text-muted); display: block; margin-top: 0.25rem;">{{ $resource->description }}</small>
                                            @endif
                                        </div>
                                        <a href="{{ $resource->url }}" target="_blank" class="btn-declare" style="text-decoration: none; font-size: 0.8rem; padding: 0.4rem 0.8rem;">
                                            @if($resource->type === 'file') Télécharger @else Accéder @endif
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted small" style="margin: 0;">Aucune ressource n'a encore été ajoutée pour ce cours. Restez connecté(e) !</p>
                        @endif
                    </div>
                @else
                    <div class="payments-log" style="background-color: var(--bg-surface); border: 1px dashed var(--border-color); margin-top: 1.5rem;">
                        <p class="text-muted small" style="margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                             <span>🔒</span> Vos supports de cours (PDF, liens de visioconférence) seront débloqués automatiquement dès la validation de votre versement et la confirmation de votre inscription par l'administration.
                        </p>
                    </div>
                @endif

                <!-- Payment Declaration form -->
                @if($status !== 'paid')
                    <div class="declaration-box">
                        <button type="button" class="btn-declare" onclick="toggleForm({{ $reg->id }})">➕ Déclarer un versement partiel</button>
                        
                        <div class="declaration-form-wrapper" id="form-{{ $reg->id }}">
                            <h4 class="form-title" style="margin-top: 0;">Déclarer un paiement par virement ou mobile money</h4>
                            <p class="text-muted small" style="margin-top: -0.75rem; margin-bottom: 1.25rem;">Remplissez les détails après avoir effectué le versement. L'administrateur validera ensuite la transaction.</p>
                            
                            <form action="{{ route('student.payments.declare') }}" method="POST">
                                @csrf
                                <input type="hidden" name="registration_id" value="{{ $reg->id }}">

                                <div class="form-row-grid">
                                    <div>
                                        <label class="lbl-custom" for="amount-{{ $reg->id }}">Montant versé (CFA) *</label>
                                        <input type="number" id="amount-{{ $reg->id }}" name="amount" class="ctrl-custom" required max="{{ $balance }}" placeholder="ex: 15000">
                                    </div>
                                    <div>
                                        <label class="lbl-custom" for="method-{{ $reg->id }}">Méthode utilisée *</label>
                                        <select id="method-{{ $reg->id }}" name="method" class="ctrl-custom" required>
                                            <option value="Orange Money">Orange Money</option>
                                            <option value="Wave">Wave</option>
                                            <option value="MTN Mobile Money">MTN MoMo</option>
                                            <option value="Moov Money">Moov Flooz</option>
                                            <option value="Virement bancaire">Virement bancaire</option>
                                            <option value="Espèces">Espèces</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="lbl-custom" for="reference-{{ $reg->id }}">N° de transaction / Référence</label>
                                        <input type="text" id="reference-{{ $reg->id }}" name="reference" class="ctrl-custom" placeholder="ex: Ref: OM_89712">
                                    </div>
                                </div>
                                <button type="submit" class="btn-submit-payment">Soumettre la déclaration</button>
                                <button type="button" class="btn-cancel" onclick="toggleForm({{ $reg->id }})">Annuler</button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <div class="card card-borderless p-5 text-center text-muted">
                <p style="font-size: 1.1rem; margin-bottom: 1.5rem;">Vous n'êtes inscrit à aucune formation pour le moment.</p>
                <div>
                    <a href="{{ url('/') }}" class="btn btn-primary" style="text-decoration: none; padding: 0.75rem 1.5rem; font-weight: 700;">Consulter notre catalogue de formations</a>
                </div>
            </div>
        @endforelse
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

    </main>
    </div> <!-- End student-main -->

    <script>
        function toggleForm(id) {
            const form = document.getElementById('form-' + id);
            if (form) {
                form.classList.toggle('active');
            }
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
