<?php
// Configuração da conexão com o banco de dados
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $athlete = $_POST['athlete'];

    try {
        // Preparar a consulta SQL usando prepared statements
        $sql = "SELECT weight, height, backFlexibility, verticalThrust, dateTest FROM ConditionTest WHERE idAthlete = :athlete";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':athlete', $athlete, PDO::PARAM_INT);
        $stmt->execute();

        // Verificar se há registros encontrados
        if ($stmt->rowCount() > 0) {
            echo "<table><thead><tr><th>Peso</th><th>Altura</th><th>Flexibilidade</th><th>Impulso Vertical</th><th>Data do Teste</th></tr></thead><tbody>";
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr><td>" . htmlspecialchars($row["weight"]) . "</td><td>" . htmlspecialchars($row["height"]) . "</td><td>" . htmlspecialchars($row["backFlexibility"]) . "</td><td>" . htmlspecialchars($row["verticalThrust"]) . "</td><td>" . htmlspecialchars($row["dateTest"]) . "</td></tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "Nenhum registro encontrado.";
        }
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }

    // Fechar a conexão
    $conn = null;
}
?>
