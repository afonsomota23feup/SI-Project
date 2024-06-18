<?php
include 'db.php';

$sql = "SELECT * FROM Athlete";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "ID: " . $row["idAthlete"]. " - Nome: " . $row["name"]. " - Idade: " . $row["birthday"]. "<br>";
    }
} else {
    echo "0 resultados";
}
$conn->close();
?>
