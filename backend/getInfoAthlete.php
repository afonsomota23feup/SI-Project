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

    // Consulta para obter a data de nascimento do atleta
    $sql_birthdate = "SELECT birthdate 
                      FROM Athlete 
                      WHERE idAthlete = :idAthlete";
    $stmt_birthdate = $conn->prepare($sql_birthdate);
    $stmt_birthdate->bindParam(':idAthlete', $idAthlete);
    $stmt_birthdate->execute();
    $birthdate = $stmt_birthdate->fetchColumn();

    if ($birthdate) {
        // Atualizar o grupo etário automaticamente com base no ano de nascimento e no ano atual
        $currentYear = (int)date("Y");
        $birthYear = (int)date("Y", strtotime($birthdate));
        $age = $currentYear - $birthYear;

        if ($age >= 17) {
            $ageGroup = '17+';
        } elseif ($age >= 15 && $age <= 16) {
            $ageGroup = '15-16';
        } elseif ($age >= 13 && $age <= 14) {
            $ageGroup = '13-14';
        } elseif ($age >= 11 && $age <= 12) {
            $ageGroup = '11-12';
        } elseif ($age >= 9 && $age <= 10) {
            $ageGroup = '9-10';
        } elseif ($age >= 7 && $age <= 8) {
            $ageGroup = '7-8';
        } else {
            $ageGroup = 'Under 7';
        }

        // Atualizar o grupo etário no banco de dados
        $sql_update_age_group = "UPDATE Athlete 
                                 SET ageGroup = :ageGroup 
                                 WHERE idAthlete = :idAthlete";
        $stmt_update_age_group = $conn->prepare($sql_update_age_group);
        $stmt_update_age_group->bindParam(':ageGroup', $ageGroup);
        $stmt_update_age_group->bindParam(':idAthlete', $idAthlete);
        $stmt_update_age_group->execute();
    } else {
        throw new Exception("Data de nascimento não encontrada para o atleta.");
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
