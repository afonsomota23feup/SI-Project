<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conectar ao banco de dados (substitua com suas credenciais)
    $servername = "localhost";
    $username = "seu_usuario_mysql";
    $password = "sua_senha_mysql";
    $dbname = "nome_do_banco_de_dados";

    // Criar conexão
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar conexão
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Obter dados do formulário
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Consultar o banco de dados para encontrar o atleta com o email fornecido
    $sql = "SELECT * FROM Athlete WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Senha correta, iniciar sessão
            $_SESSION['logged_in'] = true;
            $_SESSION['athlete_id'] = $row['idAthlete'];
            echo "Login successful";
        } else {
            // Senha incorreta
            echo "Invalid email or password";
        }
    } else {
        // Atleta não encontrado
        echo "Invalid email or password";
    }

    $conn->close();
}
?>
