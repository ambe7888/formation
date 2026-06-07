<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programme & Calendrier - Success Business Training</title>
    <meta name="description" content="Découvrez le calendrier chronologique de nos sessions de formation et inscrivez-vous à la date qui vous convient.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/style.css') }}?v=1.0.2">
    <style>
        .page-header-hero {
            padding: 80px 0 40px;
            text-align: center;
            background: linear-gradient(135deg, rgba(249, 115, 22, 0.05), transparent 45%);
            border-bottom: 1px solid var(--line);
        }
        .page-title {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            font-weight: 900;
            color: #283746;
            margin: 0 0 10px 0;
            letter-spacing: -0.02em;
        }
        .page-subtitle {
            font-size: 1.1rem;
            color: var(--muted);
            max-width: 600px;
            margin: 0 auto;
        }

        /* Timeline styling */
        .timeline {
            position: relative;
            max-width: 800px;
            margin: 0 auto;
            padding: 40px 0;
        }
        .timeline::after {
            content: '';
            position: absolute;
            width: 4px;
            background-color: var(--brand);
            top: 0;
            bottom: 0;
            left: 50%;
            margin-left: -2px;
            border-radius: 99px;
        }
        @media screen and (max-width: 768px) {
            .timeline::after {
                left: 31px;
            }
        }

        .timeline-container {
            padding: 10px 40px;
            position: relative;
            background-color: inherit;
            width: 50%;
            box-sizing: border-box;
        }
        @media screen and (max-width: 768px) {
            .timeline-container {
                width: 100%;
                padding-left: 70px;
                padding-right: 25px;
            }
        }

        .timeline-container::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            right: -10px;
            background-color: #ffffff;
            border: 4px solid var(--accent);
            top: 25px;
            border-radius: 50%;
            z-index: 1;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .left {
            left: 0;
        }
        .right {
            left: 50%;
        }

        .left::after {
            left: auto;
            right: -10px;
        }
        .right::after {
            left: -10px;
        }

        @media screen and (max-width: 768px) {
            .left::after, .right::after {
                left: 21px;
                right: auto;
            }
            .right {
                left: 0%;
            }
        }

        .timeline-content {
            padding: 24px;
            background-color: #ffffff;
            position: relative;
            border-radius: 20px;
            border: 1px solid var(--line);
            box-shadow: var(--shadow);
            transition: var(--transition);
        }
        .timeline-content:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-strong);
        }
    </style>
</head>
<body>
    <header class="site-header">
        <div class="container header-bar">
            <a class="brand" href="{{ url('/') }}">
                <span class="brand-mark">SB</span>
                <span>
                    <strong>Success Business Training</strong>
                    <small>IA, Business et Marketing </small>
                </span>
            </a>
            <nav class="nav">
                <a href="{{ route('trainings.page') }}">Nos formations</a>
                <a href="{{ route('program.page') }}" style="color: var(--brand); font-weight: 800;">Programme</a>
                <a href="{{ route('skills.page') }}">Compétences</a>
                @if(auth('client')->check())
                    <a href="{{ route('student.dashboard') }}" class="btn btn-primary" style="min-height: 38px; padding: 0 16px; font-size: 0.85rem;">Mon Espace 🧑‍🎓</a>
                    <form action="{{ route('student.logout') }}" method="POST" style="margin: 0; display: inline-flex;">
                        @csrf
                        <button type="submit" style="background: none; border: none; color: var(--text); font-family: inherit; font-size: 0.85rem; font-weight: 600; cursor: pointer; margin-left: 10px;">Déconnexion</button>
                    </form>
                @else
                    <a href="{{ route('student.login') }}" style="font-size: 0.85rem; font-weight: 700; text-decoration: none;">Connexion</a>
                    <a href="{{ route('student.signup') }}" class="btn btn-primary" style="min-height: 38px; padding: 0 16px; font-size: 0.85rem;">S'inscrire</a>
                @endif
            </nav>
        </div>
    </header>

    <div class="page-header-hero">
        <div class="container">
            <span class="eyebrow" style="color: var(--brand);">Calendrier des sessions</span>
            <h1 class="page-title">Nos Programmes & Planning</h1>
            <p class="page-subtitle">Planifiez votre apprentissage en consultant les dates clés de nos prochaines sessions de formation.</p>
        </div>
    </div>

    <main style="padding: 60px 0;">
        <div class="container">
            <div class="timeline">
                @php $isLeft = true; @endphp
                @forelse($trainings as $training)
                    <div class="timeline-container {{ $isLeft ? 'left' : 'right' }}">
                        <div class="timeline-content">
                            <span style="font-size: 0.8rem; font-weight: 800; text-transform: uppercase; color: var(--accent); display: block; margin-bottom: 6px;">
                                📅 {{ $training['date'] }}
                            </span>
                            <h3 style="margin: 0 0 10px 0; font-family: 'Roboto', sans-serif; font-size: 1.25rem; font-weight: 800; color: #283746;">
                                {{ $training['name'] }}
                            </h3>
                            <p style="margin: 0 0 15px 0; font-size: 0.85rem; color: var(--muted); line-height: 1.5;">
                                {{ Str::limit($training['description'], 120) }}
                            </p>
                            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; border-top: 1px solid var(--line); padding-top: 12px; margin-top: 12px;">
                                <span style="font-size: 0.9rem; font-weight: 800; color: var(--text);">
                                    {{ number_format($training['promo_price'] ?: $training['price'], 0, ',', ' ') }} CFA
                                </span>
                                <div style="display: flex; gap: 8px;">
                                    <a href="{{ route('training.show', $training['id']) }}" class="btn btn-secondary" style="min-height: 36px; padding: 0 12px; font-size: 0.8rem;">Infos</a>
                                    @if(auth('client')->check())
                                        <form action="{{ route('register') }}" method="POST" style="margin: 0; display: inline;">
                                            @csrf
                                            <input type="hidden" name="course" value="{{ $training['name'] }}">
                                            <input type="hidden" name="month" value="Juin 2026">
                                            <button type="submit" class="btn btn-primary" style="min-height: 36px; padding: 0 12px; font-size: 0.8rem;">S'inscrire</button>
                                        </form>
                                    @else
                                        <a href="{{ route('student.signup') }}" class="btn btn-primary" style="min-height: 36px; padding: 0 12px; font-size: 0.8rem;">S'inscrire</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @php $isLeft = !$isLeft; @endphp
                @empty
                    <p style="text-align: center; color: var(--muted); width: 100%;">Aucun programme planifié pour le moment.</p>
                @endforelse
            </div>
        </div>
    </main>

    <footer style="background-color: #171312; color: #94a3b8; text-align: center; padding: 3rem 0; margin-top: 5rem; border-top: 1px solid #1e293b;">
        <div class="container">
            <p style="margin: 0; font-weight: 700; color: #fff;">Success Business Training</p>
            <p style="margin: 0.5rem 0 0 0; font-size: 0.9rem;">Formations professionnelles haut de gamme en IA, Business et Marketing.</p>
        </div>
    </footer>
</body>
</html>
