<?php
include('db.php');

// Перевірка наявності id продукту в GET
if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    // Запит для отримання даних про продукт
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch();

    // Якщо продукт не знайдений
    if (!$product) {
        echo "Продукт не знайдений!";
        exit();
    }

    // Якщо форма надіслана
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Валідація введених даних
        $name = htmlspecialchars(trim($_POST['name']));
        $price = floatval($_POST['price']);
        $stock = intval($_POST['stock']);

        // Перевірка на коректність введених даних
        if ($price <= 0 || $stock < 0) {
            echo "Невірні дані. Ціна та кількість повинні бути позитивними значеннями.";
            exit();
        }

        // Оновлення даних про продукт у базі даних
        $stmt = $pdo->prepare("UPDATE products SET name = ?, price = ?, stock = ? WHERE id = ?");
        $stmt->execute([$name, $price, $stock, $productId]);

        // Перенаправлення на сторінку товарів після оновлення
        header("Location: products.php");
        exit();
    }
} else {
    echo "Продукт не знайдений!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редагувати продукт</title>
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

        .container {
            background: #292929;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(255, 255, 255, 0.1);
        }

        /* Заголовки */
        .section-title {
            text-align: center;
            margin-bottom: 20px;
            font-size: 22px;
            color: #ff4081;
        }

        /* Форми */
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-size: 16px;
            color: #ff4081;
        }

        input[type="text"], input[type="number"] {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #444;
            background-color: #333;
            color: #fff;
            font-size: 14px;
        }

        input[type="number"] {
            width: 150px;
        }

        button {
            background-color: #ff4081;
            padding: 10px;
            border-radius: 5px;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #ff0059;
        }

        /* Кнопка виходу */
        .logout-link {
            background: #ff4081;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            font-weight: bold;
            display: inline-block;
            margin-top: 20px;
        }

        .logout-link:hover {
            background: #ff0059;
        }
    </style>
</head>
<body>
    <header class="admin-header">
        <nav class="admin-nav">
            <ul class="nav-list">
                <li><a href="admin.panel.php">Головна</a></li>
                <li><a href="products.php">Управління товарами</a></li>
                <li><a href="orders.php">Замовлення</a></li>
                <li><a href="users.php">Користувачі</a></li>
                <li><a href="logout.php" class="logout-link">Вихід</a></li>
            </ul>
        </nav>
    </header>

    <main class="admin-main">
        <div class="container">
            <h2>Редагувати продукт №<?= htmlspecialchars($product['id']) ?></h2>
            <form action="edit_product.php?id=<?= $product['id'] ?>" method="POST">
                <label for="name">Назва товару:</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>

                <label for="price">Ціна:</label>
                <input type="number" id="price" name="price" value="<?= htmlspecialchars($product['price']) ?>" required step="0.01">

                <label for="stock">Кількість на складі:</label>
                <input type="number" id="stock" name="stock" value="<?= htmlspecialchars($product['stock']) ?>" required>

                <button type="submit">Зберегти зміни</button>
            </form>
        </div>
    </main>
</body>
</html>

