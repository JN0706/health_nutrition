<?php
date_default_timezone_set('Asia/Kuala_Lumpur');
header('Content-Type: application/json');
session_start();

require_once 'db_connect.php';
$username = $_SESSION['user']['username'];
$input = json_decode(file_get_contents('php://input'), true);
$response = ['status' => 'error', 'message' => 'Invalid action'];

if (!empty($input['action'])) {
    switch ($input['action']) {
        case 'save_workout':
            $stmt = $pdo->prepare("INSERT INTO workouts (username, goal, plan, date) VALUES (?, ?, ?, ?)");
            $success = $stmt->execute([$username, $input['goal'], $input['plan'], $input['date']]);
            $response = $success 
                ? ['status' => 'success', 'message' => 'Workout saved successfully']
                : ['status' => 'error', 'message' => 'Failed to save workout'];
            break;

        case 'save_water':
	    $date = date('Y-m-d', strtotime($input['date']));
	    $stmt = $pdo->prepare("INSERT INTO water_intake (username, amount, date) VALUES (?, ?, ?)");
	    $success = $stmt->execute([$username, $input['amount'], $date]);
            
	    $response = $success 
                ? ['status' => 'success', 'message' => 'Water intake saved successfully']
                : ['status' => 'error', 'message' => 'Failed to save water intake'];
            break;

            
        case 'save_sleep':
            $date = date('Y-m-d', strtotime($input['date']));
            $stmt = $pdo->prepare("INSERT INTO sleep_data (username, hours, date) VALUES (?, ?, ?)");
            $success = $stmt->execute([$username, $input['hours'], $date]);
            $response = $success 
                ? ['status' => 'success', 'message' => 'Sleep data saved successfully']
                : ['status' => 'error', 'message' => 'Failed to save sleep data'];
            break;

            
        case 'get_history':
            $workoutStmt = $pdo->prepare("
                SELECT username, goal, plan, date 
                FROM workouts 
                WHERE username = ? 
                ORDER BY date DESC
            ");
	    $workoutStmt->execute([$username]);
            $workoutData = $workoutStmt->fetchAll(PDO::FETCH_ASSOC);

    	    // Fetch water and sleep data for the last 7 days
            $waterStmt = $pdo->prepare("
                SELECT DATE(date) as day, SUM(amount) as total 
                FROM water_intake 
                WHERE username = ? 
                GROUP BY day 
                ORDER BY day DESC 
                LIMIT 7
            ");

	    $waterStmt->execute([$username]);
            $waterData = [];
            foreach ($waterStmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $waterData[$row['day']] = (int) $row['total'];
            }

            // 😴 Sleep hours per day (last 7 distinct days)
            $sleepStmt = $pdo->prepare("
                SELECT DATE(date) as day, MAX(hours) as total 
                FROM sleep_data 
                WHERE username = ? 
                GROUP BY day 
                ORDER BY day DESC 
                LIMIT 7
            ");
            $sleepStmt->execute([$username]);
            $sleepData = [];
            foreach ($sleepStmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $sleepData[$row['day']] = (float) $row['total'];
            }
            $response = [
                'status' => 'success',
		'workout_data' => $workoutData,
                'water_data' => $waterData,
                'sleep_data' => $sleepData
            ];
            break;

        // Delete Workout
        case 'delete_workout':
            $stmt = $pdo->prepare("DELETE FROM workouts WHERE username = ? AND date = ?");
            $success = $stmt->execute([$input['username'], $input['date']]);
            $response = $success
                ? ['status' => 'success', 'message' => 'Workout deleted']
                : ['status' => 'error', 'message' => 'Failed to delete workout'];
            break;

        default:
            $response = ['status' => 'error', 'message' => 'Unknown action'];
    }
}
echo json_encode($response);
?>