<?php
header('Content-Type: application/json');
require 'db.php';

$username = $_GET['username'];

try {
    $stmt = $pdo->prepare("
        SELECT DATE(created_at) AS meal_date,
               SUM(calories) AS calories,
               SUM(protein) AS protein
        FROM meal_tracker
        WHERE username = ?
        GROUP BY DATE(created_at)
        ORDER BY meal_date ASC
    ");
    $stmt->execute([$username]);
    $history = $stmt->fetchAll();

    echo json_encode($history);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
