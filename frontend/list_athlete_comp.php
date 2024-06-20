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
        <h1>Competições dos Atletas</h1>
    </header>
    <main>
        <div class="competition-list">
            <h2>Lista de Competições</h2>

            <form method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="search-container">
                    <label for="athlete_id">Selecionar Atleta:</label>
                    <select name="athlete_id" id="athlete_id">
                        <?php
                        session_start();
                        include '../backend/db_connect.php';

                        // Obtém o ID do treinador da sessão
                        $coach_id = $_SESSION['user_id'];

                        try {
                            if ($conn === null) {
                                throw new Exception("Falha na conexão com o banco de dados.");
                            }

                            // Consulta para obter os atletas do treinador
                            $sql = "SELECT A.idAthlete, A.name
                                    FROM Athlete A
                                    JOIN AthleteDiscipline AD ON A.idAthlete = AD.idAthlete
                                    JOIN CoachingStaffDiscipline CD ON AD.idDiscipline = CD.idDiscipline
                                    WHERE CD.idCoachingStaff = :coach_id";
                            $stmt = $conn->prepare($sql);
                            $stmt->bindParam(':coach_id', $coach_id, PDO::PARAM_INT);
                            $stmt->execute();

                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $selected = ($_GET['athlete_id'] == $row['idAthlete']) ? 'selected' : '';
                                echo "<option value='{$row['idAthlete']}' $selected>{$row['name']}</option>";
                            }
                        } catch (Exception $e) {
                            echo "<option value=''>Erro ao carregar atletas: " . $e->getMessage() . "</option>";
                        }

                        $conn = null;
                        ?>
                    </select>
                    <button type="submit">Selecionar</button>
                </div>
            </form>

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
                    include '../backend/db_connect.php';

                    // Processar o formulário e exibir os resultados das competições do atleta selecionado
                    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['athlete_id'])) {
                        $athlete_id = $_GET['athlete_id'];

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
                            $stmt->bindParam(':athlete_id', $athlete_id, PDO::PARAM_INT);
                            $stmt->execute();

                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr>";
                                echo "<td>{$row['name']}</td>";
                                echo "<td>{$row['local']}</td>";
                                echo "<td>{$row['startTime']}</td>";
                                echo "<td>{$row['endTime']}</td>";
                                echo "<td>{$row['description']}</td>";
                                echo "<td><a href='comp_result2.php?competition_id={$row['idCompetition']}&athlete_id={$athlete_id}'>Resultados</a></td>";
                                echo "</tr>";
                            }
                        } catch (Exception $e) {
                            echo "<tr><td colspan='6'>Erro: " . $e->getMessage() . "</td></tr>";
                        }

                        $conn = null;
                    }
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
