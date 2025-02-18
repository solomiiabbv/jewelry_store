<?php
include 'db.php'; // Підключення до бази даних

// Хешуємо паролі
$passwordHrystyna = password_hash('password', PASSWORD_DEFAULT);
$passwordDavid = password_hash('qwerty', PASSWORD_DEFAULT);

// Запит для додавання користувачів з хешованими паролями
$query = "INSERT INTO users (name, email, password, phone, address) VALUES
('Hrystyna', 'hrystyna@gmail.com', '$passwordHrystyna', '+380631234567', 'Київ, вул. Шевченка 10'),
('David', 'david@gmail.com', '$passwordDavid', '+380931234567', 'Львів, вул. Франка 20')";

if ($pdo->exec($query)) {
    echo "Користувачі додані успішно!";
} else {
    echo "Помилка: не вдалося додати користувачів.";
}
?>
