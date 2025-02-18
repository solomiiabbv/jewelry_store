<?php
include('db.php');

// Додавання нової знижки
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $discount_name = $_POST['discount_name'];
    $discount_percentage = $_POST['discount_percentage'];
    $product_id = $_POST['product_id'];
    
    $stmt = $pdo->prepare("INSERT INTO discounts (discount_name, discount_percentage, product_id) VALUES (?, ?, ?)");
    $stmt->execute([$discount_name, $discount_percentage, $product_id]);
    header("Location: discount.php"); // Перенаправляє на сторінку зі списком знижок
    exit;
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Додати знижку</title>
    <style>
        /* Загальні стилі */
        body {
            background-color: #1a1a1a;
            color: #ffffff;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Панель навігації */
        .admin-nav {
            background: #222;
            width: 220px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding: 20px;
            box-shadow: 2px 0 10px rgba(255, 255, 255, 0.1);
        }

        .admin-title {
            color: #ff4081;
            text-align: center;
            font-size: 22px;
            margin-bottom: 20px;
        }

        .nav-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .nav-list li {
            margin-bottom: 10px;
        }

        .nav-list a {
            display: block;
            color: #ffffff;
            text-decoration: none;
            padding: 12px;
            background: #333;
            border-radius: 5px;
            transition: background 0.3s;
            text-align: center;
        }

        .nav-list a:hover,
        .nav-list .active {
            background: #ff4081;
        }

        /* Основний контент */
        .admin-main {
            margin-left: 250px;
            padding: 40px;
            text-align: center;
            background: rgba(0, 0, 0, 0.5);
        }

        h2 {
            color: #ff4081;
            font-size: 24px;
        }

        .add-discount-form input, .add-discount-form select {
            padding: 10px;
            margin: 5px;
            border-radius: 5px;
            border: 1px solid #444;
        }

        .add-discount-form button {
            background-color: #ff4081;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        .add-discount-form button:hover {
            background-color: #ff0059;
        }

        /* Кнопка виходу */
        .logout-link {
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
        <div class="admin-nav">
            <h1 class="admin-title">Панель адміністратора</h1>
            <nav>
                <ul class="nav-list">
                    <li><a href="admin.panel.php">Головна</a></li>
                    <li><a href="products.php">Управління товарами</a></li>
                    <li><a href="orders.php">Замовлення</a></li>
                    <li><a href="users.php">Користувачі</a></li>
                    <li><a href="delivery_list.php">Доставка</a></li>
                    <li><a href="discount.php" class="active">Акції</a></li>
                    <li><a href="logout.php" class="logout-link">Вихід</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="admin-main">
        <div class="add-discount-form">
            <h2>Додати нову знижку</h2>
            <form action="add_discount.php" method="POST">
                <input type="text" name="discount_name" placeholder="Назва знижки" required>
                <input type="number" name="discount_percentage" placeholder="Процент знижки" min="0" max="100" required>
                <input type="number" name="product_id" placeholder="Ідентифікатор товару" required>
                <button type="submit">Додати знижку</button>
            </form>
        </div>
    </main>
</body>
</html>
