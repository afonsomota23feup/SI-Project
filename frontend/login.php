<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="..\css\login.css">
    <style>
        .error-message {
            color: red;
        }
    </style>
</head>
<body>
    <form action="../backend/login.php" method="POST">
        <input type="email" name="email" placeholder="Email" required>
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

    <!-- Área para exibir mensagens de erro -->
    
</body>
</html>
