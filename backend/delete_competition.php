<?php
session_start();
include 'db_connect.php';

// Verificar se o usuário está logado e se os parâmetros necessários estão presentes
if (!isset($_SESSION['user_id']) || !isset($_GET['idCompetition']) || !isset($_GET['idAthlete'])) {
    header('Location: ../frontend/login.php');
    exit;
}

$competition_id = $_GET['idCompetition'];
$athlete_id = $_GET['idAthlete'];

try {
    if ($conn === null) {
        throw new Exception("Falha na conexão com o banco de dados.");
    }

    // Verificar se o resultado da competição existe para o atleta
    $sqlCheck = "SELECT COUNT(*) FROM Result WHERE idCompetition = :competition_id AND idAthlete = :athlete_id";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bindParam(':competition_id', $competition_id);
    $stmtCheck->bindParam(':athlete_id', $athlete_id);
    $stmtCheck->execute();

    if ($stmtCheck->fetchColumn() > 0) {
        // Se o resultado existe, proceder com a exclusão
        $sqlDelete = "DELETE FROM Result WHERE idCompetition = :competition_id AND idAthlete = :athlete_id";
        $stmtDelete = $conn->prepare($sqlDelete);
        $stmtDelete->bindParam(':competition_id', $competition_id);
        $stmtDelete->bindParam(':athlete_id', $athlete_id);
        $stmtDelete->execute();

        // Redirecionar com sucesso após a exclusão
        header("Location: ../frontend/list_athletes.php?success=deleted");
    } else {
        // Redirecionar com erro se o resultado não foi encontrado
        header("Location: ../frontend/list_compByAthlete.php?idAthlete={$athlete_id}&error=not_found");
    }
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}

$conn = null;
?>
