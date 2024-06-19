<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Associar Atleta a Disciplina</title>
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
        .search-container {
            margin-bottom: 20px;
        }
        .search-container input[type=text] {
            padding: 6px;
            margin-top: 8px;
            font-size: 17px;
            border: none;
            width: 80%;
        }
        .search-container button {
            float: right;
            padding: 6px 10px;
            margin-top: 8px;
            margin-right: 16px;
            background: #ddd;
            font-size: 17px;
            border: none;
            cursor: pointer;
        }
        .search-container select {
            padding: 6px;
            margin-top: 8px;
            font-size: 17px;
            border: none;
            width: 80%;
        }
    </style>
</head>
<body>
    <header>
        <h1>Associar Atleta a Disciplina</h1>
    </header>
    <main>
        <div class="search-container">
            <select id="disciplineSelect">
                <option value="">Selecione a disciplina</option>
                <!-- Aqui você deve preencher as opções com as disciplinas associadas ao treinador -->
                <?php
                session_start();
                include '../backend/db_connect.php';

                try {
                    if ($conn === null) {
                        throw new Exception("Falha na conexão com o banco de dados.");
                    }

                    // Consulta para obter as disciplinas associadas ao treinador
                    $coach_id = $_SESSION['user_id'];
                    $sql = "SELECT D.idDiscipline, D.name FROM Discipline D
                            JOIN CoachingStaffDiscipline CD ON D.idDiscipline = CD.idDiscipline
                            WHERE CD.idCoachingStaff = :coach_id";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':coach_id', $coach_id);
                    $stmt->execute();

                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='{$row['idDiscipline']}'>{$row['name']}</option>";
                    }
                } catch (Exception $e) {
                    echo "<option value=''>Erro ao carregar disciplinas</option>";
                }

                $conn = null;
                ?>
            </select>
            <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Pesquisar por nome...">
            <button type="button" onclick="filterTable()">Pesquisar</button>
        </div>
        <table id="athleteTable">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Data de Nascimento</th>
                    <th>Género</th>
                    <th>Telefone</th>
                    <th>Email</th>
                    <th>Endereço</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php
                session_start();
                include '../backend/db_connect.php';

                try {
                    if ($conn === null) {
                        throw new Exception("Falha na conexão com o banco de dados.");
                    }

                    // Consulta para obter todos os atletas no clube
                    $sql = "SELECT idAthlete, name, birthday, genre, mobile, email, address FROM Athlete";
                    $stmt = $conn->query($sql);

                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>{$row['name']}</td>";
                        echo "<td>{$row['birthday']}</td>";
                        echo "<td>{$row['genre']}</td>";
                        echo "<td>{$row['mobile']}</td>";
                        echo "<td>{$row['email']}</td>";
                        echo "<td>{$row['address']}</td>";
                        echo "<td>
                                 <form action='../backend/assign_discipline.php' method='POST'>
                                     <input type='hidden' name='athlete_id' value='{$row['idAthlete']}'>
                                     <input type='hidden' name='discipline' value='' id='discipline_id_{$row['idAthlete']}'>
                                     <button type='submit'>Associar</button>
                                 </form>
                              </td>";
                        echo "</tr>";
                    }
                } catch (Exception $e) {
                    echo "<tr><td colspan='7'>Erro: " . $e->getMessage() . "</td></tr>";
                }

                $conn = null;
                ?>
            </tbody>
        </table>
    </main>
    <footer>
        <p>&copy; 2024 Gymnastic Club Management Software</p>
    </footer>

    <script>
        function filterTable() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("athleteTable");
            tr = table.getElementsByTagName("tr");

            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[0]; // Filtrar pela primeira coluna (nome)
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }

        // Função para carregar os atletas baseados na disciplina selecionada
        document.getElementById('disciplineSelect').addEventListener('change', function() {
            var disciplineId = this.value;
            var forms = document.querySelectorAll('form');
            forms.forEach(function(form) {
                var input = form.querySelector('input[name="discipline"]');
                input.value = disciplineId;
            });
        });
    </script>
</body>
</html>
