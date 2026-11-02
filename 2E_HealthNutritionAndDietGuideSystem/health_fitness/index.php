<?php
require '../main/authentication.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Health & Fitness Tracker</title>
  
  <link rel="icon" href="../image/logo.png">
  <link rel="stylesheet" href="health_fitness_style.css?v=<?= time() ?>" />
  <link rel="stylesheet" href="../header&nav/nav.css?v=<?= time() ?>">
  
  <script src="../header&nav/nav.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="script.js"></script>
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

    <div class="health">
      <h1>Health & Fitness Tracker</h1>
    </div>

    <div class="container">
      <nav class="section-nav">
        <ul>
          <li><a href="#" class="active" data-section="exercise">Exercise</a></li>
          <li><a href="#" data-section="water">Water Intake</a></li>
          <li><a href="#" data-section="sleep">Sleep</a></li>
        </ul>
      </nav>

      <main>
        <!-- Exercise Section -->
        <section id="exercise" class="active-section">
          <h2>Exercise Recommendations</h2>
          <div class="form-group">
            <label for="goal">Select Your Fitness Goal:</label>
            <select id="goal">
              <option value="weight_loss">Weight Loss</option>
              <option value="muscle_gain">Muscle Gain</option>
              <option value="endurance">Endurance</option>
              <option value="flexibility">Flexibility</option>
            </select>
            <button id="get-workout">Get Workout Plan</button>
          </div>
          <div id="workout-result" class="result-box"></div>
          <div class="history">
            <h3>Previous Workouts</h3>
            <ul id="workout-history"></ul>
          </div>
        </section>

        <!-- Water Intake Section -->
        <section id="water">
          <h2>Water Intake Tracker</h2>
          <div class="water-display">
            <div class="water-glass" id="water-glass">
              <div class="water-fill" id="water-fill"></div>
            </div>
            <div class="water-stats">
              <p>Today's intake: <span id="water-today">0</span> / <span id="water-target">2000</span> ml</p>
              <div class="form-group">
                <input type="number" id="water-amount" placeholder="Amount in ml" min="0" />
                <button id="add-water">Add Water</button>
              </div>
            </div>
          </div>
          <div class="history">
            <h3>Weekly Water Intake</h3>
            <canvas id="water-chart"></canvas>
          </div>
        </section>

        <!-- Sleep Section -->
        <section id="sleep">
          <h2>Sleep Monitoring</h2>
          <div class="form-group">
            <label for="sleep-hours">Last Night's Sleep (hours):</label>
            <input type="number" id="sleep-hours" min="0" max="24" step="0.5" />
            <button id="log-sleep">Log Sleep</button>
          </div>
          <div id="sleep-feedback" class="result-box"></div>
          <div class="history">
            <h3>Sleep History (Last 7 Days)</h3>
            <canvas id="sleep-chart"></canvas>
          </div>
          <div class="tips">
            <h3>Sleep Tips</h3>
            <ul id="sleep-tips"></ul>
          </div>
        </section>
      </main>
    </div>

    <footer>
      <p>&copy; 2025 Health++. All rights reserved.</p>
    </footer>
  </div>

  
</body>
</html>
