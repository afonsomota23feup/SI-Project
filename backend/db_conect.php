<?php
$db_path = __DIR__ . '/../db/project.db';

// Tentar estabelecer uma conexão com o banco de dados SQLite
try {
    $conn = new PDO("sqlite:" . $db_path);
    // Configurar o PDO para lançar exceções em caso de erro
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}
?>
