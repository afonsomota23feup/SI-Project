<?php
// Configuração da conexão com o banco de dados
include 'db_connect.php';

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (!$conn) {
        echo "Erro: Falha na conexão com o banco de dados.";
        exit();
    }

    // Verificar credenciais no CoachingStaff
    $sql = "SELECT * FROM CoachingStaff WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (password_verify($password, $user['password'])) {
            echo "Login bem-sucedido como coach!";
        } else {
            echo "Senha incorreta para coach.";
        }
    } else {
        // Verificar credenciais no Athlete
        $sql = "SELECT * FROM Athlete WHERE email = :email";    
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if (password_verify($password, $user['password'])) {
                echo "Login bem-sucedido como atleta!";
            } else {
                echo "Senha incorreta para atleta.";
            }
        } else {
            echo "Usuário não encontrado.";
        }
    }
}
?>
