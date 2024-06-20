<?php
session_start();
include '../backend/db_connect.php';

if (!isset($_GET['athlete_id'])) {
    echo "<p>ID do atleta não especificado.</p>";
    exit;
}

$athlete_id = $_GET['athlete_id'];

try {
    if ($conn === null) {
        throw new Exception("Falha na conexão com o banco de dados.");
    }

    // Consulta para obter os treinos do atleta
    $sql = "SELECT TR.idTrainingReg, TR.performance, TR.dateTrainingReg, CS.name as coach_name
            FROM TrainingReg TR
            JOIN CoachingStaff CS ON TR.idCoachingStaff = CS.idCoachingStaff
            WHERE TR.idAthlete = :athlete_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':athlete_id', $athlete_id);
    $stmt->execute();

    $trainings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Consulta para obter detalhes do atleta
    $sql_athlete = "SELECT name, birthday, genre, mobile, email, address FROM Athlete WHERE idAthlete = :athlete_id";
    $stmt_athlete = $conn->prepare($sql_athlete);
    $stmt_athlete->bindParam(':athlete_id', $athlete_id);
    $stmt_athlete->execute();
    $athlete_details = $stmt_athlete->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "<p>Erro: " . $e->getMessage() . "</p>";
    exit;
}

$conn = null;
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Atleta</title>
    <link rel="stylesheet" href="../static/styles.css">
</head>
<body>
    <header>
        <h1>Detalhes do Atleta</h1>
    </header>
    <main>
        <h2><?= htmlspecialchars($athlete_details['name']) ?></h2>
        <p>Data de Nascimento: <?= htmlspecialchars($athlete_details['birthday']) ?></p>
        <p>Género: <?= htmlspecialchars($athlete_details['genre']) ?></p>
        <p>Telefone: <?= htmlspecialchars($athlete_details['mobile']) ?></p>
        <p>Email: <?= htmlspecialchars($athlete_details['email']) ?></p>
        <p>Endereço: <?= htmlspecialchars($athlete_details['address']) ?></p>

        <h3>Treinos</h3>
        <table>
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Desempenho</th>
                    <th>Treinador</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($trainings as $training): ?>
                <tr>
                    <td><?= htmlspecialchars($training['dateTrainingReg']) ?></td>
                    <td><?= htmlspecialchars($training['performance']) ?></td>
                    <td><?= htmlspecialchars($training['coach_name']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
    <footer>
        <p>&copy; 2024 Gymnastic Club Management Software</p>
    </footer>
</body>
</html>
