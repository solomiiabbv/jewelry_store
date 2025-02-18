<?php
include('db.php');

// Отримання знижки для редагування
if (isset($_GET['id'])) {
    $discount_id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM discounts WHERE id = ?");
    $stmt->execute([$discount_id]);
    $discount = $stmt->fetch();

    if (!$discount) {
        echo "Знижка не знайдена.";
        exit;
    }
}

// Оновлення знижки
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $discount_name = $_POST['discount_name'];
    $discount_percentage = $_POST['discount_percentage'];
    $product_id = $_POST['product_id'];

    $stmt = $pdo->prepare("UPDATE discounts SET discount_name = ?, discount_percentage = ?, product_id = ? WHERE id = ?");
    $stmt->execute([$discount_name, $discount_percentage, $product_id, $discount_id]);
    header("Location: discount.php"); // Перенаправляє на сторінку зі списком знижок
    exit;
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редагувати знижку</title>
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

        .edit-discount-form input, .edit-discount-form select {
            padding: 10px;
            margin: 5px;
            border-radius: 5px;
            border: 1px solid #444;
        }

        .edit-discount-form button {
            background-color: #ff4081;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        .edit-discount-form button:hover {
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
        <div class="edit-discount-form">
            <h2>Редагувати знижку</h2>
            <form action="edit_discount.php?id=<?= $discount['id'] ?>" method="POST">
                <input type="text" name="discount_name" value="<?= htmlspecialchars($discount['discount_name']) ?>" required>
                <input type="number" name="discount_percentage" value="<?= $discount['discount_percentage'] ?>" min="0" max="100" required>
                <input type="number" name="product_id" value="<?= $discount['product_id'] ?>" required>
                <button type="submit">Зберегти зміни</button>
            </form>
        </div>
    </main>
</body>
</html>
