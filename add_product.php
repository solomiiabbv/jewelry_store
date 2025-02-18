<?php 
include('db.php');

// Якщо форма надіслана
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    // Додавання продукту до бази даних
    $stmt = $pdo->prepare("INSERT INTO products (name, price, stock) VALUES (?, ?, ?)");
    $stmt->execute([$name, $price, $stock]);

    // Перенаправлення на сторінку товарів
    header("Location: products.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Додавання продукту</title>
    <style>
        /* Загальні стилі */
        body {
            background-color: #2d2d2d;
            color: #fff;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

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

        .admin-main {
            margin-left: 240px;
            padding: 40px 20px;
        }

        h2 {
            color: #ff4081;
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
        }

        /* Стилі для форми */
        form {
            background: #333;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            margin: 0 auto;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 16px;
        }

        input {
            width: 100%;
            padding: 15px 0px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #444;
            background: #292929;
            color: #fff;
            font-size: 16px;
        }

        input:focus {
            outline: none;
            border-color: #ff4081;
        }

        button {
            background-color: #ff4081;
            color: #fff;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #e91e63;
        }

        /* Кнопка для скидання */
        .cancel-button {
            background-color: #444;
            color: #fff;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
        }

        .cancel-button:hover {
            background-color: #777;
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
        <h2>Додати новий продукт</h2>
        <form action="add_product.php" method="POST">
            <label for="name">Назва товару:</label>
            <input type="text" id="name" name="name" required>

            <label for="price">Ціна:</label>
            <input type="number" id="price" name="price" required>

            <label for="stock">Кількість на складі:</label>
            <input type="number" id="stock" name="stock" required>

            <button type="submit">Додати продукт</button>
            <button type="button" class="cancel-button" onclick="window.location.href='products.php';">Скасувати</button>
        </form>
    </main>
</body>
</html>
