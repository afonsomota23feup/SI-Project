<?php
session_start();

// Verificar se a variável de sessão 'idCoachingStaff' está definida
if (!isset($_SESSION['user_id'])) {
    // Redirecionar o usuário para a página de login ou exibir uma mensagem de erro
    header("Location: login.html"); // Redireciona para a página de login
    exit(); // Certifica-se de que o script PHP não continua a ser executado após o redirecionamento
}

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu de Funcionalidades</title>
    <link rel="stylesheet" href="../static/styles.css">
</head>
<body>
    <header>
        <h1>Menu de Funcionalidades</h1>
    </header>
    <nav>
        <ul>
            <li><a href="list_athletes.php">Listar Atletas</a></li>
            <li><a href="add_athlete.php">Adicionar Atleta</a></li>
            <li><a href="associate_athlete_discipline.php">Associar Atleta à Disciplina</a></li>
            <li><a href="schedule_training.php">Registar Treino</a></li>
            <li><a href="schedule_competition.php">Marcar Competição</a></li>
            <li><a href="list_competition.php">Adicionar Resultado</a></li>
            <!-- Adicione outros links conforme necessário -->
        </ul>
    </nav>
    <footer>
        <p>&copy; 2024 Gymnastic Club Management Software</p>
    </footer>
</body>
</html>
