<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Competições</title>
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
        <h1>Minhas Competições</h1>
    </header>
    <main>
        <div class="competition-list">
            <h2> As Lista de Competições</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Local</th>
                        <th>Data de Início</th>
                        <th>Data de Fim</th>
                        <th>Descrição</th>
                        <th>Resultados</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    session_start();
                    include '../backend/db_connect.php';

                    $athlete_id = $_SESSION['user_id']; // Obtém o ID do atleta da sessão

                    try {
                        if ($conn === null) {
                            throw new Exception("Falha na conexão com o banco de dados.");
                        }

                        // Consulta para obter as competições em que o atleta participou
                        $sql = "SELECT DISTINCT C.idCompetition, C.name, C.local, C.startTime, C.endTime, C.description
                                FROM Competition C
                                JOIN Result R ON C.idCompetition = R.idCompetition
                                WHERE R.idAthlete = :athlete_id
                                ORDER BY C.startTime DESC";
                        $stmt = $conn->prepare($sql);
                        $stmt->bindParam(':athlete_id', $athlete_id);
                        $stmt->execute();

                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>";
                            echo "<td>{$row['name']}</td>";
                            echo "<td>{$row['local']}</td>";
                            echo "<td>{$row['startTime']}</td>";
                            echo "<td>{$row['endTime']}</td>";
                            echo "<td>{$row['description']}</td>";
                            echo "<td><a href='comp_result.php?competition_id={$row['idCompetition']}'>Resultados</a></td>";
                            echo "</tr>";
                        }
                    } catch (Exception $e) {
                        echo "<tr><td colspan='6'>Erro: " . $e->getMessage() . "</td></tr>";
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
