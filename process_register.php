<?php  
session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    try {
        // Підготовка запиту для реєстрації
        $stmt = $pdo->prepare("INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $email, $phone, $password]);

        // Отримуємо останній вставлений id користувача
        $user_id = $pdo->lastInsertId();

        // Збереження даних користувача в сесію після успішної реєстрації
        $_SESSION['user_id'] = $user_id;  // Зберігаємо user_id в сесії
        $_SESSION['user_name'] = $username;  // Можна також зберегти ім'я

        // Перенаправлення на головну сторінку
        header("Location: index.php");
        exit();
    } catch (PDOException $e) {
        echo "Помилка реєстрації: " . $e->getMessage();
    }
}
?>


