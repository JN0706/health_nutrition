<?php
require_once 'bmi.php'; // Handles session, DB connection, and logic

$loggedIn = isset($_SESSION['user']);
$username = $loggedIn ? $_SESSION['user']['username'] : null;
$bmiHistory = $loggedIn ? getUserBmiHistory($username) : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>BMI Calculator</title>
  <link rel="icon" href="../image/logo.png">
  <link rel="stylesheet" href="../header&nav/nav.css?v=<?= time() ?>">
  <link rel="stylesheet" href="bmi.css?v=<?= time() ?>">
  <script src="../header&nav/nav.js?v=<?= time() ?>" defer></script>
</head>

<body>
  <div class="page-wrapper">
    <header class="main-header">
      <div class="nav-container">
        <div class="logo">Health++</div>
        <ul class="nav-links" id="navLinks">
          <li><a href="#" class="protected" data-destination="../main/index.php">Home</a></li>
          <li><a href="#" class="protected" data-destination="../profile/profile.php">Profile</a></li>
          <li><a href="#" class="protected" data-destination="../nutrition_tracker/index.php">Nutrition</a></li>
          <li><a href="#" class="protected" data-destination="../meal_plan/index.php">Meal Plans</a></li>
          <li><a href="#" class="protected" data-destination="../health_fitness/index.php">Fitness</a></li>
          <li><a href="../bmi/index.php">BMI Calculator</a></li>
        </ul>
        <div class="right-session">
          <nav class="top-nav">
            <?php if ($loggedIn): ?>
              <span class="welcome">Hi, <?= htmlspecialchars($username) ?> 👋</span>
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

    <div class="bmi-container">
      <h2>BMI Calculator</h2>

      <label for="weight">Weight (kg):</label>
      <input type="number" id="weight" placeholder="e.g. 70" required>

      <label for="height">Height (cm):</label>
      <input type="number" id="height" placeholder="e.g. 170" required>

      <button onclick="calculateBMI()">Calculate BMI</button>

      <div class="result" id="result"></div>

      <?php if ($loggedIn): ?>
        <form id="saveForm" method="POST" action="bmi.php" style="display: none;">
          <input type="hidden" name="bmi" id="bmiValue">
          <input type="hidden" name="category" id="bmiCategory">
          <button type="submit" class="save-btn">Save Record</button>
        </form>
      <?php else: ?>
        <form id="tempSaveForm" method="POST" action="bmi.php" style="display: none;">
          <input type="hidden" name="bmi" id="tempBmiValue">
          <input type="hidden" name="category" id="tempBmiCategory">
        </form>
        <div id="savePrompt" style="display: none; text-align: center; margin-top: 20px;">
          <p>Wanna save your records?</p>
          <button onclick="document.getElementById('tempSaveForm').submit()">Login</button>
          <button onclick="window.location.href='../registration/registration.html'">Register</button>
        </div>
      <?php endif; ?>

      <?php if ($loggedIn && $bmiHistory): ?>
        <div class="bmi-history">
          <h3>Your BMI History</h3>
          <table>
            <thead>
              <tr>
                <th>Date</th>
                <th>BMI</th>
                <th>Category</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($bmiHistory as $row): ?>
                <tr>
                  <td><?= htmlspecialchars($row['recorded_at']) ?></td>
                  <td><?= htmlspecialchars($row['bmi_result']) ?></td>
                  <td><?= htmlspecialchars($row['category']) ?></td>
                  <td>
                    <form method="POST" action="index.php" onsubmit="return confirm('Are you sure you want to delete this record?');">
                      <input type="hidden" name="delete_recorded_at" value="<?= htmlspecialchars($row['recorded_at']) ?>">
                      <button type="submit" class="delete-icon-btn" title="Delete">
                        <img src="../image/delete_icon.png" alt="Delete" class="delete-icon">
		      </button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <footer>
    <p>&copy; 2025 Health++. All rights reserved.</p>
  </footer>

  <script>
    const loggedIn = <?= $loggedIn ? 'true' : 'false' ?>;
  </script>
  <script src="bmi.js"></script>
</body>
</html>
