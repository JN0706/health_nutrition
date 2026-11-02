<?php
$host = 'localhost';
$db   = 'health_db';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';
$port = 3307;

header('Content-Type: application/json');

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("❌ DB connection failed: " . $e->getMessage());
}
?>


