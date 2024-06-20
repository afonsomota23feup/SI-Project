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

        // Check if the result already exists
        $sql_check = "SELECT COUNT(*) FROM Result WHERE idCompetition = :competition_id AND idAthlete = :athlete_id";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bindParam(':competition_id', $competition_id);
        $stmt_check->bindParam(':athlete_id', $athlete_id);
        $stmt_check->execute();
        $result_exists = $stmt_check->fetchColumn();

        if ($result_exists > 0) {
            // Handle the case where the result already exists (e.g., update the existing entry)
            // For now, we'll just redirect back with an error message
            $error_message = "Result for this competition and athlete already exists.";
            header("Location: ../frontend/menu_coach.php?error=" . urlencode($error_message));
            exit();
        }

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
        header("Location: ../frontend/menu_coach.php?success=Result added successfully.");
        exit();
    } catch (Exception $e) {
        // Redirect back to the form page with an error message
        header("Location: ../frontend/menu_coach.php?error=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    // If the request method is not POST, redirect back to the form page
    header("Location: ../frontend/menu_coach.php");
    exit();
}
?>
