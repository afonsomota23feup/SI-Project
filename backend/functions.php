<?php
// Inclui a conexão com o banco de dados uma única vez
include_once __DIR__ . '/../backend/db_connect.php';

function fetchAthletes($coach_id) {
    global $conn;

    try {
        $sql = "SELECT DISTINCT A.idAthlete, A.name, A.birthday, A.genre, A.mobile, A.email, A.address 
                FROM Athlete A 
                JOIN AthleteDiscipline AD ON A.idAthlete = AD.idAthlete
                JOIN CoachingStaffDiscipline CD ON AD.idDiscipline = CD.idDiscipline
                WHERE CD.idCoachingStaff = :coach_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':coach_id', $coach_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        return [];
    }
}

function fetchinfoAthlete($idAthlete) {
    global $conn;

    try {
        $sql = "SELECT * FROM Athlete WHERE idAthlete = :idAthlete";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':idAthlete', $idAthlete, PDO::PARAM_INT);
        $stmt->execute();
        $athlete = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$athlete || empty($athlete['birthday'])) {
            throw new Exception("Data de nascimento inválida ou ausente.");
        }

        // Calcula o grupo etário
        $currentYear = (int)date("Y");
        $birthYear = (int)date("Y", strtotime($athlete['birthday']));
        $age = $currentYear - $birthYear;

        $ageGroup = match (true) {
            $age >= 17 => '17+',
            $age >= 15 => '15-16',
            $age >= 13 => '13-14',
            $age >= 11 => '11-12',
            $age >= 9 => '9-10',
            $age >= 7 => '7-8',
            default => 'Under 7',
        };

        if ($athlete['ageGroup'] !== $ageGroup) {
            $sql_update = "UPDATE Athlete SET ageGroup = :ageGroup WHERE idAthlete = :idAthlete";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bindParam(':ageGroup', $ageGroup);
            $stmt_update->bindParam(':idAthlete', $idAthlete, PDO::PARAM_INT);
            $stmt_update->execute();
        }

        return $athlete;
    } catch (Exception $e) {
        return null;
    }
}

function fetchTrainingsAthlete($idAthlete) {
    global $conn;

    try {
        $sql = "SELECT * FROM TrainingReg WHERE idAthlete = :idAthlete ORDER BY dateTrainingReg DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':idAthlete', $idAthlete, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        return [];
    }
}

function fetchResultsAthlete($idAthlete) {
    global $conn;

    try {
        $sql = "SELECT * FROM Result WHERE idAthlete = :idAthlete ORDER BY competitionDate DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':idAthlete', $idAthlete, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        return [];
    }
}

function fetchResultsAndDetailsOfCompetitionAthlete($idAthlete) {
    global $conn;

    try {
        $sql = "SELECT r.*, c.* 
                FROM Result r
                JOIN Competition c ON r.idCompetition = c.idCompetition
                WHERE r.idAthlete = :idAthlete
                ORDER BY c.startTime DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':idAthlete', $idAthlete, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        return [];
    }
}

function fetchConditionTestsAthlete($idAthlete) {
    global $conn;

    try {
        $sql = "SELECT * FROM ConditionTest WHERE idAthlete = :idAthlete ORDER BY dateTest DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':idAthlete', $idAthlete, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        return [];
    }
}
?>
