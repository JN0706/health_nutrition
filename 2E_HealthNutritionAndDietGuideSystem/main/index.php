<?php
session_start();
$loggedIn = isset($_SESSION['user']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Health++</title>
  <link rel="icon" href="../image/logo.png">
  <link rel="stylesheet" href="main_style.css?v=<?= time() ?>">
  <link rel="stylesheet" href="../header&nav/nav.css?v=<?= time() ?>">
  <link rel="stylesheet" href="../article/style.css?v=<?= time() ?>">
  
  <script src="../header&nav/nav.js?v=<?= time() ?>" defer></script>
  <script defer src="index.js?v=<?= time() ?>"></script>
</head>

<body>
  <header class="main-header">
    <div class="nav-container">
      <div class="logo">Health++</div>
	
      <ul class="nav-links" id="navLinks">
	<li><a href="#" class="protected" data-destination="index.php">Home</a></li>
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

  <div class="article-container">
    <div class="interactive-article">
      <div class="image-box" onclick="expandArticle(this)">
        <h3>Building a Health & Fitness Lifestyle</h3>
	<img src="../image/article/OIP.jpg" alt="" class="article-img">
        <p class="preview-text">Building a health and fitness lifestyle is not just about hitting the gym or eating salads...</p>
      </div>
      <div class="article-content">
	<div class="full-article">
	  <?php include '../article/health_lifestyle.php'; ?>
        </div>
      </div>
    </div>

    <div class="interactive-article">
      <div class="image-box" onclick="expandArticle(this)">
        <h3>Health</h3>
	<img src="../image/article/health.jpg" alt="" class="article-img">
        <p class="preview-text">Health is the foundation upon which everything else is built...</p>
      </div>
      <div class="article-content">
	<div class="full-article">
	  <?php include '../article/health.php'; ?>
        </div>
      </div>
    </div>

    <div class="interactive-article">
      <div class="image-box" onclick="expandArticle(this)">
        <h3>Empowering Yourself Through Fitness and Wellness</h3>
	<img src="../image/article/empowerment.jpg" alt="" class="article-img">
        <p class="preview-text">Empowering yourself through fitness and wellness is about taking back control of your life...</p>
      </div>
      <div class="article-content">
	<div class="full-article">
	  <?php include '../article/empowerment.php'; ?>
        </div>
      </div>
    </div>
  </div>
  
  <script>
    const isLoggedIn = <?= $loggedIn ? 'true' : 'false' ?>;
    function navigateOrRedirect(path) {
      if (!isLoggedIn) {
        window.location.href = `../login/login.html?redirect=${encodeURIComponent(path)}`;
      } else {
        window.location.href = path;
      }
    }

    function expandArticle(imageBox) { 
      imageBox.classList.toggle('shrink');
      const article = imageBox.closest('.interactive-article');
      article.classList.toggle('expanded');
      const content = article.querySelector('.article-content');
      content.classList.toggle('show');
    }
  </script>

  <footer>
    <p>&copy; 2025 Health++. All rights reserved.</p>
  </footer>
</body>
</html>