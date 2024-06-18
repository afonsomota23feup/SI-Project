<?php
// Configuração da conexão com o banco de dados
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $birthday = $_POST["birthday"];
    $genre = $_POST["genre"];
    $mobile = $_POST["mobile"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $address = $_POST["address"];
    $ageGroup = $_POST["ageGroup"];

    try {
        // Preparar a consulta SQL usando prepared statements
        $sql = "INSERT INTO Athlete (name, birthday, genre, mobile, email, password, address, ageGroup)
                VALUES (:name, :birthday, :genre, :mobile, :email, :password, :address, :ageGroup)";
        $stmt = $conn->prepare($sql);

        // Associar os parâmetros aos valores do formulário
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':birthday', $birthday);
        $stmt->bindParam(':genre', $genre);
        $stmt->bindParam(':mobile', $mobile);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':ageGroup', $ageGroup);

        // Executar a consulta
        $stmt->execute();

        echo "Novo atleta cadastrado com sucesso!";
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }

    // Fechar a conexão
    $conn = null;
}
?>
