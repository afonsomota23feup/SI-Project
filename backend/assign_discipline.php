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

        // relacionamento entre atleta e disciplina
        $sql = "INSERT INTO AthleteDiscipline (idAthlete, idDiscipline) 
                VALUES (:athlete_id, :discipline_id)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':athlete_id', $athlete_id);
        $stmt->bindParam(':discipline_id', $discipline_id);
        $stmt->execute();

        echo "<script>alert('Atleta associado à disciplina com sucesso!');</script>";
    } catch (Exception $e) {
        echo "Erro: " . $e->getMessage();
    }

    $conn = null;
}
?>
