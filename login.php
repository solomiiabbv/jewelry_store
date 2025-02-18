<!DOCTYPE html> 
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вхід / Реєстрація</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="auth-container">
    <div class="auth-form">
        <button class="close-btn" onclick="closeRegister()">×</button>
        
        <!-- Вхід -->
        <div id="login-section" class="auth-section">
            <h2>Вхід</h2>
            <form action="process_login.php" method="POST">
                <label for="login-email">Email:</label>
                <input type="email" id="login-email" name="email" required>
                
                <label for="login-password">Пароль:</label>
                <input type="password" id="login-password" name="password" required>

                <button type="submit">Увійти</button>
            </form>
            
            <!-- Повідомлення про помилки входу -->
            <?php
            session_start();
            if (isset($_SESSION['error'])) {
                echo "<p class='error-message'>" . $_SESSION['error'] . "</p>";
                unset($_SESSION['error']);
            }
            ?>

            <p>Ще не маєте акаунту? <a href="register.php">Зареєструватися</a></p>
        </div>
    </div>
</div>

<script>
    function closeRegister() {
        window.location.href = 'index.php'; // перенаправлення на головну сторінку
    }
</script>

</body>
</html>
