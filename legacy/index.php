<?php
function buildIllustration(string $firstColor, string $secondColor, string $thirdColor): string
{
    $svg = sprintf(
        '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 420" fill="none"><defs><linearGradient id="g1" x1="70" y1="30" x2="740" y2="390" gradientUnits="userSpaceOnUse"><stop stop-color="%s"/><stop offset="1" stop-color="%s"/></linearGradient><linearGradient id="g2" x1="110" y1="70" x2="660" y2="320" gradientUnits="userSpaceOnUse"><stop stop-color="%s" stop-opacity="0.95"/><stop offset="1" stop-color="#ffffff" stop-opacity="0.12"/></linearGradient></defs><rect width="800" height="420" rx="32" fill="url(#g1)"/><circle cx="685" cy="96" r="72" fill="%s" fill-opacity="0.18"/><circle cx="150" cy="320" r="118" fill="#ffffff" fill-opacity="0.08"/><path d="M76 290C160 190 235 176 318 214C402 252 470 318 552 318C622 318 679 278 744 205" stroke="#ffffff" stroke-opacity="0.22" stroke-width="18" stroke-linecap="round"/><rect x="118" y="92" width="262" height="160" rx="28" fill="url(#g2)"/><rect x="414" y="130" width="176" height="112" rx="26" fill="#ffffff" fill-opacity="0.14"/><rect x="626" y="184" width="96" height="96" rx="24" fill="%s" fill-opacity="0.24"/><path d="M165 124h142" stroke="#fff" stroke-opacity="0.26" stroke-width="12" stroke-linecap="round"/><path d="M165 158h104" stroke="#fff" stroke-opacity="0.20" stroke-width="12" stroke-linecap="round"/><path d="M165 192h122" stroke="#fff" stroke-opacity="0.16" stroke-width="12" stroke-linecap="round"/></svg>',
        $firstColor,
        $secondColor,
        $thirdColor,
        $thirdColor,
        $thirdColor
    );

    return 'data:image/svg+xml;charset=UTF-8,' . rawurlencode($svg);
}

function excerptWords(string $text, int $limit = 20): string
{
    $words = preg_split('/\s+/u', trim($text)) ?: [];
    if (count($words) <= $limit) {
        return trim($text);
    }

    return implode(' ', array_slice($words, 0, $limit)) . '...';
}

require_once __DIR__ . '/includes/db.php';

function formatDateFr(string $date): string
{
    $parsed = DateTime::createFromFormat('Y-m-d', $date);
    if (!$parsed) {
        return $date;
    }

    $months = [
        'janvier', 'fevrier', 'mars', 'avril', 'mai', 'juin',
        'juillet', 'aout', 'septembre', 'octobre', 'novembre', 'decembre',
    ];
    $monthIndex = (int) $parsed->format('n') - 1;
    $monthName = $months[$monthIndex] ?? $parsed->format('m');

    return $parsed->format('j') . ' ' . $monthName . ' ' . $parsed->format('Y');
}

function loadTrainingsFromDb(): array
{
    try {
        $pdo = getDbConnection();
        $statement = $pdo->query('SELECT id, title, category, description, start_date, location, price, promo_price, seats, image_url FROM trainings WHERE is_active = 1 ORDER BY start_date ASC');
        $rows = $statement->fetchAll();
    } catch (Throwable $e) {
        return [];
    }

    if (!$rows) {
        return [];
    }

    $defaultThumbnail = 'assets/images/default-training.svg';

    $trainings = [];
    foreach ($rows as $index => $row) {
        $image = trim((string) ($row['image_url'] ?? ''));
        $trainings[] = [
            'id' => (string) $row['id'],
            'name' => (string) $row['title'],
            'price' => (int) $row['price'],
            'promo_price' => $row['promo_price'] !== null ? (int) $row['promo_price'] : 0,
            'tag' => (string) $row['category'],
            'group' => (string) $row['category'],
            'location' => (string) ($row['location'] ?? ''),
            'date' => formatDateFr((string) $row['start_date']),
            'available' => (int) $row['seats'],
            'description' => (string) $row['description'],
            'illustration' => $image !== '' ? $image : $defaultThumbnail,
        ];
    }

    return $trainings;
}

function loadBundlesFromDb(): array
{
    try {
        $pdo = getDbConnection();
        $stmt = $pdo->query('SELECT id, name, price, description FROM bundles ORDER BY name ASC');
        $rows = $stmt->fetchAll();
        
        if (!$rows) {
            return [];
        }
        
        $bundles = [];
        foreach ($rows as $row) {
            $bundleId = (int) $row['id'];
            
            // Fetch associated active trainings
            $tStmt = $pdo->prepare('
                SELECT t.id, t.title 
                FROM trainings t
                INNER JOIN bundle_training bt ON t.id = bt.training_id
                WHERE bt.bundle_id = ? AND t.is_active = 1
            ');
            $tStmt->execute([$bundleId]);
            $trainings = $tStmt->fetchAll();
            
            $bundles[] = [
                'id' => $bundleId,
                'name' => (string) $row['name'],
                'price' => (int) $row['price'],
                'description' => (string) $row['description'],
                'trainings' => $trainings,
            ];
        }
        return $bundles;
    } catch (Throwable $e) {
        return [];
    }
}

function buildTrainingGroups(array $trainings, array $descriptions): array
{
    $groups = [];
    foreach ($trainings as $training) {
        $key = $training['group'] ?? '';
        if ($key === '') {
            continue;
        }
        $groups[$key] = true;
    }

    $orderedKeys = array_merge(array_keys($descriptions), array_diff(array_keys($groups), array_keys($descriptions)));
    $result = [];
    foreach ($orderedKeys as $key) {
        if (!isset($groups[$key])) {
            continue;
        }
        $result[] = [
            'key' => $key,
            'title' => $key,
            'description' => $descriptions[$key] ?? 'Formations disponibles dans cette categorie.',
        ];
    }

    return $result;
}

$trainingMonths = [
    [
        'month' => 'Juin',
        'theme' => 'Lancement & bases',
        'status' => 'Disponible',
        'sessions' => [
            ['title' => 'Formation IA', 'price' => 10000, 'duration' => '2 jours', 'type' => 'À venir'],
            ['title' => 'Formation Marketing', 'price' => 20000, 'duration' => '3 jours', 'type' => 'À venir'],
        ],
    ],
    [
        'month' => 'Juillet',
        'theme' => 'Pratique & cas réels',
        'status' => 'Ouvertes',
        'sessions' => [
            ['title' => 'Formation IA', 'price' => 10000, 'duration' => '2 jours', 'type' => 'Disponible'],
            ['title' => 'Formation Marketing', 'price' => 20000, 'duration' => '3 jours', 'type' => 'Disponible'],
        ],
    ],
    [
        'month' => 'Août',
        'theme' => 'Intensif & certification',
        'status' => 'À venir',
        'sessions' => [
            ['title' => 'Formation IA', 'price' => 10000, 'duration' => '2 jours', 'type' => 'Préinscription'],
            ['title' => 'Formation Marketing', 'price' => 20000, 'duration' => '3 jours', 'type' => 'Préinscription'],
        ],
    ],
    [
        'month' => 'Septembre',
        'theme' => 'Rentrée professionnelle',
        'status' => 'À venir',
        'sessions' => [
            ['title' => 'Formation IA', 'price' => 10000, 'duration' => '2 jours', 'type' => 'Préinscription'],
            ['title' => 'Formation Marketing', 'price' => 20000, 'duration' => '3 jours', 'type' => 'Préinscription'],
        ],
    ],
];

$availableTrainings = [
    [
        'id' => 'ia',
        'name' => 'Formation IA',
        'price' => 10000,
        'tag' => 'Intelligence artificielle',
        'group' => 'Intelligence artificielle',
        'location' => 'Bingerville',
        'date' => '12 juin 2026',
        'available' => 12,
        'description' => 'Initiez-vous aux outils IA, automatisez vos tâches et créez des usages concrets pour votre activité.',
        'illustration' => buildIllustration('#0f766e', '#155e75', '#1d4ed8'),
    ],
    [
        'id' => 'marketing',
        'name' => 'Formation Marketing',
        'price' => 20000,
        'tag' => 'Croissance commerciale',
        'group' => 'Marketing',
        'location' => 'Bingerville',
        'date' => '18 juin 2026',
        'available' => 8,
        'description' => 'Apprenez à attirer plus de clients avec une stratégie marketing simple, claire et efficace.',
        'illustration' => buildIllustration('#7c3aed', '#0f766e', '#f97316'),
    ],
    [
        'id' => 'business',
        'name' => 'Formation Business',
        'price' => 15000,
        'tag' => 'Stratégie & croissance',
        'group' => 'Business',
        'location' => 'Bingerville',
        'date' => '21 juin 2026',
        'available' => 10,
        'description' => 'Apprenez à structurer votre activité, organiser vos priorités et améliorer vos résultats commerciaux.',
        'illustration' => buildIllustration('#0b5b55', '#1d4ed8', '#f97316'),
    ],
    [
        'id' => 'communication',
        'name' => 'Formation Communication',
        'price' => 12000,
        'tag' => 'Message & image',
        'group' => 'Autres formations',
        'location' => 'Bingerville',
        'date' => '24 juin 2026',
        'available' => 9,
        'description' => 'Apprenez à communiquer clairement, convaincre votre audience et renforcer votre image de marque.',
        'illustration' => buildIllustration('#f97316', '#7c3aed', '#0f766e'),
    ],
    [
        'id' => 'design',
        'name' => 'Formation Design',
        'price' => 18000,
        'tag' => 'Création visuelle',
        'group' => 'Intelligence artificielle',
        'location' => 'Bingerville',
        'date' => '28 juin 2026',
        'available' => 7,
        'description' => 'Créez des visuels plus professionnels pour vos contenus, vos offres et vos supports marketing.',
        'illustration' => buildIllustration('#334155', '#0f766e', '#7c3aed'),
    ],
    [
        'id' => 'entrepreneuriat',
        'name' => 'Formation Entrepreneuriat',
        'price' => 16000,
        'tag' => 'Projet & lancement',
        'group' => 'Business',
        'location' => 'Bingerville',
        'date' => '1 juillet 2026',
        'available' => 11,
        'description' => 'Passez de l’idée au projet concret avec une approche pratique pour lancer et faire évoluer votre activité.',
        'illustration' => buildIllustration('#155e75', '#f97316', '#0f766e'),
    ],
    [
        'id' => 'vente',
        'name' => 'Formation Vente',
        'price' => 14000,
        'tag' => 'Conversion & closing',
        'group' => 'Marketing',
        'location' => 'Bingerville',
        'date' => '4 juillet 2026',
        'available' => 13,
        'description' => 'Développez des techniques simples pour mieux convaincre, conclure et fidéliser vos clients.',
        'illustration' => buildIllustration('#1d4ed8', '#0f766e', '#f97316'),
    ],
    [
        'id' => 'soft-skills',
        'name' => 'Formation Soft Skills',
        'price' => 11000,
        'tag' => 'Organisation & posture',
        'group' => 'Autres formations',
        'location' => 'Bingerville',
        'date' => '8 juillet 2026',
        'available' => 14,
        'description' => 'Renforcez votre communication, votre discipline et votre posture professionnelle au quotidien.',
        'illustration' => buildIllustration('#7c3aed', '#334155', '#0f766e'),
    ],
    [
        'id' => 'seo',
        'name' => 'Formation SEO',
        'price' => 13000,
        'tag' => 'Visibilité digitale',
        'group' => 'Marketing',
        'location' => 'Bingerville',
        'date' => '10 juillet 2026',
        'available' => 10,
        'description' => 'Positionnez mieux votre activité sur les moteurs de recherche avec une méthode simple et pratique.',
        'illustration' => buildIllustration('#2563eb', '#0f766e', '#f97316'),
    ],
    [
        'id' => 'content',
        'name' => 'Formation Content Marketing',
        'price' => 12500,
        'tag' => 'Contenu & audience',
        'group' => 'Marketing',
        'location' => 'Bingerville',
        'date' => '13 juillet 2026',
        'available' => 12,
        'description' => 'Construisez un calendrier de contenu qui attire, engage et transforme vos prospects en clients.',
        'illustration' => buildIllustration('#0f766e', '#7c3aed', '#f97316'),
    ],
    [
        'id' => 'leadership',
        'name' => 'Formation Leadership',
        'price' => 17000,
        'tag' => 'Management humain',
        'group' => 'Business',
        'location' => 'Bingerville',
        'date' => '15 juillet 2026',
        'available' => 9,
        'description' => 'Développez votre leadership pour mieux piloter les équipes, déléguer et atteindre vos objectifs.',
        'illustration' => buildIllustration('#0b5b55', '#334155', '#1d4ed8'),
    ],
    [
        'id' => 'finance',
        'name' => 'Formation Finance PME',
        'price' => 19000,
        'tag' => 'Pilotage financier',
        'group' => 'Business',
        'location' => 'Bingerville',
        'date' => '18 juillet 2026',
        'available' => 8,
        'description' => 'Apprenez à suivre vos indicateurs, maîtriser vos coûts et améliorer la rentabilité de votre activité.',
        'illustration' => buildIllustration('#155e75', '#0f766e', '#f97316'),
    ],
    [
        'id' => 'automation',
        'name' => 'Formation IA Automation',
        'price' => 14500,
        'tag' => 'Automatisation IA',
        'group' => 'Intelligence artificielle',
        'location' => 'Bingerville',
        'date' => '20 juillet 2026',
        'available' => 11,
        'description' => 'Automatisez les tâches répétitives avec des workflows intelligents adaptés à votre quotidien professionnel.',
        'illustration' => buildIllustration('#1d4ed8', '#0f766e', '#7c3aed'),
    ],
    [
        'id' => 'data-ai',
        'name' => 'Formation Data & IA',
        'price' => 15500,
        'tag' => 'Analyse augmentée',
        'group' => 'Intelligence artificielle',
        'location' => 'Bingerville',
        'date' => '23 juillet 2026',
        'available' => 10,
        'description' => 'Utilisez la donnée et les outils IA pour mieux comprendre vos performances et prendre de meilleures décisions.',
        'illustration' => buildIllustration('#334155', '#1d4ed8', '#0f766e'),
    ],
    [
        'id' => 'productivity',
        'name' => 'Formation Productivité',
        'price' => 10500,
        'tag' => 'Méthodes de travail',
        'group' => 'Autres formations',
        'location' => 'Bingerville',
        'date' => '25 juillet 2026',
        'available' => 15,
        'description' => 'Organisez votre semaine, priorisez les tâches essentielles et améliorez votre efficacité au quotidien.',
        'illustration' => buildIllustration('#7c3aed', '#0f766e', '#155e75'),
    ],
    [
        'id' => 'public-speaking',
        'name' => 'Formation Prise de parole',
        'price' => 11500,
        'tag' => 'Expression orale',
        'group' => 'Autres formations',
        'location' => 'Bingerville',
        'date' => '28 juillet 2026',
        'available' => 12,
        'description' => 'Gagnez en confiance pour présenter vos idées clairement devant un public et convaincre avec impact.',
        'illustration' => buildIllustration('#f97316', '#7c3aed', '#0f766e'),
    ],
];

$dbTrainings = loadTrainingsFromDb();
if (!empty($dbTrainings)) {
    $availableTrainings = $dbTrainings;
}

$categoryDescriptions = [
    'Marketing' => 'Des formations pour attirer plus de clients, mieux communiquer et convertir davantage.',
    'Business' => 'Des contenus pour structurer l’activite, lancer des projets et renforcer la croissance.',
    'Intelligence artificielle' => 'Des formations pour gagner du temps, automatiser et produire plus vite avec l’IA.',
    'Autres formations' => 'Communication, design et posture professionnelle pour completer le parcours.',
];

$trainingGroups = buildTrainingGroups($availableTrainings, $categoryDescriptions);

$specialPackages = [
    [
        'name' => 'Formation IA',
        'price' => 10000,
        'category' => 'Intelligence Artificielle',
        'available' => 12,
        'location' => 'Bingerville',
        'date' => '12 juin 2026',
        'description' => 'Accès à la formation IA pour apprendre les outils essentiels, automatiser des tâches et produire des résultats concrets rapidement.',
        'illustration' => buildIllustration('#0f766e', '#155e75', '#1d4ed8'),
    ],
    [
        'name' => 'Formation Marketing',
        'price' => 20000,
        'category' => 'Marketing',
        'available' => 8,
        'location' => 'Bingerville',
        'date' => '18 juin 2026',
        'description' => 'Accès à la formation Marketing pour structurer votre communication, vendre mieux et mettre en place des campagnes efficaces.',
        'illustration' => buildIllustration('#7c3aed', '#0f766e', '#f97316'),
    ],
    [
        'name' => 'Pack IA + Marketing',
        'price' => 25000,
        'category' => 'Bundle Complet',
        'available' => 5,
        'location' => 'Bingerville',
        'date' => '25 juin 2026',
        'savings' => 5000,
        'original_price' => 30000,
        'description' => 'Accès aux deux formations avec réduction spéciale pour apprendre à la fois l’intelligence artificielle et le marketing de façon coordonnée.',
        'illustration' => buildIllustration('#f97316', '#0f766e', '#1d4ed8'),
    ],
    [
        'name' => 'Business & IA',
        'price' => 22000,
        'category' => 'Business',
        'available' => 10,
        'location' => 'Bingerville',
        'date' => '2 juillet 2026',
        'description' => 'Accès à un pack orienté business et IA pour améliorer votre organisation, votre productivité et vos décisions.',
        'illustration' => buildIllustration('#0b5b55', '#334155', '#f97316'),
    ],
];

$bundlePrice = 25000;
$bundleSavings = 5000;
$status = $_GET['status'] ?? '';
$selectedCourse = $_GET['course'] ?? '';
$statusClassMap = [
    'Disponible' => 'disponible',
    'Ouvertes' => 'ouvertes',
    'À venir' => 'a-venir',
];
$basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
if (!str_ends_with(strtolower($basePath), 'public')) {
    $basePath = rtrim($basePath, '/\\') . '/public';
}
?>
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
    <link rel="stylesheet" href="assets/style.css?v=<?php echo filemtime(__DIR__ . '/assets/style.css'); ?>">
</head>
<body>
    <header class="site-header">
        <div class="container header-bar">
            <a class="brand" href="#accueil">
                <span class="brand-mark">SB</span>
                <span>
                    <strong>Success Business Training</strong>
                    <small>IA, Business et Marketing </small>
                </span>
            </a>
            <nav class="nav">
                <a href="#formations">Formations</a>
                <a href="#inscription">Inscription</a>
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
                            <div class="carousel-track" id="packageCarousel">
                                <?php foreach ($specialPackages as $index => $package): ?>
                                    <article class="carousel-slide package-slide" data-index="<?php echo $index; ?>">
                                        <div class="package-image" style="background-image: url('<?php echo htmlspecialchars($package['illustration']); ?>');">
                                            <div class="package-image-badges">
                                                <span class="package-chip">Offre spéciale</span>
                                                <span class="package-chip package-chip-soft"><?php echo htmlspecialchars($package['category']); ?></span>
                                            </div>
                                            <div class="package-price-block price-badge">
                                                <?php if (isset($package['original_price']) && $package['original_price'] > $package['price']): ?>
                                                    <span class="package-price-old"><?php echo number_format($package['original_price'], 0, ',', ' '); ?> CFA</span>
                                                <?php endif; ?>
                                                <strong class="package-price-current"><?php echo number_format($package['price'], 0, ',', ' '); ?> CFA</strong>
                                            </div>
                                        </div>
                                        <div class="package-body">
                                             <div class="package-location">
                                                 <span class="package-icon-pin">📍</span>
                                                 <span><?php echo htmlspecialchars($package['location']); ?></span>
                                             </div>
                                            <div class="package-title-row">
                                                <h3><?php echo htmlspecialchars($package['name']); ?></h3>
                                            </div>
                                            <p class="package-description"><?php echo htmlspecialchars(excerptWords($package['description'], 20)); ?></p>
                                             <div class="package-action-row">
                                                 <span class="package-date"><span class="package-icon-cal">📅</span> <?php echo htmlspecialchars($package['date']); ?></span>
                                                 <?php 
                                                 $targetId = null;
                                                 foreach ($availableTrainings as $t) {
                                                     if (strcasecmp($t['name'], $package['name']) === 0) {
                                                         $targetId = $t['id'];
                                                         break;
                                                     }
                                                 }
                                                 ?>
                                                 <?php if ($targetId): ?>
                                                     <a class="btn btn-dark package-action" href="<?php echo htmlspecialchars($basePath . '/formations/' . $targetId); ?>">Détails</a>
                                                 <?php else: ?>
                                                     <a class="btn btn-dark package-action" href="#inscription" data-course="<?php echo htmlspecialchars($package['name']); ?>">S'inscrire</a>
                                                 <?php endif; ?>
                                             </div>
                                            <div class="package-remaining">👥 <?php echo $package['available']; ?> places disponibles</div>
                                        </div>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="carousel-dots" id="carouselDots">
                            <?php foreach ($specialPackages as $index => $package): ?>
                                <button class="dot <?php echo $index === 0 ? 'active' : ''; ?>" data-index="<?php echo $index; ?>" aria-label="Afficher <?php echo htmlspecialchars($package['name']); ?>"></button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </aside>
            </div>
        </section>

        <section id="formations" class="section">
            <div class="container">
                <div class="section-heading">
                    <span class="eyebrow">Formations disponibles</span>
                    <h2>Programmes disponibles ce mois-ci</h2>
                    <p>Choisissez votre catégorie et découvrez les formations associées.</p>
                </div>

                <?php
                $dbBundles = loadBundlesFromDb();
                if (!empty($dbBundles)):
                ?>
                    <!-- Section Nos Packs de formation -->
                    <div class="category-header-block reveal" style="background: linear-gradient(135deg, #f5f3ff 0%, #edd8ff 100%); border: 1px solid #c084fc; margin-bottom: 2rem;">
                        <div class="category-header-inner">
                            <div class="category-header-copy">
                                <span class="eyebrow" style="color: #7e22ce;">Économie & Offres exclusives</span>
                                <h3>Nos Packs de formation</h3>
                                <p>Bénéficiez de nos meilleurs programmes regroupés dans des offres exclusives à tarifs réduits.</p>
                            </div>
                        </div>
                    </div>

                    <div class="category-cards-grid reveal" style="margin-bottom: 4rem;">
                        <?php foreach ($dbBundles as $bundle): ?>
                            <article class="training-card" style="border: 2px solid #ddd6fe; box-shadow: 0 10px 25px rgba(126, 34, 206, 0.05);">
                                <div class="training-image" style="background: linear-gradient(135deg, #7e22ce 0%, #4f46e5 100%); height: 180px; position: relative;">
                                    <div class="training-image-badges" style="position: absolute; top: 1rem; left: 1rem;">
                                        <span class="package-chip" style="background: #ffffff; color: #7e22ce; font-weight: 800;">PACK EXCLUSIF</span>
                                    </div>
                                    <div class="price-badge" style="background: #ffffff; color: #7e22ce; border: 1px solid #c084fc; padding: 0.5rem 1rem; border-radius: 0.75rem; position: absolute; bottom: 1rem; right: 1rem; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                                        <strong class="package-price-current" style="color: #7e22ce; font-size: 1.2rem; font-weight: 800;"><?php echo number_format($bundle['price'], 0, ',', ' '); ?> CFA</strong>
                                    </div>
                                </div>
                                <div class="training-content" style="padding: 1.5rem;">
                                    <h3 style="font-family: 'Playfair Display', serif; font-size: 1.35rem; font-weight: 900; margin: 0 0 1rem 0; color: #0f172a;">🎁 <?php echo htmlspecialchars($bundle['name']); ?></h3>
                                    <p style="font-size: 0.95rem; color: #4b5563; line-height: 1.6; margin-bottom: 1.25rem;"><?php echo htmlspecialchars($bundle['description']); ?></p>
                                    
                                    <p style="font-weight: 700; color: #7e22ce; margin-bottom: 0.5rem; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.03em;">📚 Formations incluses :</p>
                                    <ul style="padding-left: 1.2rem; margin: 0 0 1.5rem 0; font-size: 0.9rem; color: #374151; line-height: 1.5;">
                                        <?php foreach ($bundle['trainings'] as $bt): ?>
                                            <li><strong><?php echo htmlspecialchars($bt['title']); ?></strong></li>
                                        <?php endforeach; ?>
                                    </ul>
                                    
                                    <div class="training-action-row" style="margin-top: auto; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #f3f4f6; padding-top: 1rem;">
                                        <span style="font-size: 0.8rem; color: #7e22ce; font-weight: bold;">🔥 Offre limitée</span>
                                        <div style="display: flex; gap: 0.5rem;">
                                            <a class="btn btn-dark btn-compact" href="<?php echo htmlspecialchars($basePath . '/packs/' . $bundle['id']); ?>" style="text-decoration: none; text-align: center; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; padding: 0.5rem 1rem; border-radius: 0.5rem;">Détails</a>
                                            <button class="btn btn-dark btn-compact" onclick="selectBundleForSignup(<?php echo $bundle['id']; ?>, '<?php echo htmlspecialchars($bundle['name']); ?>')" style="background: linear-gradient(135deg, #7e22ce 0%, #6b21a8 100%); color: #fff; border: none; font-weight: 700; padding: 0.5rem 1rem; border-radius: 0.5rem; cursor: pointer; transition: all 0.2s ease;">
                                                S'inscrire
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php
                $allFacts = [
                    '💡 L\'IA peut réduire votre temps de travail répétitif de 40%.',
                    '🚀 85% des entreprises cherchent des experts en Marketing Digital.',
                    '📈 L\'automatisation est la compétence n°1 demandée en 2026.',
                    '🤖 70% des tâches administratives peuvent être gérées par l\'IA.',
                    '🎯 Le marketing ciblé augmente les conversions de 300%.',
                    '💎 La certification IA est un accélérateur de carrière majeur.',
                    '📱 90% du contenu web sera généré par l\'IA d’ici 2030.',
                    '⚡ L\'IA permet de créer du contenu 10x plus rapidement.'
                ];
                shuffle($allFacts);
                $factIndex = 0;
                ?>

                <?php foreach ($trainingGroups as $groupIdx => $group): ?>
                    <?php $groupTrainings = array_values(array_filter($availableTrainings, function ($training) use ($group) { return $training['group'] === $group['key']; })); ?>

                    <!-- Bloc catégorie : titre + description + bouton seulement -->
                    <div class="category-header-block reveal">
                        <div class="category-header-inner">
                            <div class="category-header-copy">
                                <span class="eyebrow"><?php echo htmlspecialchars($group['title']); ?></span>
                                <p><?php echo htmlspecialchars($group['description']); ?></p>
                            </div>
                            <a class="btn btn-secondary" href="#inscription">Voir plus</a>
                        </div>
                    </div>

                    <!-- Cartes de formations pour cette catégorie -->
                    <div class="category-cards-grid reveal">
                        <?php foreach ($groupTrainings as $training): ?>
                            <article class="training-card">
                                <div class="training-image" style="background-image: url('<?php echo htmlspecialchars($training['illustration']); ?>');">
                                    <div class="training-image-badges">
                                        <span class="package-chip">OFFRE SPÉCIALE</span>
                                        <span class="package-chip package-chip-soft"><?php echo strtoupper(htmlspecialchars($training['tag'])); ?></span>
                                    </div>
                                    <?php
                                    $basePrice = (int) $training['price'];
                                    $promoPrice = isset($training['promo_price']) ? (int) $training['promo_price'] : 0;
                                    if ($promoPrice > 0 && $promoPrice < $basePrice) {
                                        $oldPrice = $basePrice;
                                        $currentPrice = $promoPrice;
                                    } else {
                                        $oldPrice = (int) round($basePrice * 1.2);
                                        $currentPrice = $basePrice;
                                    }
                                    ?>
                                    <div class="price-badge">
                                        <span class="package-price-old"><?php echo number_format($oldPrice, 0, ',', ' '); ?> CFA</span>
                                        <strong class="package-price-current"><?php echo number_format($currentPrice, 0, ',', ' '); ?> CFA</strong>
                                    </div>
                                </div>
                                <div class="training-content">
                                    <div class="training-location">
                                        <span class="package-icon-pin">📍</span>
                                        <span><?php echo htmlspecialchars($training['location']); ?></span>
                                    </div>
                                    <h3><?php echo htmlspecialchars($training['name']); ?></h3>
                                    <p><?php echo htmlspecialchars(excerptWords($training['description'], 14)); ?></p>
                                     <div class="training-action-row">
                                         <span class="training-date">📅 <?php echo htmlspecialchars($training['date']); ?></span>
                                         <a class="btn btn-dark btn-compact" href="<?php echo htmlspecialchars($basePath . '/formations/' . $training['id']); ?>">Détails</a>
                                     </div>
                                    <div class="training-remaining">👥 <?php echo $training['available']; ?> places disponibles</div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>

                    <!-- Quick Fact entre les catégories (sauf après la dernière) -->
                    <?php if ($groupIdx < count($trainingGroups) - 1 && isset($allFacts[$factIndex])): ?>
                        <div class="quick-fact-banner">
                            <p><?php echo $allFacts[$factIndex++]; ?></p>
                        </div>
                    <?php endif; ?>

                <?php endforeach; ?>
            </div>
        </section>



        <section id="inscription" class="section section-alt">
            <div class="container signup-layout">
                <div class="signup-copy">
                    <span class="eyebrow">Inscription</span>
                    <h2>Permettez aux participants de réserver leur place en quelques secondes.</h2>
                    <p>Le formulaire enregistre les demandes localement et peut servir de base à un envoi vers WhatsApp, email ou base de données plus tard.</p>
                    <div class="bundle-callout">
                        <strong>Rappel pack</strong>
                        <p>Deux formations séparées coûtent 30 000. Le pack IA + Marketing coûte 25 000.</p>
                    </div>
                </div>
                <?php if ($status === 'success'): ?>
                    <div class="thank-you-card animate-fade-in" style="background: #ffffff; border-radius: 1.5rem; border: 2px solid #10b981; box-shadow: 0 20px 40px rgba(16, 185, 129, 0.08); padding: 3rem; text-align: center; max-width: 500px; margin: 0 auto; box-sizing: border-box; width: 100%;">
                        <span style="font-size: 4.5rem; display: block; margin-bottom: 1.5rem;">🎉</span>
                        <h3 style="color: #10b981; font-size: 1.6rem; font-weight: 800; margin: 0 0 1rem 0;">Merci pour votre inscription !</h3>
                        <p style="font-size: 1.05rem; line-height: 1.6; color: #334155; margin-bottom: 2rem;">
                            Votre demande pour <strong><?php echo htmlspecialchars($selectedCourse); ?></strong> a bien été enregistrée et transmise avec succès.
                        </p>
                        <div style="background: #f0fdf4; border-radius: 1rem; padding: 1.25rem; margin-bottom: 2rem; border: 1px solid #bbf7d0; text-align: left;">
                            <p style="margin: 0 0 0.5rem 0; font-size: 0.95rem; color: #166534;"><strong>Prochaines étapes :</strong></p>
                            <ul style="margin: 0; padding-left: 1.2rem; font-size: 0.85rem; color: #166534; line-height: 1.5;">
                                <li>Notre équipe pédagogique va valider votre dossier sous 24h.</li>
                                <li>Rendez-vous dans votre <strong>Espace Étudiant</strong> pour déclarer vos versements de paiement.</li>
                                <li>Dès validation, vos accès aux supports de cours 🔒 seront débloqués.</li>
                            </ul>
                        </div>
                        <a href="<?php echo htmlspecialchars($basePath . '/login'); ?>" class="btn btn-primary btn-full" style="text-decoration: none; display: block; text-align: center; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; color: white;">
                            Accéder à mon Espace Étudiant 🧑‍🎓
                        </a>
                    </div>
                <?php else: ?>
                    <form class="signup-form" action="register.php" method="post">
                        <?php if ($status === 'error'): ?>
                            <div class="alert alert-error">Veuillez remplir tous les champs obligatoires.</div>
                        <?php elseif ($status === 'duplicate'): ?>
                            <div class="alert alert-error" style="background-color: #fee2e2; border-color: #fca5a5; color: #991b1b;">Vous êtes déjà inscrit(e) à cette formation ou ce pack !</div>
                        <?php endif; ?>
                        <input type="hidden" name="bundle_id" id="home_bundle_id" value="">
                        <label>
                            Nom complet
                            <input type="text" name="name" placeholder="Votre nom" required>
                        </label>
                        <label>
                            Téléphone
                            <input type="text" name="phone" placeholder="Votre numéro" required>
                        </label>
                        <label>
                            Email
                            <input type="email" name="email" placeholder="Votre email" required>
                        </label>
                        <label>
                            Formation souhaitée
                            <select name="course" id="courseSelect" required>
                                <option value="">Choisir une formation</option>
                                <?php
                                $courseOptions = [];
                                foreach ($availableTrainings as $training) {
                                    $courseOptions[$training['name']] = $training['price'];
                                }
                                foreach ($specialPackages as $package) {
                                    $courseOptions[$package['name']] = $package['price'];
                                }
                                ?>
                                <?php foreach ($courseOptions as $courseName => $coursePrice): ?>
                                    <option value="<?php echo htmlspecialchars($courseName); ?>" <?php echo $selectedCourse === $courseName ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($courseName); ?> - <?php echo number_format((int) $coursePrice, 0, ',', ' '); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label>
                            Mois
                            <select name="month" required>
                                <option value="">Choisir un mois</option>
                                <?php foreach ($trainingMonths as $month): ?>
                                    <option value="<?php echo htmlspecialchars($month['month']); ?>"><?php echo htmlspecialchars($month['month']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label>
                            Message
                            <textarea name="message" rows="4" placeholder="Dites-nous si vous voulez la session IA, Marketing ou le pack."></textarea>
                        </label>
                        <button class="btn btn-primary btn-full" type="submit">Envoyer l’inscription</button>
                        <p class="form-note">Les champs marqués sont obligatoires. Le pack est sélectionnable directement dans le formulaire.</p>
                    </form>
                <?php endif; ?>

                <script>
                    function selectBundleForSignup(bundleId, bundleName) {
                        const courseSelect = document.getElementById('courseSelect');
                        const bundleInput = document.getElementById('home_bundle_id');
                        
                        if (bundleInput) {
                            bundleInput.value = bundleId;
                        }
                        
                        if (courseSelect) {
                            let exists = false;
                            for (let i = 0; i < courseSelect.options.length; i++) {
                                if (courseSelect.options[i].value === bundleName) {
                                    exists = true;
                                    courseSelect.selectedIndex = i;
                                    break;
                                }
                            }
                            
                            if (!exists) {
                                const opt = document.createElement('option');
                                opt.value = bundleName;
                                opt.text = bundleName;
                                courseSelect.add(opt);
                                courseSelect.value = bundleName;
                            }
                        }
                        
                        const target = document.getElementById('inscription');
                        if (target) {
                            target.scrollIntoView({ behavior: 'smooth' });
                        }
                    }
                </script>
            </div>
        </section>
    </main>

    <footer class="site-footer">
        <div class="container footer-grid">
            <div>
                <strong>Formation Pro</strong>
                <p>Une base simple pour gérer vos formations, vos mois, vos prix et vos inscriptions.</p>
            </div>
            <div>
                <strong>Contact</strong>
                <p>Téléphone, WhatsApp, email ou formulaire en ligne selon vos besoins.</p>
            </div>
            <div>
                <strong>Évolution</strong>
                <p>On peut ensuite ajouter d’autres parcours, des paiements et un tableau d’administration.</p>
            </div>
        </div>
    </footer>

    <script src="assets/script.js"></script>
</body>
</html>
