<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="..\css\login.css">
</head>
<body>
    <div class="banner-container">
    <img class="banner-image" src="..\imagens\logo3.png" alt="Login Image">
        <form action="../backend/login.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
                <div class="error-message">
                <?php
                // Verifica se há um parâmetro de erro na URL vindo de login.php
                if (isset($_GET['error']) && $_GET['error'] == 'login_failed') {
                    echo "Email ou senha incorretos.";
                }
                ?>
                </div>
        </form>
    </div>
    <!-- Área para exibir mensagens de erro -->
    <footer>
        <p>&copy; 2024 Gravity Masters Management Software</p>
    </footer>
</body>
</html>
