<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu de Funcionalidades</title>
    <link rel="stylesheet" href="../css/menu_coach.css">
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo-content">
                <img src="../imagens/teste.png" alt="Logo do Clube" class="header-logo">
            </div>
            <h1 class="page-title">Menu de Funcionalidades</h1>
            <button class="logout-button" onclick="window.location.href='../backend/logout.php'">Logout</button>
        </div>
    </header>
    <nav>
        <ul>
            <li><a href="list_athletes.php">Atletas</a></li>
            <li><a href="schedule_competition.php">Marcar Competição</a></li>
            <li><a href="registerCoach.php">Registar Novo Treinador</a></li>
            <li><a href="view_attendance.php">Ver e Exportar Presenças</a></li>
        </ul>
    </nav>
    <main>
        <!-- Exibir mensagens de erro ou sucesso -->
        <?php if (!empty($error_message)): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <?php if (!empty($success_message)): ?>
            <div class="success"><?php echo $success_message; ?></div>
        <?php endif; ?>
    </main>
    <footer>
        <p>&copy; 2024 Gravity Masters Management Software</p>
    </footer>
</body>
</html>
