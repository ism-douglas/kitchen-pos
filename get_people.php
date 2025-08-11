<?php
// get_people.php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

try {
    require 'db.php';

    $meal_type = $_GET['meal_type'] ?? '';
    $search = $_GET['term'] ?? '';
    $page = max((int)($_GET['page'] ?? 1), 1);
    $limit = 10;  // items per page
    $offset = ($page - 1) * $limit;

    if (!$meal_type) {
        throw new Exception('Meal type is required.');
    }

    // Base query - count total eligible records
    $countSql = "
        SELECT COUNT(*) FROM people
        WHERE id NOT IN (
            SELECT person_id FROM meals
            WHERE meal_type = :meal_type AND DATE(served_at) = CURDATE()
        )
    ";

    // Base query - select people with optional search
    $selectSql = "
        SELECT id, full_name, category FROM people
        WHERE id NOT IN (
            SELECT person_id FROM meals
            WHERE meal_type = :meal_type AND DATE(served_at) = CURDATE()
        )
    ";

    $params = [':meal_type' => $meal_type];

    if ($search !== '') {
        $countSql .= " AND full_name LIKE :search";
        $selectSql .= " AND full_name LIKE :search";
        $params[':search'] = "%$search%";
    }

    // Get total count for pagination
    $countStmt = $pdo->prepare($countSql);
    $countStmt->execute($params);
    $total = (int)$countStmt->fetchColumn();

    // Add limit and offset for pagination
    $selectSql .= " ORDER BY full_name ASC LIMIT :limit OFFSET :offset";
    $stmt = $pdo->prepare($selectSql);

    // Bind parameters (PDO requires integer params bound explicitly)
    foreach ($params as $key => $val) {
        $stmt->bindValue($key, $val);
    }
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

    $stmt->execute();

    $items = [];
    while ($row = $stmt->fetch()) {
        $items[] = [
            'id' => $row['id'],
            'text' => $row['full_name'] . " (" . $row['category'] . ")"
        ];
    }

    // Calculate if there are more pages
    $more = ($offset + $limit) < $total;

    echo json_encode([
        'items' => $items,
        'more' => $more
    ]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
