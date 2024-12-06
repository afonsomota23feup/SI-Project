<?php
session_start();
include __DIR__ . '/db_connect.php';

// Verifique se a conexão ao banco foi estabelecida corretamente
if (!$conn) {
    $response = array(
        "status" => "Erro",
        "message" => "Falha na conexão com o banco de dados"
    );
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

// Receber os dados enviados pelo ESP32 ou interface
$data = json_decode(file_get_contents('php://input'), true);

// Verificar se a tag foi recebida
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

// Passo 1: Verificar se o UID da tag RFID está associado a um atleta
$stmt = $conn->prepare('SELECT * FROM Athlete WHERE tag_uid = :tag');
if (!$stmt) {
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

// Verifique se a execução da consulta falhou
if ($stmt->errorCode() !== '00000') {
    $response = array(
        "status" => "Erro",
        "message" => "Erro ao executar a consulta: " . $stmt->errorInfo()[2]
    );
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

// Se o atleta foi encontrado
if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $idAthlete = $row['idAthlete'];
    $name = $row['name'];

    // Passo 2: Verificar o último registro de presença do atleta para determinar se é "Entrada" ou "Saída"
    $sql = "SELECT status, timestamp FROM Presencas 
            WHERE idAthlete = :idAthlete 
            AND DATE(timestamp) = DATE('now') 
            ORDER BY timestamp DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':idAthlete', $idAthlete, PDO::PARAM_INT);
    $stmt->execute();
    $lastEntry = $stmt->fetch(PDO::FETCH_ASSOC);

    // Define o novo status com base no último registro
    if ($lastEntry && $lastEntry['status'] === 'Entrada') {
        $newStatus = 'Saída';
    } else {
        $newStatus = 'Entrada';
    }

    // Passo 3: Registrar a presença com o status definido
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

    // Verificar se a inserção foi bem-sucedida
    if ($stmt->execute()) {
        $response = array(
            "status" => "Sucesso",
            "message" => "Presença registrada para o atleta: $name",
            "idAthlete" => $idAthlete,
            "name" => $name
        );
    } else {
        $response = array(
            "status" => "Erro",
            "message" => "Falha ao registrar a presença"
        );
    }
} else {
    // Se não encontrar o atleta com a tag fornecida
    $response = array(
        "status" => "Erro",
        "message" => "Tag RFID não associada a nenhum atleta"
    );
}

// Retornar resposta em JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
