<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados da Competição</title>
    <link rel="stylesheet" href="../css/comp_result.css">
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
        .search-container {
            margin-bottom: 20px;
        }
        .search-container input[type=text] {
            padding: 6px;
            margin-top: 8px;
            font-size: 17px;
            border: none;
            width: 80%;
        }
        .search-container button {
            float: right;
            padding: 6px 10px;
            margin-top: 8px;
            margin-right: 16px;
            background: #ddd;
            font-size: 17px;
            border: none;
            cursor: pointer;
        }
        .search-container select {
            padding: 6px;
            margin-top: 8px;
            font-size: 17px;
            border: none;
            width: 80%;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <h1>Resultados da Competição</h1>
        </div>
        <div class="logo-content">
            <img src="..\imagens\teste.png" alt="Logo do Clube" class="header-logo">
        </div>
    </header>
    <main>
        <div class="competition-results">
            <h2>Resultados da Competição</h2>
            <?php
            session_start();
            include '../backend/db_connect.php';

            $athlete_id = $_SESSION['user_id'];
            $competition_id = $_GET['competition_id'];

            try {
                if ($conn === null) {
                    throw new Exception("Falha na conexão com o banco de dados.");
                }

                // Consulta para obter os resultados da competição para o atleta específico
                $sql = "SELECT A.name AS athlete_name, R.place, R.score, AP.name AS apparatus_name
                        FROM Result R
                        JOIN Athlete A ON R.idAthlete = A.idAthlete
                        JOIN Apparatus AP ON R.idAparatus = AP.idApparatus
                        WHERE R.idCompetition = :competition_id
                        AND R.idAthlete = :athlete_id
                        ORDER BY R.place";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':competition_id', $competition_id);
                $stmt->bindParam(':athlete_id', $athlete_id);
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
                echo "</table>";

            } catch (Exception $e) {
                echo "<p>Erro: " . $e->getMessage() . "</p>";
            }

            $conn = null;
            ?>
        </div>
    </main>
    <footer>
        <p>&copy; 2024 Gravity Masters Management Software</p>
    </footer>
</body>
</html>
