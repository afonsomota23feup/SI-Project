<?php
// Configuração da conexão com o banco de dados
include 'db_connect.php';

// Obter dados do formulário
$date = $_POST['date'];
$performance = $_POST['performance'];
$coach = $_POST['coach'];
$athlete = $_POST['athlete'];

// Inserir dados na tabela TrainingReg
$sql = "INSERT INTO TrainingReg (dateTrainingReg, performance, idCoachingStaff, idAthlete) VALUES ('$date', '$performance', '$coach', '$athlete')";

if ($conn->query($sql) === TRUE) {
    echo "Sessão de treinamento agendada com sucesso!";
} else {
    echo "Erro: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
