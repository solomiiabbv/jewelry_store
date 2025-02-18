<?php   
include('db.php');

// Перевірка, чи є запит на пошук за ID замовленням
$search_order_id = isset($_GET['search_order_id']) ? $_GET['search_order_id'] : '';
// Перевірка, чи є запит на пошук за ID користувача
$search_user_id = isset($_GET['search_user_id']) ? $_GET['search_user_id'] : '';

// Запит для отримання всіх замовлень або з фільтрацією
$query = "SELECT * FROM orders";
if ($search_order_id || $search_user_id) {
    $query .= " WHERE";
    if ($search_order_id) {
        $query .= " id = :search_order_id";
    }
    if ($search_user_id) {
        if ($search_order_id) {
            $query .= " AND";
        }
        $query .= " user_id = :search_user_id";
    }
}

$stmt = $pdo->prepare($query);

// Якщо є запит на пошук, додаємо параметри для підготовленого запиту
if ($search_order_id) {
    $stmt->bindValue(':search_order_id', $search_order_id);
}
if ($search_user_id) {
    $stmt->bindValue(':search_user_id', $search_user_id);
}

$stmt->execute();
$orders = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Замовлення</title>
    <link rel="stylesheet" href="admin.panel.css">
</head>
<body>
    <header class="admin-header">
        <div class="admin-nav">
            <h1 class="admin-title">Панель адміністратора</h1>
            <nav>
                <ul class="nav-list">
                    <li><a href="admin.panel.php">Головна</a></li>
                    <li><a href="products.php">Товари</a></li>
                    <li><a href="orders.php" class="active">Замовлення</a></li>
                    <li><a href="users.php">Користувачі</a></li>
                    <li><a href="delivery_list.php">Доставка</a></li>
                    <li><a href="discount.php">Акції</a></li>
                    <li><a href="logout.php" class="logout-link">Вихід</a></li>
                </ul>
            </nav>
    </header>

    <main class="admin-main">
        <h2 class="section-title">Список замовлень</h2>
        
        <!-- Форма пошуку за ID замовлення та за ID користувача -->
        <form method="GET" action="orders.php">
            <input type="text" name="search_order_id" placeholder="Пошук за ID замовлення..." value="<?= htmlspecialchars($search_order_id) ?>">
            <input type="text" name="search_user_id" placeholder="Пошук за ID користувача..." value="<?= htmlspecialchars($search_user_id) ?>">
            <button type="submit" class="btn">Пошук</button>
        </form>

        <table class="admin-table">
            <tr>
                <th>ID замовлення</th>
                <th>Користувач</th>
                <th>Загальна сума</th>
                <th>Дії</th>
            </tr>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= $order['id'] ?></td>
                    <td><?= htmlspecialchars($order['user_id']) ?></td>
                    <td><?= number_format($order['total_price'], 2) ?>$</td>
                    <td><a href="order_details.php?id=<?= $order['id'] ?>" class="btn">Деталі</a></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </main>
</body>
</html>

