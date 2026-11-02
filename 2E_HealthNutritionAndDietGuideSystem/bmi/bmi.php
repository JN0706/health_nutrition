<?php
session_start();
require_once '../main/config.php';

$pdo = openConnection();

$loggedIn = isset($_SESSION['user']);
$username = $loggedIn ? $_SESSION['user']['username'] : null;

// Handle BMI submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bmi']) && isset($_POST['category'])) {
  $bmi = floatval($_POST['bmi']);
  $category = $_POST['category'];

  if ($loggedIn) {
    $stmt = $pdo->prepare("INSERT INTO bmi_records (username, bmi_result, category) VALUES (?, ?, ?)");
    $stmt->execute([$username, $bmi, $category]);
    header("Location: index.php?saved=1");
    exit;
  } else {
    $_SESSION['pending_bmi'] = ['bmi' => $bmi, 'category' => $category];
    header("Location: ../login/login.html?redirect=../bmi/index.php");
    exit;
  }
}

// Handle deletion request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_recorded_at']) && $loggedIn) {
  $recordedAt = $_POST['delete_recorded_at'];

  $stmt = $pdo->prepare("DELETE FROM bmi_records WHERE username = ? AND recorded_at = ?");
  $stmt->execute([$username, $recordedAt]);

  header("Location: index.php?deleted=1");
  exit;
}

// Save pending BMI after login
if ($loggedIn && isset($_SESSION['pending_bmi'])) {
  $bmi = $_SESSION['pending_bmi']['bmi'];
  $category = $_SESSION['pending_bmi']['category'];

  $stmt = $pdo->prepare("INSERT INTO bmi_records (username, bmi_result, category) VALUES (?, ?, ?)");
  $stmt->execute([$username, $bmi, $category]);

  unset($_SESSION['pending_bmi']);
  header("Location: index.php?saved=1");
  exit;
}

// Helper function
function getUserBmiHistory($username) {
  $pdo = openConnection();
  $stmt = $pdo->prepare("SELECT bmi_result, category, recorded_at FROM bmi_records WHERE username = ? ORDER BY recorded_at DESC");
  $stmt->execute([$username]);
  return $stmt->fetchAll();
}
