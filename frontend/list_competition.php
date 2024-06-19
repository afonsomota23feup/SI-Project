<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selecionar Competição</title>
    <link rel="stylesheet" href="../static/styles.css">
</head>
<body>
    <header>
        <h1>Selecionar Competição</h1>
    </header>
    <main>
        <form action="add_result.php" method="get">
            <label for="competition_id">Selecione a Competição:</label>
            <select id="competition_id" name="competition_id" required>
                <option value="">Selecione a competição</option>
                <?php
                session_start();
                include '../backend/db_connect.php';

                try {
                    if ($conn === null) {
                        throw new Exception("Falha na conexão com o banco de dados.");
                    }

                    $coach_id = $_SESSION['user_id'];
                    $sql = "SELECT idCompetition, name FROM Competition";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();

                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='{$row['idCompetition']}'>{$row['name']}</option>";
                    }
                } catch (Exception $e) {
                    echo "<option disabled selected>Erro ao carregar competições</option>";
                }

                $conn = null;
                ?>
            </select>

            <button type="submit">Adicionar Resultado</button>
        </form>
    </main>
    <footer>
        <p>&copy; 2024 Gymnastic Club Management Software</p>
    </footer>
</body>
</html>
