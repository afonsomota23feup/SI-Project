<?php
// Configuração da conexão com o banco de dados
include __DIR__ . '/db_connect.php'; // Usando __DIR__ para garantir que o caminho é relativo ao diretório atual

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Coleta de dados do formulário
        $name = $_POST['name'];
        $birthdate = $_POST['birthdate'];
        $gender = $_POST['gender'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $function = $_POST['role'];
        $discipline_id = $_POST['discipline_id'];

        // Inserir dados na tabela CoachingStaff
        $sql = "INSERT INTO CoachingStaff (name, birthday, genre, mobile, email, address, password, function) 
                VALUES (:name, :birthdate, :gender, :phone, :email, :address, :password, :function)";

        // Preparar e executar a query
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':birthdate', $birthdate);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':function', $function);

        if ($stmt->execute()) {
            // Recuperar o id do CoachingStaff inserido
            $coachingStaffId = $conn->lastInsertId();

            // Inserir associação na tabela CoachingStaffDiscipline
            $sqlAssoc = "INSERT INTO CoachingStaffDiscipline (idCoachingStaff, idDiscipline) 
                         VALUES (:idCoachingStaff, :idDiscipline)";

            $stmtAssoc = $conn->prepare($sqlAssoc);
            $stmtAssoc->bindParam(':idCoachingStaff', $coachingStaffId);
            $stmtAssoc->bindParam(':idDiscipline', $discipline_id);

            if ($stmtAssoc->execute()) {
                echo "<script>alert('Registro e associação realizados com sucesso!');</script>";
            } else {
                echo "<script>alert('Erro ao realizar associação.');</script>";
            }
        } else {
            echo "<script>alert('Erro ao realizar registro.');</script>";
        }
    } catch (Exception $e) {
        echo "Erro: " . $e->getMessage();
    }

    // Fechar a conexão
    $conn = null;
    header("Location: ../frontend/menu_coach.php");
    exit;
}
?>
