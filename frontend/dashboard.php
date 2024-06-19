<?php
session_start();

// Verificar se a variável de sessão 'idCoachingStaff' está definida
if (!isset($_SESSION['user_id'])) {
    // Redirecionar o usuário para a página de login ou exibir uma mensagem de erro
    header("Location: login.html"); // Redireciona para a página de login
    exit(); // Certifica-se de que o script PHP não continua a ser executado após o redirecionamento
}
// Verificar se há mensagens de erro ou sucesso definidas na sessão
$error_message = isset($_SESSION['error']) ? $_SESSION['error'] : "";
$success_message = isset($_SESSION['message']) ? $_SESSION['message'] : "";

// Limpar as mensagens da sessão para que sejam exibidas apenas uma vez
unset($_SESSION['error']);
unset($_SESSION['message']);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="..\static\styles.css">
</head>
<body>
    <header>
        <h1>Dashboard</h1>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="dashboard.html">Dashboard</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section>
            <h2>Bem-vindo, [Nome do Usuário]</h2>
            <ul>
                <li><a href="atheletemanagement.html">Os meus treinos</a></li>
                <li><a href="trainingsessions.html">As minhas competições</a></li>
                <li><a href="competitions.html">As minhas disciplinas</a></li>
            </ul>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Gymnastic Club Management Software</p>
    </footer>
</body>
</html>
