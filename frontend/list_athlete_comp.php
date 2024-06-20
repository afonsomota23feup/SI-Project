<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados do Atleta</title>
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
        <h1>Resultados do Atleta</h1>
    </header>
    <main>
        <div class="search-container">
            <select id="athleteSelect">
                <option value="">Selecione um atleta</option>
                <?php
                session_start();
                include '../backend/db_connect.php';

                try {
                    if ($conn === null) {
                        throw new Exception("Falha na conexão com o banco de dados.");
                    }

                    // Consulta para obter os atletas associados ao treinador
                    $coach_id = $_SESSION['user_id'];
                    $sql = "SELECT A.idAthlete, A.name FROM Athlete A
                            JOIN AthleteDiscipline AD ON A.idAthlete = AD.idAthlete
                            JOIN CoachingStaffDiscipline CD ON AD.idDiscipline = CD.idDiscipline
                            WHERE CD.idCoachingStaff = :coach_id";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':coach_id', $coach_id);
                    $stmt->execute();

                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='{$row['idAthlete']}'>{$row['name']}</option>";
                    }
                } catch (Exception $e) {
                    echo "<option value=''>Erro ao carregar atletas</option>";
                }

                $conn = null;
                ?>
            </select>
        </div>
        <div id="resultsContainer">
            <!-- Resultados do atleta serão exibidos aqui -->
        </div>
    </main>
    <footer>
        <p>&copy; 2024 Gymnastic Club Management Software</p>
    </footer>

    <script>
        document.getElementById('athleteSelect').addEventListener('change', function() {
            var athleteId = this.value;
            if (athleteId) {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById('resultsContainer').innerHTML = this.responseText;
                    }
                };
                xhttp.open("GET", "../backend/get_competitions_by_ahlete.php?athlete_id=" + athleteId, true);
                xhttp.send();
            } else {
                document.getElementById('resultsContainer').innerHTML = "";
            }
        });
    </script>
</body>
</html>
