<?php
$servername = "your_server";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$idAthlete = $_POST['athlete'];
$idCoachingStaff = $_POST['coachingStaff'];
$weight = $_POST['weight'];
$height = $_POST['height'];
$backFlexibility = $_POST['backFlexibility'];
$verticalThrust = $_POST['verticalThrust'];
$dateTest = $_POST['dateTest'];

$sql = "INSERT INTO ConditionTest (idAthlete, idCoachingStaff, weight, height, backFlexibility, verticalThrust, dateTest)
VALUES ('$idAthlete', '$idCoachingStaff', '$weight', '$height', '$backFlexibility', '$verticalThrust', '$dateTest')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
