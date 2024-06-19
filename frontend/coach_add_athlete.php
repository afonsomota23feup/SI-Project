<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Atleta</title>
    <link rel="stylesheet" href="../static/styles.css">
</head>
<body>
    <header>
        <h1>Adicionar Novo Atleta</h1>
    </header>
    <main>
        <form action="../backend/add_athlete.php" method="post">
            <label for="name">Nome:</label>
            <input type="text" id="name" name="name" required>
            <label for="birthdate">Data de Nascimento:</label>
            <input type="date" id="birthdate" name="birthdate" required>
            <label for="gender">Género:</label>
            <select id="gender" name="gender">
                <option value="M">Masculino</option>
                <option value="F">Feminino</option>
            </select>
            <label for="phone">Telefone:</label>
            <input type="tel" id="phone" name="phone" pattern="[0-9]{9}" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="address">Endereço:</label>
            <input type="text" id="address" name="address" required>
            <input type="hidden" name="coach_id" value="<?php echo $_SESSION['user_id']; ?>">
            <button type="submit">Adicionar Atleta</button>
        </form>
    </main>
    <footer>
        <p>&copy; 2024 Gymnastic Club Management Software</p>
    </footer>
</body>
</html>
