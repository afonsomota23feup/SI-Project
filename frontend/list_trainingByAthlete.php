<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Treinos</title>
    <link rel="stylesheet" href="../css/list_training.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
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
        <div class="header-content">
            <img src="..\imagens\teste.png" alt="Logo do Clube" class="header-logo">
            <h1>Meus Treinos</h1>
        </div>
    </header>
    <main>
        <div class="training-list">
            <h2>Lista de Treinos</h2>
            <table>
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Performance</th>
                        <th>Descrição</th>
                        <th>Aparelho</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    session_start();
                    include '../backend/db_connect.php';

                    $athlete_id = $_GET['idAthlete'];

                    try {
                        if ($conn === null) {
                            throw new Exception("Falha na conexão com o banco de dados.");
                        }

                        // Consulta para obter os treinos do atleta com o nome do aparelho
                        $sql = "SELECT tr.*, n.description AS description, a.name AS apparatus_name
                                FROM TrainingReg tr
                                LEFT JOIN Notes n ON tr.idTrainingReg = n.idTrainingReg
                                LEFT JOIN Apparatus a ON tr.apparatus = a.idApparatus
                                WHERE tr.idAthlete = :athlete_id";

                        $stmt = $conn->prepare($sql);
                        $stmt->bindParam(':athlete_id', $athlete_id);
                        $stmt->execute();

                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['dateTrainingReg']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['performance']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['apparatus_name']) . "</td>"; // Nome do aparelho
                            echo "<td><a href='../backend/delete_training.php?idTrainingReg={$row['idTrainingReg']}&idAthlete={$athlete_id}' onclick='return confirm(\"Tem certeza de que deseja excluir este treino?\")'>Excluir</a></td>";
                            echo "</tr>";
                        }
                    } catch (Exception $e) {
                        echo "<tr><td colspan='5'>Erro: " . $e->getMessage() . "</td></tr>";
                    }

                    $conn = null;
                    ?>

                </tbody>
            </table>
        </div>
    </main>
    <footer>
        <p>&copy; 2024 Gravity Masters Management Software</p>
    </footer>
</body>

</html>