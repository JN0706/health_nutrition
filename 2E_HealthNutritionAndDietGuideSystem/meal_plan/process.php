<?php
require '../main/authentication.php';
require 'config.php';

$username = $_SESSION['user']['username'];

$gender = $_POST['gender'];
$age = $_POST['age'];
$weight = $_POST['weight'];
$height = $_POST['height'];
$preference = $_POST['preference'];
$activity = $_POST['activity'];
$goal = $_POST['goal'];

// Calculate BMR and TDEE
$bmr = ($gender == 'male') ?
    10 * $weight + 6.25 * $height - 5 * $age + 5 :
    10 * $weight + 6.25 * $height - 5 * $age - 161;

$activity_factors = [
    'sedentary' => 1.2,
    'light' => 1.375,
    'moderate' => 1.55,
    'active' => 1.725
];

$tdee = $bmr * $activity_factors[$activity];

if ($goal === 'lose') {
    $tdee -= 500;
} elseif ($goal === 'gain') {
    $tdee += 500;
}

$calories = round($tdee);

$bmi = $weight / pow($height / 100, 2); 

if ($bmi < 18.5) {
    $bmiCategory = "underweight";
} elseif ($bmi < 25) {
    $bmiCategory = "normal";
} elseif ($bmi < 30) {
    $bmiCategory = "overweight";
} else {
    $bmiCategory = "obese";
}

$activityFeedback = [
    'sedentary' => "Try incorporating light walks or stretching into your day.",
    'light'     => "You're on the right track — keep moving!",
    'moderate'  => "Great job staying active! Consider mixing in strength training.",
    'active'    => "Impressive activity level — keep up the momentum!"
];

switch ($bmiCategory) {
    case 'underweight':
        $feedback = "Your BMI suggests you're underweight. Consider a nutrient-rich diet to support healthy weight gain.";
        break;
    case 'normal':
        $feedback = "You're in a healthy BMI range. Maintain your balance with consistent habits.";
        break;
    case 'overweight':
        $feedback = "You're slightly above the normal BMI range. A mix of cardio and mindful eating can help.";
        break;
    case 'obese':
        $feedback = "Your BMI indicates obesity. Small, consistent lifestyle changes can make a big difference.";
        break;
}

$feedback .= " " . $activityFeedback[$activity];

$mealSuggestions = [
    'vegetarian' => [
        'underweight' => ['Avocado toast with eggs', 'Peanut butter banana smoothie', 'Chickpea curry with naan'],
        'normal'      => ['Vegetable stir-fry with rice', 'Paneer wrap', 'Lentil soup with bread'],
        'overweight'  => ['Zucchini noodles with pesto', 'Chickpea salad', 'Steamed broccoli with tofu'],
        'obese'       => ['Cabbage soup', 'Grilled tofu with greens', 'Cauliflower rice bowl']
    ],
    'non-vegetarian' => [
        'underweight' => ['Chicken biryani', 'Omelette with cheese and toast', 'Beef stew with potatoes'],
        'normal'      => ['Chicken stir-fry with rice', 'Egg curry with chapati', 'Fish soup with vegetables'],
        'overweight'  => ['Grilled chicken salad', 'Boiled eggs with spinach', 'Tuna lettuce wraps'],
        'obese'       => ['Steamed fish with greens', 'Chicken broth soup', 'Egg white veggie scramble']
    ]
];

$meals = $mealSuggestions[$preference][$bmiCategory];
$suggestion = implode(', ', $meals);

// Insert or update meal plan
$stmt = $conn->prepare("
    INSERT INTO meal_plan (username, gender, age, weight, height, preference, activity, goal, calories, feedback, suggestion)
    VALUES (:username, :gender, :age, :weight, :height, :preference, :activity, :goal, :calories, :feedback, :suggestion)
    ON DUPLICATE KEY UPDATE
        gender = VALUES(gender),
        age = VALUES(age),
        weight = VALUES(weight),
        height = VALUES(height),
        preference = VALUES(preference),
        activity = VALUES(activity),
        goal = VALUES(goal),
        calories = VALUES(calories),
        feedback = VALUES(feedback),
        suggestion = VALUES(suggestion)
");

$stmt->execute([
    ':username'   => $username,
    ':gender'     => $gender,
    ':age'        => $age,
    ':weight'     => $weight,
    ':height'     => $height,
    ':preference' => $preference,
    ':activity'   => $activity,
    ':goal'       => $goal,
    ':calories'   => $calories,
    ':feedback'   => $feedback,
    ':suggestion' => $suggestion
]);

$_SESSION['calories'] = $calories;
$_SESSION['meals'] = $meals;
$_SESSION['feedback'] = $feedback;

header("Location: index.php");
exit();
?>
