<?php
session_start();
include __DIR__ . '/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['competition_id']) && isset($_GET['athlete_id'])) {
    $competition_id = $_GET['competition_id'];
    $athlete_id = $_GET['athlete_id'];

    try {
        if ($conn === null) {
            throw new Exception("Falha na conexão com o banco de dados.");
        }

        // Consulta SQL para obter os resultados da competição para o atleta específico
        $sql = "SELECT R.place, R.score, A.name AS apparatusName
                FROM Result R
                JOIN Apparatus A ON R.idApparatus = A.idApparatus
                WHERE R.idCompetition = :competition_id AND R.idAthlete = :athlete_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':competition_id', $competition_id);
        $stmt->bindParam(':athlete_id', $athlete_id);
        $stmt->execute();

        // Exibe os resultados da competição
        echo "<h2>Resultados da Competição</h2>";
        echo "<table>";
        echo "<thead><tr><th>Posição</th><th>Pontuação</th><th>Aparelho</th></tr></thead><tbody>";

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>{$row['place']}</td>";
            echo "<td>{$row['score']}</td>";
            echo "<td>{$row['apparatusName']}</td>";
            echo "</tr>";
        }

        echo "</tbody></table>";
    } catch (PDOException $e) {
        echo "Erro na consulta SQL: " . htmlspecialchars($e->getMessage());
    } catch (Exception $e) {
        echo "Erro geral: " . htmlspecialchars($e->getMessage());
    }

    $conn = null;
} else {
    echo "Erro: Parâmetros inválidos.";
}
?>
