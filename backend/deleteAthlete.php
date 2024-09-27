<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['idAthlete'])) {
    header("Location: ../frontend/list_athletes.php?error=invalid_request");
    exit;
}

$athlete_id = $_GET['idAthlete'];

try {
    if ($conn === null) {
        throw new Exception("Falha na conexão com o banco de dados.");
    }

    // Preparar e executar a exclusão do atleta
    $sql = "DELETE FROM Athlete WHERE idAthlete = :athlete_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':athlete_id', $athlete_id);
    $stmt->execute();

    // Redirecionar para a lista de atletas com sucesso
    header("Location: ../frontend/list_athletes.php?success=athlete_deleted");
} catch (Exception $e) {
    // Redirecionar para a lista de atletas com mensagem de erro
    header("Location: ../frontend/list_athletes.php?error=" . urlencode($e->getMessage()));
}

$conn = null;
?>
