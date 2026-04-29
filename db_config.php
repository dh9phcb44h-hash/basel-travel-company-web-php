<?php
// DB server (local machine)
$host = "localhost";
// MySQL port
$port = "3307";
// DB name
$dbname = "travel_company";
// MySQL username
$username = "root";
// MySQL password
$password = "";

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    // create PDO connection
    $pdo = new PDO($dsn, $username, $password);
    // enable exceptions for error handling
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // set default fetch style to associative array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // if connection fails → show error message
    die("Database connection failed: " . $e->getMessage());
}
