<?php 
session_start();
include('db.php');

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Логіка для обробки відгуку
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $review = trim($_POST['review']);
    $user_id = $_SESSION['user']['id'];

    if (!empty($review)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO reviews (user_id, review) VALUES (:user_id, :review)");
            $stmt->execute(['user_id' => $user_id, 'review' => $review]);
            $success_message = "Дякуємо за ваш відгук!";
        } catch (PDOException $e) {
            $error_message = "Сталася помилка при збереженні відгуку. Спробуйте ще раз.";
        }
    } else {
        $error_message = "Будь ласка, введіть ваш відгук перед відправкою.";
    }
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Залишити відгук</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="review-container">
    <h2>Залишити відгук</h2>

    <?php if (!empty($success_message)): ?>
        <p class="message"><?= $success_message; ?></p>
        <a href="index.php" class="back-home">Повернутися на головну</a>
    <?php elseif (!empty($error_message)): ?>
        <p class="error"><?= $error_message; ?></p>
    <?php endif; ?>

    <form method="POST" class="review-form">
        <textarea name="review" placeholder="Ваш відгук..."></textarea>
        <button type="submit">Надіслати відгук</button>
    </form>
</div>

</body>
</html>





