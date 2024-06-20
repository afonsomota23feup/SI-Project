<?php
// Configuração da conexão com o banco de dados
include __DIR__ . '/db_connect.php'; // Usando __DIR__ para garantir que o caminho é relativo ao diretório atual

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $birthdate = $_POST['birthday']; // Corrigido para usar o nome correto do campo
    $gender = $_POST['genre']; // Corrigido para usar o nome correto do campo
    $phone = $_POST['mobile']; // Corrigido para usar o nome correto do campo
    $email = $_POST['email'];
    $address = $_POST['address'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $ageGroup = $_POST['ageGroup']; // Adicionado para capturar o grupo de idade

    // Calcular a idade com base na data de nascimento
    $today = new DateTime();
    $dob = new DateTime($birthdate);
    $age = $today->diff($dob)->y;

    // Determinar o grupo etário com base na idade
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
    header("Location: ../frontend/list_athletes.php ");
    exit;
}
?>
