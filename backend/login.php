<?php
session_start();
include __DIR__ . '/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $user = null;

    try {
        if ($conn === null) {
            throw new Exception("Falha na conexão com o banco de dados.");
        }

        // Verificar se é um Atleta
        $sqlAthlete = "SELECT idAthlete AS id, name, password FROM Athlete WHERE email = :email";
        $stmtAthlete = $conn->prepare($sqlAthlete);
        $stmtAthlete->bindParam(':email', $email);
        $stmtAthlete->execute();
        $athlete = $stmtAthlete->fetch(PDO::FETCH_ASSOC);

        if ($athlete && password_verify($password, $athlete['password'])) {
            $_SESSION['user_id'] = $athlete['id'];
            $_SESSION['user_name'] = $athlete['name'];
            $_SESSION['user_role'] = 'athlete';

            header("Location: ../frontend/dashboard.php");
            exit;
        }

        // Verificar se é um Treinador
        $sqlCoach = "SELECT idCoachingStaff AS id, name, password FROM CoachingStaff WHERE email = :email";
        #echo"<script>alert('Email encontrado.');</script>";
        $stmtCoach = $conn->prepare($sqlCoach);
        $stmtCoach->bindParam(':email', $email);
        $stmtCoach->execute();
        $coach = $stmtCoach->fetch(PDO::FETCH_ASSOC);

        if ($coach &&  $password === $coach['password']) {
            $_SESSION['user_id'] = $coach['id'];
            $_SESSION['user_name'] = $coach['name'];
            $_SESSION['user_role'] = 'coach';

            header("Location: ../frontend/menu_coach.php");
            exit;
        }

        // Se não encontrou nenhum usuário correspondente
        echo "<script>alert('Email ou senha incorretos.');</script>";
    } catch (Exception $e) {
        echo "Erro: " . $e->getMessage();
    }

    $conn = null;
}
?>
