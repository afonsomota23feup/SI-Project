<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Condition Test Form</title>
    <link rel="stylesheet" href="../css/createConditionTest.css">
</head>
<body>
    <header>
    <div class="header-content">
            <img src="..\imagens\teste.png" alt="Logo do Clube" class="header-logo">
            <h1>Formulário de teste de condição</h1>
        </div>
    </header>
    <form id="conditionTestForm" action="submit_condition_test.php" method="POST">
        <label for="athlete">Athlete:</label>
        <select id="athlete" name="athlete" required>
            <!-- Add options for athletes here -->
            <option value="1">Athlete 1</option>
            <option value="2">Athlete 2</option>
            <!-- Add more athlete options as needed -->
        </select>
        <br>

        <label for="coachingStaff">Coaching Staff:</label>
        <select id="coachingStaff" name="coachingStaff" required>
            <!-- Add options for coaching staff here -->
            <option value="1">Coach 1</option>
            <option value="2">Coach 2</option>
            <!-- Add more coaching staff options as needed -->
        </select>
        <br>

        <label for="weight">Weight:</label>
        <input type="number" id="weight" name="weight" step="0.1" min="0.1" required>
        <br>

        <label for="height">Height:</label>
        <input type="number" id="height" name="height" step="0.1" min="0.1" required>
        <br>

        <label for="backFlexibility">Back Flexibility:</label>
        <input type="number" id="backFlexibility" name="backFlexibility" step="0.1" min="0.1" required>
        <br>

        <label for="verticalThrust">Vertical Thrust:</label>
        <input type="number" id="verticalThrust" name="verticalThrust" step="0.1" min="0.1" required>
        <br>

        <label for="dateTest">Date of Test:</label>
        <input type="date" id="dateTest" name="dateTest" required>
        <br>

        <input type="submit" value="Submit">
    </form>
        <footer>
        <p>&copy; 2024 Gravity Masters Management Software</p>
    </footer>
</body>
</html>
