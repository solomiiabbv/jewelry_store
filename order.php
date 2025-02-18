<?php
session_start();
include('db.php');

// Перевіряємо, чи користувач авторизований
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user']['id'];
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Ініціалізуємо змінну загальної суми
$total_price = 0;

if (!empty($cart)) {
    foreach ($cart as $item) {
        $total_price += $item['price'] * $item['quantity'];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $address = isset($_POST['address']) ? trim($_POST['address']) : null;
    $delivery_method = isset($_POST['delivery_method']) ? $_POST['delivery_method'] : null;

    if (!$address || !$delivery_method || empty($cart)) {
        echo "<p class='error'>Будь ласка, заповніть всі поля!</p>";
    } else {
        // Додаємо запис у таблицю orders
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_price) VALUES (?, ?)");
        $stmt->execute([$user_id, $total_price]);
        $order_id = $pdo->lastInsertId();

        // Додаємо товари у order_items
        foreach ($cart as $item) {
            // Оновлюємо кількість товару в базі
            $stmt = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
            $stmt->execute([$item['quantity'], $item['id']]);

            // Додаємо товар у таблицю order_items
            $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt->execute([$order_id, $item['id'], $item['quantity'], $item['price']]);
        }

        // Додаємо інформацію про доставку
        $stmt = $pdo->prepare("INSERT INTO deliveries (order_id, delivery_method, delivery_address) VALUES (?, ?, ?)");
        $stmt->execute([$order_id, $delivery_method, $address]);

        // Очищаємо кошик
        unset($_SESSION['cart']);

        header("Location: success.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Оформлення замовлення</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .order-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .order-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .order-container label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
        .order-container input, 
        .order-container select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .order-container ul {
            list-style: none;
            padding: 0;
        }
        .order-container ul li {
            background: #fff;
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        .order-container .total {
            font-size: 18px;
            font-weight: bold;
            text-align: right;
            margin-top: 10px;
        }
        .order-container button {
            width: 100%;
            padding: 10px;
            background-color: yellowgreen;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 20px;
        }
        .order-container button:hover {
            background: #45a049;
        }
        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="order-container">
        <h2>Оформлення замовлення</h2>

        <form action="order.php" method="POST">
            <label for="address">Адреса доставки:</label>
            <input type="text" id="address" name="address" required>

            <label for="delivery_method">Спосіб доставки:</label>
            <select id="delivery_method" name="delivery_method" required>
                <option value="курєр">Кур’єр</option>
                <option value="у відділення">У відділення</option>
            </select>

            <h3>Ваші товари</h3>
            <ul>
                <?php if (!empty($cart)): ?>
                    <?php foreach ($cart as $item): ?>
                        <li><?= htmlspecialchars($item['name']) ?> (<?= $item['quantity'] ?> шт.) - <?= number_format($item['price'] * $item['quantity'], 2) ?> $</li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="error">Ваш кошик порожній.</p>
                <?php endif; ?>
            </ul>

            <p class="total">Загальна сума: <?= number_format($total_price, 2) ?>$</p>

            <button type="submit">Оформити замовлення</button>
        </form>
    </div>
</body>
</html>




