<?php

declare(strict_types=1);

function getDbConfig(): array
{
    $config = require __DIR__ . '/config.php';

    return $config['db'] ?? [];
}

function getDbConnection(): PDO
{
    $config = getDbConfig();
    $charset = $config['charset'] ?? 'utf8mb4';
    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', $config['host'], $config['name'], $charset);

    return new PDO($dsn, $config['user'], $config['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
}
