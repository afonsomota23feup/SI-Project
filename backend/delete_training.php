<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['idTrainingReg']) || !isset($_GET['idAthlete'])) {
    header('Location: ../frontend/login.php');
    exit;
}

$training_id = $_GET['idTrainingReg'];
$athlete_id = $_GET['idAthlete'];

try {
    if ($conn === null) {
        throw new Exception("Falha na conexão com o banco de dados.");
    }

    // Verificar se o treino pertence ao atleta
    $sqlCheck = "SELECT COUNT(*) FROM TrainingReg WHERE idTrainingReg = :training_id AND idAthlete = :athlete_id";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bindParam(':training_id', $training_id);
    $stmtCheck->bindParam(':athlete_id', $athlete_id);
    $stmtCheck->execute();

    if ($stmtCheck->fetchColumn() > 0) {
        // Deletar o treino
        $sqlDelete = "DELETE FROM TrainingReg WHERE idTrainingReg = :training_id";
        $stmtDelete = $conn->prepare($sqlDelete);
        $stmtDelete->bindParam(':training_id', $training_id);
        $stmtDelete->execute();

        // Redirecionar com sucesso
        header("Location: ../frontend/list_trainingByAthlete.php?idAthlete={$athlete_id}&success=deleted");
    } else {
        // Redirecionar com erro se o treino não pertencer ao atleta
        header("Location: ../frontend/list_training.php?idAthlete={$athlete_id}&error=not_found");
    }
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}

$conn = null;
?>
