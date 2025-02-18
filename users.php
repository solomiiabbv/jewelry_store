<?php  
include('db.php');

// Перевірка, чи є запит на пошук за ID користувача або номером телефону
$search_id = isset($_GET['search_id']) ? $_GET['search_id'] : '';
$search_phone = isset($_GET['search_phone']) ? $_GET['search_phone'] : '';

// Запит для отримання всіх користувачів або з фільтрацією
$query = "SELECT * FROM users";
$conditions = [];

if ($search_id) {
    $conditions[] = "id = :search_id";
}
if ($search_phone) {
    $conditions[] = "phone LIKE :search_phone";
}

if (count($conditions) > 0) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}

$stmt = $pdo->prepare($query);

// Якщо є запит на пошук, додаємо параметри для підготовленого запиту
if ($search_id) {
    $stmt->bindValue(':search_id', $search_id);
}
if ($search_phone) {
    $stmt->bindValue(':search_phone', '%' . $search_phone . '%');
}

$stmt->execute();
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Користувачі</title>
    <link rel="stylesheet" href="admin.panel.css">
</head>
<body>
    <header class="admin-header">
        <div class="admin-nav">
            <h1 class="admin-title">Панель адміністратора</h1>
            <nav>
                <ul class="nav-list">
                    <li><a href="admin.panel.php">Головна</a></li>
                    <li><a href="products.php">Товари</a></li>
                    <li><a href="orders.php">Замовлення</a></li>
                    <li><a href="users.php" class="active">Користувачі</a></li>
                    <li><a href="delivery_list.php">Доставка</a></li>
                    <li><a href="discount.php">Акції</a></li>
                    <li><a href="logout.php" class="logout-link">Вихід</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="admin-main">
        <div class="container">
            <h2 class="section-title">Список користувачів</h2>
            
            <!-- Форма пошуку за ID або номером телефону -->
            <form method="GET" action="users.php">
                <input type="text" name="search_id" placeholder="Пошук за ID..." value="<?= htmlspecialchars($search_id) ?>">
                <input type="text" name="search_phone" placeholder="Пошук за номером телефону..." value="<?= htmlspecialchars($search_phone) ?>">
                <button type="submit" class="btn">Пошук</button>
            </form>
            
            <a href="add_user.php" class="btnn">Додати користувача</a>
            
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ім'я</th>
                        <th>Email</th>
                        <th>Телефон</th>
                        <th>Дії</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($users) > 0): ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['id']) ?></td>
                                <td><?= htmlspecialchars($user['name']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><?= htmlspecialchars($user['phone'] ?? 'Не вказано') ?></td>
                                <td>
                                    <a href="delete_user.php?id=<?= $user['id'] ?>" class="btn" onclick="return confirm('Ви впевнені, що хочете видалити цього користувача?');">Видалити</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">Користувачів не знайдено</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
