<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Avaliação</title>
    <link rel="stylesheet" href="../css/condition_add.css"> <!-- Link to the CSS file -->
</head>
<body>
    <header>
        <div class="header-content">
            <h1>Adicionar Avaliação</h1>
        </div>
        <div class="logo-content">
            <img src="../imagens/teste.png" alt="Logo do Clube" class="header-logo">
        </div>
    </header>
    <main>
        <form action="../backend/insert_condition_test.php" method="post">
            <label for="athlete">Selecione o Atleta:</label>
            <select id="athlete" name="athlete" required>
                <option value="">Selecione o atleta</option>
                <?php
                session_start();
                include '../backend/db_connect.php';

                // Fetch athletes of the logged-in coach
                $coach_id = $_SESSION['user_id'];
                $sql = "SELECT A.idAthlete, A.name 
                        FROM Athlete A 
                        JOIN AthleteDiscipline AD ON A.idAthlete = AD.idAthlete
                        JOIN CoachingStaffDiscipline CD ON AD.idDiscipline = CD.idDiscipline
                        WHERE CD.idCoachingStaff = :coach_id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':coach_id', $coach_id);
                $stmt->execute();

                // Populate the select options with fetched athletes
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<option value='{$row['idAthlete']}'>{$row['name']}</option>";
                }
                ?>
            </select>

            <label for="weight">Peso (kg):</label>
            <input type="number" id="weight" name="weight" step="0.1" min="0" required>

            <label for="height">Altura (cm):</label>
            <input type="number" id="height" name="height" step="0.1" min="0" required>

            <label for="back_flexibility">Flexibilidade das Costas:</label>
            <input type="number" id="back_flexibility" name="back_flexibility" step="0.1" min="0" required>

            <label for="vertical_thrust">Impulso Vertical:</label>
            <input type="number" id="vertical_thrust" name="vertical_thrust" step="0.1" min="0" required>

            <label for="date_test">Data da Avaliação:</label>
            <input type="date" id="date_test" name="date_test" required>

            <button type="submit">Adicionar Avaliação</button>
        </form>
    </main>
    <footer>
        <p>&copy; 2024 Gravity Masters Management Software</p>
    </footer>
</body>
</html>
