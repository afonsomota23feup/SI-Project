<?php
session_start();
include __DIR__ . '/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $athlete_id = $_POST['athlete_id'];
    $discipline_id = $_POST['discipline'];

    try {
        if ($conn === null) {
            throw new Exception("Falha na conexão com o banco de dados.");
        }

        // Relacionamento entre atleta e disciplina
        $sql = "INSERT INTO AthleteDiscipline (idAthlete, idDiscipline) 
                VALUES (:athlete_id, :discipline_id)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':athlete_id', $athlete_id);
        $stmt->bindParam(':discipline_id', $discipline_id);
        $stmt->execute();

        // Definir mensagem de sucesso
        $_SESSION['success'] = "Atleta associado à disciplina com sucesso!";
        header("Location: ../frontend/menu_coach.php");
        exit;
    } catch (Exception $e) {
        // Em caso de erro, definir mensagem de erro e redirecionar
        $_SESSION['error'] = "Erro: " . $e->getMessage();
        header("Location: ../frontend/menu_coach.php");
        exit;
    } finally {
        $conn = null;
    }
}
?>
