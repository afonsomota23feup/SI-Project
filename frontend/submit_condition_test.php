<?php
// Configuração da conexão com o banco de dados
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idAthlete = $_POST['athlete'];
    $idCoachingStaff = $_POST['coachingStaff'];
    $weight = $_POST['weight'];
    $height = $_POST['height'];
    $backFlexibility = $_POST['backFlexibility'];
    $verticalThrust = $_POST['verticalThrust'];
    $dateTest = $_POST['dateTest'];

    try {
        // Preparar a consulta SQL usando prepared statements
        $sql = "INSERT INTO ConditionTest (idAthlete, idCoachingStaff, weight, height, backFlexibility, verticalThrust, dateTest)
                VALUES (:idAthlete, :idCoachingStaff, :weight, :height, :backFlexibility, :verticalThrust, :dateTest)";
        $stmt = $conn->prepare($sql);

        // Associar os parâmetros aos valores do formulário
        $stmt->bindParam(':idAthlete', $idAthlete);
        $stmt->bindParam(':idCoachingStaff', $idCoachingStaff);
        $stmt->bindParam(':weight', $weight);
        $stmt->bindParam(':height', $height);
        $stmt->bindParam(':backFlexibility', $backFlexibility);
        $stmt->bindParam(':verticalThrust', $verticalThrust);
        $stmt->bindParam(':dateTest', $dateTest);

        // Executar a consulta
        $stmt->execute();

        echo "New record created successfully";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    // Fechar a conexão
    $conn = null;
}
?>
