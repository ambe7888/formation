<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pack {{ $bundle->name }} - Success Business Training</title>
    <meta name="description" content="{{ Str::limit($bundle->description, 160) }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/style.css') }}?v=1.0.2">
    <style>
        :root {
            --primary: #7e22ce;
            --primary-dark: #6b21a8;
            --dark: #0f172a;
            --light-bg: #f8fafc;
            --border-color: #e2e8f0;
            --text-main: #334155;
            --text-dark: #0f172a;
        }

        body {
            font-family: 'Manrope', sans-serif;
            background-color: var(--light-bg);
            color: var(--text-main);
            margin: 0;
            padding: 0;
        }

        /* Glassmorphic Navigation Header */
        .details-header {
            position: sticky;
            top: 0;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            z-index: 100;
            padding: 1rem 0;
        }

        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            color: var(--text-dark);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s ease;
            gap: 0.5rem;
        }

        .back-btn:hover {
            color: var(--primary);
            transform: translateX(-3px);
        }

        /* Hero details banner */
        .training-hero {
            background: linear-gradient(135deg, #3b0764 0%, #0f172a 100%);
            color: #ffffff;
            padding: 4.5rem 0;
            position: relative;
            overflow: hidden;
        }

        .training-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -30%;
            width: 100%;
            height: 200%;
            background: radial-gradient(circle, rgba(126, 34, 206, 0.15) 0%, rgba(0, 0, 0, 0) 60%);
            pointer-events: none;
        }

        .hero-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1.5rem;
            position: relative;
            z-index: 2;
        }

        .hero-tag {
            display: inline-block;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(4px);
            color: #e9d5ff;
            padding: 0.4rem 1rem;
            border-radius: 999px;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.75rem;
            font-weight: 900;
            line-height: 1.2;
            margin: 0 0 1.5rem 0;
            letter-spacing: -0.01em;
        }

        /* Grid Layout */
        .content-container {
            max-width: 1200px;
            margin: 3rem auto;
            padding: 0 1.5rem;
            display: grid;
            grid-template-columns: 1fr;
            gap: 2.5rem;
        }

        @media(min-width: 992px) {
            .content-container {
                grid-template-columns: 7fr 5fr;
            }
        }

        /* Card blocks */
        .details-card {
            background: #ffffff;
            border-radius: 1.5rem;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);
            border: 1px solid var(--border-color);
            padding: 2.5rem;
            margin-bottom: 2rem;
            overflow: hidden;
        }

        .section-title {
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--text-dark);
            margin: 0 0 1.25rem 0;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--light-bg);
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 60px;
            height: 2px;
            background: var(--primary);
        }

        .description-text {
            line-height: 1.7;
            font-size: 1.05rem;
            color: var(--text-main);
            margin-bottom: 2.5rem;
            white-space: pre-line;
        }

        /* Training item style inside bundle */
        .bundle-training-item {
            background: var(--light-bg);
            border-radius: 1rem;
            border: 1px solid var(--border-color);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        @media (min-width: 576px) {
            .bundle-training-item {
                flex-direction: row;
            }
        }

        .bundle-training-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.04);
        }

        .training-img-box {
            width: 100%;
            height: 150px;
            border-radius: 0.75rem;
            background-size: cover;
            background-position: center;
            border: 1px solid var(--border-color);
            flex-shrink: 0;
        }

        @media (min-width: 576px) {
            .training-img-box {
                width: 150px;
                height: 150px;
            }
        }

        .training-content-box {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            flex-grow: 1;
        }

        .training-title-link {
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--text-dark);
            text-decoration: none;
            margin-bottom: 0.5rem;
            transition: color 0.2s ease;
        }

        .training-title-link:hover {
            color: var(--primary);
        }

        /* Skills badges */
        .skills-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .skill-pill {
            display: inline-flex;
            align-items: center;
            color: #ffffff;
            font-weight: 700;
            font-size: 0.75rem;
            padding: 0.3rem 0.75rem;
            border-radius: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        /* Sidebar & Form card */
        .sidebar-box {
            position: sticky;
            top: 6rem;
        }

        .pricing-card {
            background: #ffffff;
            border-radius: 1.5rem;
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.06);
            border: 1px solid var(--border-color);
            padding: 2.5rem;
        }

        .price-badge-row {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }

        .price-label {
            font-size: 0.85rem;
            font-weight: 700;
            color: #7e22ce;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .current-price {
            font-size: 2.5rem;
            font-weight: 900;
            color: var(--text-dark);
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .old-price {
            font-size: 1.25rem;
            text-decoration: line-through;
            color: #94a3b8;
        }

        .savings-badge {
            display: inline-block;
            background-color: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #065f46;
            font-weight: 700;
            font-size: 0.85rem;
            padding: 0.35rem 0.75rem;
            border-radius: 999px;
            margin-top: 0.5rem;
            text-align: center;
        }

        .meta-list {
            list-style: none;
            padding: 0;
            margin: 0 0 2.5rem 0;
        }

        .meta-item {
            display: flex;
            align-items: center;
            padding: 0.85rem 0;
            border-bottom: 1px solid #f1f5f9;
            font-weight: 600;
            color: var(--text-dark);
        }

        .meta-item:last-child {
            border-bottom: none;
        }

        .meta-icon {
            font-size: 1.25rem;
            margin-right: 1rem;
            width: 24px;
            text-align: center;
        }

        .meta-label {
            color: #64748b;
            font-size: 0.9rem;
            margin-right: auto;
        }

        /* Form styling */
        .form-title {
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 1.25rem;
        }

        .form-group-custom {
            margin-bottom: 1.25rem;
        }

        .form-group-custom label {
            display: block;
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .form-control-custom {
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: 0.75rem;
            border: 1px solid var(--border-color);
            background: var(--light-bg);
            font-family: inherit;
            font-size: 0.95rem;
            color: var(--text-dark);
            box-sizing: border-box;
            transition: all 0.2s ease;
        }

        .form-control-custom:focus {
            outline: none;
            border-color: var(--primary);
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(126, 34, 206, 0.1);
        }

        .btn-submit-premium {
            width: 100%;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: #ffffff;
            border: none;
            padding: 1rem;
            border-radius: 0.75rem;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 10px 20px rgba(126, 34, 206, 0.25);
            transition: all 0.2s ease;
        }

        .btn-submit-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(126, 34, 206, 0.35);
        }

        /* Alerts */
        .alert-premium {
            padding: 1.25rem;
            border-radius: 1rem;
            margin-bottom: 1.5rem;
            font-weight: 600;
            font-size: 0.95rem;
            line-height: 1.4;
        }

        .alert-premium-success {
            background-color: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #065f46;
        }
    </style>
</head>
<body>

    <!-- Header / Navigation backlink -->
    <header class="details-header">
        <div class="header-container">
            <a href="{{ url('/') }}" class="back-btn">
                <span>←</span> Retour à l'accueil
            </a>
            <div style="display: flex; align-items: center; gap: 1.5rem;">
                @if(auth('client')->check())
                    <span style="font-weight: 700; font-size: 0.95rem; color: var(--text-dark);">🧑‍🎓 {{ auth('client')->user()->name }}</span>
                    <a href="{{ route('student.dashboard') }}" style="background: var(--primary); color: #fff; padding: 0.4rem 1rem; border-radius: 0.5rem; font-weight: 700; text-decoration: none; font-size: 0.85rem;">Mon Espace</a>
                    <form action="{{ route('student.logout') }}" method="POST" style="margin: 0; display: inline;">
                        @csrf
                        <button type="submit" style="background: none; border: none; color: var(--text-main); font: inherit; cursor: pointer; font-weight: 600; padding: 0; font-size: 0.85rem;">Déconnexion</button>
                    </form>
                @else
                    <a href="{{ route('student.login') }}" style="color: var(--text-dark); text-decoration: none; font-weight: 700; font-size: 0.85rem;">Connexion</a>
                    <a href="{{ route('student.signup') }}" style="background: var(--dark); color: #fff; padding: 0.4rem 1rem; border-radius: 0.5rem; font-weight: 700; text-decoration: none; font-size: 0.85rem;">Créer un compte</a>
                @endif
            </div>
        </div>
    </header>

    <!-- Hero Banner -->
    <section class="training-hero" @if($bundle->image_url) style="background: linear-gradient(135deg, rgba(59, 7, 100, 0.9) 0%, rgba(15, 23, 42, 0.95) 100%), url('{{ asset($bundle->image_url) }}') center/cover;" @endif>
        <div class="hero-container">
            <span class="hero-tag">Offre Spéciale Pack</span>
            <h1 class="hero-title">Pack : {{ $bundle->name }}</h1>
            <p style="font-size: 1.15rem; color: #e9d5ff; max-width: 800px; margin: 0; font-weight: 500;">
                Regroupe {{ $bundle->trainings->count() }} formations d'exception à un prix réduit exclusif.
            </p>
        </div>
    </section>

    <!-- Content Grid -->
    <main class="content-container">
        <!-- Details Column -->
        <article>
            <div class="details-card">
                <h2 class="section-title">Présentation du Pack</h2>
                <div class="description-text">
                    {{ $bundle->description }}
                </div>

                <h2 class="section-title" style="margin-top: 3rem; margin-bottom: 2rem;">Formations incluses dans ce pack ({{ $bundle->trainings->count() }})</h2>
                <div>
                    @foreach($formattedTrainings as $trainingItem)
                        <div class="bundle-training-item">
                            <div class="training-img-box" style="background-image: url('{{ $trainingItem['illustration'] }}');"></div>
                            <div class="training-content-box">
                                <div>
                                    <a href="{{ route('training.show', $trainingItem['id']) }}" class="training-title-link" target="_blank">
                                        {{ $trainingItem['name'] }} ↗
                                    </a>
                                    <p style="font-size: 0.9rem; color: #64748b; margin: 0.25rem 0 0.5rem 0;">
                                        📅 Début : <strong>{{ $trainingItem['date'] }}</strong> | 📍 Lieu : <strong>{{ $trainingItem['location'] }}</strong>
                                    </p>
                                    <p style="font-size: 0.95rem; margin: 0.5rem 0; line-height: 1.5; color: var(--text-main);">
                                        {{ Str::limit($trainingItem['description'], 180) }}
                                    </p>
                                </div>
                                <div class="skills-grid">
                                    @foreach($trainingItem['skills'] as $skill)
                                        <span class="skill-pill" style="background-color: {{ $skill->badge_color }};">
                                            {{ $skill->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </article>

        <!-- Form / Sidebar Column -->
        <aside class="sidebar-box">
            <div class="pricing-card">
                <!-- Price badge -->
                <div class="price-badge-row">
                    <span class="price-label">Tarif unique du pack</span>
                    <strong class="current-price">{{ number_format($bundle->price, 0, ',', ' ') }} CFA</strong>
                    <span class="old-price">Cumulé : {{ number_format($totalPromoPrice, 0, ',', ' ') }} CFA</span>
                    @if($savings > 0)
                        <div class="savings-badge">
                            ✨ Économisez {{ number_format($savings, 0, ',', ' ') }} CFA !
                        </div>
                    @endif
                </div>

                <!-- Meta Session parameters -->
                <ul class="meta-list">
                    <li class="meta-item">
                        <span class="meta-icon">📚</span>
                        <span class="meta-label">Nombre de formations</span>
                        <span>{{ $bundle->trainings->count() }} formations</span>
                    </li>
                    <li class="meta-item">
                        <span class="meta-icon">🔒</span>
                        <span class="meta-label">Accès ressources</span>
                        <span>Inclus (les {{ $bundle->trainings->count() }} formations)</span>
                    </li>
                    <li class="meta-item">
                        <span class="meta-icon">📜</span>
                        <span class="meta-label">Certificat de formation</span>
                        <span>Inclus pour chaque module</span>
                    </li>
                </ul>

                @if(request('status') === 'success')
                    <div class="thank-you-card" style="text-align: center; padding: 2rem; background: #ffffff; border-radius: 1.5rem; border: 2px solid #10b981; box-shadow: 0 20px 40px rgba(16, 185, 129, 0.08); width: 100%; box-sizing: border-box;">
                        <span style="font-size: 3.5rem; display: block; margin-bottom: 1rem;">🎉</span>
                        <h3 style="color: #10b981; font-size: 1.35rem; font-weight: 800; margin: 0 0 1rem 0;">Inscription au Pack Confirmée !</h3>
                        <p style="font-size: 0.95rem; line-height: 1.5; color: var(--text-main); margin-bottom: 1.5rem;">
                            Votre demande pour le pack <strong>{{ request('course') }}</strong> a bien été enregistrée.
                        </p>
                        <div style="background: #f0fdf4; border-radius: 1rem; padding: 1rem; margin-bottom: 1.5rem; border: 1px solid #bbf7d0; text-align: left;">
                            <p style="margin: 0 0 0.5rem 0; font-size: 0.85rem; color: #166534;"><strong>Prochaines étapes :</strong></p>
                            <ul style="margin: 0; padding-left: 1.2rem; font-size: 0.8rem; color: #166534; line-height: 1.5;">
                                <li>Notre équipe pédagogique va valider votre dossier sous 24h.</li>
                                <li>Rendez-vous dans votre <strong>Espace Étudiant</strong> pour déclarer vos versements de paiement.</li>
                                <li>Dès réception, vos accès aux supports de cours 🔒 pour toutes les formations du pack seront débloqués.</li>
                            </ul>
                        </div>
                        <a href="{{ route('student.dashboard') }}" class="btn-submit-premium" style="text-decoration: none; display: block; text-align: center; background: linear-gradient(135deg, #10b981 0%, #059669 100%); font-size: 0.95rem; font-weight: 700; color: white;">Accéder à mon Espace Étudiant 🧑‍🎓</a>
                    </div>
                @else
                    @if(request('status') === 'duplicate')
                        <div class="alert-premium" style="background-color: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; font-weight: 700; border-radius: 0.75rem; padding: 1rem; margin-bottom: 1rem;">
                            ⚠️ Vous êtes déjà inscrit(e) à ce pack !
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert-premium" style="background-color: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; font-weight: 700; border-radius: 0.75rem; padding: 1rem; margin-bottom: 1rem;">
                            ⚠️ {{ session('error') }}
                        </div>
                    @endif

                    @auth('client')
                        <!-- Form Section -->
                        <h3 class="form-title">S'inscrire au pack complet</h3>
                        <form action="{{ route('register') }}" method="POST" id="main_registration_form">
                            @csrf
                            <!-- Hidden field to redirect back to details page on success -->
                            <input type="hidden" name="redirect_to" value="{{ route('bundle.show', $bundle->id) }}">
                            <!-- Preselected course -->
                            <input type="hidden" name="course" value="{{ $bundle->name }}" id="main_course_input">
                            <!-- Hidden bundle id input -->
                            <input type="hidden" name="bundle_id" value="{{ $bundle->id }}" id="main_bundle_id">

                            <div class="form-group-custom">
                                <label>Pack sélectionné</label>
                                <input type="text" class="form-control-custom" value="Pack {{ $bundle->name }}" id="selected_course_label" disabled style="font-weight: 700; background-color: #f1f5f9;">
                            </div>

                            <div class="alert-premium alert-premium-success" style="margin-top: 1rem; margin-bottom: 1.5rem; background-color: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 1rem; border-radius: 1rem;">
                                Bonjour <strong>{{ auth('client')->user()->name }}</strong> ! Vous êtes connecté(e). Vos coordonnées sont pré-remplies automatiquement.
                            </div>
                            <input type="hidden" name="name" value="{{ auth('client')->user()->name }}">
                            <input type="hidden" name="phone" value="{{ auth('client')->user()->phone }}">
                            <input type="hidden" name="email" value="{{ auth('client')->user()->email }}">

                            <div class="form-group-custom">
                                <label for="month">Session souhaitée</label>
                                <select id="month" name="month" class="form-control-custom">
                                    <option value="Juin 2026">Juin 2026</option>
                                    <option value="Juillet 2026">Juillet 2026</option>
                                    <option value="Août 2026">Août 2026</option>
                                    <option value="Septembre 2026">Septembre 2026</option>
                                </select>
                            </div>

                            <div class="form-group-custom">
                                <label for="message">Message ou questions (optionnel)</label>
                                <textarea id="message" name="message" class="form-control-custom" rows="3" placeholder="Avez-vous des questions particulières ?"></textarea>
                            </div>

                            <button type="submit" class="btn-submit-premium">
                                S'inscrire en 1 clic 🚀
                            </button>
                        </form>
                    @else
                        <!-- Premium Login/Signup CTA Box -->
                        <div style="background: var(--light-bg); border: 1px solid var(--border-color); padding: 2rem 1.5rem; border-radius: 1.25rem; text-align: center; margin-top: 0.5rem; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);">
                            <div style="width: 52px; height: 52px; background: rgba(126, 34, 206, 0.1); color: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            </div>
                            <h4 style="margin: 0 0 0.5rem; font-size: 1.15rem; font-weight: 800; color: var(--text-dark);">Inscription réservée aux membres</h4>
                            <p style="margin: 0 0 1.5rem; font-size: 0.875rem; color: var(--text-main); line-height: 1.6;">
                                Pour vous inscrire à ce pack de formation et accéder à votre espace de cours, veuillez vous connecter ou créer un compte étudiant.
                            </p>
                            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                                <a href="{{ route('student.login') }}" class="btn-submit-premium" style="text-decoration: none; display: flex; align-items: center; justify-content: center; height: 46px; padding: 0; box-sizing: border-box; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); font-size: 0.95rem;">
                                    Se connecter
                                </a>
                                <a href="{{ route('student.signup') }}" style="text-decoration: none; display: flex; align-items: center; justify-content: center; height: 46px; border: 1.5px solid var(--primary); color: var(--primary); border-radius: 0.75rem; font-weight: 700; transition: all 0.2s ease; font-size: 0.95rem; background: transparent; box-sizing: border-box;" onmouseover="this.style.background='rgba(126, 34, 206, 0.05)'" onmouseout="this.style.background='transparent'">
                                    Créer un compte étudiant
                                </a>
                            </div>
                        </div>
                    @endauth
                @endif
            </div>
        </aside>
    </main>

    <footer style="background-color: var(--dark); color: #94a3b8; text-align: center; padding: 3rem 0; margin-top: 5rem; border-top: 1px solid #1e293b;">
        <div style="max-width: 1200px; margin: 0 auto; padding: 0 1.5rem;">
            <p style="margin: 0; font-weight: 700; color: #fff;">Success Business Training</p>
            <p style="margin: 0.5rem 0 0 0; font-size: 0.9rem;">Formations professionnelles haut de gamme en IA, Business et Marketing.</p>
        </div>
    </footer>

</body>
</html>
