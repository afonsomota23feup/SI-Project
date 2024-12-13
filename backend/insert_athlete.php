<?php
// Configuração da conexão com o banco de dados
include __DIR__ . '/db_connect.php'; // Usando __DIR__ para garantir que o caminho é relativo ao diretório atual

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $birthdate = $_POST['birthday']; // Corrigido para usar o nome correto do campo
    $gender = $_POST['genre']; // Corrigido para usar o nome correto do campo
    $phone = $_POST['mobile']; // Corrigido para usar o nome correto do campo
    $email = $_POST['email'];
    $username = $_POST['username']; // Corrigido para usar o nome correto do campo
    $address = $_POST['address'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $ageGroup = $_POST['ageGroup']; // Adicionado para capturar o grupo de idade

    // Calcular a idade com base na data de nascimento
    $today = new DateTime();
    $dob = new DateTime($birthdate);
    $age = $today->diff($dob)->y;

    // Determinar o grupo etário com base na idade
    if ($age >= 17) {
        $ageGroup = '17+';
    } elseif ($age >= 15 && $age <= 16) {
        $ageGroup = '15-16';
    } elseif ($age >= 13 && $age <= 14) {
        $ageGroup = '13-14';
    } elseif ($age >= 11 && $age <= 12) {
        $ageGroup = '11-12';
    } elseif ($age >= 9 && $age <= 10) {
        $ageGroup = '9-10';
    } elseif ($age >= 7 && $age <= 8) {
        $ageGroup = '7-8';
    } else {
        $ageGroup = 'Under 7'; 
    }

    try {
        // Verificar se a conexão foi bem-sucedida
        if ($conn === null) {
            throw new Exception("Falha na conexão com o banco de dados.");
        }

        // Inserir dados na tabela Athlete
        $sql = "INSERT INTO Athlete (name, birthday, genre, mobile, email,username, address, password, ageGroup) 
                VALUES (:name, :birthdate, :gender, :phone, :email, :username, :address, :password, :ageGroup)";

        // Preparar e executar a query
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':birthdate', $birthdate);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':ageGroup', $ageGroup);

        $stmt->execute();

        echo "Registro inserido com sucesso!";
    } catch (Exception $e) {
        echo "Erro: " . $e->getMessage();
    }
}
?>