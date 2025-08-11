<?php
// db.php

$host = 'localhost';     // Database host
$db   = 'kitchen_system';    // Database name
$user = 'root';          // Database username
$pass = '';              // Database password
$charset = 'utf8mb4';    // Character set

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Return arrays by default
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Use native prepared statements
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // You might want to disable detailed error messages in production
    die('Database connection failed: ' . $e->getMessage());
}
