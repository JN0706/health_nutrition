<?php
session_start();

// Set the correct response header for JSON
header('Content-Type: application/json');

// Check if user session exists
$response = [
  "loggedIn" => isset($_SESSION['user']) && !empty($_SESSION['user'])
];

// Output the JSON
echo json_encode($response);