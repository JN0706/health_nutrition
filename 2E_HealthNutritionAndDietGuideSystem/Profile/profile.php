<?php
require_once "../main/config.php";
require '../main/authentication.php';

if (!isset($_SESSION['user'])) {
  header("Location: ../login/login.html");
  exit;
}

$user = $_SESSION['user'];
$message = $_GET['success'] ?? ($_GET['error'] ?? '');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Profile</title>
  <link rel="icon" href="../image/logo.png">
  <link rel="stylesheet" href="profile.css?v=<?= time() ?>">
  <link rel="stylesheet" href="../header&nav/nav.css?v=<?= time() ?>">
  
  <script src="../header&nav/nav.js?v=<?= time() ?>" defer></script>
  <script defer src="profile.js?v=<?= time() ?>"></script>
</head>

<body>
  <div class="page-wrapper">
    <header class="main-header">
      <div class="nav-container">
        <div class="logo">Health++</div>
	
        <ul class="nav-links" id="navLinks">
	  <li><a href="#" class="protected" data-destination="../main/index.php">Home</a></li>
          <li><a href="#" class="protected" data-destination="../profile/profile.php"> Profile</a></li>
          <li><a href="#" class="protected" data-destination="../nutrition_tracker/index.php">Nutrition</a></li>
          <li><a href="#" class="protected" data-destination="../meal_plan/index.php">Meal Plans</a></li>
          <li><a href="#" class="protected" data-destination="../health_fitness/index.php">Fitness</a></li>
          <li><a href="../bmi/index.php">BMI Calculator</a></li>
        </ul>

        <div class="right-session">
          <nav class="top-nav">
            <?php if ($loggedIn): ?>
              <span class="welcome">Hi, <?= htmlspecialchars($_SESSION['user']['username']) ?> 👋</span>
              <a href="../login/logout.php" class="nav-link">Logout</a>
            <?php else: ?>
              <a href="../login/login.html" class="nav-link">Login</a>
              <a href="../registration/registration.html" class="nav-link">Register</a>
            <?php endif; ?>
          </nav>
        </div>
        <button class="hamburger" id="hamburger">&#9776;</button>
      </div>
    </header>

    <div class="profile-container">
      <?php if (isset($_GET['success'])): ?>
        <div class="success-message"✔️ Profile updated successfully.</div>
      <?php elseif (isset($_GET['error'])): ?>
        <div class="error-message"><?= htmlspecialchars($_GET['error']) ?></div>
      <?php endif; ?>

      <!-- Username (read-only) -->
      <div class="field-group">
        <label>Username:</label>
        <span><?= htmlspecialchars($user['username']) ?></span>
      </div>

      <!-- Editable fields -->
      <?php
      $fields = [
        "name" => ["FirstName", "LastName"],
        "email" => ["email"],
        "phone" => ["phone"],
        "dob" => ["DoB"],
        "height" => ["height"],
        "weight" => ["weight"]
      ];

      foreach ($fields as $field => $keys) {
        $displayValue = implode(" ", array_map(fn($k) => htmlspecialchars($user[$k]), $keys));
        echo "<div class=\"field-group\" id=\"{$field}Section\">";
        echo "  <label>" . ucfirst($field) . ":</label>";
        echo "  <span id=\"{$field}Display\">$displayValue</span>";
        echo "  <button onclick=\"editField('$field')\">✏️</button>";
        echo "  <form action=\"update_profile.php\" method=\"POST\" id=\"{$field}Edit\" class=\"edit-form\">";
        echo "    <input type=\"hidden\" name=\"field\" value=\"$field\">";

        foreach ($keys as $key) {
          $value = htmlspecialchars($user[$key]);
          $type = match($key) {
            'DoB' => 'date',
            'email' => 'email',
            'phone' => 'tel',
            'height', 'weight' => 'number',
            default => 'text'
          };
          echo "<input type=\"$type\" name=\"$key\" value=\"$value\" required>";
        }
        echo "    <button type=\"submit\">Save</button>";
        echo "  </form>";
        echo "</div>";
      }
      ?>

      <!-- Change Password -->
      <div class="change-password-container">
        <a href="change_password.php" class="change-password-link">Change Password</a>
      </div>

      <!-- Logout -->
      <div class="logout-container">
        <a href="../login/logout.php" class="btn-logout">Logout</a>
      </div>
    </div>
  </div>

  <footer>
    <p>&copy; 2025 Health++. All rights reserved.</p>
  </footer>
</body>
</html>