<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/db.php';

$message = '';
$error = '';

try {
    $pdo = getDbConnection();
    $pdo->exec(
        'CREATE TABLE IF NOT EXISTS trainings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(160) NOT NULL,
            category VARCHAR(120) NOT NULL,
            description TEXT NOT NULL,
            start_date DATE NOT NULL,
            location VARCHAR(160) NOT NULL,
            price INT NOT NULL,
            promo_price INT NULL,
            seats INT NOT NULL,
            image_url VARCHAR(255) NULL,
            is_active TINYINT(1) NOT NULL DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
    );

    $message = 'Table trainings creee avec succes.';
} catch (Throwable $e) {
    $error = 'Erreur de connexion ou creation: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup BDD</title>
</head>
<body>
    <h1>Configuration BDD</h1>
    <?php if ($message !== ''): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
        <p><a href="index.php">Aller a la dashboard</a></p>
    <?php else: ?>
        <p><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
</body>
</html>
