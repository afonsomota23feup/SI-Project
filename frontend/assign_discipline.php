<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=devicShah, initial-scale=1.0">
    <title>Associar Atleta a Disciplina</title>
    <link rel="stylesheet" href="../css/assign_discipline.css">

</head>
<body>
    <header>
        <div class="header-content">
            <h1>Associar Atleta a Disciplina</h1>
        </div>
        <div class="logo-content">
            <img src="..\imagens\teste.png" alt="Logo do Clube" class="header-logo">
        </div>
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
                                 <form action='../backend/assign_discipline.php' method='POST' onsubmit='return validateForm(this);'>
                                     <input type='hidden' name='athlete_id' value='{$row['idAthlete']}'>
                                     <input type='hidden' name='discipline' value='' class='disciplineInput'>
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
        <p>&copy; 2024 Gravity Masters Management Software</p>
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
            var inputs = document.querySelectorAll('.disciplineInput');
            inputs.forEach(function(input) {
                input.value = disciplineId;
            });
        });

        // Validação do formulário
        function validateForm(form) {
            var disciplineSelect = document.getElementById('disciplineSelect');
            if (disciplineSelect.value === "") {
                alert("Por favor, selecione uma disciplina antes de associar.");
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
