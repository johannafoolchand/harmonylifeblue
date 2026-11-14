<?php
declare(strict_types=1);

// Database credentials are supplied through environment variables.
// Never commit real credentials to the repository.
$host = getenv('DB_HOST') ?: '127.0.0.1';
$user = getenv('DB_USER') ?: '';
$password = getenv('DB_PASSWORD') ?: '';
$database = getenv('DB_NAME') ?: 'bluelifeharmony';

if ($user === '' || $password === '') {
    http_response_code(500);
    error_log('Database configuration is incomplete. Set DB_USER and DB_PASSWORD.');
    exit('Service temporarily unavailable.');
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $lien = new mysqli($host, $user, $password, $database);
    $lien->set_charset('utf8mb4');
} catch (mysqli_sql_exception $exception) {
    http_response_code(500);
    error_log('Database connection failed: ' . $exception->getMessage());
    exit('Service temporarily unavailable.');
}
