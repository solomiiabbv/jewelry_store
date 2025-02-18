<?php
include('db.php');

// Перевірка наявності id продукту в GET
if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    // Видалення товару з бази даних
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$productId]);

    // Перенаправлення після видалення
    header("Location: products.php");
    exit();
} else {
    echo "Продукт не знайдений!";
}
?>
