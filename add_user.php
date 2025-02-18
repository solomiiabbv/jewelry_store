<?php  
include('db.php');

// Якщо форма надіслана
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $user_role = $_POST['user_role']; // Отримуємо роль

    // Перевірка, чи є пароль (якщо є) - додайте поле для пароля у форму
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Додавання нового користувача до бази даних
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, phone, user_role) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $email, $password, $phone, $user_role]);

    // Перенаправлення на сторінку користувачів
    header("Location: users.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Додавання користувача</title>
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

        input, select {
            width: 100%;
            padding: 15px 0px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #444;
            background: #292929;
            color: #fff;
            font-size: 16px;
        }

        input:focus, select:focus {
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
        <h2>Додати нового користувача</h2>
        <form action="add_user.php" method="POST">
            <label for="name">Ім'я:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="phone">Телефон:</label>
            <input type="text" id="phone" name="phone">

            <label for="password">Пароль:</label>
            <input type="text" id="password" name="password">

            <label for="user_role">Роль:</label>
            <select id="user_role" name="user_role" required>
                <option value="admin">admin</option>
                <option value="customer">customer</option>
            </select>

            <button type="submit">Додати користувача</button>
            <button type="button" class="cancel-button" onclick="window.location.href='users.php';">Скасувати</button>
        </form>
    </main>
</body>
</html>
