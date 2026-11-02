<?php
require_once "../main/config.php";
$conn = openConnection();
session_start();

// ✅ Handle username availability check first
if (isset($_GET['username'])) {
    $username = trim($_GET['username']);
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $exists = $stmt->fetchColumn();

    header('Content-Type: application/json');
    echo json_encode(["available" => $exists == 0]);
    exit;
}

// ✅ Only process registration if it's a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST["FirstName"] ?? '';
    $lastName = $_POST["LastName"] ?? '';
    $username = $_POST["username"] ?? '';
    $email = $_POST["email"] ?? '';
    $phone = $_POST["phone"] ?? '';
    $dob = $_POST["DoB"] ?? '';
    $height = $_POST["height"] ?? '';
    $weight = $_POST["weight"] ?? '';
    $password = $_POST["password"] ?? '';
    $confirm = $_POST["confirm_password"] ?? '';

    // Password Validation
    if ($password !== $confirm || strlen($password) < 8) {
        echo "<script>alert('Password mismatch or too short'); window.history.back();</script>";
        exit;
    }

    // Email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format.'); window.history.back();</script>";
        exit;
    }

    // Malaysian phone number format
    if (!preg_match('/^(\+?60|0)1[0-46-9][0-9]{7,8}$/', $phone)) {
        echo "<script>alert('Invalid Malaysian phone number.'); window.history.back();</script>";
        exit;
    }

    // DoB format and logic
    $parts = explode('-', $dob);
    if (count($parts) !== 3 || !checkdate((int)$parts[1], (int)$parts[2], (int)$parts[0])) {
        echo "<script>alert('Invalid date of birth.'); window.history.back();</script>";
        exit;
    }

    // Uniqueness checks
    $checks = [
        ['username', $username, 'Username already taken.'],
        ['email', $email, 'Email already registered.'],
        ['phone', $phone, 'Phone number already registered.']
    ];

    foreach ($checks as [$field, $value, $error]) {
        $stmt = $conn->prepare("SELECT 1 FROM users WHERE $field = ?");
        $stmt->execute([$value]);
        if ($stmt->rowCount() > 0) {
            echo "<script>alert('$error'); window.history.back();</script>";
            exit;
        }
    }

    // Hash & Insert Data
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (FirstName, LastName, username, email, phone, DoB, height, weight, password)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$firstName, $lastName, $username, $email, $phone, $dob, $height, $weight, $hashed]);

    header("Location: ../login/login.html?message=Registration successful");
    exit;
}
?>
