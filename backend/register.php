<?php
// Configuração da conexão com o banco de dados
include __DIR__ . '/db_connect.php'; // Usando __DIR__ para garantir que o caminho é relativo ao diretório atual

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $birthdate = $_POST['birthdate'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Calculate age based on birthdate
    $dob = new DateTime($birthdate);
    $today = new DateTime();
    $age = $today->diff($dob)->y;

    // Determine age group
    if ($age >= 18) {
        $ageGroup = 'Senior';
    } elseif ($age >= 15) {
        $ageGroup = 'Juvenil';
    } elseif ($age >= 12) {
        $ageGroup = 'Infantil';
    } else {
        $ageGroup = 'Outro'; 
    }

    try {
        // Verificar se a conexão foi bem-sucedida
        if ($conn === null) {
            throw new Exception("Falha na conexão com o banco de dados.");
        }

        // Inserir dados na tabela Athlete
        $sql = "INSERT INTO Athlete (name, birthday, genre, mobile, email, address, password, ageGroup) 
                VALUES (:name, :birthdate, :gender, :phone, :email, :address, :password, :ageGroup)";

        // Preparar e executar a query
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':birthdate', $birthdate);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':ageGroup', $ageGroup);
        $stmt->execute();

        echo "<script>alert('Registro realizado com sucesso!');</script>";
    } catch (Exception $e) {
        echo "Erro: " . $e->getMessage();
    }

    // Fechar a conexão
    $conn = null;
    header("Location: ../index.html");
    exit;
}
?>
