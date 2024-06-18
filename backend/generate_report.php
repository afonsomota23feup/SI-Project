<?php
// Configuração da conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gymnastic_club";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Obter dados do formulário
$athlete = $_POST['athlete'];

// Selecionar dados de ConditionTest
$sql = "SELECT weight, height, backFlexibility, verticalThrust, dateTest FROM ConditionTest WHERE idAthlete='$athlete'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table><thead><tr><th>Peso</th><th>Altura</th><th>Flexibilidade</th><th>Impulso Vertical</th><th>Data do Teste</th></tr></thead><tbody>";
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["weight"]. "</td><td>" . $row["height"]. "</td><td>" . $row["backFlexibility"]. "</td><td>" . $row["verticalThrust"]. "</td><td>" . $row["dateTest"]. "</td></tr>";
    }
    echo "</tbody></table>";
} else {
    echo "Nenhum registro encontrado.";
}

$conn->close();
?>
