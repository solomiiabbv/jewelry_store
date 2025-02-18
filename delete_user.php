<?php
include('db.php');

// Перевірка наявності id користувача в GET
if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Видалення користувача з бази даних
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$userId]);

    // Перенаправлення після видалення
    header("Location: users.php");
    exit();
} else {
    echo "Користувач не знайдений!";
}
?>
