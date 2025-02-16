<?php
session_start();
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Перевірка, чи є користувач в базі
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Зберігаємо користувача в сесії
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name']
        ];

        // Перенаправлення на головну сторінку після успішного входу
        header("Location: index.php");
        exit();
    } else {
        // Виведення повідомлення про помилку
        echo "<p>Невірний email або пароль.</p>";
    }
}
?>





