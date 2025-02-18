<?php 
include('db.php');
session_start();

// Перевірка, чи користувач увійшов
if (!isset($_SESSION['user'])) {
    header("Location: login.php"); 
    exit();
}

$user_id = $_SESSION['user']['id']; 

// Отримуємо інформацію про замовлення та номер відстеження
$stmt = $pdo->prepare("
    SELECT o.id, d.tracking_number, d.delivery_status 
    FROM orders o 
    LEFT JOIN deliveries d ON o.id = d.order_id 
    WHERE o.user_id = ?
");
$stmt->execute([$user_id]); 
$orders = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Номер відстеження</title>
    <style>
        /* Загальні стилі */
        body {
            background-color: #1a1a1a;
            color: #ffffff;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Основний контейнер */
        .container {
            max-width: 800px;
            margin: 50px auto;
            background: #292929;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(255, 255, 255, 0.1);
        }

        /* Заголовок */
        h1 {
            text-align: center;
            font-size: 24px;
            color: #ff4081;
        }

        /* Стилі для кожного замовлення */
        .order {
            background: #333;
            padding: 15px;
            border-radius: 8px;
            margin: 10px 0;
            box-shadow: 0 2px 5px rgba(255, 255, 255, 0.1);
        }

        .order p {
            margin: 5px 0;
            font-size: 16px;
        }

        .status {
            font-weight: bold;
            color: #ff4081;
        }

        .tracking {
            font-weight: bold;
            color: #28a745;
        }

        /* Повідомлення, якщо немає замовлень */
        .no-orders {
            text-align: center;
            font-size: 18px;
            color: #ff4081;
        }

        /* Кнопка повернення */
        .back-button {
            display: block;
            width: 200px;
            text-align: center;
            margin: 20px auto;
            padding: 10px;
            background-color: #ff4081;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }

        .back-button:hover {
            background-color: #ff0059;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Ваші замовлення та їх статуси доставки</h1>

        <?php if (empty($orders)): ?>
            <p class="no-orders">У вас немає замовлень з відстеженням.</p>
        <?php else: ?>
            <?php foreach ($orders as $order): ?>
                <div class="order">
                    <p><strong>Замовлення ID:</strong> <?= htmlspecialchars($order['id']) ?></p>
                    <p class="status"><strong>Статус доставки:</strong> <?= htmlspecialchars($order['delivery_status'] ?? 'Немає даних') ?></p>
                    <p class="tracking"><strong>Номер відстеження:</strong> <?= htmlspecialchars($order['tracking_number'] ?: 'Не доступно') ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <a href="index.php" class="back-button">На головну</a>
    </div>
</body>
</html>


