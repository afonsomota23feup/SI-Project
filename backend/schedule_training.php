<?php
session_start();
include __DIR__ . '/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $coach_id = $_SESSION['user_id'];
    $athlete_id = $_POST['athlete'];
    $training_date = $_POST['training_date'];
    $apparatus_id = $_POST['apparatus'];
    $performance = $_POST['performance'];
    $note = $_POST['note'];

    try {
        if ($conn === null) {
            throw new Exception("Falha na conexão com o banco de dados.");
        }

        // Inserir treino na tabela TrainingReg
        $sql = "INSERT INTO TrainingReg (idCoachingStaff, idAthlete, performance, dateTrainingReg)
                VALUES (:coach_id, :athlete_id, :performance, :training_date)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':coach_id', $coach_id);
        $stmt->bindParam(':athlete_id', $athlete_id);
        $stmt->bindParam(':performance', $performance);
        $stmt->bindParam(':training_date', $training_date);
        $stmt->execute();

        // Obter o ID do treino inserido
        $training_id = $conn->lastInsertId();

        // Inserir nota na tabela Notes, se houver uma nota fornecida
        if (!empty($note)) {
            $sql_note = "INSERT INTO Notes (idCoachingStaff, idTrainingReg, description)
                         VALUES (:coach_id, :training_id, :note)";
            $stmt_note = $conn->prepare($sql_note);
            $stmt_note->bindParam(':coach_id', $coach_id);
            $stmt_note->bindParam(':training_id', $training_id);
            $stmt_note->bindParam(':note', $note);
            $stmt_note->execute();
        }

        echo "<script>alert('Treino marcado com sucesso!');</script>";
        header("Location: ../frontend/menu_coach.php");
        exit;
    } catch (Exception $e) {
        echo "<script>alert('Erro ao marcar treino: " . $e->getMessage() . "');</script>";
    }

    $conn = null;
}
?>
