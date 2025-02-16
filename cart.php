<?php 
  ini_set('session.cookie_lifetime', 3600);
session_start();


// Перевірка наявності товарів у кошику
if (empty($_SESSION['cart'])) {
    echo "<div class='empty-cart-message'>";
    echo "<p>Ваш кошик порожній.</p>";
    echo "<a href='index.php' class='continue-shopping'>Перейти до покупок</a>";
    echo "</div>";
}
 else {
    echo "<div class='cart-container'>";
    echo "<h2>Ваш кошик:</h2>";
    echo "<form action='cart.php' method='POST'>";
    echo "<ul>";
    $total = 0;
    
    // Перебір товарів у кошику
    foreach ($_SESSION['cart'] as $key => $item) {
        $item_total = $item['price'] * $item['quantity'];
        $total += $item_total;

        // Виведення товару з новою структурою
        echo "<li class='cart-item'> 
                <div class='item-details'>
                    <span class='item-name'>{$item['name']}</span>
                    <span class='item-price'>Ціна за одиницю: $ {$item['price']}</span>
                </div>
                <div class='item-quantity'>
                    <label for='quantity[{$key}]'>Кількість:</label>
                    <input type='number' name='quantity[{$key}]' value='{$item['quantity']}' min='1'>
                </div>
                <div class='item-total'>
                    <span>Загальна ціна: $ {$item_total}</span>
                </div>
                <a href='cart.php?remove={$key}' class='remove-item'>Видалити</a>
              </li>";
    }
    
    echo "</ul>";
    echo "<p class='total'>Загальна сума: $ {$total}</p>";
    
    // Кнопка оновлення кошика
    echo "<div class='buttons'>";
    echo "<button type='submit' name='update_cart'>Оновити кошик</button>";
    echo "</div>";
    echo "</form>";
    
    // Кнопка оформлення замовлення
    echo "<form action='order.php' method='POST'>";
    echo "<div class='buttons'>
            <button type='submit'>Оформити замовлення</button>
          </div>";
    echo "</form>";
    
    // Кнопка для продовження покупок
    echo "<a href='index.php' class='continue-shopping'>Продовжити покупки</a>";
    echo "</div>";
}

// Обробка зміни кількості товару
if (isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $key => $new_quantity) {
        if ($new_quantity > 0) {
            $_SESSION['cart'][$key]['quantity'] = $new_quantity;
        }
    }
    // Перезавантаження сторінки після оновлення кошика
    header("Location: cart.php");
    exit();
}

// Обробка видалення товару
if (isset($_GET['remove'])) {
    $key = $_GET['remove'];
    unset($_SESSION['cart'][$key]);
    // Перезавантаження сторінки після видалення товару
    header("Location: cart.php");
    exit();
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
