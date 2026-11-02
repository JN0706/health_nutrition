<?php
header('Content-Type: application/json');
require 'db.php';

$username = $_GET['username'];
$date = $_GET['date'];

try {
    $stmt = $pdo->prepare("SELECT * FROM meal_tracker WHERE username = :username AND DATE(created_at) = :date");
    $stmt->execute(['username' => $username, 'date' => $date]);
    $meals = $stmt->fetchAll();
    echo json_encode($meals);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>