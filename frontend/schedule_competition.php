<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marcar Competição</title>
    <link rel="stylesheet" href="../css/schedule_competition.css">
</head>
<body>
    <header>
        <div class="header-content">
                <img src="..\imagens\teste.png" alt="Logo do Clube" class="header-logo">
                <h1>Marcar Competição</h1>
            </div>
    </header>
    <main>
        <form action="../backend/schedule_competition.php" method="post">
            <label for="competition_name">Nome da Competição:</label>
            <input type="text" id="competition_name" name="competition_name" required>
            <label for="competition_location">Local da Competição:</label>
            <input type="text" id="competition_location" name="competition_location" required>
            <label for="competition_start_time">Data e Hora de Início:</label>
            <input type="datetime-local" id="competition_start_time" name="competition_start_time" required>
            <label for="competition_end_time">Data e Hora de Fim:</label>
            <input type="datetime-local" id="competition_end_time" name="competition_end_time" required>
            <label for="competition_description">Descrição:</label>
            <textarea id="competition_description" name="competition_description" rows="4" required></textarea>
            <button type="submit">Marcar Competição</button>
        </form>
    </main>
    <footer>
        <p>&copy; 2024 Gravity Masters Management Software</p>
    </footer>
</body>
</html>
