<?php
function openConnection() {
  try {
    $user = "root";
    $pass = "";
    $port = 3307;

    $dsn = "mysql:host=localhost;port=$port;dbname=health_db;charset=utf8";

    $options = [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES => false,
    ];

    return new PDO($dsn, $user, $pass, $options);
  } catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
  }
}
?>