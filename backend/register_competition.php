<?php
// Configuração da conexão com o banco de dados
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $local = $_POST['local'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $description = $_POST['description'];

    try {
        // Preparar a consulta SQL usando prepared statements
        $sql = "INSERT INTO Competition (name, local, startTime, endTime, description)
                VALUES (:name, :local, :start_time, :end_time, :description)";
        $stmt = $conn->prepare($sql);

        // Associar os parâmetros aos valores do formulário
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':local', $local);
        $stmt->bindParam(':start_time', $start_time);
        $stmt->bindParam(':end_time', $end_time);
        $stmt->bindParam(':description', $description);

        // Executar a consulta
        $stmt->execute();

        echo "Competição registrada com sucesso!";
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }

    // Fechar a conexão
    $conn = null;
}
?>
