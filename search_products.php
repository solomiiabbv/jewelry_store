<?php
session_start();
include('db.php');

// Отримуємо пошуковий запит з GET
$search_query = '';
if (isset($_GET['search'])) {
    $search_query = $_GET['search'];
}

// Підготовка запиту для отримання товарів, які містять пошукове слово
$stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE :search_query OR description LIKE :search_query");
$stmt->execute(['search_query' => '%' . $search_query . '%']);
$products = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Результати пошуку товарів</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="products-container">
    <h2>Результати пошуку для: "<?= htmlspecialchars($search_query) ?>"</h2>

    <?php if ($products): ?>
        <!-- Якщо є товари за запитом -->
        <div class="products-list">
            <?php foreach ($products as $product): ?>
                <div class="product-item">
                    <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <p><?= htmlspecialchars($product['description']) ?></p>
                    <p>Ціна: <?= htmlspecialchars($product['price']) ?> грн</p>
                    <a href="product-detail.php?id=<?= $product['id'] ?>" class="view-product">Переглянути</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Товари не знайдені за запитом: "<?= htmlspecialchars($search_query) ?>"</p>
    <?php endif; ?>

    <a href="index.php" class="back-to-home">Повернутися на головну</a>
</div>

</body>
</html>

