

<?php
header('Content-Type: application/json');
session_start();
require 'db.php';

date_default_timezone_set('Asia/Kuala_Lumpur');
$now_local = date('Y-m-d H:i:s');

$data = json_decode(file_get_contents('php://input'), true);

$username = $_SESSION['user']['username'] ?? $data['username'] ?? null;
$meal_name = trim($data['meal_name'] ?? '');
$calories = (int) ($data['calories'] ?? 0);
$protein = (int) ($data['protein'] ?? 0);
$carbs = (int) ($data['carbs'] ?? 0);
$fat = (int) ($data['fat'] ?? 0);
$vitaminC = (int) ($data['vitaminC'] ?? 0);
$created_at = date('Y-m-d H:i:s');

if (!$username || !$meal_name) {
    echo json_encode(['success' => false, 'error' => 'Missing required fields.']);
    exit;
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO meal_tracker (username, meal_name, calories, protein, carbs, fat, vitaminC, created_at)
        VALUES (:username, :meal_name, :calories, :protein, :carbs, :fat, :vitaminC, :created_at)
        ");
    $stmt->execute([
        ':username' => $username,
        ':meal_name' => $meal_name,
        ':calories' => $calories,
        ':protein' => $protein,
        ':carbs' => $carbs,
        ':fat' => $fat,
        ':vitaminC' => $vitaminC,
        ':created_at' => $created_at
    ]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    error_log("AddMeal Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Database error occurred.']);
}
