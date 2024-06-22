<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Testes de Condição</title>
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
            <h1>Testes de Condição</h1>
        </div>
    </header>
    <main>
        <div class="training-list">
            <h2>Lista de Testes de Condição</h2>
            <table>
                <thead>
                    <tr>
                        <th>Data do Teste</th>
                        <th>Peso</th>
                        <th>Altura</th>
                        <th>Flexibilidade das Costas</th>
                        <th>Impulso Vertical</th>
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

                        // Consulta para obter os testes de condição do atleta
                        $sql = "SELECT dateTest, weight, height, backFlexibility, verticalThrust
                                FROM ConditionTest
                                WHERE idAthlete = :athlete_id";

                        $stmt = $conn->prepare($sql);
                        $stmt->bindParam(':athlete_id', $athlete_id);
                        $stmt->execute();

                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['dateTest']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['weight']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['height']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['backFlexibility']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['verticalThrust']) . "</td>";
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
