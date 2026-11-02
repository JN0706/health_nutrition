<?php
require 'config.php';
require '../main/authentication.php';
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Meal Planner</title>

  <link rel="icon" href="../image/logo.png">
  <link rel="stylesheet" href="meal_style.css?v=<?= time() ?>">
  <link rel="stylesheet" href="../header&nav/nav.css?v=<?= time() ?>">

  <script src="meal_script.js?v=<?= time() ?>" defer></script>
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
          <li><a href="../bmi/index.php">BMI Caculator</a></li>
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
      <h1>Meal Planner</h1>
    </div>

    <div class="container">
      <div class="form-section">
        <h2>Generate Your Meal Plan</h2>
        <form id="mealForm" method="POST" action="process.php">
          <label>Gender:</label>
          <select name="gender" required>
            <option value="male">Male</option>
            <option value="female">Female</option>
          </select>

          <label>Age:</label>
          <input type="number" name="age" required>

          <label>Weight (kg):</label>
          <input type="number" name="weight" required>

          <label>Height (cm):</label>
          <input type="number" name="height" required>

          <label>Food Preference:</label>
          <select name="preference" required>
            <option value="vegetarian">Vegetarian</option>
            <option value="non-vegetarian">Non-Vegetarian</option>
          </select>

          <label>Activity Level:</label>
          <select name="activity" required>
            <option value="sedentary">Sedentary (Little or No exercise)</option>
            <option value="light">Lightly Active (Light exercise 1-3 days/week)</option>
            <option value="moderate">Moderately Active (Moderate exercise 3-5 days/week)</option>
            <option value="active">Very Active (Hard exercise 6-7 days/week)</option>
          </select>

          <label>Goal:</label>
          <select name="goal" required>
            <option value="lose">Lose Weight</option>
            <option value="maintain">Maintain Weight</option>
            <option value="gain">Gain Weight</option>
          </select>

          <input type="submit" value="Get Meal Plan">
        </form>
      </div>

      <?php if (isset($_SESSION['calories']) && isset($_SESSION['meals'])): ?>
        <div class="summary-section<?= (isset($_SESSION['calories']) && isset($_SESSION['meals'])) ? ' show' : '' ?>">
          <h2>Your Daily Calorie Target: <?= $_SESSION['calories'] ?> kcal</h2>

          <?php if (isset($_SESSION['feedback'])): ?>
            <p class="feedback-message"><strong>Feedback:</strong> <?= htmlspecialchars($_SESSION['feedback']) ?></p>
          <?php endif; ?>

          <h3>Suggested Meals:</h3>
          <ul>
            <?php foreach ($_SESSION['meals'] as $meal): ?>
              <li><?= htmlspecialchars($meal) ?></li>
            <?php endforeach; ?>
          </ul>

          <?php
            unset($_SESSION['calories']);
            unset($_SESSION['meals']);
            unset($_SESSION['feedback']);
          ?>
        </div>
      <?php endif; ?>
    </div>

    <div class="container">
      <?php
      // Fetch all previous meal plans for the logged-in user, ordered by most recent
      $stmt = $conn->prepare("SELECT * FROM meal_plan WHERE username = :username ORDER BY created_at DESC");
      $stmt->execute([':username' => $username]);
      $plans = $stmt->fetchAll();
      ?>

      <?php if ($plans): ?>
	<h2 class="meal-plan-title">Your Meal Plan History</h2>
	<div class="meal-plan-wrapper">
          <?php foreach ($plans as $plan): ?>
            <div class="meal-plan-entry">
              <form method="POST" action="delete_plan.php" onsubmit="return confirm('Delete this meal plan?');" class="delete-form">
                <input type="hidden" name="created_at" value="<?= $plan['created_at'] ?>">
                <button type="submit" class="delete-btn" title="Delete this plan">
                  <img src="../image/delete_icon.png" alt="Delete" class="delete-icon">
		</button>

              </form>

              <div class="meal-plan-header">
		<h3><?= date('F j, Y, g:i a', strtotime($plan['created_at'])) ?></h3>
		<p><strong>Goal:</strong> <?= htmlspecialchars($plan['goal']) ?></p>
                <span class="chevron">&#9660;</span>
	      </div>

	      <div class="meal-plan-details hidden">
                <ul>
                  <li><strong>Gender:</strong> <?= htmlspecialchars($plan['gender']) ?></li>
                  <li><strong>Age:</strong> <?= $plan['age'] ?></li>
                  <li><strong>Weight:</strong> <?= $plan['weight'] ?> kg</li>
                  <li><strong>Height:</strong> <?= $plan['height'] ?> cm</li>
                  <li><strong>Food Preference:</strong> <?= htmlspecialchars($plan['preference']) ?></li>
                  <li><strong>Activity Level:</strong> <?= htmlspecialchars($plan['activity']) ?></li>
                  <li><strong>Goal:</strong> <?= htmlspecialchars($plan['goal']) ?></li>
                  <li><strong>Total Daily Energy Expenditure:</strong> <?= round($plan['calories']) ?> kcal</li>

                  <?php if (!empty($plan['feedback'])): ?>
                    <li><strong>Feedback:</strong> <?= htmlspecialchars($plan['feedback']) ?></li>
                  <?php endif; ?>
		  <?php if (!empty($plan['suggestion'])): ?>
		    <li><strong>Suggested Meals:</strong>
		      <ul>
		        <?php foreach (explode(',', $plan['suggestion']) as $suggestion): ?>
		          <li><?= htmlspecialchars(trim($suggestion)) ?></li>
		        <?php endforeach; ?>
		      </ul>
		    </li>
		  <?php endif; ?>
                </ul>
	      </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <p>No previous meal plans found.</p>
      <?php endif; ?>
    </div>

    <footer>
      <p>&copy; 2025 Health++. All rights reserved.</p>
    </footer>
  </div>

</body>
</html>
