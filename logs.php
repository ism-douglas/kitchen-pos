<?php include 'db.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Meal Pickup Logs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <h2 class="mb-4">📋 Meal Pickup Logs</h2>
    <table class="table table-striped table-bordered shadow">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Person</th>
                <th>Category</th>
                <th>Meal</th>
                <th>Pickup Time</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $sql = "SELECT m.id, p.full_name, p.category, m.meal_type, m.pickup_time
                FROM meal_logs m
                JOIN people p ON m.person_id = p.id
                ORDER BY m.pickup_time DESC";
        $result = $conn->query($sql);

        $meal_icons = [
            'early_breakfast' => '<i class="fas fa-sun text-primary"></i> Early Breakfast',
            'tea_break' => '<i class="fas fa-mug-hot text-warning"></i> Tea Break',
            'lunch' => '<i class="fas fa-utensils text-success"></i> Lunch',
            'supper' => '<i class="fas fa-moon text-dark"></i> Supper'
        ];

        $i = 1;
        while ($row = $result->fetch_assoc()):
        ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= $row['full_name'] ?></td>
                <td><?= ucfirst($row['category']) ?></td>
                <td><?= $meal_icons[$row['meal_type']] ?></td>
                <td><?= $row['pickup_time'] ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

    <div class="text-center mt-4">
        <a href="index.php" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Back to POS</a>
    </div>
</div>

</body>
</html>
