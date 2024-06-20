<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados da Competição</title>
    <link rel="stylesheet" href="../static/styles.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <header>
        <h1>Resultados da Competição</h1>
    </header>
    <main>
        <div class="competition-results">
            <?php
            session_start();
            include '../backend/db_connect.php';

            // Verificar se o ID da competição foi fornecido via GET
            if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['competition_id']) && isset($_GET['athlete_id'])) {
                $competition_id = $_GET['competition_id'];
                $athlete_id = $_GET['athlete_id'];


                try {
                    if ($conn === null) {
                        throw new Exception("Falha na conexão com o banco de dados.");
                    }

                    // Consulta para obter os resultados da competição específica para o atleta selecionado
                    $sql = "SELECT A.name AS athlete_name, R.place, R.score, AP.name AS apparatus_name
                        FROM Result R
                        JOIN Athlete A ON R.idAthlete = A.idAthlete
                        JOIN Apparatus AP ON R.idAparatus = AP.idApparatus
                        WHERE R.idCompetition = :competition_id
                        AND R.idAthlete = :athlete_id
                        ORDER BY R.place";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':competition_id', $competition_id, PDO::PARAM_INT);
                    $stmt->bindParam(':athlete_id', $athlete_id, PDO::PARAM_INT);
                    $stmt->execute();

                    echo "<table>";
                echo "<thead><tr><th>Atleta</th><th>Aparelho</th><th>Posição</th><th>Pontuação</th></tr></thead>";
                echo "<tbody>";
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>{$row['athlete_name']}</td>";
                    echo "<td>{$row['apparatus_name']}</td>";
                    echo "<td>{$row['place']}</td>";
                    echo "<td>{$row['score']}</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                    
                } catch (Exception $e) {
                    echo "<p>Erro: " . $e->getMessage() . "</p>";
                } finally {
                    // Fechar conexão com o banco de dados
                    $conn = null;
                }
            } else {
                echo "<p>ID da competição ou do atleta não fornecido.</p>";
            }
            ?>
        </div>
    </main>
    <footer>
        <p>&copy; 2024 Gymnastic Club Management Software</p>
    </footer>
</body>
</html>
