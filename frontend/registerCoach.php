<?php
// Configuração da conexão com o banco de dados
include __DIR__ . '/../backend/db_connect.php'; // Usando __DIR__ para garantir que o caminho é relativo ao diretório atual

// Fetch disciplines to display in the drop-down
try {
    $stmt = $conn->prepare("SELECT idDiscipline, name FROM Discipline");
    $stmt->execute();
    $disciplines = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../css/register.css">
</head>
<body>
    <header>
        <h1>Registro</h1>
    </header>
    <main>
        <form action="../backend/register.php" method="POST">
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
            <label for="password">Senha:</label>
            <input type="password" id="password" name="password" required>
            <label for="role">Tipo de Usuário:</label>
            <select id="role" name="role">
                <option value="coach">Treinador</option>
                <option value="athlete">Coordenador</option>
            </select>
            <label for="discipline_id">Disciplina:</label>
            <select id="discipline_id" name="discipline_id" required>
                <?php foreach ($disciplines as $discipline): ?>
                    <option value="<?php echo htmlspecialchars($discipline['idDiscipline']); ?>">
                        <?php echo htmlspecialchars($discipline['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Registrar</button>
        </form>
    </main>
    <footer>
        <p>&copy; 2024 Gymnastic Club Management Software</p>
    </footer>
</body>
</html>
