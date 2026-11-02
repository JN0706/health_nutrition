<?php
require '../main/authentication.php';
require_once "../main/config.php";

$message = $_GET['error'] ?? ($_GET['message'] ?? ''); // 🛠 Define it here
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Change Password | Health++</title>
  <link rel="stylesheet" href="change_password.css?v=<?= time() ?>">
</head>
<body>
  <div class="page-wrapper">
    <div class="password-card">
      <h2>Change Password</h2>

      <?php if ($message): ?>
        <div class="feedback"><?= htmlspecialchars($message) ?></div>
      <?php endif; ?>

      <form action="update_password.php" method="POST">
        <input type="password" name="new_password" placeholder="New Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <button type="submit">Update Password</button>
      </form>

      <p class="back-link"><a href="profile.php">← Back to Profile</a></p>
    </div>
  </div>

  <footer>
    <p>&copy; 2025 Health++. All rights reserved.</p>
  </footer>
</body>
</html>
