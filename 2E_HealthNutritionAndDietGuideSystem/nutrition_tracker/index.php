<?php
require '../main/authentication.php';
$username = $_SESSION['user']['username'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Meal Plan Tracker</title>

  <link rel="icon" href="../image/logo.png">
  <link rel="stylesheet" href="nutrition_tracker_style.css?v=<?= time() ?>" />
  <link rel="stylesheet" href="../header&nav/nav.css?v=<?= time() ?>">

  <script>
    window.loggedInUsername = "<?= htmlspecialchars($username) ?>";
  </script>

  <script src="../header&nav/nav.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="nutrition_tracker.js"></script>
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

    <div class="meal">
	<h1>Meal & Nutrition Tracker</h1>
    </div>

    <div class="container">
      <main>
        <section id="meal-input">
          <h2>Add Meal</h2>
          <form id="meal-form">
            <input type="text" id="meal-name" placeholder="Meal name" required />
            <input type="number" id="calories" placeholder="Calories (kcal)" step="0.01" required />
            <input type="number" id="protein" placeholder="Protein (g)" step="0.01" required />
            <input type="number" id="carbs" placeholder="Carbs (g)" step="0.01" required />
            <input type="number" id="fat" placeholder="Fat (g)" step="0.01" required />
            <input type="number" id="vitaminC" placeholder="Vitamin C (mg)" step="0.01" />
            <button type="submit">Add Meal</button>
          </form>
        </section>

        <section id="daily-summary">
          <h2>Today's Nutrient Summary</h2>
          <ul>
            <li>Calories: <span id="total-calories">0</span> kcal</li>
            <li>Protein: <span id="total-protein">0</span> g</li>
            <li>Carbs: <span id="total-carbs">0</span> g</li>
            <li>Fat: <span id="total-fat">0</span> g</li>
            <li>Vitamin C: <span id="total-vitaminC">0</span> mg</li>
          </ul>
        </section>

        <section id="breakdown">
          <h2>Nutrient Goal Breakdown</h2>
          <div class="breakdown-item"><label>Calories</label><progress id="bar-calories" max="2000" value="0"></progress><span id="percent-calories">0%</span></div>
          <div class="breakdown-item"><label>Protein</label><progress id="bar-protein" max="100" value="0"></progress><span id="percent-protein">0%</span></div>
          <div class="breakdown-item"><label>Carbs</label><progress id="bar-carbs" max="250" value="0"></progress><span id="percent-carbs">0%</span></div>
          <div class="breakdown-item"><label>Fat</label><progress id="bar-fat" max="70" value="0"></progress><span id="percent-fat">0%</span></div>
          <div class="breakdown-item"><label>Vitamin C</label><progress id="bar-vitaminC" max="90" value="0"></progress><span id="percent-vitaminC">0%</span></div>
        </section>

        <section id="meal-log">
          <h2>Meal Log</h2>
          <ul id="log-list"></ul>
        </section>

        <section id="progress-history">
          <h2>Diet History & Progress</h2>
          <canvas id="progressChart" width="400" height="200"></canvas>
        </section>
      </main>
    </div>

    <footer>
      <p>&copy; 2025 Health++. All rights reserved.</p>
    </footer>
  </div>

  
</body>
</html>
