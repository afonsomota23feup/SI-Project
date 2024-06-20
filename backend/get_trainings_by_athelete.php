<?php
session_start();
include __DIR__ . '/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['athlete_id'])) {
    $athlete_id = $_GET['athlete_id'];
    $coach_id = $_SESSION['user_id'];

    try {
        if ($conn === null) {
            throw new Exception("Falha na conexão com o banco de dados.");
        }

        // Consulta para obter os treinos do atleta relacionados ao treinador
        $sql = "SELECT T.dateTrainingReg, T.performance, N.description FROM TrainingReg T
                LEFT JOIN Notes N ON T.idTrainingReg = N.idTrainingReg
                WHERE T.idAthlete = :athlete_id AND T.idCoachingStaff = :coach_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':athlete_id', $athlete_id);
        $stmt->bindParam(':coach_id', $coach_id);
        $stmt->execute();

        echo "<table>";
        echo "<thead><tr><th>Data</th><th>Performance</th><th>Notas</th></tr></thead><tbody>";

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>{$row['dateTrainingReg']}</td>";
            echo "<td>{$row['performance']}</td>";
            echo "<td>{$row['description']}</td>";
            echo "</tr>";
        }

        echo "</tbody></table>";
    } catch (Exception $e) {
        echo "Erro: " . $e->getMessage();
    }

    $conn = null;
}
?>
