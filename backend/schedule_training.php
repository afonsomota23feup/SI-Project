<?php
// Configuração da conexão com o banco de dados
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST['date'];
    $performance = $_POST['performance'];
    $coach = $_POST['coach'];
    $athlete = $_POST['athlete'];

    try {
        // Preparar a consulta SQL usando prepared statements
        $sql = "INSERT INTO TrainingReg (dateTrainingReg, performance, idCoachingStaff, idAthlete)
                VALUES (:date, :performance, :coach, :athlete)";
        $stmt = $conn->prepare($sql);

        // Associar os parâmetros aos valores do formulário
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':performance', $performance);
        $stmt->bindParam(':coach', $coach);
        $stmt->bindParam(':athlete', $athlete);

        // Executar a consulta
        $stmt->execute();

        echo "Sessão de treinamento agendada com sucesso!";
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }

    // Fechar a conexão
    $conn = null;
}
?>
