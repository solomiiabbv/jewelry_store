<!DOCTYPE html> 
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Реєстрація</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="auth-container">
    <div class="auth-form">
        <button class="close-btn" onclick="closeRegister()">×</button>
        <h2>Реєстрація</h2>
        <form action="process_register.php" method="POST">
            <label for="register-username">Ім'я:</label>
            <input type="text" id="register-username" name="username" required>
            
            <label for="register-email">Email:</label>
            <input type="email" id="register-email" name="email" required>
            
            <label for="register-phone">Номер телефону:</label>
            <input type="tel" id="register-phone" name="phone" required>
            
            <label for="register-password">Пароль:</label>
            <input type="password" id="register-password" name="password" required>
            
            <button type="submit">Зареєструватися</button>
        </form>
        <p>Вже маєте акаунт? <a href="login.php">Увійти</a></p>
    </div>
</div>

<script>
    function closeRegister() {
        window.location.href = 'index.php';
    }
</script>

</body>
</html>





