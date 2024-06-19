<?php
// Caminho absoluto para o arquivo do banco de dados
$db_path = realpath(__DIR__ . '/../db/project.db');

// Verificar se o arquivo de banco de dados existe
if (!file_exists($db_path)) {
    die("Arquivo de banco de dados não encontrado: " . $db_path);
}

// Verificar permissões de leitura/escrita
if (!is_readable($db_path) || !is_writable($db_path)) {
    die("Permissões insuficientes para o arquivo de banco de dados: " . $db_path);
}

// Tentar estabelecer uma conexão com o banco de dados SQLite
try {
    $conn = new PDO("sqlite:" . $db_path);
    // Configurar o PDO para lançar exceções em caso de erro
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    $conn = null;
    exit();
}
?>
