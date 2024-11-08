<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Atletas</title>
    <link rel="stylesheet" href="../css/list_athletes.css">
</head>
<body>
    <header>
        <div class="header-content">
            <h1>Lista de Atletas</h1>
        </div>
        <div class="logo-content">
            <img src="..\imagens\teste.png" alt="Logo do Clube" class="header-logo">
        </div>
    </header>
    <main>
        <div class="menuAtletas">
            <nav>
                <ul>
                    <li><a href="assign_discipline.php">Associar Atleta à Disciplina</a></li>
                    <li><a href="schedule_training.php">Registar Treino</a></li>
                    <li><a href="list_competition.php">Adicionar Resultado</a></li>
                    <li><a href="condition_add.php">Adicionar Avaliação Física</a></li>
                    <li><a href="addAthlete.php">Registar Atleta</a></li>
                </ul>
            </nav>
        </div>
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
                    include __DIR__ . '/../backend/db_connect.php';

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
                            echo "<td><a href='personalAthlete.php?idAthlete={$row['idAthlete']}'>{$row['name']}</a></td>";
                            echo "<td>{$row['birthday']}</td>";
                            echo "<td>{$row['genre']}</td>";
                            echo "<td>{$row['mobile']}</td>";
                            echo "<td>{$row['email']}</td>";
                            echo "<td>{$row['address']}</td>";
                            echo "<td><a href='../backend/delete_athlete.php?idAthlete={$row['idAthlete']}' onclick='return confirm(\"Tem certeza de que deseja excluir este atleta?\")'>Excluir</a></td>";
                            echo "</tr>";
                        }
                    } catch (Exception $e) {
                        echo "<tr><td colspan='7'>Erro: " . $e->getMessage() . "</td></tr>";
                    }

                    $conn = null;
                    ?>
                </tbody>

            </table>
        </div>

        <div class="back-button">
            <a href="menu_coach.php">Voltar ao Menu Principal</a>
        </div>

    </main>
    <footer>
        <p>&copy; 2024 Gravity Masters Management Software</p>
    </footer>
</body>
</html>
