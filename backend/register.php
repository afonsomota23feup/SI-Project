<?php
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
    $name = $_POST['name'];
    $birthday = $_POST['birthday'];
    $genre = $_POST['genre'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash da senha
    $address = $_POST['address'];
    $age_group = $_POST['age_group'];

    // Inserir dados no banco de dados
    $sql = "INSERT INTO Athlete (name, birthday, genre, mobile, email, password, address, ageGroup)
            VALUES ('$name', '$birthday', '$genre', '$mobile', '$email', '$password', '$address', '$age_group')";

    if ($conn->query($sql) === TRUE) {
        echo "Athlete registered successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
