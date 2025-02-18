<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель адміністратора</title>
    <style>
        /* Загальні стилі */
        body {
            background-color: #1a1a1a;
            color: #ffffff;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('darkened_blurred_image.webp'); 
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

        .logout-link:hover {
            background: #ff0059 !important;
        }
    </style>
</head>
<body>
    <header class="admin-header">
        <div class="admin-nav">
            <h1 class="admin-title">Панель адміністратора</h1>
            <nav>
                <ul class="nav-list">
                    <li><a href="admin.panel.php" class="active">Головна</a></li>
                    <li><a href="products.php">Товари</a></li>
                    <li><a href="orders.php">Замовлення</a></li>
                    <li><a href="users.php">Користувачі</a></li>
                    <li><a href="delivery_list.php">Доставка</a></li>
                    <li><a href="discount.php">Акції</a></li>
                    <li><a href="logout.php" class="logout-link">Вихід</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="admin-main">
        <h2>Вітаємо в панелі адміністратора!</h2>
    </main>
</body>
</html>

