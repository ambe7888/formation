<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos Formations - Success Business Training</title>
    <meta name="description" content="Découvrez notre catalogue complet de formations professionnelles en IA, Business et Marketing.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/style.css') }}?v=1.0.2">
    <style>
        .page-header-hero {
            padding: 80px 0 40px;
            text-align: center;
            background: linear-gradient(135deg, rgba(15, 118, 110, 0.05), transparent 45%);
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
                <a href="{{ route('trainings.page') }}" style="color: var(--brand); font-weight: 800;">Nos formations</a>
                <a href="{{ route('program.page') }}">Programme</a>
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
            <span class="eyebrow" style="color: var(--accent);">Catalogue complet</span>
            <h1 class="page-title">Découvrez nos Formations</h1>
            <p class="page-subtitle">Des parcours intensifs animés par des experts pour booster votre carrière et vos projets entrepreneuriaux.</p>
        </div>
    </div>

    <main style="padding: 60px 0;">
        <div class="container">
            <div class="formations-grid">
                @forelse($trainingGroups as $group)
                    <article class="formation-group" style="background: #ffffff; border: 1px solid var(--line); border-radius: 28px; padding: 32px; box-shadow: var(--shadow);">
                        <div class="formation-group-header" style="margin-bottom: 28px;">
                            <h3 style="font-size: 1.5rem; font-weight: 800; color: #283746; margin: 0 0 8px 0;">{{ $group['title'] }}</h3>
                            <p style="color: var(--muted); margin: 0; font-size: 0.95rem; line-height: 1.5;">{{ $group['description'] }}</p>
                        </div>
                        <div class="formation-group-list" style="display: grid; grid-template-columns: 1fr; gap: 24px;">
                            @foreach($trainings->where('group', $group['key']) as $training)
                                <div class="formation-item" style="display: flex; gap: 24px; border: 1px solid var(--line); border-radius: 20px; overflow: hidden; background: var(--surface); transition: var(--transition);">
                                    <div class="formation-image" style="width: 200px; height: 100%; min-height: 180px; background-size: cover; background-position: center; background-image: url('{{ $training['illustration'] }}'); flex-shrink: 0; position: relative;">
                                        <div class="formation-price" style="position: absolute; bottom: 12px; left: 12px; background: #ffffff; color: var(--text); padding: 6px 12px; border-radius: 8px; font-weight: 800; box-shadow: 0 4px 10px rgba(0,0,0,0.1); font-size: 0.85rem;">
                                            {{ number_format($training['promo_price'] ?: $training['price'], 0, ',', ' ') }} CFA
                                        </div>
                                    </div>
                                    <div class="formation-content" style="padding: 24px; display: flex; flex-direction: column; justify-content: space-between; flex-grow: 1;">
                                        <div>
                                            <h4 style="margin: 0 0 8px 0; font-size: 1.25rem; font-weight: 800; color: #283746;">{{ $training['name'] }}</h4>
                                            <p style="margin: 0 0 16px 0; font-size: 0.9rem; color: var(--muted); line-height: 1.5;">{{ $training['description'] }}</p>
                                            <div class="formation-meta" style="display: flex; gap: 16px; font-size: 0.8rem; color: var(--muted); flex-wrap: wrap; margin-bottom: 12px;">
                                                <span>📅 {{ $training['date'] }}</span>
                                                <span>📍 {{ $training['location'] }}</span>
                                                <span style="color: {{ $training['available'] > 3 ? 'var(--brand)' : 'var(--accent)' }}; font-weight: 700;">👥 {{ $training['available'] }} places restantes</span>
                                            </div>
                                            <div style="display: flex; gap: 8px; flex-wrap: wrap; margin-top: 10px;">
                                                @foreach($training['skills'] as $skill)
                                                    <span style="font-size: 0.7rem; font-weight: 700; background: {{ $skill->badge_color }}; color: #fff; padding: 3px 8px; border-radius: 6px; text-transform: uppercase;">{{ $skill->name }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div style="display: flex; gap: 12px; margin-top: 20px; align-self: flex-end;">
                                            <a href="{{ route('training.show', $training['id']) }}" class="btn btn-secondary" style="min-height: 40px; padding: 0 16px; font-size: 0.85rem;">Détails</a>
                                            @if(auth('client')->check())
                                                <form action="{{ route('register') }}" method="POST" style="margin: 0; display: inline;">
                                                    @csrf
                                                    <input type="hidden" name="course" value="{{ $training['name'] }}">
                                                    <input type="hidden" name="month" value="Juin 2026">
                                                    <button type="submit" class="btn btn-primary" style="min-height: 40px; padding: 0 16px; font-size: 0.85rem;">S'inscrire</button>
                                                </form>
                                            @else
                                                <a href="{{ route('student.signup') }}" class="btn btn-primary" style="min-height: 40px; padding: 0 16px; font-size: 0.85rem;">S'inscrire</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </article>
                @empty
                    <p style="text-align: center; color: var(--muted); width: 100%;">Aucune formation disponible pour le moment.</p>
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
