<?php
session_start();
include 'db_connect.php'; // Adjust path as necessary

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Validate input data
        $competition_id = $_POST['competition_id'];
        $athlete_id = $_POST['athlete'];
        $apparatus_id = $_POST['apparatus'];
        $place = $_POST['place'];
        $score = $_POST['score'];

        // Prepare SQL statement for inserting data into Result table
        $sql_insert = "INSERT INTO Result (idCompetition, idAthlete, idAparatus, place, score)
                       VALUES (:competition_id, :athlete_id, :apparatus_id, :place, :score)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bindParam(':competition_id', $competition_id);
        $stmt_insert->bindParam(':athlete_id', $athlete_id);
        $stmt_insert->bindParam(':apparatus_id', $apparatus_id);
        $stmt_insert->bindParam(':place', $place);
        $stmt_insert->bindParam(':score', $score);

        // Execute the statement
        $stmt_insert->execute();

        // Redirect back to the form page with a success message
        header("Location: ../frontend/menu_coach.php");
        exit();
    } catch (Exception $e) {
        // Redirect back to the form page with an error message
        header("Location: ../frontend/menu_coach.php" . urlencode($e->getMessage()));
        exit();
    }
} else {
    // If the request method is not POST, redirect back to the form page
    header("Location: ../frontend/menu_coach.php");
    exit();
}
?>
