<?php
session_start();
include __DIR__ . '/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['athlete_id'])) {
    $athlete_id = $_GET['athlete_id'];

    try {
        if ($conn === null) {
            throw new Exception("Falha na conexão com o banco de dados.");
        }

        // Consulta para obter os resultados das competições do atleta
        $sql = "SELECT Competition.name AS competitionName, Competition.startTime AS competitionDate, Competition.local AS competitionLocation,
                Apparatus.name AS apparatusName, Result.place, Result.score
            FROM Result
            JOIN Competition ON Result.idCompetition = Competition.idCompetition
            JOIN Apparatus ON Result.idAparatus = Apparatus.idApparatus
            WHERE Result.idAthlete = :athlete_id";
    

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':athlete_id', $athlete_id, PDO::PARAM_INT);
        $stmt->execute();

        // Verifica se existem resultados
        if ($stmt->rowCount() > 0) {
            echo "<table>";
            echo "<thead><tr><th>Competição</th><th>Data</th><th>Local</th><th>Aparato</th><th>Posição</th><th>Pontuação</th></tr></thead><tbody>";

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>{$row['competitionName']}</td>";
                echo "<td>{$row['competitionDate']}</td>";
                echo "<td>{$row['competitionLocation']}</td>";
                echo "<td>{$row['apparatusName']}</td>";
                echo "<td>{$row['place']}</td>";
                echo "<td>{$row['score']}</td>";
                echo "</tr>";
                var_dump($row); // Debug dos dados retornados
            }

            echo "</tbody></table>";
        } else {
            echo "O atleta selecionado não tem resultados registrados em competições.";
        }
    } catch (Exception $e) {
        echo "Erro: " . $e->getMessage();
    } finally {
        // Fechar conexão com o banco de dados
        $conn = null;
    }
}
?>
