<?php
session_start();
include __DIR__ . '/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Verifica se o objeto de conexão está disponível
        if ($conn === null) {
            throw new Exception("Falha na conexão com o banco de dados.");
        }

        // Recupera os dados do formulário
        $athlete_id = $_POST['athlete'];
        $weight = $_POST['weight'];
        $height = $_POST['height'];
        $back_flexibility = $_POST['back_flexibility'];
        $vertical_thrust = $_POST['vertical_thrust'];
        $date_test = $_POST['date_test'];

        $coach_id = $_SESSION['user_id'];

        // Insere os dados da avaliação na tabela ConditionTest
        $sql_insert = "INSERT INTO ConditionTest (idAthlete, idCoachingStaff, weight, height, backFlexibility, verticalThrust, dateTest) 
                       VALUES (:athlete_id, :coach_id, :weight, :height, :back_flexibility, :vertical_thrust, :date_test)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bindParam(':athlete_id', $athlete_id);
        $stmt_insert->bindParam(':coach_id', $coach_id);
        $stmt_insert->bindParam(':weight', $weight);
        $stmt_insert->bindParam(':height', $height);
        $stmt_insert->bindParam(':back_flexibility', $back_flexibility);
        $stmt_insert->bindParam(':vertical_thrust', $vertical_thrust);
        $stmt_insert->bindParam(':date_test', $date_test);
        $stmt_insert->execute();

        // Define mensagem de sucesso na sessão
        $_SESSION['message'] = "Avaliação adicionada com sucesso!";
        header("Location: ../frontend/menu_coach.php");
        exit;

    } catch (Exception $e) {
        // Em caso de erro, define mensagem de erro na sessão
        $_SESSION['error'] = "Erro: " . $e->getMessage();
        header("Location: ../frontend/menu_coach.php");
        exit;
    }
} else {
    // Se não for uma requisição POST, define mensagem de erro na sessão e redireciona
    $_SESSION['error'] = "Erro: Método inválido de requisição.";
    header("Location: ../frontend/menu_coach.php");
    exit;
}
?>
