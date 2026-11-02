<?php
session_start();
require_once "../main/config.php";

if (!isset($_SESSION['user'])) {
  header("Location: ../login/login.html");
  exit;
}

$conn = openConnection();
$username = $_SESSION['user']['username'];

$newPassword = $_POST['new_password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';

if (strlen($newPassword) < 8 || $newPassword !== $confirmPassword) {
  header("Location: change_password.php?error=Passwords must match and be at least 8 characters.");
  exit;
}

$hashed = password_hash($newPassword, PASSWORD_DEFAULT);

$stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
$stmt->execute([$hashed, $username]);

session_unset();
session_destroy();

header("Location: ../login/login.html?message=Password updated. Please log in again.");
exit;
