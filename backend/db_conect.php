<?php
$servername = "your_server";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

// Criar a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar a conexão
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
