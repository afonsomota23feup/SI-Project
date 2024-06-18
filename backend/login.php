<?php
// Configuração da conexão com o banco de dados
include 'db_connect.php';

// Obter dados do formulário
$email = $_POST['email'];
$password = $_POST['password'];

// Verificar credenciais
$sql = "SELECT * FROM CoachingStaff WHERE email='$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
        echo "Login bem-sucedido!";
    } else {
        echo "Senha incorreta.";
    }
} else {
    $sql = "SELECT * FROM Athlete WHERE email='$email'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            echo "Login bem-sucedido!";
        } else {
            echo "Senha incorreta.";
        }
    } else {
        echo "Usuário não encontrado.";
    }
}

$conn->close();
?>
