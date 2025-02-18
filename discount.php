<?php 
include('db.php');

// Перевірка, чи є запит на пошук
$search_product_id = isset($_GET['search_product_id']) ? $_GET['search_product_id'] : '';

// Запит для отримання всіх знижок або з фільтрацією за ID товару
$query = "SELECT * FROM discounts";
if ($search_product_id) {
    $query .= " WHERE product_id = :search_product_id";
}

$stmt = $pdo->prepare($query);

// Якщо є запит на пошук, додаємо параметр для підготовленого запиту
if ($search_product_id) {
    $stmt->bindValue(':search_product_id', $search_product_id);
}

$stmt->execute();
$discounts = $stmt->fetchAll();

// Додавання знижки
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_discount'])) {
    $discount_name = $_POST['discount_name'];
    $discount_percentage = $_POST['discount_percentage'];
    $product_id = $_POST['product_id'];
    
    $stmt = $pdo->prepare("INSERT INTO discounts (discount_name, discount_percentage, product_id) VALUES (?, ?, ?)");
    $stmt->execute([$discount_name, $discount_percentage, $product_id]);
    header("Location: discount.php"); // Перезавантажує сторінку після додавання
    exit;
}

// Видалення знижки
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM discounts WHERE id = ?");
    $stmt->execute([$delete_id]);
    header("Location: discount.php"); // Перезавантажує сторінку після видалення
    exit;
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Акції та знижки</title>
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
                    <li><a href="users.php">Користувачі</a></li>
                    <li><a href="delivery_list.php">Доставка</a></li>
                    <li><a href="discount.php" class="active">Акції</a></li>
                    <li><a href="logout.php" class="logout-link">Вихід</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="admin-main">
        <div class="container">
            <h2 class="section-title">Список знижок</h2>
            
            <!-- Форма пошуку за ID товару -->
            <form method="GET" action="discount.php">
                <input type="text" name="search_product_id" placeholder="Пошук за ID товару" value="<?= htmlspecialchars($search_product_id) ?>">
                <button type="submit" class="btn">Пошук</button>
            </form>
            
            <a href="add_discount.php" class="btnn">Додати знижку</a>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID товару</th>
                        <th>Назва знижки</th>
                        <th>Процент знижки</th>
                        <th>Дії</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($discounts as $discount): ?>
                        <tr>
                            <td><?= $discount['product_id'] ?></td>
                            <td><?= htmlspecialchars($discount['discount_name']) ?></td>
                            <td><?= $discount['discount_percentage'] ?>%</td>
                            <td>
                                <a href="edit_discount.php?id=<?= $discount['id'] ?>" class="btn">Редагувати</a>
                                <a href="discount.php?delete_id=<?= $discount['id'] ?>" class="btn" onclick="return confirm('Ви впевнені, що хочете видалити цю знижку?');">Видалити</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
