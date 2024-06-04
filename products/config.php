<?php

$host = 'localhost';
$dbname = 'balsadb';
$username = 'root';
$password = '';
/*
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'u593341949_dev_demo');
define('DB_PASSWORD', '');
define('DB_NAME', 'balsadb');
*/
try {
 $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
 $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
 die("Database connection failed: " . $e->getMessage());
}