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
?>
