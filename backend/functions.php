<?php
function fetchAthletes($coach_id) {
    include '../backend/db_connect.php';

    $athletes = [];
    try {
        if ($conn === null) {
            throw new Exception("Falha na conexão com o banco de dados.");
        }

        // Consulta para obter os atletas associados às disciplinas do treinador
        $sql = "SELECT DISTINCT A.idAthlete, A.name, A.birthday, A.genre, A.mobile, A.email, A.address 
                FROM Athlete A 
                JOIN AthleteDiscipline AD ON A.idAthlete = AD.idAthlete
                JOIN CoachingStaffDiscipline CD ON AD.idDiscipline = CD.idDiscipline
                WHERE CD.idCoachingStaff = :coach_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':coach_id', $coach_id);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $athletes[] = $row;
        }
    } catch (Exception $e) {
        return "Erro: " . $e->getMessage();
    }

    $conn = null;
    return $athletes;
}
function fetchinfoAthlete($idAthlete) {
    include '../backend/db_connect.php';

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
    } catch (Exception $e) {
        return "Erro: " . $e->getMessage();
    }

    $conn = null;
    return $athlete;
}

function fetchTrainingsAthlete($idAthlete) {
    include '../backend/db_connect.php';

    try {
        if ($conn === null) {
            throw new Exception("Falha na conexão com o banco de dados.");
        }

        // Consulta para obter o histórico de treinos
        $sql_trainings = "SELECT * 
                        FROM TrainingReg
                        WHERE idAthlete = :idAthlete";
        $stmt_trainings = $conn->prepare($sql_trainings);
        $stmt_trainings->bindParam(':idAthlete', $idAthlete);
        $stmt_trainings->execute();
        $trainings = $stmt_trainings->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        return "Erro: " . $e->getMessage();
    }

    $conn = null;
    return $trainings;
}

function fetchResultsAthlete($idAthlete) {
    include '../backend/db_connect.php';

    try {
        if ($conn === null) {
            throw new Exception("Falha na conexão com o banco de dados.");
        }

        // Consulta para obter o histórico de resultados
        $sql_results = "SELECT * 
                    FROM Result
                    WHERE idAthlete = :idAthlete";
        $stmt_results = $conn->prepare($sql_results);
        $stmt_results->bindParam(':idAthlete', $idAthlete);
        $stmt_results->execute();
        $results = $stmt_results->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        return "Erro: " . $e->getMessage();
    }

    $conn = null;
    return $results;
}

function fetchResultsAndDetailsOfCompetitionAthlete($idAthlete){
    include '../backend/db_connect.php';

    try {
        if ($conn === null) {
            throw new Exception("Falha na conexão com o banco de dados.");
        }

        // Consulta para obter o histórico de resultados
        $sql_results = "SELECT * 
                        FROM Result
                        WHERE idAthlete = :idAthlete";
        $stmt_results = $conn->prepare($sql_results);
        $stmt_results->bindParam(':idAthlete', $idAthlete);
        $stmt_results->execute();
        $results = $stmt_results->fetchAll(PDO::FETCH_ASSOC);

        // Array para armazenar resultados e detalhes da competição
        $data = array();

        foreach ($results as $result) {
            // Consulta para obter os detalhes da competição de cada resultado
            $sql_competition = "SELECT * 
                                FROM Competition
                                WHERE idCompetition = :idCompetition";
            $stmt_competition = $conn->prepare($sql_competition);
            $stmt_competition->bindParam(':idCompetition', $result['idCompetition']);
            $stmt_competition->execute();
            $competition = $stmt_competition->fetch(PDO::FETCH_ASSOC);

            // Adiciona resultado e detalhes da competição ao array
            $data[] = array(
                'result' => $result,
                'competition' => $competition
            );
        }
    } catch (Exception $e) {
        return "Erro: " . $e->getMessage();
    }

    $conn = null;
    return $data;
}


function fetchConditionTestsAthlete($idAthlete) {
    include '../backend/db_connect.php';

    try {
        if ($conn === null) {
            throw new Exception("Falha na conexão com o banco de dados.");
        }

        // Consulta para obter o histórico de avaliações físicas
        $sql_evaluations = "SELECT * 
                        FROM ConditionTest
                        WHERE idAthlete = :idAthlete";
        $stmt_evaluations = $conn->prepare($sql_evaluations);
        $stmt_evaluations->bindParam(':idAthlete', $idAthlete);
        $stmt_evaluations->execute();
        $evaluations = $stmt_evaluations->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        return "Erro: " . $e->getMessage();
    }

    $conn = null;
    return $evaluations;
}





?>
