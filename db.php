<?php
// db.php - Підключення до бази даних через PDO
$host = 'localhost';
$dbname = 'jew_store';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Не вдалося підключитися до бази даних: " . $e->getMessage());
}
?>
