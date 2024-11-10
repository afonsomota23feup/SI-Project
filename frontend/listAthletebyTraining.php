<?php
session_start();
include __DIR__ . '/../backend/db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

// Variável para armazenar atletas presentes
$presentAthletes = [];
$selectedDate = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d'); // Default para o dia atual

if ($selectedDate) {
    try {
        // Consulta para buscar atletas que registraram "Entrada" na data selecionada
        $stmt = $conn->prepare("
            SELECT DISTINCT Athlete.idAthlete, Athlete.name, Athlete.ageGroup, Athlete.birthday
            FROM Presencas
            JOIN Athlete ON Presencas.idAthlete = Athlete.idAthlete
            WHERE Presencas.status = 'Entrada'
            AND DATE(Presencas.timestamp) = :date
            ORDER BY Athlete.name ASC
        ");
        $stmt->bindParam(':date', $selectedDate, PDO::PARAM_STR);
        $stmt->execute();
        $presentAthletes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Erro ao buscar atletas presentes: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atletas Presentes no Treino</title>
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

        form input[type="date"] {
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
            <h1>Atletas Presentes no Treino</h1>
        </div>
    </header>
    <main>
        <form method="get">
            <label for="date">Selecione a Data:</label>
            <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($selectedDate); ?>" required>
            <button type="submit">Ver Atletas Presentes</button>
        </form>

        <?php if ($presentAthletes): ?>
            <h2>Atletas Presentes em <?php echo date('d/m/Y', strtotime($selectedDate)); ?></h2>
            <table>
                <tr>
                    <th>Nome</th>
                    <th>Escalão</th>
                </tr>
                <?php foreach ($presentAthletes as $athlete): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($athlete['name']); ?></td>
                        <td><?php echo htmlspecialchars($athlete['ageGroup']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>Nenhum atleta presente para a data selecionada.</p>
        <?php endif; ?>
    </main>
    <footer>
        <p>© 2023 Seu Site. Todos os direitos reservados.</p>
    </footer>
</body>
</html>
