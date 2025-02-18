<?php
session_start();
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Успішне оформлення замовлення</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .success-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .success-container h2 {
            color: #28a745;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .success-container p {
            font-size: 18px;
            margin-bottom: 20px;
        }
        .success-container button {
            padding: 10px 20px;
            background: yellowgreen;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        .success-container button:hover {
            background: #45a049;
        }
    </style>
</head>
<body>
    <div class="success-container">
        <h2>Ваше замовлення успішно оформлено!</h2>
        <p>Дякуємо за покупку! Ваше замовлення скоро буде оброблено.</p>
        <button onclick="window.location.href='index.php'">Повернутися на головну</button>
    </div>
</body>
</html>
