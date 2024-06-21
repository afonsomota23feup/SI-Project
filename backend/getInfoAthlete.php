<?php
session_start();
include '../backend/db_connect.php';

// Verifica se o parâmetro idAthlete está presente na URL
if (!isset($_GET['idAthlete'])) {
    echo "Atleta não especificado.";
    exit;
}

$idAthlete = $_GET['idAthlete'];

try {
    if ($conn === null) {
        throw new Exception("Falha na conexão com o banco de dados.");
    }

    // Consulta para obter os detalhes do atleta
    $sql = "SELECT * 
            FROM Athlete 
            WHERE idAthlete = :idAthlete";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':idAthlete', $idAthlete);
    $stmt->execute();

    $athlete = $stmt->fetch(PDO::FETCH_ASSOC);

    // Consulta para obter o histórico de treinos
    $sql_trainings = "SELECT * 
                      FROM TrainingHistory 
                      WHERE idAthlete = :idAthlete";
    $stmt_trainings = $conn->prepare($sql_trainings);
    $stmt_trainings->bindParam(':idAthlete', $idAthlete);
    $stmt_trainings->execute();
    $trainings = $stmt_trainings->fetchAll(PDO::FETCH_ASSOC);

    // Consulta para obter o histórico de resultados
    $sql_results = "SELECT * 
                    FROM ResultsHistory 
                    WHERE idAthlete = :idAthlete";
    $stmt_results = $conn->prepare($sql_results);
    $stmt_results->bindParam(':idAthlete', $idAthlete);
    $stmt_results->execute();
    $results = $stmt_results->fetchAll(PDO::FETCH_ASSOC);

    // Consulta para obter o histórico de avaliações físicas
    $sql_evaluations = "SELECT * 
                        FROM PhysicalEvaluations 
                        WHERE idAthlete = :idAthlete";
    $stmt_evaluations = $conn->prepare($sql_evaluations);
    $stmt_evaluations->bindParam(':idAthlete', $idAthlete);
    $stmt_evaluations->execute();
    $evaluations = $stmt_evaluations->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
    exit;
}

$conn = null;
?>
