<?php
require '../main/authentication.php';
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['created_at'])) {
    $username = $_SESSION['user']['username'];
    $createdAt = $_POST['created_at'];

    $stmt = $conn->prepare("DELETE FROM meal_plan WHERE username = :username AND created_at = :created_at");
    $stmt->execute([
        ':username' => $username,
        ':created_at' => $createdAt
    ]);
}

header("Location: index.php");
exit();
?>
