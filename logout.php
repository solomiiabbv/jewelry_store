<?php
session_start();

// Видаляємо всі сесійні дані
session_unset();
session_destroy();

// Перенаправлення на головну сторінку після виходу
header("Location: index.php");
exit();
?>
