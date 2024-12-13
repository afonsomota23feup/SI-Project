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
$athlete_id = $_SESSION['user_id'];
$athlete_name = $_SESSION['user_name'];

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="..\css\menu_athlete.css">
</head>
<body>
    <header>
    <div class="header-content">
        <img src="..\imagens\teste.png" alt="Logo do Clube" class="header-logo">
        <h1></h1>
        <nav>
            <ul>
                <li><a href="../backend/logout.php">Logout</a></li>
            </ul>
        </nav>
    </div>
    </header>
    <main>
        <section>
            <h2>Bem-vindo, <?php echo $athlete_name ?></h2>
            <ul>
                <li><a href="list_training.php">Os meus treinos</a></li>
                <li><a href="list_comp.php">As minhas competições</a></li>
                <li><a href="list_all_conditions.php?idAthlete=<?php echo $athlete_id ?>">Os meus testes de condição</a></li>
                <li><a href="lista_all_presences.php?idAthlete=<?php echo $athlete_id ?>">As minhas presenças</a></li>
            </ul>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Gymnastic Club Management Software</p>
    </footer>
</body>
</html>
