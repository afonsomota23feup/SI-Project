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
                    include '../backend/functions.php';

                    $coach_id = $_SESSION['user_id'];
                    $athletes = fetchAthletes($coach_id);

                    if (is_string($athletes)) {
                        echo "<tr><td colspan='6'>$athletes</td></tr>";
                    } else {
                        foreach ($athletes as $athlete) {
                            echo "<tr>";
                            echo "<td>{$athlete['name']}</td>";
                            echo "<td>{$athlete['birthday']}</td>";
                            echo "<td>{$athlete['genre']}</td>";
                            echo "<td>{$athlete['mobile']}</td>";
                            echo "<td>{$athlete['email']}</td>";
                            echo "<td>{$athlete['address']}</td>";
                            echo "</tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>
    <a href="addAthlete.php">Adicionar Atletas</a>
    <footer>
        <p>&copy; 2024 Gymnastic Club Management Software</p>
    </footer>
</body>
</html>
