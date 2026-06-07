<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compétences cibles - Success Business Training</title>
    <meta name="description" content="Découvrez les compétences clés que vous allez acquérir grâce à nos programmes de formation en IA, Business et Marketing.">
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

        /* Skills grid */
        .skills-container-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 28px;
            padding: 40px 0;
        }
        .skill-card {
            background-color: #ffffff;
            border: 1px solid var(--line);
            border-radius: 24px;
            padding: 28px;
            box-shadow: var(--shadow);
            transition: var(--transition);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .skill-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-strong);
        }
        .skill-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 18px;
        }
        .skill-badge-circle {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: grid;
            place-items: center;
            color: #ffffff;
            font-weight: 800;
            font-size: 1.25rem;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
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
                <a href="{{ route('program.page') }}">Programme</a>
                <a href="{{ route('skills.page') }}" style="color: var(--brand); font-weight: 800;">Compétences</a>
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
            <span class="eyebrow" style="color: var(--accent);">Objectifs opérationnels</span>
            <h1 class="page-title">Vos Compétences Cibles</h1>
            <p class="page-subtitle">Découvrez les savoir-faire concrets et compétences métiers valorisables que vous développerez à l'issue de nos formations.</p>
        </div>
    </div>

    <main style="padding: 60px 0;">
        <div class="container">
            <div class="skills-container-grid">
                @forelse($skills as $skill)
                    <article class="skill-card">
                        <div>
                            <div class="skill-header">
                                <div class="skill-badge-circle" style="background-color: {{ $skill->badge_color ?: 'var(--brand)' }}">
                                    💡
                                </div>
                                <h3 style="margin: 0; font-family: 'Roboto', sans-serif; font-size: 1.35rem; font-weight: 800; color: #283746; text-transform: uppercase;">
                                    {{ $skill->name }}
                                </h3>
                            </div>
                            <p style="color: var(--muted); font-size: 0.9rem; line-height: 1.5; margin-bottom: 20px;">
                                Cette compétence clé est activement travaillée et validée au travers des cas pratiques et ateliers de nos formations spécialisées.
                            </p>
                        </div>
                        
                        <div style="border-top: 1px solid var(--line); padding-top: 18px; margin-top: auto;">
                            <h4 style="font-size: 0.85rem; font-weight: 700; color: var(--muted); text-transform: uppercase; margin: 0 0 12px 0; letter-spacing: 0.05em;">
                                Formations associées :
                            </h4>
                            <ul style="list-style: none; padding: 0; margin: 0; display: grid; gap: 8px;">
                                @forelse($skill->trainings as $training)
                                    <li>
                                        <a href="{{ route('training.show', $training->id) }}" style="display: flex; justify-content: space-between; align-items: center; text-decoration: none; color: var(--text); font-weight: 600; font-size: 0.9rem; padding: 8px 12px; background: var(--surface); border: 1px solid var(--line); border-radius: 10px; transition: var(--transition);">
                                            <span>{{ $training->title }}</span>
                                            <span style="font-size: 0.8rem; color: var(--brand);">Détails →</span>
                                        </a>
                                    </li>
                                @empty
                                    <li style="color: var(--muted); font-size: 0.85rem; font-style: italic;">Aucune formation planifiée pour le moment.</li>
                                @endforelse
                            </ul>
                        </div>
                    </article>
                @empty
                    <p style="text-align: center; color: var(--muted); width: 100%;">Aucune compétence cible répertoriée pour le moment.</p>
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
