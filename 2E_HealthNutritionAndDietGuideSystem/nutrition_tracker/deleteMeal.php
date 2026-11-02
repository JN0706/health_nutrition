<?php
header('Content-Type: application/json');
require 'db.php';

$input = json_decode(file_get_contents("php://input"), true);
file_put_contents('debug.log', print_r($input, true)); //Debug
$response = [];

if (!isset($input['meal_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing meal ID']);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM meal_tracker WHERE meal_id = ?");
    $success = $stmt->execute([$input['meal_id']]);

    $response = $success
        ? ['status' => 'success', 'message' => 'Meal deleted']
        : ['status' => 'error', 'message' => 'Failed to delete meal'];
} catch (PDOException $e) {
    error_log("DeleteMeal Error: " . $e->getMessage());
    $response = ['status' => 'error', 'message' => 'Database error'];
}

echo json_encode($response);
?>