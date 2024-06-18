<?php
// Configuração da conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gymnastic_club";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Obter dados do formulário
$name = $_POST['name'];
$local = $_POST['local'];
$start_time = $_POST['start_time'];
$end_time = $_POST['end_time'];
$description = $_POST['description'];

// Inserir dados na tabela Competition
$sql = "INSERT INTO Competition (name, local, startTime, endTime, description) VALUES ('$name', '$local', '$start_time', '$end_time', '$description')";

if ($conn->query($sql) === TRUE) {
    echo "Competição registrada com sucesso!";
} else {
    echo "Erro: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
