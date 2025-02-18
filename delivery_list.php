<?php  
session_start();
include('db.php');

// Перевірка наявності авторизації адміністратора
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Перевірка наявності запитів для пошуку
$search_order_id = isset($_GET['search_order_id']) ? $_GET['search_order_id'] : '';
$search_tracking_number = isset($_GET['search_tracking_number']) ? $_GET['search_tracking_number'] : '';

// Запит для отримання всіх доставок або з фільтрацією
$query = "SELECT id, order_id, delivery_status, tracking_number, delivery_method, delivery_address FROM deliveries";
$conditions = [];

if ($search_order_id) {
    $conditions[] = "order_id = :search_order_id";
}
if ($search_tracking_number) {
    $conditions[] = "tracking_number LIKE :search_tracking_number";
}

if (count($conditions) > 0) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}

$stmt = $pdo->prepare($query);

// Якщо є запит на пошук, додаємо параметри для підготовленого запиту
if ($search_order_id) {
    $stmt->bindValue(':search_order_id', $search_order_id);
}
if ($search_tracking_number) {
    $stmt->bindValue(':search_tracking_number', '%' . $search_tracking_number . '%');
}

$stmt->execute();
$deliveries = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список доставок</title>
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
                <li><a href="orders.php">Замовлення</a></li>
                <li><a href="users.php">Користувачі</a></li>
                <li><a href="delivery_list.php" class="active">Доставка</a></li>
                <li><a href="discount.php">Акції</a></li>
                <li><a href="logout.php" class="logout-link">Вихід</a></li>
            </ul>
        </nav>
    </div>
</header>

<main class="admin-main">
    <h2 class="section-title">Список всіх доставок</h2>

    <!-- Форма пошуку за ID замовлення та номером відстеження -->
    <form method="GET" action="delivery_list.php">
        <input type="text" name="search_order_id" placeholder="Пошук за ID замовлення..." value="<?= htmlspecialchars($search_order_id) ?>">
        <input type="text" name="search_tracking_number" placeholder="Пошук за номером відстеження..." value="<?= htmlspecialchars($search_tracking_number) ?>">
        <button type="submit" class="btn">Пошук</button>
    </form>

    <table class="admin-table">
        <tr>
            <th>ID доставки</th>
            <th>ID замовлення</th>
            <th>Статус доставки</th>
            <th>Номер відстеження</th>
            <th>Метод доставки</th>
            <th>Адреса доставки</th>
            <th>Дії</th>
        </tr>
        <?php foreach ($deliveries as $delivery): ?>
            <tr>
                <td><?= $delivery['id'] ?></td>
                <td><?= $delivery['order_id'] ?></td>
                <td><?= htmlspecialchars($delivery['delivery_status']) ?></td>
                <td><?= htmlspecialchars($delivery['tracking_number']) ?></td>
                <td><?= htmlspecialchars($delivery['delivery_method']) ?></td>
                <td><?= htmlspecialchars($delivery['delivery_address']) ?></td>
                <td><a href="manage.delivery.php?id=<?= $delivery['id'] ?>" class="btn">Редагувати</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
</main>
</body>
</html>
