<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formation Pro - Programmes IA & Marketing</title>
    <meta name="description" content="Site de formations avec programmes IA, Marketing, calendrier mensuel, prix et formulaire d'inscription.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400;500;600;700&family=Manrope:wght@400;500;600;700;800&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('assets/style.css')); ?>?v=1.0.2">
    <style>
        /* Styles spécifiques pour les Packs de Formations (Violets) */
        .bundle-card {
            border: 2px solid #ddd6fe !important;
            background: rgba(250, 245, 255, 0.72) !important;
            box-shadow: 0 18px 60px rgba(126, 34, 206, 0.08) !important;
        }
        
        .bundle-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 24px 68px rgba(126, 34, 206, 0.15) !important;
            background: rgba(250, 245, 255, 0.9) !important;
            border-color: #c084fc !important;
        }

        .bundle-card .package-price-current {
            color: #7e22ce !important;
        }

        .bundle-card .training-location {
            color: #7e22ce !important;
        }

        .bundle-card .training-date {
            color: #7e22ce !important;
        }
        
        .bundle-badge {
            background: linear-gradient(135deg, #7e22ce, #6b21a8) !important;
            color: #ffffff !important;
        }
    </style>
</head>
<body>
    <header class="site-header">
        <div class="container header-bar">
            <a class="brand" href="<?php echo e(url('/')); ?>">
                <span class="brand-mark">SB</span>
                <span>
                    <strong>Success Business Training</strong>
                    <small>IA, Business et Marketing </small>
                </span>
            </a>
            <nav class="nav">
                <a href="<?php echo e(route('trainings.page')); ?>">Nos formations</a>
                <a href="<?php echo e(route('program.page')); ?>">Programme</a>
                <a href="<?php echo e(route('skills.page')); ?>">Compétences</a>
                <?php if(auth('client')->check()): ?>
                    <a href="<?php echo e(route('student.dashboard')); ?>" class="btn btn-primary" style="min-height: 38px; padding: 0 16px; font-size: 0.85rem;">Mon Espace 🧑‍🎓</a>
                    <form action="<?php echo e(route('student.logout')); ?>" method="POST" style="margin: 0; display: inline-flex;">
                        <?php echo csrf_field(); ?>
                        <button type="submit" style="background: none; border: none; color: var(--text); font-family: inherit; font-size: 0.85rem; font-weight: 600; cursor: pointer; margin-left: 10px;">Déconnexion</button>
                    </form>
                <?php else: ?>
                    <a href="<?php echo e(route('student.login')); ?>" style="font-size: 0.85rem; font-weight: 700; text-decoration: none;">Connexion</a>
                    <a href="<?php echo e(route('student.signup')); ?>" class="btn btn-primary" style="min-height: 38px; padding: 0 16px; font-size: 0.85rem;">S'inscrire</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main id="accueil">
        <section class="hero">
            <div class="hero-bg"></div>
            <div class="container hero-grid">
                <div class="hero-copy animate-fade-in">
                    <span class="eyebrow hero-eyebrow">Développez vos compétences en</span>
                    <h1 class="hero-title">Marketing, Business <br>&<br> Intelligence Artificielle</h1>
                    <p>
                        Chaque programme est conçu pour vous donner des compétences directement applicables afin de générer des résultats rapides dans votre activité.
                    </p>
                    <div class="hero-actions-row">
                        <a class="btn btn-primary" href="#formations">Voir les formations</a>
                    </div>
                </div>
                <aside class="hero-card animate-fade-in" style="animation-delay: 0.2s;">
                    <div class="carousel-container">
                        <div class="carousel-wrapper">
                            <?php
                                $sliderTrainings = $heroTrainings->isNotEmpty() ? $heroTrainings : $trainings->take(4);
                            ?>
                            <div class="carousel-track" id="packageCarousel">
                                <?php $__currentLoopData = $sliderTrainings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $training): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <article class="carousel-slide package-slide" data-index="<?php echo e($index); ?>">
                                        
                                        <div class="package-image" style="background-image: url('<?php echo e($training['illustration']); ?>');">
                                            <div class="package-image-badges">
                                                <span class="package-chip"><?php echo e($training['tag']); ?></span>
                                            </div>
                                            <div class="price-badge">
                                                <?php if($training['promo_price'] > 0): ?>
                                                    <span class="package-price-old"><?php echo e(number_format($training['price'], 0, ',', ' ')); ?> CFA</span>
                                                <?php endif; ?>
                                                <strong class="package-price-current"><?php echo e(number_format($training['promo_price'] ?: $training['price'], 0, ',', ' ')); ?> CFA</strong>
                                            </div>
                                        </div>

                                        
                                        <div class="package-body">
                                            <div class="package-location">
                                                <span class="package-location-icon">
                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                                </span>
                                                <?php echo e($training['location'] ?: 'En ligne'); ?>

                                            </div>

                                            <div class="package-title-row">
                                                <h3><?php echo e($training['name']); ?></h3>
                                            </div>

                                            <p class="package-description"><?php echo e(Str::limit($training['description'], 90)); ?></p>

                                            <div class="package-action-row">
                                                <span class="package-date">
                                                    <?php if(isset($training['type']) && $training['type'] === 'bundle'): ?>
                                                        <span class="package-location-icon" style="margin-right: 4px;">📚</span> <?php echo e($training['date']); ?>

                                                    <?php else: ?>
                                                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                                        <?php echo e($training['date']); ?>

                                                    <?php endif; ?>
                                                </span>
                                                <?php if(isset($training['type']) && $training['type'] === 'bundle'): ?>
                                                    <a class="btn btn-primary package-action" href="<?php echo e(route('bundle.show', $training['id'])); ?>">Détails</a>
                                                <?php else: ?>
                                                    <a class="btn btn-primary package-action" href="<?php echo e(route('training.show', $training['id'])); ?>">Détails</a>
                                                <?php endif; ?>
                                            </div>

                                            <div class="package-remaining">
                                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                                <?php echo e($training['available']); ?> places disponibles
                                            </div>
                                        </div>
                                    </article>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        <div class="carousel-dots" id="carouselDots">
                            <?php for($i = 0; $i < min(4, $sliderTrainings->count()); $i++): ?>
                                <button class="dot <?php echo e($i === 0 ? 'active' : ''); ?>" data-index="<?php echo e($i); ?>" aria-label="Afficher formation <?php echo e($i + 1); ?>"></button>
                            <?php endfor; ?>
                        </div>
                    </div>
                </aside>
            </div>
        </section>

        <section id="packs" class="section section-alt" style="border-bottom: 1px solid var(--line); margin-top: 0; padding-top: 56px; padding-bottom: 56px;">
            <div class="container">
                <div class="section-heading" style="text-align: center; margin: 0 auto 40px;">
                    <span class="eyebrow" style="color: #7e22ce;">Offres Exceptionnelles</span>
                    <h2 style="font-family: 'Roboto', sans-serif; font-size: 36px; font-weight: 800; text-transform: uppercase; color: #283746; margin: 10px 0;">Nos Packs Promotionnels</h2>
                    <p style="color: var(--muted); max-width: 600px; margin: 0 auto;">Combinez plusieurs formations pour maximiser vos compétences tout en réalisant des économies substantielles.</p>
                </div>

                <div class="training-group-grid packs-grid">
                    <?php $__empty_1 = true; $__currentLoopData = $bundles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bundle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="training-card bundle-card">
                            <div class="training-image" style="background-image: url('<?php echo e($bundle['illustration'] ?? asset('assets/images/default-training.svg')); ?>');">
                                <div class="training-image-badges">
                                    <span class="package-chip bundle-badge">🎁 Offre Pack</span>
                                </div>
                                <div class="price-badge">
                                    <?php if($bundle['savings'] > 0): ?>
                                        <span class="package-price-old"><?php echo e(number_format($bundle['total_original'], 0, ',', ' ')); ?> CFA</span>
                                    <?php endif; ?>
                                    <strong class="package-price-current"><?php echo e(number_format($bundle['price'], 0, ',', ' ')); ?> CFA</strong>
                                </div>
                            </div>
                            <div class="training-content">
                                <div class="training-location">
                                    <span class="training-location-icon">📚</span> <?php echo e(count($bundle['trainings'])); ?> formations incluses
                                </div>
                                <h3 style="color: #283746; font-size: 1.25rem;">Pack : <?php echo e($bundle['name']); ?></h3>
                                <p class="package-description" style="margin-bottom: 8px; flex: 0 0 auto; line-height: 1.5;"><?php echo e(Str::limit($bundle['description'], 100)); ?></p>
                                
                                <!-- Included formations tags -->
                                <div style="margin: 8px 0 14px 0; border-top: 1px dashed rgba(126, 34, 206, 0.15); padding-top: 10px; flex: 1;">
                                    <span style="font-size: 0.7rem; font-weight: 800; color: #6b21a8; display: block; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.05em;">Modules inclus :</span>
                                    <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                                        <?php $__currentLoopData = $bundle['trainings']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span style="background-color: rgba(126, 34, 206, 0.06); color: #7e22ce; border: 1px solid rgba(126, 34, 206, 0.12); font-size: 0.725rem; padding: 3px 8px; border-radius: 999px; font-weight: 700; display: inline-block; white-space: nowrap;">
                                                ✓ <?php echo e($t['tag']); ?>

                                            </span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>

                                <div class="training-action-row" style="border-top: 1px solid var(--line); padding-top: 12px; margin-top: auto;">
                                    <a href="<?php echo e(route('bundle.show', $bundle['id'])); ?>" class="btn btn-primary btn-compact" style="background: linear-gradient(135deg, #7e22ce, #6b21a8); box-shadow: 0 10px 20px rgba(126, 34, 206, 0.2);">Détails</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p style="text-align: center; color: var(--muted); grid-column: 1 / -1;">Aucun pack disponible pour le moment.</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <section id="formations" class="section">
            <div class="container">
                <div class="section-heading" style="text-align: center; margin: 0 auto 32px;">
                    <span class="eyebrow" style="color: var(--accent);">Nos Programmes</span>
                    <h2>Nos Formations</h2>
                    <p style="color: var(--muted); max-width: 600px; margin: 0 auto;">Découvrez nos programmes de formation par domaine</p>
                </div>

                
                <div class="month-filter-container">
                    <button class="filter-tab active" data-month="all">
                        Toutes les sessions
                    </button>
                    <button class="filter-tab" data-month="Juin">
                        Session de Juin
                    </button>
                    <button class="filter-tab" data-month="Juillet">
                        Session de Juillet
                    </button>
                </div>

                <div class="training-groups">
                    <?php $__currentLoopData = $trainingGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        
                        <article class="training-group">
                            <div class="training-group-head">
                                <div class="training-group-copy">
                                    <h3 style="margin: 0; font-family: 'Roboto', sans-serif; font-size: 1.5rem; font-weight: 800; color: #283746;"><?php echo e($group['title']); ?></h3>
                                    <p><?php echo e($group['description']); ?></p>
                                </div>
                            </div>
                        </article>

                        
                        <div class="training-cards-scroll">
                            <?php $__currentLoopData = $trainings->where('group', $group['key']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $training): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="training-card" data-month="<?php echo e($training['planned_month']); ?>">
                                    <div class="training-image" style="background-image: url('<?php echo e($training['illustration']); ?>');">
                                        <div class="training-image-badges">
                                            <span class="package-chip"><?php echo e($training['tag']); ?></span>
                                        </div>
                                        <div class="price-badge">
                                            <?php if($training['promo_price'] > 0): ?>
                                                <span class="package-price-old"><?php echo e(number_format($training['price'], 0, ',', ' ')); ?> CFA</span>
                                            <?php endif; ?>
                                            <strong class="package-price-current"><?php echo e(number_format($training['promo_price'] ?: $training['price'], 0, ',', ' ')); ?> CFA</strong>
                                        </div>
                                    </div>
                                    <div class="training-content">
                                        <div class="training-location">
                                            <span class="training-location-icon">
                                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                            </span>
                                            <?php echo e($training['location'] ?: 'En ligne'); ?>

                                        </div>
                                        <h3><?php echo e($training['name']); ?></h3>
                                        <p class="package-description"><?php echo e(Str::limit($training['description'], 80)); ?></p>

                                        
                                        <?php if($training['skills']->isNotEmpty()): ?>
                                        <div class="training-skills-strip">
                                            <?php $__currentLoopData = $training['skills']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="training-skill-tag" style="background-color:<?php echo e($skill->badge_color); ?>;"><?php echo e($skill->name); ?></span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                        <?php endif; ?>

                                        <div class="training-card-sep"></div>

                                        <div class="training-action-row">
                                            <span class="training-date">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                                <?php echo e($training['date']); ?>

                                            </span>
                                            <a href="<?php echo e(route('training.show', $training['id'])); ?>" class="btn btn-primary btn-compact">Détails</a>
                                        </div>

                                        <div class="training-remaining">
                                            <span class="training-remaining-icon">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                            </span>
                                            <?php echo e($training['available']); ?> places disponibles
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </section>

        
        <section id="programme" class="section section-alt" style="border-top: 1px solid var(--line); border-bottom: 1px solid var(--line); padding-top: 56px; padding-bottom: 56px;">
            <div class="container">
                <div class="section-heading" style="text-align: center; margin: 0 auto 40px;">
                    <span class="eyebrow" style="color: var(--brand);">Calendrier des sessions</span>
                    <h2 style="font-family: 'Roboto', sans-serif; font-size: 36px; font-weight: 800; text-transform: uppercase; color: #283746; margin: 10px 0;">Notre Programme & Planning</h2>
                    <p style="color: var(--muted); max-width: 600px; margin: 0 auto;">Planifiez votre montée en compétences en consultant les dates clés de nos prochaines sessions de formation.</p>
                </div>

                <div class="timeline">
                    <?php $isLeft = true; ?>
                    <?php $__empty_1 = true; $__currentLoopData = $trainings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $training): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="timeline-container <?php echo e($isLeft ? 'left' : 'right'); ?>" data-month="<?php echo e($training['planned_month']); ?>">
                            <div class="timeline-content">
                                <span class="timeline-date-chip">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px; vertical-align: middle;"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                    <?php echo e($training['date']); ?>

                                </span>
                                <h3 style="margin: 10px 0 10px 0; font-family: 'Roboto', sans-serif; font-size: 1.25rem; font-weight: 800; color: #283746;">
                                    <?php echo e($training['name']); ?>

                                </h3>
                                <p style="margin: 0 0 15px 0; font-size: 0.85rem; color: var(--muted); line-height: 1.5;">
                                    <?php echo e(Str::limit($training['description'], 120)); ?>

                                </p>
                                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; border-top: 1px solid var(--line); padding-top: 12px; margin-top: 12px;">
                                    <span style="font-size: 0.95rem; font-weight: 800; color: var(--text);">
                                        <?php echo e(number_format($training['promo_price'] ?: $training['price'], 0, ',', ' ')); ?> CFA
                                    </span>
                                    <div style="display: flex; gap: 8px;">
                                        <a href="<?php echo e(route('training.show', $training['id'])); ?>" class="btn btn-secondary btn-compact" style="min-height: 36px; padding: 0 14px; font-size: 0.8rem;">Détails</a>
                                        <?php if(auth('client')->check()): ?>
                                            <form action="<?php echo e(route('register')); ?>" method="POST" style="margin: 0; display: inline;">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="course" value="<?php echo e($training['name']); ?>">
                                                <input type="hidden" name="month" value="<?php echo e($training['planned_month'] ?? 'Juin 2026'); ?>">
                                                <button type="submit" class="btn btn-primary btn-compact" style="min-height: 36px; padding: 0 14px; font-size: 0.8rem;">S'inscrire</button>
                                            </form>
                                        <?php else: ?>
                                            <a href="<?php echo e(route('student.signup')); ?>" class="btn btn-primary btn-compact" style="min-height: 36px; padding: 0 14px; font-size: 0.8rem;">S'inscrire</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php $isLeft = !$isLeft; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p style="text-align: center; color: var(--muted); width: 100%;">Aucun programme planifié pour le moment.</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>

    <footer style="background-color: #171312; color: #94a3b8; text-align: center; padding: 3rem 0; margin-top: 5rem; border-top: 1px solid #1e293b;">
        <div class="container">
            <p style="margin: 0; font-weight: 700; color: #fff;">Success Business Training</p>
            <p style="margin: 0.5rem 0 0 0; font-size: 0.9rem;">Formations professionnelles haut de gamme en IA, Business et Marketing.</p>
        </div>
    </footer>

    <script src="<?php echo e(asset('assets/script.js')); ?>"></script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\formation\resources\views/home.blade.php ENDPATH**/ ?>