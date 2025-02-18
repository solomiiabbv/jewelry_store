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
            'name' => $user['name'],
            'role' => $user['user_role'] // Зберігаємо роль користувача
        ];

        // Перенаправлення в залежності від ролі користувача
        if ($user['user_role'] == 'admin') {
            header("Location: admin.panel.php"); // Панель адміністратора
        } else {
            header("Location: index.php"); // Головна сторінка для звичайного користувача
        }
        exit();
    } else {
        // Виведення повідомлення про помилку
        $_SESSION['error'] = 'Невірний email або пароль.';
        header("Location: login.php"); // Повернення на сторінку входу з помилкою
        exit();
    }
}
?>






