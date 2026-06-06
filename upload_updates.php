<?php
/**
 * Script de déploiement FTP - Upload des fichiers modifiés vers le serveur
 */

set_time_limit(300);
ini_set('display_errors', 1);
error_reporting(E_ALL);

$ftp_host = 'successbusinessweb.com';
$ftp_user = 'successb';
$ftp_pass = '3Q60Zu7ynl';
$remote_base = '/formation.successbusinessweb.com';
$local_base = __DIR__;

// Liste de tous les fichiers à transférer
$files = [
    // Controllers
    'app/Http/Controllers/HomeController.php',
    'app/Http/Controllers/Controller.php',
    'app/Http/Controllers/AdminAuthController.php',
    'app/Http/Controllers/AdminController.php',
    'app/Http/Controllers/StudentAuthController.php',
    'app/Http/Controllers/StudentDashboardController.php',

    // Models
    'app/Models/Bundle.php',
    'app/Models/Category.php',
    'app/Models/Client.php',
    'app/Models/Payment.php',
    'app/Models/Registration.php',
    'app/Models/Skill.php',
    'app/Models/Training.php',
    'app/Models/TrainingResource.php',

    // Views - Main
    'resources/views/home.blade.php',
    'resources/views/bundle_details.blade.php',
    'resources/views/program_page.blade.php',
    'resources/views/skills_page.blade.php',
    'resources/views/training_details.blade.php',
    'resources/views/trainings_page.blade.php',

    // Views - Admin
    'resources/views/admin/dashboard.blade.php',
    'resources/views/admin/layout.blade.php',
    'resources/views/admin/login.blade.php',
    'resources/views/admin/payments/index.blade.php',
    'resources/views/admin/registrations/index.blade.php',
    'resources/views/admin/trainings/create.blade.php',
    'resources/views/admin/trainings/edit.blade.php',
    'resources/views/admin/trainings/index.blade.php',
    'resources/views/admin/categories/create.blade.php',
    'resources/views/admin/categories/edit.blade.php',
    'resources/views/admin/categories/index.blade.php',

    // Views - Auth
    'resources/views/auth/',

    // Views - Admin bundles & skills
    'resources/views/admin/bundles/',
    'resources/views/admin/skills/',

    // Views - Student
    'resources/views/student/',

    // Public assets
    'public/assets/style.css',
    'public/assets/script.js',
    'public/assets/images/default-training.svg',
    'public/assets/images/ai-automation.svg',
    'public/assets/images/ecommerce.svg',
    'public/assets/images/sales-funnel.svg',
    'public/assets/images/visibility.svg',
    'public/assets/images/bundles/',
    'public/index.php',

    // Routes
    'routes/web.php',

    // Config
    'config/auth.php',

    // Migrations
    'database/migrations/',
    'database/seeders/MigrateLegacyDataSeeder.php',
    'database/seeders/TrainingSeeder.php',

    // Root files
    '.gitignore',
    '.htaccess',
];

echo "<h1>🚀 Déploiement FTP en cours...</h1>";
echo "<pre>";

// Connect
echo "Connexion FTP à $ftp_host...\n";
$conn = ftp_connect($ftp_host, 21, 30);
if (!$conn) {
    die("❌ Impossible de se connecter au serveur FTP\n");
}

if (!ftp_login($conn, $ftp_user, $ftp_pass)) {
    die("❌ Échec de l'authentification FTP\n");
}

ftp_pasv($conn, true);
echo "✅ Connecté au serveur FTP\n\n";

$success = 0;
$errors = 0;
$skipped = 0;

/**
 * Upload a single file
 */
function uploadFile($conn, $local_path, $remote_path) {
    // Ensure remote directory exists
    $remote_dir = dirname($remote_path);
    @ftp_mkdir($conn, $remote_dir);
    
    // Create all parent directories
    $parts = explode('/', trim($remote_dir, '/'));
    $path = '';
    foreach ($parts as $part) {
        $path .= '/' . $part;
        @ftp_mkdir($conn, $path);
    }

    if (ftp_put($conn, $remote_path, $local_path, FTP_BINARY)) {
        return true;
    }
    return false;
}

/**
 * Upload a directory recursively
 */
function uploadDirectory($conn, $local_dir, $remote_dir, &$success, &$errors) {
    if (!is_dir($local_dir)) {
        echo "⚠️  Dossier inexistant: $local_dir\n";
        return;
    }
    
    @ftp_mkdir($conn, $remote_dir);
    
    $items = scandir($local_dir);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;
        
        $local_path = $local_dir . '/' . $item;
        $remote_path = $remote_dir . '/' . $item;
        
        if (is_dir($local_path)) {
            uploadDirectory($conn, $local_path, $remote_path, $success, $errors);
        } else {
            if (uploadFile($conn, $local_path, $remote_path)) {
                echo "  ✅ $item\n";
                $success++;
            } else {
                echo "  ❌ $item\n";
                $errors++;
            }
        }
    }
}

foreach ($files as $file) {
    $local_path = $local_base . '/' . str_replace('/', DIRECTORY_SEPARATOR, $file);
    $remote_path = $remote_base . '/' . $file;
    
    // If it's a directory (ends with /)
    if (substr($file, -1) === '/') {
        $dir = rtrim($file, '/');
        $local_dir = $local_base . '/' . str_replace('/', DIRECTORY_SEPARATOR, $dir);
        $remote_dir = $remote_base . '/' . $dir;
        
        if (is_dir($local_dir)) {
            echo "📁 Dossier: $dir/\n";
            uploadDirectory($conn, $local_dir, $remote_dir, $success, $errors);
        } else {
            echo "⚠️  Dossier inexistant localement: $dir/\n";
            $skipped++;
        }
        continue;
    }
    
    // Regular file
    if (!file_exists($local_path)) {
        echo "⚠️  Fichier inexistant: $file\n";
        $skipped++;
        continue;
    }
    
    if (uploadFile($conn, $local_path, $remote_path)) {
        echo "✅ $file\n";
        $success++;
    } else {
        echo "❌ ERREUR: $file\n";
        $errors++;
    }
}

ftp_close($conn);

echo "\n========================================\n";
echo "📊 Résultat du déploiement:\n";
echo "  ✅ Réussis: $success\n";
echo "  ❌ Erreurs: $errors\n";
echo "  ⚠️  Ignorés: $skipped\n";
echo "========================================\n";

if ($errors === 0) {
    echo "\n🎉 Déploiement terminé avec succès!\n";
} else {
    echo "\n⚠️  Déploiement terminé avec $errors erreur(s).\n";
}

echo "</pre>";
