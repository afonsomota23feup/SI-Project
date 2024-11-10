<?php
session_start();
include __DIR__ . '/../backend/db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

// Fetch all athletes for the dropdown
$athletes = [];
try {
    $coach_id = $_SESSION['user_id'];

    $sql = "SELECT DISTINCT A.idAthlete, A.name, A.birthday, A.genre, A.mobile, A.email, A.address 
            FROM Athlete A 
            JOIN AthleteDiscipline AD ON A.idAthlete = AD.idAthlete
            JOIN CoachingStaffDiscipline CD ON AD.idDiscipline = CD.idDiscipline
            WHERE CD.idCoachingStaff = :coach_id";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':coach_id', $coach_id);
    $stmt->execute();
    $athletes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erro ao buscar atletas: " . $e->getMessage();
}

// Check if a specific athlete is selected
$athleteId = isset($_GET['idAthlete']) ? $_GET['idAthlete'] : null;
$selectedMonth = isset($_GET['month']) ? str_pad($_GET['month'], 2, '0', STR_PAD_LEFT) : date('m'); // Default to current month
$selectedYear = isset($_GET['year']) ? $_GET['year'] : date('Y'); // Default to current year
$data = [];
$totalTrainings = 0;

if ($athleteId) {
    try {
        // Query to fetch attendance records
        $stmt = $conn->prepare("
            SELECT Presencas.idpresenca, Athlete.name AS athlete_name, Presencas.tag_uid, Presencas.status, Presencas.timestamp
            FROM Presencas
            JOIN Athlete ON Presencas.idAthlete = Athlete.idAthlete
            WHERE Presencas.idAthlete = :athleteId
            AND strftime('%m', Presencas.timestamp) = :month
            AND strftime('%Y', Presencas.timestamp) = :year
            ORDER BY Presencas.timestamp ASC
        ");
        $stmt->bindParam(':athleteId', $athleteId, PDO::PARAM_INT);
        $stmt->bindParam(':month', $selectedMonth, PDO::PARAM_STR);
        $stmt->bindParam(':year', $selectedYear, PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Query to count "Entradas" for the selected month and year
        $stmt = $conn->prepare("
            SELECT COUNT(*) AS total_trainings
            FROM Presencas
            WHERE idAthlete = :athleteId
            AND status = 'Entrada'
            AND strftime('%m', timestamp) = :month
            AND strftime('%Y', timestamp) = :year
        ");
        $stmt->bindParam(':athleteId', $athleteId, PDO::PARAM_INT);
        $stmt->bindParam(':month', $selectedMonth, PDO::PARAM_STR);
        $stmt->bindParam(':year', $selectedYear, PDO::PARAM_STR);
        $stmt->execute();
        $totalTrainings = $stmt->fetchColumn();
        
    } catch (PDOException $e) {
        echo "Erro ao buscar presenças: " . $e->getMessage();
    }
}

// Process data to group by date with Entry and Exit pairs
$attendanceData = [];
foreach ($data as $row) {
    $date = date('Y-m-d', strtotime($row['timestamp'])); // Group by date
    if (!isset($attendanceData[$date])) {
        $attendanceData[$date] = [];
    }
    $attendanceData[$date][] = $row;
}

// Export data if requested
if (isset($_POST['export'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename=attendance_' . $athleteId . '.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, array('ID', 'Nome do Atleta', 'Tag UID', 'Status', 'Timestamp'));

    foreach ($data as $row) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver e Exportar Presenças</title>
    <style>
        /* Reset básico */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f4f4f4;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            background: #333;
            color: #fff;
            padding: 1rem;
            max-height: 90px;
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        header h1 {
            flex: 1;
            text-align: center;
            margin: 0;
            font-size: 2rem;
        }

        main {
            flex: 1;
            padding: 2rem;
        }

        form {
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        form label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
        }

        form select {
            width: 100%;
            padding: 0.5rem;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        form button {
            display: inline-block;
            background: #333;
            color: #fff;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 1rem;
        }

        form button:hover {
            background: #B99A45;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
        }

        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background: #333;
            color: #fff;
            font-weight: bold;
        }

        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 25px;
            position: sticky;
            top: 100%;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <h1>Dados de Presenças</h1>
        </div>
    </header>
    <main>
        <form method="get">
            <label for="idAthlete">Selecione o Atleta:</label>
            <select id="idAthlete" name="idAthlete" required>
                <option value="">-- Escolha um Atleta --</option>
                <?php foreach ($athletes as $athlete): ?>
                    <option value="<?php echo $athlete['idAthlete']; ?>" <?php echo ($athleteId == $athlete['idAthlete']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($athlete['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="month">Mês:</label>
            <select id="month" name="month" required>
                <?php for ($m = 1; $m <= 12; $m++): ?>
                    <option value="<?php echo str_pad($m, 2, '0', STR_PAD_LEFT); ?>" <?php echo ($selectedMonth == str_pad($m, 2, '0', STR_PAD_LEFT)) ? 'selected' : ''; ?>>
                        <?php echo date('F', mktime(0, 0, 0, $m, 10)); ?>
                    </option>
                <?php endfor; ?>
            </select>

            <label for="year">Ano:</label>
            <select id="year" name="year" required>
                <?php for ($y = date('Y'); $y >= 2000; $y--): ?>
                    <option value="<?php echo $y; ?>" <?php echo ($selectedYear == $y) ? 'selected' : ''; ?>>
                        <?php echo $y; ?>
                    </option>
                <?php endfor; ?>
            </select>

            <button type="submit">Ver Dados</button>
            <li><a href="menu_coach.php">Back to Menu</a></li>

        </form>

        <?php if ($athleteId): ?>
            <h2>Total de Treinos no Mês: <?php echo $totalTrainings; ?></h2>
        <?php endif; ?>

        <?php if ($data): ?>
            <?php foreach ($attendanceData as $date => $records): ?>
                <h2>Data: <?php echo $date; ?></h2>
                <table>
                    <tr>
                        <th>Nome do Atleta</th>
                        <th>Status</th>
                        <th>Horário</th>
                    </tr>
                    <?php foreach ($records as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['athlete_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                            <td><?php echo htmlspecialchars($row['timestamp']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endforeach; ?>

            <form method="post">
                <button type="submit" name="export">Exportar para CSV</button>
            </form>
        <?php elseif ($athleteId): ?>
            <p>Nenhum dado encontrado para o atleta selecionado.</p>
        <?php endif; ?>
    </main>
    <footer>
        <p>© 2023 Seu Site. Todos os direitos reservados.</p>
    </footer>
</body>
</html>
