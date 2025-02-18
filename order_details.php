<?php 
session_start();
include('db.php');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "Помилка: Не вказано ID замовлення.";
    exit();
}

$orderId = $_GET['id'];

// Отримуємо інформацію про замовлення
$orderStmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$orderStmt->execute([$orderId]);
$order = $orderStmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo "Замовлення не знайдено.";
    exit();
}

// Отримуємо дані про користувача
$userStmt = $pdo->prepare("SELECT name, email, phone FROM users WHERE id = ?");
$userStmt->execute([$order['user_id']]);
$user = $userStmt->fetch(PDO::FETCH_ASSOC);

// Отримуємо товари в замовленні з їх назвами та цінами
$orderItemsStmt = $pdo->prepare("SELECT oi.*, p.name, p.price 
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE oi.order_id = ?");
$orderItemsStmt->execute([$orderId]);
$orderItems = $orderItemsStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Деталі замовлення</title>
    <style>
        /* Загальні стилі */
        body {
            background-color: #1a1a1a;
            color: #ffffff;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Заголовок */
        .admin-header {
            background-color: #333;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .admin-title {
            font-size: 24px;
            margin: 0;
        }

        /* Навігація */
        .admin-nav {
            background: #222;
            padding: 10px 0;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 220px;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 2px 0 10px rgba(255, 255, 255, 0.1);
        }

        .nav-list {
            list-style: none;
            padding: 0;
            width: 100%;
        }

        .nav-list li {
            width: 100%;
            text-align: center;
        }

        .nav-list a {
            display: block;
            color: #fff;
            text-decoration: none;
            padding: 15px;
            transition: background 0.3s;
        }

        .nav-list a:hover,
        .nav-list .active {
            background: #e91e63;
        }

        /* Основний контент */
        .admin-main {
            margin-left: 240px;
            padding: 20px;
        }

        /* Контейнери */
        .container {
            background: #292929;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(255, 255, 255, 0.1);
        }

        /* Заголовки */
        h1, h2 {
            color: #ff4081;
        }

        /* Таблиця */
        table {
            width: 100%;
            border-collapse: collapse;
            background: #1e1e1e;
            border-radius: 10px;
            overflow: hidden;
        }

        table th, table td {
            border: 1px solid #444;
            padding: 10px;
            text-align: center;
        }

        table th {
            background: #333;
            color: #ff4081;
        }

        table tr:nth-child(even) {
            background: #292929;
        }

        /* Кнопки */
        .btn {
            background: #ff4081;
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            font-weight: bold;
            display: inline-block;
            margin-top: 20px;
        }

        .btn:hover {
            background: #ff0059;
        }
    </style>
</head>
<body>
    <header class="admin-header">
        <h1 class="admin-title">Деталі замовлення №<?= htmlspecialchars($orderId) ?></h1>
    </header>

    <div class="container">
        <h2>Користувач:</h2>
        <p><strong>Ім'я:</strong> <?= htmlspecialchars($user['name'] ?? 'Невідомий') ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email'] ?? 'Невідомий') ?></p>
        <p><strong>Телефон:</strong> <?= htmlspecialchars($user['phone'] ?? 'Не вказано') ?></p>

        <h2>Загальна сума: <?= number_format($order['total_price'], 2) ?> грн</h2>

        <h2>Продукти в замовленні:</h2>
        <table>
            <tr>
                <th>Назва продукту</th>
                <th>Ціна</th>
                <th>Кількість</th>
                <th>Сума</th>
            </tr>
            <?php if (!empty($orderItems)): ?>
                <?php foreach ($orderItems as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['name'] ?? 'Невідомий товар') ?></td>
                        <td><?= number_format($item['price'], 2) ?> грн</td>
                        <td><?= $item['quantity'] ?></td>
                        <td><?= number_format($item['price'] * $item['quantity'], 2) ?> грн</td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">Немає товарів у замовленні.</td>
                </tr>
            <?php endif; ?>
        </table>

        <a href="orders.php" class="btn">Назад до замовлень</a>
    </div>
</body>
</html>

