<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Atleta</title>
    <link rel="stylesheet" href="../css/addAtleta.css">
</head>
<body>
    <header>
        <div class="header-content">
            <img src="..\imagens\teste.png" alt="Logo do Clube" class="header-logo">
            <h1>Adicionar Atleta</h1>
        </div>

    </header>
    <main>
        <section>
            <form action="../backend/insert_athlete.php" method="POST">
                <label for="name">Nome:</label>
                <input type="text" id="name" name="name" required>
                <br>

                <label for="birthday">Data de Nascimento:</label>
                <input type="date" id="birthday" name="birthday" required>
                <br>

                <label for="genre">Género:</label>
                <select id="genre" name="genre">
                    <option value="M">Masculino</option>
                    <option value="F">Feminino</option>
                </select>

                <label for="mobile">Telemóvel:</label>
                <input type="text" id="mobile" name="mobile" required>
                <br>

                <label for="address">Endereço:</label>
                <input type="text" id="address" name="address" required>
                <br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <br>

                <label for="password">Senha:</label>
                <input type="password" id="password" name="password" required>
                <br>

                <button type="submit">Adicionar</button>
            </form>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Gravity Masters Management Software</p>
    </footer>
</body>
</html>
