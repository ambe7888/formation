<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $formattedTraining['name'] }} - Success Business Training</title>
    <meta name="description" content="{{ Str::limit($formattedTraining['description'], 160) }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/style.css') }}?v=1.0.2">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #3730a3;
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



        /* Hero details banner */
        .training-hero {
            background: linear-gradient(135deg, #1e1b4b 0%, #0f172a 100%);
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
            background: radial-gradient(circle, rgba(79, 70, 229, 0.15) 0%, rgba(0, 0, 0, 0) 60%);
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
            color: #e2e8f0;
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

        .illustration-box {
            width: 100%;
            height: 380px;
            border-radius: 1rem;
            background-size: cover;
            background-position: center;
            margin-bottom: 2rem;
            border: 1px solid var(--border-color);
            transition: transform 0.5s ease;
        }

        .illustration-box:hover {
            transform: scale(1.01);
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

        /* Skills badges */
        .skills-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .skill-pill {
            display: inline-flex;
            align-items: center;
            color: #ffffff;
            font-weight: 700;
            font-size: 0.85rem;
            padding: 0.5rem 1rem;
            border-radius: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease;
        }

        .skill-pill:hover {
            transform: translateY(-2px);
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
            align-items: baseline;
            gap: 1rem;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }

        .current-price {
            font-size: 2.25rem;
            font-weight: 800;
            color: var(--text-dark);
        }

        .old-price {
            font-size: 1.25rem;
            text-decoration: line-through;
            color: #94a3b8;
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
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
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
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.25);
            transition: all 0.2s ease;
        }

        .btn-submit-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(79, 70, 229, 0.35);
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

    <!-- Hero Banner -->
    <section class="training-hero">
        <div class="hero-container">
            <!-- Compact, elegant back arrow link -->
            <a href="{{ url('/') }}" style="display: inline-flex; align-items: center; color: #a5b4fc; text-decoration: none; font-size: 0.85rem; font-weight: 700; margin-bottom: 1.5rem; transition: color 0.2s;" onmouseover="this.style.color='#ffffff'" onmouseout="this.style.color='#a5b4fc'">
                <svg style="width: 1rem; height: 1rem; margin-right: 0.35rem; fill: none; stroke: currentColor; stroke-width: 2.5; stroke-linecap: round; stroke-linejoin: round;" viewBox="0 0 24 24">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Retour aux formations
            </a>

            <div>
                <span class="hero-tag">{{ $formattedTraining['tag'] }}</span>
            <h1 class="hero-title">{{ $formattedTraining['name'] }}</h1>
            <div style="display: flex; gap: 2rem; color: #cbd5e1; font-weight: 600; font-size: 0.95rem;">
                <span>📅 Début : {{ $formattedTraining['date'] }}</span>
                <span>📍 Lieu : {{ $formattedTraining['location'] }}</span>
            </div>
        </div>
    </section>

    <!-- Content Grid -->
    <main class="content-container">
        <!-- Details Column -->
        <article>
            <div class="details-card">
                <!-- Illustration Box -->
                <div class="illustration-box" style="background-image: url('{{ $formattedTraining['illustration'] }}');"></div>

                <!-- Description -->
                <h2 class="section-title">Présentation du programme</h2>
                <div class="description-text">
                    {{ $formattedTraining['description'] }}
                </div>

                <!-- Skills acquired -->
                <h2 class="section-title">Compétences cibles acquises</h2>
                <div class="skills-grid">
                    @forelse($formattedTraining['skills'] as $skill)
                        <span class="skill-pill" style="background-color: {{ $skill->badge_color }};">
                            {{ $skill->name }}
                        </span>
                    @empty
                        <span class="text-muted">Aucune compétence spécifique répertoriée pour cette formation.</span>
                    @endforelse
                </div>


            </div>
        </article>

        <!-- Form / Sidebar Column -->
        <aside class="sidebar-box">
            <div class="pricing-card">
                <!-- Price badge -->
                <div class="price-badge-row">
                    @if($formattedTraining['promo_price'] > 0)
                        <strong class="current-price">{{ number_format($formattedTraining['promo_price'], 0, ',', ' ') }} CFA</strong>
                        <span class="old-price">{{ number_format($formattedTraining['price'], 0, ',', ' ') }} CFA</span>
                    @else
                        <strong class="current-price">{{ number_format($formattedTraining['price'], 0, ',', ' ') }} CFA</strong>
                    @endif
                </div>

                <!-- Meta Session parameters -->
                <ul class="meta-list">
                    <li class="meta-item">
                        <span class="meta-icon">📍</span>
                        <span class="meta-label">Lieu</span>
                        <span>{{ $formattedTraining['location'] }}</span>
                    </li>
                    <li class="meta-item">
                        <span class="meta-icon">📅</span>
                        <span class="meta-label">Date de début</span>
                        <span>{{ $formattedTraining['date'] }}</span>
                    </li>
                    <li class="meta-item">
                        <span class="meta-icon">👥</span>
                        <span class="meta-label">Places disponibles</span>
                        <span style="color: {{ $formattedTraining['available'] > 3 ? '#059669' : '#d97706' }};">
                            {{ $formattedTraining['available'] }} places
                        </span>
                    </li>
                </ul>

                @if(request('status') === 'success')
                    <div class="thank-you-card" style="text-align: center; padding: 2rem; background: #ffffff; border-radius: 1.5rem; border: 2px solid #10b981; box-shadow: 0 20px 40px rgba(16, 185, 129, 0.08); width: 100%; box-sizing: border-box;">
                        <span style="font-size: 3.5rem; display: block; margin-bottom: 1rem;">🎉</span>
                        <h3 style="color: #10b981; font-size: 1.35rem; font-weight: 800; margin: 0 0 1rem 0;">Merci pour votre inscription !</h3>
                        <p style="font-size: 0.95rem; line-height: 1.5; color: var(--text-main); margin-bottom: 1.5rem;">
                            Votre demande pour <strong>{{ request('course') }}</strong> a bien été enregistrée.
                        </p>
                        <div style="background: #f0fdf4; border-radius: 1rem; padding: 1rem; margin-bottom: 1.5rem; border: 1px solid #bbf7d0; text-align: left;">
                            <p style="margin: 0 0 0.5rem 0; font-size: 0.85rem; color: #166534;"><strong>Prochaines étapes :</strong></p>
                            <ul style="margin: 0; padding-left: 1.2rem; font-size: 0.8rem; color: #166534; line-height: 1.5;">
                                <li>Notre équipe pédagogique va valider votre dossier sous 24h.</li>
                                <li>Rendez-vous dans votre <strong>Espace Étudiant</strong> pour déclarer vos versements de paiement.</li>
                                <li>Dès réception, vos accès aux supports de cours 🔒 seront débloqués.</li>
                            </ul>
                        </div>
                        <a href="{{ route('student.dashboard') }}" class="btn-submit-premium" style="text-decoration: none; display: block; text-align: center; background: linear-gradient(135deg, #10b981 0%, #059669 100%); font-size: 0.95rem; font-weight: 700; color: white;">Accéder à mon Espace Étudiant 🧑‍🎓</a>
                    </div>
                @else
                    @if(request('status') === 'duplicate')
                        <div class="alert-premium" style="background-color: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; font-weight: 700; border-radius: 0.75rem; padding: 1rem; margin-bottom: 1rem;">
                            ⚠️ Vous êtes déjà inscrit(e) à cette formation ou ce pack !
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert-premium" style="background-color: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; font-weight: 700; border-radius: 0.75rem; padding: 1rem; margin-bottom: 1rem;">
                            ⚠️ {{ session('error') }}
                        </div>
                    @endif

                    <!-- Form Section -->
                    <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 1.25rem;">
                        <h3 class="form-title" style="margin: 0;">S'inscrire à cette session</h3>
                        @guest('client')
                            <a href="{{ route('student.login') }}" style="font-size: 0.85rem; font-weight: 700; color: var(--primary); text-decoration: none;">Déjà inscrit ? Se connecter</a>
                        @endguest
                    </div>

                    <form action="{{ route('register') }}" method="POST" id="main_registration_form">
                        @csrf
                        <!-- Hidden field to redirect back to details page on success -->
                        <input type="hidden" name="redirect_to" value="{{ route('training.show', $training->id) }}">
                        <!-- Preselected course -->
                        <input type="hidden" name="course" value="{{ $formattedTraining['name'] }}" id="main_course_input">
                        <!-- Hidden bundle id input -->
                        <input type="hidden" name="bundle_id" value="" id="main_bundle_id">

                        @if($training->bundles->isNotEmpty())
                            <!-- Pack Promo FAQ Accordion inside Form -->
                            <div class="faq-accordion-container" style="margin-bottom: 1.25rem;">
                                <label style="font-size: 0.85rem; font-weight: 700; color: var(--text-dark); display: block; margin-bottom: 0.5rem;">Option d'inscription *</label>
                                
                                <!-- Option: Single Training -->
                                <div class="faq-accordion-item active-faq" id="faq_item_single" style="border: 1px solid #7e22ce; border-radius: 0.75rem; background-color: #fff; margin-bottom: 0.5rem; overflow: hidden; transition: all 0.2s ease; box-shadow: 0 2px 4px rgba(126, 34, 206, 0.05);">
                                    <div class="faq-accordion-header" onclick="selectFaqOption('single', '')" style="padding: 0.75rem 1rem; display: flex; justify-content: space-between; align-items: center; cursor: pointer; background-color: #f5f3ff; transition: all 0.2s;">
                                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                                            <div class="faq-radio-circle" style="width: 1rem; height: 1rem; border-radius: 50%; border: 2px solid #7e22ce; display: flex; align-items: center; justify-content: center; background-color: #7e22ce; transition: all 0.2s;">
                                                <div style="width: 0.4rem; height: 0.4rem; border-radius: 50%; background-color: #fff;"></div>
                                            </div>
                                            <span style="font-size: 0.85rem; font-weight: 700; color: #581c87;">Formation seule uniquement</span>
                                        </div>
                                        <span style="font-size: 0.85rem; font-weight: 700; color: #7e22ce;">{{ number_format($formattedTraining['promo_price'] > 0 ? $formattedTraining['promo_price'] : $formattedTraining['price'], 0, ',', ' ') }} CFA</span>
                                    </div>
                                </div>

                                <!-- Option: Bundles -->
                                @foreach($training->bundles as $bundle)
                                    <div class="faq-accordion-item" id="faq_item_{{ $bundle->id }}" style="border: 1px solid #e2e8f0; border-radius: 0.75rem; background-color: #fff; margin-bottom: 0.5rem; overflow: hidden; transition: all 0.2s ease;">
                                        <div class="faq-accordion-header" onclick="selectFaqOption('bundle', '{{ $bundle->id }}', '{{ $bundle->name }}', '{{ number_format($bundle->price, 0, ',', ' ') }} CFA', '{{ Str::limit($bundle->description, 15) }}')" style="padding: 0.75rem 1rem; display: flex; justify-content: space-between; align-items: center; cursor: pointer; transition: all 0.2s;">
                                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                                <div class="faq-radio-circle" id="faq_radio_{{ $bundle->id }}" style="width: 1rem; height: 1rem; border-radius: 50%; border: 2px solid #cbd5e1; display: flex; align-items: center; justify-content: center; background-color: #fff; transition: all 0.2s;">
                                                    <div style="width: 0.4rem; height: 0.4rem; border-radius: 50%; background-color: #fff;"></div>
                                                </div>
                                                <span class="faq-title-span" id="faq_title_{{ $bundle->id }}" style="font-size: 0.85rem; font-weight: 700; color: #475569;">Pack : {{ $bundle->name }}</span>
                                            </div>
                                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                <span style="font-size: 0.85rem; font-weight: 700; color: #475569;">{{ number_format($bundle->price, 0, ',', ' ') }} CFA</span>
                                                <svg class="faq-chevron" id="faq_chevron_{{ $bundle->id }}" style="width: 1rem; height: 1rem; fill: none; stroke: currentColor; stroke-width: 2.5; stroke-linecap: round; stroke-linejoin: round; color: #94a3b8; transition: transform 0.2s ease;" viewBox="0 0 24 24">
                                                    <polyline points="6 9 12 15 18 9"></polyline>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="faq-accordion-body" id="faq_body_{{ $bundle->id }}" style="max-height: 0; overflow: hidden; transition: max-height 0.2s ease-out; background-color: #faf9ff;">
                                            <div style="padding: 0.75rem 1rem; border-top: 1px solid #f1f5f9; font-size: 0.8rem; color: #475569; line-height: 1.4;">
                                                <div style="margin-bottom: 0.5rem; color: #581c87;">
                                                    <strong>Description :</strong> {{ Str::limit($bundle->description, 15) }}
                                                </div>
                                                <div style="color: #6b21a8; font-weight: 600;">
                                                    Formations incluses dans ce pack :
                                                    <ul style="margin: 0.25rem 0 0 0; padding-left: 1.25rem; font-size: 0.75rem; color: #6b21a8; line-height: 1.5;">
                                                        @foreach($bundle->trainings as $bt)
                                                            <li>{{ $bt->title }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <script>
                                function selectFaqOption(type, bundleId, bundleName, priceStr, shortDesc) {
                                    // Reset all items
                                    const allItems = document.querySelectorAll('.faq-accordion-item');
                                    allItems.forEach(item => {
                                        item.style.borderColor = '#e2e8f0';
                                        item.style.boxShadow = 'none';
                                        item.querySelector('.faq-accordion-header').style.backgroundColor = '#fff';
                                        const titleSpan = item.querySelector('.faq-title-span');
                                        if (titleSpan) titleSpan.style.color = '#475569';
                                    });
                                    
                                    const allRadioCircles = document.querySelectorAll('.faq-radio-circle');
                                    allRadioCircles.forEach(circle => {
                                        circle.style.borderColor = '#cbd5e1';
                                        circle.style.backgroundColor = '#fff';
                                    });

                                    const allChevrons = document.querySelectorAll('.faq-chevron');
                                    allChevrons.forEach(chevron => {
                                        chevron.style.transform = 'rotate(0deg)';
                                        chevron.style.color = '#94a3b8';
                                    });

                                    const allBodies = document.querySelectorAll('.faq-accordion-body');
                                    allBodies.forEach(body => {
                                        body.style.maxHeight = '0';
                                    });

                                    // Set inputs
                                    const bundleInput = document.getElementById('main_bundle_id');
                                    const courseInput = document.getElementById('main_course_input');
                                    const courseLabel = document.getElementById('selected_course_label');

                                    if (type === 'bundle') {
                                        const activeItem = document.getElementById('faq_item_' + bundleId);
                                        const activeRadio = document.getElementById('faq_radio_' + bundleId);
                                        const activeChevron = document.getElementById('faq_chevron_' + bundleId);
                                        const activeBody = document.getElementById('faq_body_' + bundleId);
                                        const activeTitle = document.getElementById('faq_title_' + bundleId);

                                        if (activeItem) {
                                            activeItem.style.borderColor = '#7e22ce';
                                            activeItem.style.boxShadow = '0 2px 8px rgba(126, 34, 206, 0.08)';
                                            activeItem.querySelector('.faq-accordion-header').style.backgroundColor = '#f5f3ff';
                                        }
                                        if (activeTitle) {
                                            activeTitle.style.color = '#581c87';
                                        }
                                        if (activeRadio) {
                                            activeRadio.style.borderColor = '#7e22ce';
                                            activeRadio.style.backgroundColor = '#7e22ce';
                                        }
                                        if (activeChevron) {
                                            activeChevron.style.transform = 'rotate(180deg)';
                                            activeChevron.style.color = '#7e22ce';
                                        }
                                        if (activeBody) {
                                            activeBody.style.maxHeight = activeBody.scrollHeight + 'px';
                                        }

                                        if (bundleInput) bundleInput.value = bundleId;
                                        if (courseInput) courseInput.value = bundleName;
                                        if (courseLabel) courseLabel.value = "Pack " + bundleName;
                                    } else {
                                        // Single training selected
                                        const activeItem = document.getElementById('faq_item_single');
                                        const radioCircle = activeItem.querySelector('.faq-radio-circle');
                                        
                                        if (activeItem) {
                                            activeItem.style.borderColor = '#7e22ce';
                                            activeItem.style.boxShadow = '0 2px 8px rgba(126, 34, 206, 0.08)';
                                            activeItem.querySelector('.faq-accordion-header').style.backgroundColor = '#f5f3ff';
                                        }
                                        if (radioCircle) {
                                            radioCircle.style.borderColor = '#7e22ce';
                                            radioCircle.style.backgroundColor = '#7e22ce';
                                        }

                                        if (bundleInput) bundleInput.value = "";
                                        if (courseInput) courseInput.value = "{{ $formattedTraining['name'] }}";
                                        if (courseLabel) courseLabel.value = "{{ $formattedTraining['name'] }}";
                                    }
                                }
                            </script>
                        @endif

                        <div class="form-group-custom">
                            <label>Formation sélectionnée</label>
                            <input type="text" class="form-control-custom" value="{{ $formattedTraining['name'] }}" id="selected_course_label" disabled style="font-weight: 700; background-color: #f1f5f9;">
                        </div>

                        @auth('client')
                            <div class="alert-premium alert-premium-success" style="margin-top: 1rem; margin-bottom: 1.5rem; background-color: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 1rem; border-radius: 1rem;">
                                Bonjour <strong>{{ auth('client')->user()->name }}</strong> ! Vous êtes connecté(e). Vos coordonnées sont pré-remplies automatiquement.
                            </div>
                            <input type="hidden" name="name" value="{{ auth('client')->user()->name }}">
                            <input type="hidden" name="phone" value="{{ auth('client')->user()->phone }}">
                            <input type="hidden" name="email" value="{{ auth('client')->user()->email }}">
                        @else
                            <div class="form-group-custom">
                                <label for="name">Nom complet *</label>
                                <input type="text" id="name" name="name" class="form-control-custom" required placeholder="Votre nom et prénom">
                            </div>

                            <div class="form-group-custom">
                                <label for="phone">Numéro de téléphone *</label>
                                <input type="tel" id="phone" name="phone" class="form-control-custom" required placeholder="ex: +225 07080910">
                            </div>

                            <div class="form-group-custom">
                                <label for="email">Adresse email *</label>
                                <input type="email" id="email" name="email" class="form-control-custom" required placeholder="Votre adresse email">
                            </div>

                            <div class="form-group-custom">
                                <label for="password">Mot de passe étudiant *</label>
                                <input type="password" id="password" name="password" class="form-control-custom" required minlength="6" placeholder="Créez votre mot de passe (min. 6 caractères)">
                                <span style="font-size: 0.75rem; color: var(--text-main); margin-top: 0.25rem; display: block; opacity: 0.85;">Ce mot de passe servira à vous connecter à votre Espace Étudiant.</span>
                            </div>
                        @endauth

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
                            @auth('client')
                                S'inscrire en 1 clic 🚀
                            @else
                                Créer mon compte & m'inscrire 🚀
                            @endauth
                        </button>
                    </form>
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
