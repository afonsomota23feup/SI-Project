<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Atletas</title>
    <link rel="stylesheet" href="../static/styles.css">
</head>
<body>
    <header>
        <h1>Lista de Atletas</h1>
    </header>
    <main>
        <div class="athlete-list">
            <h2>Os Meus Atletas</h2>
            <ul>
                <?php
                session_start();
                include '../backend/db_connect.php';

                $coach_id = $_SESSION['user_id'];

                try {
                    if ($conn === null) {
                        throw new Exception("Falha na conexão com o banco de dados.");
                    }

                    // Consulta para obter os atletas associados às disciplinas do treinador
                    $sql = "SELECT DISTINCT A.idAthlete, A.name 
                            FROM Athlete A 
                            JOIN AthleteDiscipline AD ON A.idAthlete = AD.idAthlete
                            JOIN CoachingStaffDiscipline CD ON AD.idDiscipline = CD.idDiscipline
                            WHERE CD.idCoachingStaff = :coach_id";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':coach_id', $coach_id);
                    $stmt->execute();

                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<li>{$row['name']} - <a href='assign_discipline.php?athlete_id={$row['idAthlete']}'>Associar Disciplina</a></li>";
                    }
                } catch (Exception $e) {
                    echo "Erro: " . $e->getMessage();
                }

                $conn = null;
                ?>
                <button onclick="window.location.href='assign_discipline.php'">Adicionar Atleta</button>
            </ul>
        </div>
    </main>
    <footer>
        <p>&copy; 2024 Gymnastic Club Management Software</p>
    </footer>
</body>
</html>
