<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registar para Atleta</title>
    <link rel="stylesheet" href="../css/schedule_training.css">
</head>
<body>
<div class="container">
    <header>
        <div class="header-content">
                <h1>Registar para Atleta</h1>
            </div>
    </header>
    <div class="logo-content">
                <img src="..\imagens\teste.png" alt="Logo do Clube" class="header-logo">
    </div>
    <main>
        <form action="../backend/schedule_training.php" method="post">
            <label for="athlete">Atleta:</label>
            <select id="athlete" name="athlete" required>
                <option value="">Selecione o atleta</option>
                <?php
                session_start();
                include '../backend/db_connect.php';

                $coach_id = $_SESSION['user_id'];

                try {
                    if ($conn === null) {
                        throw new Exception("Falha na conexão com o banco de dados.");
                    }

                    $sql = "SELECT A.idAthlete, A.name 
                            FROM Athlete A 
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

            <label for="training_date">Data do Treino:</label>
            <input type="date" id="training_date" name="training_date" required>

            <label for="apparatus">Aparelho:</label>
            <select id="apparatus" name="apparatus" required>
                <option value="">Selecione o aparelho</option>
                <?php
                include '../backend/db_connect.php';

                try {
                    if ($conn === null) {
                        throw new Exception("Falha na conexão com o banco de dados.");
                    }

                    $sql = "SELECT idApparatus, name FROM Apparatus";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();

                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='{$row['idApparatus']}'>{$row['name']}</option>";
                    }
                } catch (Exception $e) {
                    echo "<option value=''>Erro ao carregar aparelhos</option>";
                }

                $conn = null;
                ?>
            </select>

            <label for="performance">Performance (0-10):</label>
            <input type="number" id="performance" name="performance" min="0" max="10" step="0.1" required>

            <label for="add_note">Adicionar Nota:</label>
            <input type="checkbox" id="add_note" name="add_note" onchange="toggleNoteSection(this)">
            
            <div class="note-section">
                <label for="note">Nota:</label>
                <textarea id="note" name="note" rows="4" placeholder="Digite uma nota"></textarea>
            </div>

            <button type="submit">Guardar Treino</button>
        </form>
    </main>
    <footer>
        <p>&copy; 2024 Gravity Masters Management Software</p>
    </footer>

    <script>
        function toggleNoteSection(checkbox) {
            var noteSection = document.querySelector('.note-section');
            noteSection.style.display = checkbox.checked ? 'block' : 'none';
        }
    </script>
</div>
</body>
</html>
