<?php
session_start();
include '../backend/db_connect.php';
include '../backend/functions.php';

// Verifica se o parâmetro idAthlete está presente na URL
if (!isset($_GET['idAthlete'])) {
    echo "Atleta não especificado.";
    exit;
}

$idAthlete = $_GET['idAthlete'];
$athlete = fetchinfoAthlete($idAthlete);
$condition = fetchConditionTestsAthlete($idAthlete);
$trainings = fetchTrainingsAthlete($idAthlete);
$results = fetchResultsAndDetailsOfCompetitionAthlete($idAthlete);

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Atleta</title>
    <!-- <link rel="stylesheet" href="../css/personal_athlete.css"> -->
</head>
<body>
    <header>
        <div class="header-content">
            <h1>Detalhes do Atleta</h1>
        </div>
    </header>
    <main>
        <div class="athlete-details">
            <h2>Informações do Atleta</h2>
            <p><strong>Nome:</strong> <?php echo htmlspecialchars($athlete['name']); ?></p>
            <p><strong>Data de Nascimento:</strong> <?php echo htmlspecialchars($athlete['birthday']); ?></p>
            <p><strong>Género:</strong> <?php echo htmlspecialchars($athlete['genre']); ?></p>
            <p><strong>Telefone:</strong> <?php echo htmlspecialchars($athlete['mobile']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($athlete['email']); ?></p>
            <p><strong>Endereço:</strong> <?php echo htmlspecialchars($athlete['address']); ?></p>
        </div>
        
        <div class="athlete-history">
            <h2>Histórico de Treinos</h2>
            <?php if (count($trainings) > 0): ?>
                <ul>
                    <?php foreach ($trainings as $training): ?>
                        <li><?php echo htmlspecialchars($training['dateTrainingReg']) . ': ' . htmlspecialchars($training['performance']); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Sem histórico de treinos disponível.</p>
            <?php endif; ?>
            
            <h2>Histórico de Resultados</h2>
                <?php 
                $data = fetchResultsAndDetailsOfCompetitionAthlete($idAthlete);
                if (!is_string($data) && count($data) > 0): ?>
                    <ul>
                        <?php foreach ($data as $item): ?>
                            <li><?php echo htmlspecialchars($item['competition']['name']) . ': ' . htmlspecialchars($item['result']['score']) . ' -> ' . htmlspecialchars($item['result']['place']); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>Sem histórico de resultados disponível.</p>
                <?php endif; ?>

            <h2>Histórico de Avaliações Físicas</h2>
            <?php if (count($condition) > 0): ?>
                <ul>
                    <?php foreach ($condition as $conditionTest): ?>
                        <li><?php echo htmlspecialchars($conditionTest['dateTest']) . ': ' . htmlspecialchars($conditionTest['weight']) . 'kg + ' . htmlspecialchars($conditionTest['height']). 'cm' ; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Sem histórico de avaliações físicas disponível.</p>
            <?php endif; ?>
        </div>
        
        <div class="back-button">
            <a href="list_athletes.php">Voltar à Lista de Atletas</a>
        </div>
    </main>
    <footer>
        <p>&copy; 2024 Gravity Masters Management Software</p>
    </footer>
</body>
</html>

