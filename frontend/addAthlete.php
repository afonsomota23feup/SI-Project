<!DOCTYPE html>
<html>
<head>
    <title>Adicionar Atleta</title>
</head>
<body>
    <h1>Adicionar Atleta</h1>
    <form action="../backend/insert_athlete.php" method="POST">
        <label for="name">Nome:</label>
        <input type="text" id="name" name="name" required>
        <br>

        <label for="birthday">Data de Nascimento:</label>
        <input type="date" id="birthday" name="birthday" required>
        <br>

        <label for="genre">Género:</label>
        <input type="text" id="genre" name="genre" required>
        <br>

        <label for="mobile">Telemóvel:</label>
        <input type="text" id="mobile" name="mobile" required>
        <br>

        <label for="address">Endereço:</label>
        <input type="text" id="address" name="address" required>
        <br>

        <label for="ageGroup">Grupo de Idade:</label>
        <input type="text" id="ageGroup" name="ageGroup" required>
        <br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br>

        <label for="password">Senha:</label>
        <input type="password" id="password" name="password" required>
        <br>

        <input type="submit" value="Adicionar Atleta">
    </form>
</body>
</html>
