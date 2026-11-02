<?php
require '../main/authentication.php';
require_once "../main/config.php";

$field = $_POST['field'] ?? '';
$conn = openConnection();

// Prevent invalid updates
$allowedFields = ['name', 'email', 'phone', 'dob', 'height', 'weight'];
if (!in_array($field, $allowedFields)) {
  header("Location: profile.php?error=Invalid update target.");
  exit;
}

// Process specific field group
switch ($field) {
  case 'name':
    $firstName = trim($_POST['FirstName'] ?? '');
    $lastName  = trim($_POST['LastName'] ?? '');
    if (!$firstName || !$lastName) {
      header("Location: profile.php?error=Name fields are required.");
      exit;
    }
    $stmt = $conn->prepare("UPDATE users SET FirstName = ?, LastName = ? WHERE username = ?");
    $stmt->execute([$firstName, $lastName, $username]);
    $_SESSION['user']['FirstName'] = $firstName;
    $_SESSION['user']['LastName'] = $lastName;
    break;

  case 'email':
    $email = trim($_POST['email'] ?? '');
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      header("Location: profile.php?error=Invalid email format.");
      exit;
    }
    $stmt = $conn->prepare("SELECT 1 FROM users WHERE email = ? AND username != ?");
    $stmt->execute([$email, $username]);
    if ($stmt->rowCount() > 0) {
      header("Location: profile.php?error=Email already in use.");
      exit;
    }
    $stmt = $conn->prepare("UPDATE users SET email = ? WHERE username = ?");
    $stmt->execute([$email, $username]);
    $_SESSION['user']['email'] = $email;
    break;

  case 'phone':
    $phone = trim($_POST['phone'] ?? '');
    if (!$phone) {
      header("Location: profile.php?error=Phone number required.");
      exit;
    }

    // Malaysian phone number regex validation
    if (!preg_match('/^(\+?60|0)1[0-46-9][0-9]{7,8}$/', $phone)) {
      header("Location: profile.php?error=Invalid Malaysian phone number format.");
      exit;
    }

    $stmt = $conn->prepare("SELECT 1 FROM users WHERE phone = ? AND username != ?");
    $stmt->execute([$phone, $username]);
    if ($stmt->rowCount() > 0) {
      header("Location: profile.php?error=Phone number already in use.");
      exit;
    }
    $stmt = $conn->prepare("UPDATE users SET phone = ? WHERE username = ?");
    $stmt->execute([$phone, $username]);
    $_SESSION['user']['phone'] = $phone;
    break;

  case 'dob':
    $dob = $_POST['DoB'] ?? '';
    if (!$dob) {
      header("Location: profile.php?error=Date of birth required.");
      exit;
    }

    // Validate format and logical date
    $parts = explode('-', $dob); // expecting YYYY-MM-DD
    if (count($parts) !== 3 || !checkdate((int)$parts[1], (int)$parts[2], (int)$parts[0])) {
      header("Location: profile.php?error=Invalid date format or non-existent date.");
      exit;
    }

    $stmt = $conn->prepare("UPDATE users SET DoB = ? WHERE username = ?");
    $stmt->execute([$dob, $username]);
    $_SESSION['user']['DoB'] = $dob;
    break;

  case 'height':
    $height = $_POST['height'] ?? '';
    if (!$height || !is_numeric($height)) {
      header("Location: profile.php?error=Invalid height.");
      exit;
    }
    $stmt = $conn->prepare("UPDATE users SET height = ? WHERE username = ?");
    $stmt->execute([$height, $username]);
    $_SESSION['user']['height'] = $height;
    break;

  case 'weight':
    $weight = $_POST['weight'] ?? '';
    if (!$weight || !is_numeric($weight)) {
      header("Location: profile.php?error=Invalid weight.");
      exit;
    }
    $stmt = $conn->prepare("UPDATE users SET weight = ? WHERE username = ?");
    $stmt->execute([$weight, $username]);
    $_SESSION['user']['weight'] = $weight;
    break;
}

header("Location: profile.php?success=1");
exit;