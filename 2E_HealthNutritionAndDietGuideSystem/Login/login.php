<?php
require_once "../main/config.php";
session_start();

$username = $_POST["username"];
$password = $_POST["password"];

$conn = openConnection();
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user["password"])) {
  $_SESSION["user"] = $user;
  header("Location: ../main/index.php");
} else {
  echo "<script>alert('Invalid credentials'); window.location='login.html';</script>";
}