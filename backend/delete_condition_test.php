<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['idConditionTest']) || !isset($_GET['idAthlete'])) {
    header('Location: ../frontend/login.php');
    exit;
}

$condition_test_id = $_GET['idConditionTest'];
$athlete_id = $_GET['idAthlete'];

try {
    if ($conn === null) {
        throw new Exception("Falha na conexão com o banco de dados.");
    }

    // Verificar se o teste de condição pertence ao atleta
    $sqlCheck = "SELECT COUNT(*) FROM ConditionTest WHERE idConditionTest = :condition_test_id AND idAthlete = :athlete_id";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bindParam(':condition_test_id', $condition_test_id);
    $stmtCheck->bindParam(':athlete_id', $athlete_id);
    $stmtCheck->execute();

    if ($stmtCheck->fetchColumn() > 0) {
        // Deletar o teste de condição
        $sqlDelete = "DELETE FROM ConditionTest WHERE idConditionTest = :condition_test_id";
        $stmtDelete = $conn->prepare($sqlDelete);
        $stmtDelete->bindParam(':condition_test_id', $condition_test_id);
        $stmtDelete->execute();

        // Redirecionar com sucesso
        header("Location: ../frontend/list_athletes.php");
    } else {
        // Redirecionar com erro se o teste de condição não pertencer ao atleta
        header("Location: ../frontend/list_all_conditions.php?idAthlete={$athlete_id}&error=not_found");
    }
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}

$conn = null;
?>
