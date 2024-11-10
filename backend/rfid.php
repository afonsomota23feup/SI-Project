<?php
session_start();
include __DIR__ . '/db_connect.php';

// Check if database connection was established successfully
if (!$conn) {
    $response = array(
        "status" => "Erro",
        "message" => "Falha na conexão com o banco de dados"
    );
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

// Receive data sent from ESP32 or interface
$data = json_decode(file_get_contents('php://input'), true);
var_dump($data);

// Check if the RFID tag was received
if (!isset($data['tag'])) {
    $response = array(
        "status" => "Erro",
        "message" => "Tag RFID não fornecida"
    );
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

$tag = $data['tag']; 

// Step 1: Check if the RFID tag UID is associated with an athlete
$stmt = $conn->prepare('SELECT * FROM Athlete WHERE tag_uid = :tag');
if (!$stmt) {
    // If the query preparation fails, try the CoachingStaff table
    $stmt = $conn->prepare('SELECT * FROM CoachingStaff WHERE tag_uid = :tag');
    if (!$stmt) {
        $response = array(
            "status" => "Erro",
            "message" => "Erro ao preparar a consulta"
        );
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }
}

$stmt->bindValue(':tag', $tag, PDO::PARAM_STR);
$stmt->execute();

// Check if the query execution failed
if ($stmt->errorCode() !== '00000') {
    $response = array(
        "status" => "Erro",
        "message" => "Erro ao executar a consulta: " . $stmt->errorInfo()[2]
    );
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

// If the athlete was found
if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $idAthlete = $row['idAthlete'];
    $name = $row['name'];

    // Step 2: Check the last presence record for the athlete for today
    $sql = 'SELECT status FROM Presencas WHERE idAthlete = :idAthlete AND DATE(timestamp) = CURDATE() ORDER BY timestamp DESC LIMIT 1';
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':idAthlete', $idAthlete, PDO::PARAM_INT);
    $stmt->execute();
    $lastEntry = $stmt->fetch(PDO::FETCH_ASSOC);

    // Determine the new status based on the last status
    if ($lastEntry) {
        $lastStatus = $lastEntry['status'];
        $newStatus = ($lastStatus == 'Entrada') ? 'Saída' : 'Entrada';
    } else {
        // If no record exists for today, start with "Entrada"
        $newStatus = 'Entrada';
    }

    // Step 3: Insert the new presence record in the `Presencas` table
    $stmt = $conn->prepare('INSERT INTO Presencas (idAthlete, tag_uid, status, timestamp) VALUES (:idAthlete, :tag, :status, CURRENT_TIMESTAMP)');
    if (!$stmt) {
        $response = array(
            "status" => "Erro",
            "message" => "Erro ao preparar a inserção"
        );
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }

    $stmt->bindValue(':idAthlete', $idAthlete, PDO::PARAM_INT);
    $stmt->bindValue(':tag', $tag, PDO::PARAM_STR);
    $stmt->bindValue(':status', $newStatus, PDO::PARAM_STR);

    // Check if the insertion was successful
    if ($stmt->execute()) {
        $response = array(
            "status" => "Sucesso",
            "message" => "Presença registrada como $newStatus para o atleta: $name",
            "idAthlete" => $idAthlete,
            "name" => $name,
            "status" => $newStatus
        );
    } else {
        $response = array(
            "status" => "Erro",
            "message" => "Falha ao registrar a presença"
        );
    }
} else {
    // If no athlete is found with the provided tag
    $response = array(
        "status" => "Erro",
        "message" => "Tag RFID não associada a nenhum atleta"
    );
}

// Return response in JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
