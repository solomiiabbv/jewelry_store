<?php  
include('db.php');

// Перевірка, чи є запит на пошук
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Запит для отримання всіх товарів або з фільтрацією
$query = "SELECT * FROM products";
if ($search) {
    // Пошук за ID або назвою товару
    $query .= " WHERE id LIKE :search OR name LIKE :search";
}
$stmt = $pdo->prepare($query);

// Якщо є запит на пошук, додаємо параметр для підготовленого запиту
if ($search) {
    $stmt->bindValue(':search', '%' . $search . '%');
}

$stmt->execute();
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Товари</title>
    <link rel="stylesheet" href="admin.panel.css">
</head>
<body>
    <header class="admin-header">
        <div class="admin-nav">
            <h1 class="admin-title">Панель адміністратора</h1>
            <nav>
                <ul class="nav-list">
                    <li><a href="admin.panel.php">Головна</a></li>
                    <li><a href="products.php" class="active">Товари</a></li>
                    <li><a href="orders.php">Замовлення</a></li>
                    <li><a href="users.php">Користувачі</a></li>
                    <li><a href="delivery_list.php">Доставка</a></li>
                    <li><a href="discount.php">Акції</a></li>
                    <li><a href="logout.php" class="logout-link">Вихід</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="admin-main">
        <div class="container">
            <h2 class="section-title">Список товарів</h2>
            <a href="add_product.php" class="btnn">Додати товар</a>

            <!-- Форма пошуку -->
            <form method="GET" action="products.php">
                <input type="text" name="search" placeholder="Пошук за ID або назвою..." value="<?= htmlspecialchars($search) ?>">
                <button type="submit" class="btn">Пошук</button>
            </form>

            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Назва</th>
                        <th>Ціна</th>
                        <th>Кількість</th>
                        <th>Дії</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?= htmlspecialchars($product['id']) ?></td>
                            <td><?= htmlspecialchars($product['name']) ?></td>
                            <td><?= number_format($product['price'], 0) ?>$</td>
                            <td><?= $product['stock'] ?></td>
                            <td>
                                <a href="edit_product.php?id=<?= $product['id'] ?>" class="btn">Редагувати</a>
                                <a href="delete_product.php?id=<?= $product['id'] ?>" class="btn" onclick="return confirm('Ви впевнені, що хочете видалити цей товар?');">Видалити</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
