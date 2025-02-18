<?php
session_start();
include('db.php');

// Перевірка наявності id доставки
if (isset($_GET['id'])) {
    $deliveryId = $_GET['id'];

    // Отримання даних доставки з бази даних
    $stmt = $pdo->prepare("SELECT * FROM deliveries WHERE id = ?");
    $stmt->execute([$deliveryId]);
    $delivery = $stmt->fetch();

    if (!$delivery) {
        echo "Доставка не знайдена!";
        exit();
    }

    // Обробка форми редагування
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $status = $_POST['delivery_status'];
        $tracking_number = $_POST['tracking_number'];

        // Оновлення даних доставки
        $stmt = $pdo->prepare("UPDATE deliveries SET delivery_status = ?, tracking_number = ? WHERE id = ?");
        $stmt->execute([$status, $tracking_number, $deliveryId]);

        // Перенаправлення на список доставок
        header("Location: delivery_list.php");
        exit();
    }
} else {
    echo "ID доставки не задано!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редагування доставки</title>
    <style>
        body {
            background-color: #1a1a1a;
            color: white;
            font-family: Arial, sans-serif;
            text-align: center;
        }
        .container {
            width: 50%;
            margin: 50px auto;
            background: #292929;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(255, 255, 255, 0.1);
        }
        label {
            font-size: 16px;
            color: #ff4081;
            display: block;
            margin: 10px 0 5px;
        }
        select, input {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #444;
            background-color: #333;
            color: #fff;
            font-size: 14px;
        }
        button {
            background-color: #ff4081;
            padding: 10px;
            border-radius: 5px;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
            margin-top: 20px;
            width: 100%;
        }
        button:hover {
            background-color: #ff0059;
        }
        .back-link {
        display: inline-block;
        background-color: #ff4081;  /* Рожевий фон */
        color: white;
        padding: 8px 16px;
        text-decoration: none;
        border-radius: 5px;
        font-size: 18px;
        text-align: center;
        transition: background-color 0.3s ease, transform 0.3s ease;
        margin: 20px auto;  /* Центрування кнопки */
        display: block;  /* Робить елемент блочним для центрування */
        width: fit-content;  /* Дозволяє кнопці мати ширину по її контенту */
    }
    .back-link:hover {
    background-color: #ff0059;  /* Змінюється колір при наведенні */
    transform: scale(1.05);  /* Легке збільшення кнопки при наведенні */
}
    </style>
</head>
<body>
    <div class="container">
        <h1>Редагування статусу доставки</h1>
        <form method="POST">
            <label for="delivery_status">Статус доставки:</label>
            <select id="delivery_status" name="delivery_status" required>
                <option value="в очікуванні" <?= $delivery['delivery_status'] == 'в очікуванні' ? 'selected' : '' ?>>В очікуванні</option>
                <option value="відправлено" <?= $delivery['delivery_status'] == 'відправлено' ? 'selected' : '' ?>>Відправлено</option>
                <option value="доставлено" <?= $delivery['delivery_status'] == 'доставлено' ? 'selected' : '' ?>>Доставлено</option>
                <option value="скасовано" <?= $delivery['delivery_status'] == 'скасовано' ? 'selected' : '' ?>>Скасовано</option>
            </select>

            <label for="tracking_number">Номер відстеження:</label>
            <input type="text" id="tracking_number" name="tracking_number" value="<?= htmlspecialchars($delivery['tracking_number'] ?? '') ?>" required>

            <button type="submit" class="back-link">Оновити доставку</button>
        </form>
        <a class="back-link" href="delivery_list.php">Назад до списку доставок</a>
    </div>
</body>
</html>
