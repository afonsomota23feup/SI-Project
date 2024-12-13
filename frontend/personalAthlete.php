<?php
session_start();
include __DIR__ . '/../backend/db_connect.php';
include __DIR__ . '/../backend/functions.php';

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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tag_uid'])) {
    $tag_uid = $_POST['tag_uid'] ?? null;
    if ($tag_uid) {
        $stmt = $conn->prepare("UPDATE Athlete SET tag_uid = ? WHERE idAthlete = ?");
        if ($stmt === false) {
            $errorMessage = "Erro na preparação da consulta: " . $conn->error;
        } else {
            $stmt->bind_param("si", $tag_uid, $idAthlete);
            if ($stmt->execute()) {
                $athlete['tag_uid'] = $tag_uid; // Atualiza o valor para exibição
                $successMessage = "Tag UID atualizado com sucesso!";
            } else {
                $errorMessage = "Erro ao atualizar o Tag UID: " . $stmt->error;
            }
            $stmt->close();
        }
    } else {
        $errorMessage = "Tag UID não pode estar vazio.";
    }
}

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Atleta</title>
    <link rel="stylesheet" href="../css/personalAthlete.css">
    <style>
        .hidden { display: none; }
        .message { margin: 10px 0; padding: 10px; border: 1px solid #ccc; background-color: #f9f9f9; }
        .success { color: green; }
        .error { color: red; }
    </style>
    <script>
        function toggleTagForm() {
            document.getElementById("tagForm").classList.toggle("hidden");
        }
    </script>
</head>
<body>
    <header>
        <div class="header-content">
            <h1>Detalhes do Atleta</h1>
        </div>
        <div class="logo-content">
            <img src="..\imagens\teste.png" alt="Logo do Clube" class="header-logo">
        </div>
    </header>
    <main>
        <div class="athlete-details">
            <h2>Informações do Atleta</h2>

            <p><strong>Nome:</strong> <?php echo htmlspecialchars($athlete['name']); ?></p>
            <p><strong>Data de Nascimento:</strong> <?php echo htmlspecialchars($athlete['birthday']); ?></p>
            <p><strong>Género:</strong> <?php echo htmlspecialchars($athlete['genre']); ?></p>
            <p><strong>Escalão:</strong> <?php echo htmlspecialchars($athlete['ageGroup']); ?> </p>
            <p><strong>Telefone:</strong> <?php echo htmlspecialchars($athlete['mobile']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($athlete['email']); ?></p>
            <p><strong>Endereço:</strong> <?php echo htmlspecialchars($athlete['address']); ?></p>
            <p><strong>Tag UID:</strong> <?php echo htmlspecialchars($athlete['tag_uid'] ?? 'Não atribuído'); ?></p>
            <button onclick="toggleTagForm()">Adicionar ou Editar Tag UID</button>

            <div id="tagForm" class="hidden">
    <form method="POST" action="../backend/update_tag_uid.php">
        <label for="tag_uid">Tag UID:</label>
        <input type="text" id="tag_uid" name="tag_uid" value="<?php echo htmlspecialchars($athlete['tag_uid'] ?? ''); ?>" required>
        <input type="hidden" name="idAthlete" value="<?php echo htmlspecialchars($idAthlete); ?>">
        <button type="submit">Salvar</button>
    </form>
</div>

<?php if (!empty($successMessage)): ?>
    <div class="message success"><?php echo htmlspecialchars($successMessage); ?></div>
<?php elseif (!empty($errorMessage)): ?>
    <div class="message error"><?php echo htmlspecialchars($errorMessage); ?></div>
<?php endif; ?>

        
        <div class="athlete-history">
            <h2>Histórico de Treinos</h2>
            <div id="calendar"></div>
            <?php if (count($trainings) > 0): ?>
                <ul>
                    <?php foreach (array_slice($trainings, -7) as $training): ?>
                        <li><?php echo htmlspecialchars($training['dateTrainingReg']) . ': ' . htmlspecialchars($training['performance']); ?></li>
                    <?php endforeach; ?>
                </ul>
                <button onclick="window.location.href = 'list_trainingByAthlete.php?idAthlete=<?php echo $idAthlete; ?>';">Ver todos os treinos</button>
            <?php else: ?>
                <p>Sem histórico de treinos disponível.</p>
            <?php endif; ?>

            <h2>Histórico de Resultados</h2>
            <?php if (!is_string($results) && count($results) > 0): ?>
                <ul>
                    <?php foreach ($results as $item): ?>
                        <?php 
                        $competitionDate = strtotime($item['competition']['startTime']);
                        $oneYearAgo = strtotime('-1 year');
                        if ($competitionDate >= $oneYearAgo): ?>
                            <li><?php echo htmlspecialchars($item['competition']['name']) . ': ' . htmlspecialchars($item['result']['score']) . ' -> ' . htmlspecialchars($item['result']['place']); ?></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
                <button onclick="window.location.href = 'list_compByAthlete.php?idAthlete=<?php echo $idAthlete; ?>';">Mostrar todas as competições</button>
            <?php else: ?>
                <p>Sem histórico de resultados disponível.</p>
            <?php endif; ?>

            <h2>Últimas Avaliações Físicas</h2>
            <?php if (count($condition) > 0): 
                $lastTwoConditions = array_slice($condition, -3); ?>
                <ul>
                    <?php foreach ($lastTwoConditions as $conditionTest): ?>
                        <li><?php echo htmlspecialchars($conditionTest['dateTest']) . ': ' . htmlspecialchars($conditionTest['weight']) . 'kg + ' . htmlspecialchars($conditionTest['height']) . 'cm'; ?></li>
                    <?php endforeach; ?>
                </ul>
                <button onclick="window.location.href = 'list_all_conditions.php?idAthlete=<?php echo $idAthlete; ?>';">Ver todas as avaliações físicas</button>
            <?php else: ?>
                <p>Sem histórico de avaliações físicas disponível.</p>
            <?php endif; ?>
        </div>

        <div class="back-button">
            <a href="javascript:history.back()">Voltar</a>
        </div>
    </main>
    <footer>
        <p>&copy; 2024 Gravity Masters Management Software</p>
    </footer>
</body>
</html>
