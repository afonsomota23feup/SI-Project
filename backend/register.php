<?php
// Configuração da conexão com o banco de dados
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $birthdate = $_POST['birthdate'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];

    try {
        if ($role == "coach") {
            $sql = "INSERT INTO CoachingStaff (name, birthday, genre, mobile, email, address, password, function)
                    VALUES (:name, :birthdate, :gender, :phone, :email, :address, :password, 'Coach')";
        } else {
            $sql = "INSERT INTO Athlete (name, birthday, genre, mobile, email, address, password, ageGroup)
                    VALUES (:name, :birthdate, :gender, :phone, :email, :address, :password, 'Senior')";
        }

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':birthdate', $birthdate);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':password', $password);

        $stmt->execute();
        echo "Registro realizado com sucesso!";
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }

    $conn = null;
}
?>
