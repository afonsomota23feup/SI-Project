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
$birthdate = $_POST['birthdate'];
$gender = $_POST['gender'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$address = $_POST['address'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
$role = $_POST['role'];

// Inserir dados na tabela apropriada
if ($role == "coach") {
    $sql = "INSERT INTO CoachingStaff (name, birthday, genre, mobile, email, address, password, function) VALUES ('$name', '$birthdate', '$gender', '$phone', '$email', '$address', '$password', 'Coach')";
} else {
    $sql = "INSERT INTO Athlete (name, birthday, genre, mobile, email, address, password, ageGroup) VALUES ('$name', '$birthdate', '$gender', '$phone', '$email', '$address', '$password', 'Senior')";
}

if ($conn->query($sql) === TRUE) {
    echo "Registro realizado com sucesso!";
} else {
    echo "Erro: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
