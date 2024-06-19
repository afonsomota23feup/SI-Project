<?php
session_start();
include '../backend/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $competition_id = $_GET['competition_id'];
    

    try {
        if ($conn === null) {
            throw new Exception("Falha na conexão com o banco de dados.");
        }

        $coach_id = $_SESSION['user_id'];

        // Consulta para listar atletas do treinador para esta competição
        $sql_athletes = "SELECT A.idAthlete, A.name 
                         FROM Athlete A 
                         JOIN AthleteDiscipline AD ON A.idAthlete = AD.idAthlete
                         JOIN CoachingStaffDiscipline CD ON AD.idDiscipline = CD.idDiscipline
                         WHERE CD.idCoachingStaff = :coach_id";
        $stmt_athletes = $conn->prepare($sql_athletes);
        $stmt_athletes->bindParam(':coach_id', $coach_id);
        $stmt_athletes->execute();

    } catch (Exception $e) {
        echo "Erro: " . $e->getMessage();
    }

    $conn = null;
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inserir Resultado</title>
    <link rel="stylesheet" href="../static/styles.css">
</head>
<body>
    <header>
        <h1>Inserir Resultado</h1>
    </header>
    <main>
        <form action="../backend/insert_result.php" method="post">
            <input type="hidden" name="competition_id" value="<?php echo $competition_id; ?>">

            <label for="athlete">Selecione o Atleta:</label>
            <select id="athlete" name="athlete" required>
                <option value="">Selecione o atleta</option>
                <?php
                while ($row = $stmt_athletes->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='{$row['idAthlete']}'>{$row['name']}</option>";
                }
                ?>
            </select>

            <label for="apparatus">Selecione o Aparelho:</label>
            <select id="apparatus" name="apparatus" required>
                <option value="">Selecione o aparelho</option>
                <?php
                include '../backend/db_connect.php';

                try {
                    if ($conn === null) {
                        throw new Exception("Falha na conexão com o banco de dados.");
                    }

                    $sql = "SELECT idApparatus, name FROM Apparatus";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();

                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='{$row['idApparatus']}'>{$row['name']}</option>";
                    }
                } catch (Exception $e) {
                    echo "<option disabled selected>Erro ao carregar aparelhos</option>";
                }

                $conn = null;
                ?>
            </select>

            <label for="place">Posição:</label>
            <input type="number" id="place" name="place" min="1" required>

            <label for="score">Score:</label>
            <input type="number" id="score" name="score" step="0.01" required>

            <button type="submit">Inserir Resultado</button>
        </form>
    </main>
    <footer>
        <p>&copy; 2024 Gymnastic Club Management Software</p>
    </footer>
</body>
</html>
