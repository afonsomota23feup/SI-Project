<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $birthday = $_POST["birthday"];
    $genre = $_POST["genre"];
    $mobile = $_POST["mobile"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $address = $_POST["address"];
    $ageGroup = $_POST["ageGroup"];

    $sql = "INSERT INTO Athlete (name, birthday, genre, mobile, email, password, address, ageGroup)
            VALUES ('$name', '$birthday', '$genre', '$mobile', '$email', '$password', '$address', '$ageGroup')";

    if ($conn->query($sql) === TRUE) {
        echo "Novo atleta cadastrado com sucesso!";
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
