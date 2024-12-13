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
            <img src="../imagens/teste.png" alt="Logo do Clube" class="header-logo">
        </div>
    </header>
    <main>
        <div class="athlete-list">
            <h2>Os Meus Atletas</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Data de Nascimento</th>
                        <th>Gênero</th>
                        <th>Telefone</th>
                        <th>Email</th>
                        <th>Endereço</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include __DIR__ . '/../backend/db_connect.php';

                    try {
                        $sql = "SELECT idAthlete, name, birthday, genre, mobile, email, address FROM Athlete ORDER BY name ASC";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();

                        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if (!empty($results)) {
                            foreach ($results as $row) {
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
                        } else {
                            echo "<tr><td colspan='7'>Nenhum atleta encontrado.</td></tr>";
                        }
                    } catch (Exception $e) {
                        echo "<tr><td colspan='7'>Erro: " . $e->getMessage() . "</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
