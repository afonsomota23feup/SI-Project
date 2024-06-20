<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Treino</title>
    <link rel="stylesheet" href="../static/styles.css">
</head>
<body>
    <header>
        <h1>Detalhes do Treino</h1>
    </header>
    <main>
        <div class="training-details">
            <h2>Detalhes do Treino</h2>
            <?php
            session_start();
            include '../backend/db_connect.php';

            $training_reg_id = $_GET['training_reg_id'];
            $athlete_id = $_SESSION['user_id'];

            try {
                if ($conn === null) {
                    throw new Exception("Falha na conexão com o banco de dados.");
                }

                // Consulta para obter os detalhes do treino e suas notas
                $sql = "SELECT TR.dateTrainingReg, TR.performance, N.description AS notes
                        FROM TrainingReg TR
                        LEFT JOIN Notes N ON TR.idTrainingReg = N.idTrainingReg
                        WHERE TR.idTrainingReg = :training_reg_id AND TR.idAthlete = :athlete_id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':training_reg_id', $training_reg_id);
                $stmt->bindParam(':athlete_id', $athlete_id);
                $stmt->execute();

                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($row) {
                    echo "<p><strong>Data do Treino:</strong> {$row['dateTrainingReg']}</p>";
                    echo "<p><strong>Performance:</strong> {$row['performance']}</p>";
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
        <p>&copy; 2024 Gymnastic Club Management Software</p>
    </footer>
</body>
</html>
