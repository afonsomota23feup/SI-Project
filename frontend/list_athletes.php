<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Atletas</title>
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
    </style>
</head>
<body>
    <header>
        <h1>Lista de Atletas</h1>
    </header>
    <main>
        <div class="athlete-list">
            <h2>Os Meus Atletas</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Data de Nascimento</th>
                        <th>Género</th>
                        <th>Telefone</th>
                        <th>Email</th>
                        <th>Endereço</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    session_start();
                    include '../backend/db_connect.php';

                    $coach_id = $_SESSION['user_id'];

                    try {
                        if ($conn === null) {
                            throw new Exception("Falha na conexão com o banco de dados.");
                        }

                        // Consulta para obter os atletas associados às disciplinas do treinador
                        $sql = "SELECT DISTINCT A.idAthlete, A.name, A.birthday, A.genre, A.mobile, A.email, A.address 
                                FROM Athlete A 
                                JOIN AthleteDiscipline AD ON A.idAthlete = AD.idAthlete
                                JOIN CoachingStaffDiscipline CD ON AD.idDiscipline = CD.idDiscipline
                                WHERE CD.idCoachingStaff = :coach_id";
                        $stmt = $conn->prepare($sql);
                        $stmt->bindParam(':coach_id', $coach_id);
                        $stmt->execute();

                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>";
                            echo "<td>{$row['name']}</td>";
                            echo "<td>{$row['birthday']}</td>";
                            echo "<td>{$row['genre']}</td>";
                            echo "<td>{$row['mobile']}</td>";
                            echo "<td>{$row['email']}</td>";
                            echo "<td>{$row['address']}</td>";
                            echo "</tr>";
                        }
                    } catch (Exception $e) {
                        echo "<tr><td colspan='6'>Erro: " . $e->getMessage() . "</td></tr>";
                    }

                    $conn = null;
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
