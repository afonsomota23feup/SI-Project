<?php
session_start();
include __DIR__ . '/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $competition_name = $_POST['competition_name'];
    $competition_location = $_POST['competition_location'];
    $competition_start_time = $_POST['competition_start_time'];
    $competition_end_time = $_POST['competition_end_time'];
    $competition_description = $_POST['competition_description'];

    try {
        if ($conn === null) {
            throw new Exception("Falha na conexão com o banco de dados.");
        }

        $sql = "INSERT INTO Competition (name, local, startTime, endTime, description) 
                VALUES (:name, :local, :start_time, :end_time, :description)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $competition_name);
        $stmt->bindParam(':local', $competition_location);
        $stmt->bindParam(':start_time', $competition_start_time);
        $stmt->bindParam(':end_time', $competition_end_time);
        $stmt->bindParam(':description', $competition_description);
        $stmt->execute();
        $competition_id = $conn->lastInsertId();

        echo "<script>alert('Competição marcada com sucesso!');</script>";
        header("Location: ../frontend/menu_coach.php");
    } catch (Exception $e) {
        echo "Erro: " . $e->getMessage();
    }

    $conn = null;
}
?>
