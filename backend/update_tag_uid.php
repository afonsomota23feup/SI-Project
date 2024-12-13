<?php
// Depurar erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db_connect.php'; // Certifique-se de que este arquivo configura uma conexão PDO
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idAthlete = $_POST['idAthlete'] ?? null;
    $tagUID = $_POST['tag_uid'] ?? null;

    if (!empty($idAthlete) && !empty($tagUID)) {
        try {
            // Preparar a consulta usando PDO
            $sql = "UPDATE Athlete SET tag_uid = :tag_uid WHERE idAthlete = :idAthlete";
            $stmt = $conn->prepare($sql);
            
            // Associar os parâmetros
            $stmt->bindParam(':tag_uid', $tagUID, PDO::PARAM_STR);
            $stmt->bindParam(':idAthlete', $idAthlete, PDO::PARAM_INT);

            // Executar a consulta
            if ($stmt->execute()) {
                header("Location: ../frontend/personalAthlete.php?idAthlete=$idAthlete&status=success");
                exit();
            } else {
                echo "Erro ao atualizar o Tag UID.";
            }
        } catch (PDOException $e) {
            echo "Erro no banco de dados: " . $e->getMessage();
        }
    } else {
        echo "Por favor, preencha todos os campos corretamente.";
    }
} else {
    echo "Método de requisição inválido.";
}
