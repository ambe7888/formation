<?php

declare(strict_types=1);

session_start();

$config = require __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

$errors = [];
$success = '';
$uploadDir = __DIR__ . '/../uploads';
$maxUploadBytes = 3 * 1024 * 1024;
$allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

function isLoggedIn(): bool
{
    return !empty($_SESSION['admin_logged_in']);
}

function cleanValue(string $value): string
{
    return trim($value);
}

function ensureUploadDir(string $path): bool
{
    if (is_dir($path)) {
        return true;
    }

    return mkdir($path, 0755, true);
}

function handleImageUpload(array $file, string $uploadDir, array $allowedExtensions, int $maxBytes, array &$errors): ?string
{
    if ($file['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = 'Erreur lors de l\'upload de l\'image.';
        return null;
    }

    if ($file['size'] > $maxBytes) {
        $errors[] = 'Image trop volumineuse (max 3MB).';
        return null;
    }

    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, $allowedExtensions, true)) {
        $errors[] = 'Format image non supporte (jpg, jpeg, png, webp).';
        return null;
    }

    if (!@getimagesize($file['tmp_name'])) {
        $errors[] = 'Le fichier envoye n\'est pas une image valide.';
        return null;
    }

    if (!ensureUploadDir($uploadDir)) {
        $errors[] = 'Impossible de creer le dossier d\'upload.';
        return null;
    }

    $filename = uniqid('training_', true) . '.' . $extension;
    $destination = $uploadDir . '/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        $errors[] = 'Impossible d\'enregistrer l\'image.';
        return null;
    }

    return 'uploads/' . $filename;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'login') {
        $username = cleanValue($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === ($config['admin']['username'] ?? '')
            && password_verify($password, $config['admin']['password_hash'] ?? '')) {
            session_regenerate_id(true);
            $_SESSION['admin_logged_in'] = true;
            $success = 'Connexion reussie.';
        } else {
            $errors[] = 'Identifiants incorrects.';
        }
    }

    if ($action === 'logout') {
        $_SESSION = [];
        session_destroy();
        header('Location: index.php');
        exit;
    }

    if ($action === 'add' && isLoggedIn()) {
        $title = cleanValue($_POST['title'] ?? '');
        $category = cleanValue($_POST['category'] ?? '');
        $description = cleanValue($_POST['description'] ?? '');
        $startDate = cleanValue($_POST['start_date'] ?? '');
        $location = cleanValue($_POST['location'] ?? '');
        $imageUrl = cleanValue($_POST['image_url'] ?? '');
        $uploadedImage = null;
        if (isset($_FILES['image_file'])) {
            $uploadedImage = handleImageUpload($_FILES['image_file'], $uploadDir, $allowedExtensions, $maxUploadBytes, $errors);
        }

        $price = filter_var($_POST['price'] ?? null, FILTER_VALIDATE_INT);
        $promoPrice = filter_var($_POST['promo_price'] ?? null, FILTER_VALIDATE_INT);
        $seats = filter_var($_POST['seats'] ?? null, FILTER_VALIDATE_INT);

        if ($title === '' || $category === '' || $description === '' || $startDate === '' || $location === '') {
            $errors[] = 'Veuillez remplir tous les champs obligatoires.';
        }

        if ($price === false || $price <= 0) {
            $errors[] = 'Le prix est invalide.';
        }

        if ($seats === false || $seats <= 0) {
            $errors[] = 'Le nombre de places est invalide.';
        }

        $dateValid = DateTime::createFromFormat('Y-m-d', $startDate) !== false;
        if (!$dateValid) {
            $errors[] = 'La date doit etre au format AAAA-MM-JJ.';
        }

        if (empty($errors)) {
            try {
                $pdo = getDbConnection();
                $finalImageUrl = $uploadedImage ?? ($imageUrl !== '' ? $imageUrl : null);
                $statement = $pdo->prepare('INSERT INTO trainings (title, category, description, start_date, location, price, promo_price, seats, image_url, is_active, created_at) VALUES (:title, :category, :description, :start_date, :location, :price, :promo_price, :seats, :image_url, 1, NOW())');
                $statement->execute([
                    'title' => $title,
                    'category' => $category,
                    'description' => $description,
                    'start_date' => $startDate,
                    'location' => $location,
                    'price' => $price,
                    'promo_price' => $promoPrice ?: null,
                    'seats' => $seats,
                    'image_url' => $finalImageUrl,
                ]);
                $success = 'Formation ajoutee avec succes.';
            } catch (Throwable $e) {
                $errors[] = 'Erreur lors de lenregistrement. Verifiez la connexion MySQL.';
            }
        }
    }

    if ($action === 'delete' && isLoggedIn()) {
        $trainingId = filter_var($_POST['training_id'] ?? null, FILTER_VALIDATE_INT);
        if ($trainingId) {
            try {
                $pdo = getDbConnection();
                $statement = $pdo->prepare('DELETE FROM trainings WHERE id = :id');
                $statement->execute(['id' => $trainingId]);
                $success = 'Formation supprimee.';
            } catch (Throwable $e) {
                $errors[] = 'Impossible de supprimer la formation.';
            }
        }
    }
}

$trainings = [];
if (isLoggedIn()) {
    try {
        $pdo = getDbConnection();
        $trainings = $pdo->query('SELECT id, title, category, start_date, price, promo_price, seats, location FROM trainings ORDER BY created_at DESC')->fetchAll();
    } catch (Throwable $e) {
        $errors[] = 'Impossible de charger les formations.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Formations</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css?v=<?php echo filemtime(__DIR__ . '/../assets/style.css'); ?>">
    <link rel="stylesheet" href="admin.css?v=<?php echo filemtime(__DIR__ . '/admin.css'); ?>">
</head>
<body class="admin-body">
    <div class="admin-shell">
        <div class="admin-card">
            <h1>Dashboard formations</h1>
            <p><a class="admin-link" href="../index.php">Retour au site</a></p>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="admin-alert error">
                <?php echo htmlspecialchars(implode(' ', $errors)); ?>
            </div>
        <?php endif; ?>

        <?php if ($success !== ''): ?>
            <div class="admin-alert success">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <?php if (!isLoggedIn()): ?>
            <div class="admin-card">
                <h2>Connexion</h2>
                <form class="admin-form" method="post">
                    <input type="hidden" name="action" value="login">
                    <label>
                        Nom utilisateur
                        <input type="text" name="username" required>
                    </label>
                    <label>
                        Mot de passe
                        <input type="password" name="password" required>
                    </label>
                    <div class="admin-actions">
                        <button class="btn btn-primary" type="submit">Se connecter</button>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <div class="admin-card">
                <form class="admin-inline-form" method="post">
                    <input type="hidden" name="action" value="logout">
                    <button class="btn btn-dark" type="submit">Se deconnecter</button>
                </form>
            </div>

            <div class="admin-card">
                <h2>Ajouter une formation</h2>
                <form class="admin-form" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="add">
                    <div class="admin-grid">
                        <label>
                            Titre
                            <input type="text" name="title" required>
                        </label>
                        <label>
                            Categorie
                            <input type="text" name="category" placeholder="Marketing, Business, IA" required>
                        </label>
                        <label>
                            Prix (CFA)
                            <input type="number" name="price" min="0" required>
                        </label>
                        <label>
                            Prix promo (CFA)
                            <input type="number" name="promo_price" min="0">
                        </label>
                        <label>
                            Date debut
                            <input type="date" name="start_date" required>
                        </label>
                        <label>
                            Lieu
                            <input type="text" name="location" required>
                        </label>
                        <label>
                            Nombre de places
                            <input type="number" name="seats" min="1" required>
                        </label>
                        <label>
                            Image (URL)
                            <input type="url" name="image_url" placeholder="https://...">
                        </label>
                        <label>
                            Image (upload)
                            <input type="file" name="image_file" accept="image/jpeg,image/png,image/webp">
                        </label>
                    </div>
                    <label>
                        Description
                        <textarea name="description" required></textarea>
                    </label>
                    <div class="admin-actions">
                        <button class="btn btn-primary" type="submit">Ajouter</button>
                    </div>
                </form>
            </div>

            <div class="admin-card">
                <h2>Formations existantes</h2>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Categorie</th>
                            <th>Date</th>
                            <th>Prix</th>
                            <th>Places</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($trainings)): ?>
                            <tr>
                                <td colspan="6">Aucune formation enregistree.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($trainings as $training): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($training['title']); ?></td>
                                    <td><span class="admin-badge"><?php echo htmlspecialchars($training['category']); ?></span></td>
                                    <td><?php echo htmlspecialchars($training['start_date']); ?></td>
                                    <td>
                                        <?php
                                        $displayPrice = $training['promo_price'] ? $training['promo_price'] : $training['price'];
                                        echo number_format((int) $displayPrice, 0, ',', ' ');
                                        ?>
                                    </td>
                                    <td><?php echo (int) $training['seats']; ?></td>
                                    <td>
                                        <form class="admin-inline-form" method="post" onsubmit="return confirm('Supprimer cette formation ?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="training_id" value="<?php echo (int) $training['id']; ?>">
                                            <button class="btn btn-dark" type="submit">Supprimer</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
