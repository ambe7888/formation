<?php
$ftp_host = 'successbusinessweb.com';
$ftp_user = 'successb';
$ftp_pass = '3Q60Zu7ynl';
$remote_base = '/formation.successbusinessweb.com';

$conn = ftp_connect($ftp_host, 21, 30);
ftp_login($conn, $ftp_user, $ftp_pass);
ftp_pasv($conn, true);

// List files in root to find cpanel yaml
$files = ftp_nlist($conn, $remote_base);
echo "=== Fichiers dans $remote_base ===\n";
foreach ($files as $f) {
    $name = basename($f);
    if (stripos($name, 'cpanel') !== false || stripos($name, '.yml') !== false || stripos($name, '.yaml') !== false) {
        echo ">>> TROUVÉ: $f\n";
        // Try to read it
        $tmp = tempnam(sys_get_temp_dir(), 'cpanel');
        if (ftp_get($conn, $tmp, $f, FTP_ASCII)) {
            echo "--- Contenu ---\n";
            echo file_get_contents($tmp);
            echo "\n--- Fin ---\n";
        }
        unlink($tmp);
    }
}

// Also check root account directory
$root_files = ftp_nlist($conn, '/');
echo "\n=== Fichiers dans / (racine) ===\n";
foreach ($root_files as $f) {
    $name = basename($f);
    if (stripos($name, 'cpanel') !== false || stripos($name, '.yml') !== false || stripos($name, '.yaml') !== false) {
        echo ">>> TROUVÉ: $f\n";
        $tmp = tempnam(sys_get_temp_dir(), 'cpanel');
        if (ftp_get($conn, $tmp, $f, FTP_ASCII)) {
            echo "--- Contenu ---\n";
            echo file_get_contents($tmp);
            echo "\n--- Fin ---\n";
        }
        unlink($tmp);
    }
}

// List all in remote_base for reference
echo "\n=== Liste complète $remote_base ===\n";
foreach ($files as $f) {
    echo basename($f) . "\n";
}

ftp_close($conn);
