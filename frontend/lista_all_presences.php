<?php
session_start();
include __DIR__ . '/../backend/db_connect.php';

// Verificar se o ID do atleta foi fornecido na URL
if (!isset($_GET['idAthlete'])) {
    die("ID do atleta não fornecido.");
}

$idAthlete = intval($_GET['idAthlete']); // Obter o ID do atleta de forma segura
$presences = [];
$months = [];
$selectedMonth = isset($_GET['month']) ? $_GET['month'] : null;

try {
    // Consultar as presenças do atleta pelo ID
    $stmt = $conn->prepare("SELECT Presencas.timestamp, Presencas.status, Athlete.name,
                                   strftime('%Y-%m', Presencas.timestamp) AS month
                            FROM Presencas
                            JOIN Athlete ON Presencas.idAthlete = Athlete.idAthlete
                            WHERE Presencas.idAthlete = :idAthlete
                            ORDER BY Presencas.timestamp DESC");
    $stmt->bindParam(':idAthlete', $idAthlete, PDO::PARAM_INT);
    $stmt->execute();
    $presences = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Organizar por meses
    foreach ($presences as $presence) {
        $month = $presence['month'];
        if (!isset($months[$month])) {
            $months[$month] = [];
        }
        $months[$month][] = $presence;
    }
} catch (PDOException $e) {
    echo "Erro ao buscar presenças: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Presenças</title>
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

        .table-container {
            background: #fff;
            padding: 1rem;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 2rem;
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

        .empty-message {
            text-align: center;
            margin-top: 2rem;
            font-size: 1.2rem;
            color: #666;
        }

        .month-title {
            margin-top: 2rem;
            font-size: 1.5rem;
            color: #444;
        }

        .month-select {
            margin-bottom: 1.5rem;
        }

        .month-select select {
            padding: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }

        .month-select button {
            padding: 0.5rem 1rem;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
        }

        .month-select button:hover {
            background-color: #555;
        }

        .athlete-photo {
            max-width: 150px;
            border-radius: 50%;
            margin: 0 auto 1rem;
            display: block;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <h1>Minhas Presenças</h1>
        </div>
    </header>
    <main>
        <div class="table-container">
            <div class="month-select">
                <form method="get">
                    <input type="hidden" name="idAthlete" value="<?php echo htmlspecialchars($idAthlete); ?>">
                    <label for="month">Selecione o mês:</label>
                    <select id="month" name="month">
                        <option value="">Todos os meses</option>
                        <?php foreach (array_keys($months) as $month): ?>
                            <option value="<?php echo htmlspecialchars($month); ?>" <?php echo ($selectedMonth === $month) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars(date('F Y', strtotime($month . '-01'))); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit">Filtrar</button>
                </form>
            </div>


            <?php if ($months): ?>
                <?php foreach ($months as $month => $records): ?>
                    <?php if (!$selectedMonth || $selectedMonth === $month): ?>
                        <h3 class="month-title">Mês: <?php echo htmlspecialchars(date('F Y', strtotime($month . '-01'))); ?></h3>
                        <table>
                            <tr>
                                <th>Data</th>
                                <th>Horário</th>
                                <th>Status</th>
                            </tr>
                            <?php foreach ($records as $presence): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($presence['timestamp']))); ?></td>
                                    <td><?php echo htmlspecialchars(date('H:i', strtotime($presence['timestamp']))); ?></td>
                                    <td><?php echo htmlspecialchars($presence['status']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-message">
                    <p>Você não possui registros de presença.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>
    <footer>
        <p>Todos os direitos reservados.</p>
    </footer>
</body>
</html>
