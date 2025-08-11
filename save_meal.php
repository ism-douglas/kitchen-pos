<?php
// save_meal.php - insert meal record, return JSON success/error
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

try {
    require 'db.php';

    $meal_type = $_POST['meal_type'] ?? '';
    $person_id = $_POST['person_id'] ?? '';

    if (empty($meal_type) || empty($person_id)) {
        throw new Exception('Missing meal type or person selection.');
    }

    // Prevent duplicate meals on same day
    $checkStmt = $pdo->prepare("
        SELECT id FROM meals 
        WHERE person_id = :person_id 
          AND meal_type = :meal_type 
          AND DATE(served_at) = CURDATE()
        LIMIT 1
    ");
    $checkStmt->execute([
        ':person_id' => $person_id,
        ':meal_type' => $meal_type,
    ]);

    if ($checkStmt->rowCount() > 0) {
        throw new Exception('This person has already taken this meal today.');
    }

    // Insert meal record
    $insertStmt = $pdo->prepare("
        INSERT INTO meals (person_id, meal_type, served_at)
        VALUES (:person_id, :meal_type, NOW())
    ");
    $success = $insertStmt->execute([
        ':person_id' => $person_id,
        ':meal_type' => $meal_type,
    ]);

    if (!$success) {
        throw new Exception('Failed to record meal. Please try again.');
    }

    echo json_encode([
        'status' => 'success',
        'message' => 'Meal recorded successfully.'
    ]);
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
