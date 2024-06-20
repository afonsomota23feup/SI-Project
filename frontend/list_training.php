<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Treinos</title>
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
        <h1>Meus Treinos</h1>
    </header>
    <main>
        <div class="training-list">
            <h2>Lista de Treinos</h2>
            <table>
                <thead>
                    <tr>
                        <th>Data do Treino</th>
                        <th>Performance</th>
                        <th>Detalhes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    session_start();
                    include '../backend/db_connect.php';

                    $athlete_id = $_SESSION['user_id'];

                    try {
                        if ($conn === null) {
                            throw new Exception("Falha na conexão com o banco de dados.");
                        }

                        // Consulta para obter os treinos do atleta
                        $sql = "SELECT idTrainingReg, dateTrainingReg, performance
                                FROM TrainingReg
                                WHERE idAthlete = :athlete_id
                                ORDER BY dateTrainingReg DESC";
                        $stmt = $conn->prepare($sql);
                        $stmt->bindParam(':athlete_id', $athlete_id);
                        $stmt->execute();

                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>";
                            echo "<td>{$row['dateTrainingReg']}</td>";
                            echo "<td>{$row['performance']}</td>";
                            echo "<td><a href='training_details.php?training_reg_id={$row['idTrainingReg']}'>Detalhes</a></td>";
                            echo "</tr>";
                        }
                    } catch (Exception $e) {
                        echo "<tr><td colspan='3'>Erro: " . $e->getMessage() . "</td></tr>";
                    }

                    $conn = null;
                    ?>
                </tbody>
            </table>
        </div>
    </main>
    <footer>
        <p>&copy; 2024 Gymnastic Club Management Software</p>
    </footer>
</body>
</html>
