<?php
session_start();
if (!isset($_SESSION['user']['username'])) {
    header("Location: ../login/login.html?redirect=" . urlencode($_SERVER['REQUEST_URI']));
    exit();
}
$loggedIn = isset($_SESSION['user']) && isset($_SESSION['user']['username']);

$username = $_SESSION['user']['username'];
