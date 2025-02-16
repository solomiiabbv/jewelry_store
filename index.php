<?php 
session_start();

// Підключення до бази даних
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "jew_store";

$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Помилка підключення: " . $conn->connect_error);
}

// Отримання товарів разом із знижками
$sql = "SELECT p.*, d.discount_percentage 
        FROM products p 
        LEFT JOIN discounts d ON p.id = d.product_id";

$result = $conn->query($sql);

$products_by_category = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $category = $row['category']; // Назва категорії
        $products_by_category[$category][] = $row; // Додаємо товар у відповідну категорію
    }
}

$conn->close();

// Старт сесії для перевірки, чи користувач авторизований


if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Якщо кошик не створений, створюємо його
}


// Додавання товару в кошик
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $quantity = $_POST['quantity'];

    // Перевірка, чи товар уже в кошику
    $product_exists = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] += $quantity;  // Оновлення кількості
            $product_exists = true;
            break;
        }
    }

    // Якщо товар не існує в кошику, додаємо новий товар
    if (!$product_exists) {
        $_SESSION['cart'][] = [
            'id' => $product_id,
            'name' => $product_name,
            'price' => $product_price,
            'quantity' => $quantity
        ];
    }
}
?>


<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jewelry store</title>
    <link rel="stylesheet" href="./style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    
</head>
<body>
    <div class="header">
        <div class="logo-container">
            <a href="">
                <svg class="logo-svg" xmlns="http://www.w3.org/2000/svg" width="150" height="40" viewBox="20 5 200 45">
                    <polygon points="50,10 70,30 50,50 30,30" fill="hotpink" stroke="black" stroke-width="2"/>
                    <polygon points="50,10 60,20 40,20" fill="lightpink" stroke="black" stroke-width="2"/>
                    <polygon points="60,20 70,30 50,50 50,30" fill="hotpink" stroke="black" stroke-width="2"/>
                    <polygon points="40,20 30,30 50,50 50,30" fill="palevioletred" stroke="black" stroke-width="2"/>
                    <text x="80" y="35" font-family="serif" font-size="24" fill="palevioletred">Jewelry store</text>
                </svg>
            </a>
        </div>
        
        <ul class="nav-list">
            <li><a class="nav-list-pidkr" href="about.html">Про нас</a></li>
            <li><a class="nav-list-pidkr" href="#rings">Каблучки</a></li>
            <li><a class="nav-list-pidkr" href="#pendants">Підвіски</a></li>
            <li><a class="nav-list-pidkr" href="#earrings">Сережки</a></li>
            <li><a class="nav-list-pidkr" href="#bracelets">Браслети</a></li>
            <li><a class="nav-list-pidkr" href="#reviews">Відгуки</a></li>
           <a href="cart.php" class="cart-icon">
    <svg width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M3 3H21V5H3V3Z" fill="white"/>
        <path d="M6 7H18L17 19H7L6 7Z" stroke="white" stroke-width="2"/>
    </svg>
    Кошик
    <span id="cart-count">0</span> <!-- Кількість товарів у кошику -->
</a>
            <a href="register.php" class="modal-open-btn">
             <svg xmlns="http://www.w3.org/2000/svg" width="15" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="8" r="4"></circle>
                        <path d="M16 16c-1.6-1-3.4-1.5-4-1.5s-2.4.5-4 1.5c-1.6 1-3 2.7-3 4v1h14v-1c0-1.3-1.4-3-3-4z"></path>
                    </svg>
                    Зареєструватися
</a>

            </li>
            </li>
        </ul>
    </div>


    <?php if (isset($_SESSION['user'])): ?>
    <div class="auth-message logged-in">
        <p>Вітаємо, <?php echo $_SESSION['user']['name']; ?>! <a href="logout.php">Вийти</a></p>
    </div>
<?php else: ?>
    <div class="auth-message logged-out">
        <p>Ви не авторизовані.</p>
    </div>
<?php endif; ?>



    <section class="main-content">
        <div class="welcome-box">
            <h1>Ласкаво просимо до нашого ювелірного магазину!</h1>
            <p>Ознайомтесь з нашою ексклюзивною колекцією ювелірних виробів, створених з любов'ю та витонченістю. Знайдіть щось особливе для кожної нагоди.</p>
        </div>

        <form>
        <input type="text" id="search-input" class = 'search-container' placeholder="Пошук товарів..." onkeyup="searchProducts()">
</form>
        <!-- Product Categories -->
        <section class="product-category" id="rings">
        
            <h2>Каблучки</h2>
            <div class="product-container">
                <div class="product-card">
                    <img src="images/zolotoe-kolco-s-fianitom-swarovski-zirconia-kd4096sw_1.webp" alt="Каблучка з діамантом" class="product-image">
                    <div class="product-info">
                        <h1 class="product-name">Золота каблучка з діамантом</h2>
                        <p class="product-description">Елегантна каблучка з високоякісним діамантом, ідеальна для особливих нагод.</p>
                        <p class="product-price">Ціна: $200</p>
                        <p class="product-stock">Кількість: 8 шт.</p>
                        <form action="index.php" method="POST">
            <input type="hidden" name="product_id" value="6"> <!-- Унікальний ID для статичного товару -->
            <input type="hidden" name="product_name" value="Золота каблучка з діамантом">
            <input type="hidden" name="product_price" value="200">
            <input type="number" name="quantity" value="1" min="1">
            <button type="submit" name="add_to_cart">Додати в кошик</button>
        </form>
                    </div>
                </div>
                <div class="product-card"> 
                    <img src="images/1172rh_serebrjanoe-kolco-1172rh_2_.webp" alt="Срібна каблучка" class="product-image">
                    <div class="product-info">
                        <h1 class="product-name">Срібна каблучка</h2>
                        <p class="product-description">Елегантна каблучка, ідеальна для особливих нагод.</p>
                        <p class="product-price">Ціна: $100</p>
                        <p class="product-stock">Кількість: 9 шт.</p>
                        <form action="index.php" method="POST">
            <input type="hidden" name="product_id" value="7"> <!-- Унікальний ID для статичного товару -->
            <input type="hidden" name="product_name" value="Срібна каблучка">
            <input type="hidden" name="product_price" value="100">
            <input type="number" name="quantity" value="1" min="1">
            <button type="submit" name="add_to_cart">Додати в кошик</button>
        </form>
                    </div>
                </div>
                <div class="product-card"> 
                    <img src="images/s-l1200 (1).jpg" alt="Каблучка з міді" class="product-image">
                    <div class="product-info">
                        <h1 class="product-name">Каблучка з міді</h2>
                        <p class="product-description">Елегантна каблучка, ідеальна для особливих нагод.</p>
                        <p class="product-price">Ціна: $20</p>
                        <p class="product-stock">Кількість: 18 шт.</p>
                        <form action="index.php" method="POST">
            <input type="hidden" name="product_id" value="8"> <!-- Унікальний ID для статичного товару -->
            <input type="hidden" name="product_name" value="Каблучка з міді">
            <input type="hidden" name="product_price" value="20">
            <input type="number" name="quantity" value="1" min="1">
            <button type="submit" name="add_to_cart">Додати в кошик</button>
        </form>
                    </div>
                </div>

                <?php if (!empty($products_by_category['Каблучки'])): ?>
            <?php foreach ($products_by_category['Каблучки'] as $product): ?>
                <?php
                    $original_price = $product['price'];
                    $discount = isset($product['discount_percentage']) ? $product['discount_percentage'] : 0;
                    $discounted_price = $original_price - ($original_price * ($discount / 100));
                    $promotion_type = isset($product['promotion_type']) ? $product['promotion_type'] : ''; // Тип акції
                ?>
                <div class="product-card">
    <?php if ($discount > 0): ?>
        <span class="discount-badge">Акція: <?php echo htmlspecialchars($promotion_type); ?> -<?php echo $discount; ?>%</span>
    <?php endif; ?>
    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">
    <div class="product-info">
        <h1 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h2>
        <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
        <?php if ($discount > 0): ?>
            <p class="product-price">
                <span class="old-price">$<?php echo number_format($original_price, 0); ?></span>
                <span class="new-price">$<?php echo number_format($discounted_price, 0); ?></span>
            </p>
        <?php else: ?>
            <p class="product-price">Ціна: $<?php echo number_format($original_price, 0); ?></p>
        <?php endif; ?>
        <p class="product-stock">Кількість: <?php echo htmlspecialchars($product['stock']); ?> шт.</p>
        <?php if ($product['stock'] > 0): ?>
            <form action="index.php" method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['name']); ?>">
                        <input type="hidden" name="product_price" value="<?php echo ($discount > 0) ? $discounted_price : $original_price; ?>">
                        <input type="number" name="quantity" value="1" min="1">
                        <button type="submit" name="add_to_cart">Додати в кошик</button>
                    </form>
        <?php else: ?>
            <button class="out-of-stock" disabled>Немає в наявності</button>
        <?php endif; ?>
    </div>
</div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

        
        <section class="product-category" id="pendants">
            <h2>Підвіски</h2>
            <div class="product-container">
                <div class="product-card">
                    <img src="images/hiji.png" alt="Золота підвіска" class="product-image">
                    <div class="product-info">
                        <h1 class="product-name">Золота підвіска</h2>
                        <p class="product-description">Елегантна підвіска з високоякісним ізумрудом, ідеальна для особливих нагод.</p>
                        <p class="product-price">Ціна: $500</p>
                        <p class="product-stock">Кількість: 4 шт.</p>
                        <form action="index.php" method="POST">
            <input type="hidden" name="product_id" value="9"> <!-- Унікальний ID для статичного товару -->
            <input type="hidden" name="product_name" value="Золота підвіска">
            <input type="hidden" name="product_price" value="500">
            <input type="number" name="quantity" value="1" min="1">
            <button type="submit" name="add_to_cart">Додати в кошик</button>
        </form>
                    </div>
                    </div>
                <div class="product-card">
                    <img src="images/cliffperl-1.webp" alt="Срібна підвіска" class="product-image">
                    <div class="product-info">
                        <h1 class="product-name">Срібна підвіска</h2>
                        <p class="product-description">Елегантна підвіска, ідеальна для особливих нагод.</p>
                        <p class="product-price">Ціна: $400</p>
                        <p class="product-stock">Кількість: 5 шт.</p>
                        <form action="index.php" method="POST">
            <input type="hidden" name="product_id" value="10"> <!-- Унікальний ID для статичного товару -->
            <input type="hidden" name="product_name" value="Срібна підвіска">
            <input type="hidden" name="product_price" value="400">
            <input type="number" name="quantity" value="1" min="1">
            <button type="submit" name="add_to_cart">Додати в кошик</button>
        </form>
                </div>
        </div>
                <div class="product-card">
                    <img src="images/4DD18FBC-1433-40BC-B87B-5D997C14163D.webp" alt="Підвіска з міді" class="product-image">
                    <div class="product-info">
                        <h1 class="product-name">Підвіска з міді</h2>
                        <p class="product-description">Елегантна підвіска, ідеальна для особливих нагод.</p>
                        <p class="product-price">Ціна: $200</p>
                        <p class="product-stock">Кількість: 3 шт.</p>
                        <form action="index.php" method="POST">
            <input type="hidden" name="product_id" value="11"> <!-- Унікальний ID для статичного товару -->
            <input type="hidden" name="product_name" value="Підвіска з міді">
            <input type="hidden" name="product_price" value="200">
            <input type="number" name="quantity" value="1" min="1">
            <button type="submit" name="add_to_cart">Додати в кошик</button>
        </form>
                    </div>
                </div>
                <!-- Динамічні товари -->
                <?php if (!empty($products_by_category['Підвіски'])): ?>
            <?php foreach ($products_by_category['Підвіски'] as $product): ?>
                <?php
                    $original_price = $product['price'];
                    $discount = isset($product['discount_percentage']) ? $product['discount_percentage'] : 0;
                    $discounted_price = $original_price - ($original_price * ($discount / 100));
                    $promotion_type = isset($product['promotion_type']) ? $product['promotion_type'] : ''; // Тип акції
                ?>
                <div class="product-card">
                    <?php if ($discount > 0): ?>
                        <span class="discount-badge">Акція: <?php echo htmlspecialchars($promotion_type); ?> -<?php echo $discount; ?>%</span>
                    <?php endif; ?>
                    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">
                    <div class="product-info">
                        <h1 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h2>
                        <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
                        <?php if ($discount > 0): ?>
                            <p class="product-price">
                                <span class="old-price">$<?php echo number_format($original_price, 0); ?></span>
                                <span class="new-price">$<?php echo number_format($discounted_price, 0); ?></span>
                            </p>
                        <?php else: ?>
                            <p class="product-price">Ціна: $<?php echo number_format($original_price, 0); ?></p>
                        <?php endif; ?>
                        <p class="product-stock">Кількість: <?php echo htmlspecialchars($product['stock']); ?> шт.</p>
                        <?php if ($product['stock'] > 0): ?>
                            <form action="index.php" method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['name']); ?>">
                        <input type="hidden" name="product_price" value="<?php echo ($discount > 0) ? $discounted_price : $original_price; ?>">
                        <input type="number" name="quantity" value="1" min="1">
                        <button type="submit" name="add_to_cart">Додати в кошик</button>
                    </form>
                        <?php else: ?>
                            <button class="out-of-stock" disabled>Немає в наявності</button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>
        
        <section class="product-category" id="earrings">
            <h2>Сережки</h2>
            <div class="product-container">
                <div class="product-card">
                    <img src="images/shopping.webp" alt="Золоті сережки" class="product-image">
                    <div class="product-info">
                        <h1 class="product-name">Золоті сережки</h2>
                        <p class="product-description">Елегантні сережки у формі клевера, ідеальні для особливих нагод.</p>
                        <p class="product-price">Ціна: $1000</p>
                        <p class="product-stock">Кількість: 6 шт.</p>
                        <form action="index.php" method="POST">
            <input type="hidden" name="product_id" value="12"> <!-- Унікальний ID для статичного товару -->
            <input type="hidden" name="product_name" value="Золоті сережки">
            <input type="hidden" name="product_price" value="1000">
            <input type="number" name="quantity" value="1" min="1">
            <button type="submit" name="add_to_cart">Додати в кошик</button>
        </form>
                    </div>
                </div>
                <div class="product-card">
                    <img src="images/завантаження.jpg" alt="Срібні сережки" class="product-image">
                    <div class="product-info">
                        <h1 class="product-name">Срібні сережки</h2>
                        <p class="product-description">Елегантні сережки, ідеальні для особливих нагод.</p>
                        <p class="product-price">Ціна: $200</p>
                        <p class="product-stock">Кількість: 5 шт.</p>
                        <form action="index.php" method="POST">
            <input type="hidden" name="product_id" value="13"> <!-- Унікальний ID для статичного товару -->
            <input type="hidden" name="product_name" value="Срібні сережки">
            <input type="hidden" name="product_price" value="200">
            <input type="number" name="quantity" value="1" min="1">
            <button type="submit" name="add_to_cart">Додати в кошик</button>
        </form>
                    </div>
                </div>
                <div class="product-card">
                    <img src="images/29997a8b7d131e097311ab165d663a29.webp" alt="Сережки з міді" class="product-image">
                    <div class="product-info">
                        <h1 class="product-name">Сережки з міді</h2>
                        <p class="product-description">Вишукані, ідеальні для особливих нагод.</p>
                        <p class="product-price">Ціна: $20</p>
                        <p class="product-stock">Кількість: 20 шт.</p>
                        <form action="index.php" method="POST">
            <input type="hidden" name="product_id" value="14"> <!-- Унікальний ID для статичного товару -->
            <input type="hidden" name="product_name" value="Сережки з міді">
            <input type="hidden" name="product_price" value="20">
            <input type="number" name="quantity" value="1" min="1">
            <button type="submit" name="add_to_cart">Додати в кошик</button>
        </form>
                    </div>
                </div>
                <?php if (!empty($products_by_category['Сережки'])): ?>
            <?php foreach ($products_by_category['Сережки'] as $product): ?>
                <?php
                    $original_price = $product['price'];
                    $discount = isset($product['discount_percentage']) ? $product['discount_percentage'] : 0;
                    $discounted_price = $original_price - ($original_price * ($discount / 100));
                    $promotion_type = isset($product['promotion_type']) ? $product['promotion_type'] : ''; // Тип акції
                ?>
                <div class="product-card">
                    <?php if ($discount > 0): ?>
                        <span class="discount-badge">Акція: <?php echo htmlspecialchars($promotion_type); ?> -<?php echo $discount; ?>%</span>
                    <?php endif; ?>
                    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">
                    <div class="product-info">
                        <h1 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h2>
                        <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
                        <?php if ($discount > 0): ?>
                            <p class="product-price">
                                <span class="old-price">$<?php echo number_format($original_price, 0); ?></span>
                                <span class="new-price">$<?php echo number_format($discounted_price, 0); ?></span>
                            </p>
                        <?php else: ?>
                            <p class="product-price">Ціна: $<?php echo number_format($original_price, 0); ?></p>
                        <?php endif; ?>
                        <p class="product-stock">Кількість: <?php echo htmlspecialchars($product['stock']); ?> шт.</p>
                        <?php if ($product['stock'] > 0): ?>
                            <form action="index.php" method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['name']); ?>">
                        <input type="hidden" name="product_price" value="<?php echo ($discount > 0) ? $discounted_price : $original_price; ?>">
                        <input type="number" name="quantity" value="1" min="1">
                        <button type="submit" name="add_to_cart">Додати в кошик</button>
                    </form>
                        <?php else: ?>
                            <button class="out-of-stock" disabled>Немає в наявності</button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section> 
        
        <section class="product-category" id="bracelets">
            <h2>Браслети</h2>
            <div class="product-container">
                <div class="product-card">
                    <img src="images/бр1.jpeg" alt="Золотий браслет" class="product-image">
                    <div class="product-info">
                        <h1 class="product-name">Золотий браслет</h2>
                        <p class="product-description">Елегантний браслет з високоякісного золота, ідеальний для особливих нагод.</p>
                        <p class="product-price">Ціна: $300</p>
                        <p class="product-stock">Кількість: 9 шт.</p>
                        <form action="index.php" method="POST">
            <input type="hidden" name="product_id" value="15"> <!-- Унікальний ID для статичного товару -->
            <input type="hidden" name="product_name" value="Золотий браслет">
            <input type="hidden" name="product_price" value="300">
            <input type="number" name="quantity" value="1" min="1">
            <button type="submit" name="add_to_cart">Додати в кошик</button>
        </form>
                        </div>
                </div>
                <div class="product-card">
                    <img src="images/ьр2.webp" alt="Срібний браслет" class="product-image">
                    <div class="product-info">
                        <h1 class="product-name">Срібний браслет</h2>
                        <p class="product-description">Елегантний браслет, ідеальний для особливих нагод.</p>
                        <p class="product-price">Ціна: $150</p>
                        <p class="product-stock">Кількість: 8 шт.</p>
                        <form action="index.php" method="POST">
            <input type="hidden" name="product_id" value="16"> <!-- Унікальний ID для статичного товару -->
            <input type="hidden" name="product_name" value="Срібний браслет">
            <input type="hidden" name="product_price" value="150">
            <input type="number" name="quantity" value="1" min="1">
            <button type="submit" name="add_to_cart">Додати в кошик</button>
        </form>
                </div>
                        </div>
                <div class="product-card">
                    <img src="images/ьр3.webp" alt="Браслет з міді" class="product-image">
                    <div class="product-info">
                        <h1 class="product-name">Браслет з міді</h2>
                        <p class="product-description">Елегантний браслет, ідеальний для особливих нагод.</p>
                        <p class="product-price">Ціна: $30</p>
                        <p class="product-stock">Кількість: 2 шт.</p>
                        <form action="index.php" method="POST">
            <input type="hidden" name="product_id" value="17"> <!-- Унікальний ID для статичного товару -->
            <input type="hidden" name="product_name" value="Браслет з міді">
            <input type="hidden" name="product_price" value="30">
            <input type="number" name="quantity" value="1" min="1">
            <button type="submit" name="add_to_cart">Додати в кошик</button>
        </form>
                </div>
                        </div>
                <?php if (!empty($products_by_category['Браслети'])): ?>
            <?php foreach ($products_by_category['Браслети'] as $product): ?>
                <?php
                    $original_price = $product['price'];
                    $discount = isset($product['discount_percentage']) ? $product['discount_percentage'] : 0;
                    $discounted_price = $original_price - ($original_price * ($discount / 100));
                    $promotion_type = isset($product['promotion_type']) ? $product['promotion_type'] : ''; // Тип акції
                ?>
                <div class="product-card">
    <?php if ($discount > 0): ?>
        <span class="discount-badge">Акція: <?php echo htmlspecialchars($promotion_type); ?> -<?php echo $discount; ?>%</span>
    <?php endif; ?>
    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">
    <div class="product-info">
        <h1 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h2>
        <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
        <?php if ($discount > 0): ?>
            <p class="product-price">
                <span class="old-price">$<?php echo number_format($original_price, 0); ?></span>
                <span class="new-price">$<?php echo number_format($discounted_price, 0); ?></span>
            </p>
        <?php else: ?>
            <p class="product-price">Ціна: $<?php echo number_format($original_price, 0); ?></p>
        <?php endif; ?>
        <p class="product-stock">Кількість: <?php echo htmlspecialchars($product['stock']); ?> шт.</p>
        <?php if ($product['stock'] > 0): ?>
            <form action="index.php" method="POST">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['name']); ?>">
                            <input type="hidden" name="product_price" value="<?php echo ($discount > 0) ? $discounted_price : $original_price; ?>">
                        <input type="number" name="quantity" value="1" min="1">
                        <button type="submit" name="add_to_cart">Додати в кошик</button>
                    </form>
        <?php else: ?>
            <button class="out-of-stock" disabled>Немає в наявності</button>
        <?php endif; ?>
    </div>
</div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>
<script>
    // Функція для оновлення лічильника кошика
    function updateCartCount() {
        // Отримуємо кількість товарів у кошику з PHP (через глобальну змінну)
        var cartCount = <?php echo count($_SESSION['cart'] ?? []); ?>;
        // Оновлюємо лічильник на сторінці
        document.getElementById('cart-count').textContent = cartCount;
    }

    // Оновлюємо лічильник при завантаженні сторінки
    window.onload = updateCartCount;
</script>
<section class="product-category" id="reviews">
<?php
include('db.php'); // Підключення до бази даних

// Виведення відгуків
echo "<div class='reviews-container'>";
echo "<h1 class='reviews-title'>Відгуки</h1>";


// Запит на отримання всіх відгуків з бази даних
$stmt = $pdo->query("SELECT r.review, r.date, u.name AS username FROM reviews r JOIN users u ON r.user_id = u.id ORDER BY r.date DESC");
$reviews = $stmt->fetchAll();

// Виведення відгуків
foreach ($reviews as $review) {
    echo "<div class='review-item'>";
    echo "<p><strong>{$review['username']}</strong> - {$review['date']}</p>";
    echo "<p>{$review['review']}</p>";
    echo "</div>";
}

// Перевірка авторизації користувача для кнопки залишити відгук
if (isset($_SESSION['user'])) {
    echo "<a href='leave-review.php' class='leave-review-btn'>Залишити відгук</a>";
} else {
    echo " <p class = 'shob_zalush_vidhuk'> Щоб залишити відгук, будь ласка, <a href='login.php' class='leave-review-btn' >увійдіть</a>.</p>";
}

echo "</div>";
?>
<script src="search.js"></script>
</body>
</html>
    <!-- Footer with contact info -->
    <footer>
        <div class="footer-container">
            <ul class="footer-links">
                <li><a href="tel:+380682857651">Телефон: +380 68 28 57 651</a></li>
                <li><a href="mailto:solomiabbv.com.ua">Електронна пошта: solomiabbv.com.ua</a></li>
                <li><a href="">Адреса: вул. Чорновола 12, Коломия, Україна</a></li>
            </ul>
        </div>
    </footer>
</body>
</html>