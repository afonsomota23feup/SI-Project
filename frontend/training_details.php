<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Treino</title>
    <link rel="stylesheet" href="../css/training_details.css">
</head>
<body>
    <header>
            <div class="header-content">
                <h1>Detalhes do Treino</h1>
            </div>
    </header>
    <main>
    <div class="logo-content">
          <img src="..\imagens\teste.png" alt="Logo do Clube" class="header-logo">
    </div>
        <div class="training-details">
            <?php
            session_start();
            include '../backend/db_connect.php';

            $training_id = $_GET['training_id'];
            $athlete_id = $_SESSION['user_id'];

            try {
                if ($conn === null) {
                    throw new Exception("Falha na conexão com o banco de dados.");
                }

                // Consulta para obter os detalhes do treino
                $sql = "SELECT T.date, D.name as discipline, T.description, N.notes
                        FROM Training T
                        JOIN Discipline D ON T.idDiscipline = D.idDiscipline
                        LEFT JOIN Notes N ON T.idTraining = N.idTraining AND N.idAthlete = :athlete_id
                        WHERE T.idTraining = :training_id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':training_id', $training_id);
                $stmt->bindParam(':athlete_id', $athlete_id);
                $stmt->execute();

                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($row) {
                    echo "<p><strong>Data:</strong> {$row['date']}</p>";
                    echo "<p><strong>Disciplina:</strong> {$row['discipline']}</p>";
                    echo "<p><strong>Descrição:</strong> {$row['description']}</p>";
                    echo "<p><strong>Notas:</strong> " . ($row['notes'] ? $row['notes'] : 'Sem notas') . "</p>";
                } else {
                    echo "<p>Detalhes do treino não encontrados.</p>";
                }
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
