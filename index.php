<!DOCTYPE html>
<html>
<head>
    <title>Coaching Staff Form</title>
</head>
<body>
    <h1>Coaching Staff Form</h1>
    <form action="process_coaching_staff.php" method="POST">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br><br>

        <label for="birthday">Birthday:</label>
        <input type="date" id="birthday" name="birthday" required><br><br>

        <label for="genre">Genre:</label>
        <input type="text" id="genre" name="genre" required><br><br>

        <label for="mobile">Mobile:</label>
        <input type="text" id="mobile" name="mobile" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address" required><br><br>

        <label for="function">Function:</label>
        <input type="text" id="function" name="function" required><br><br>

        <input type="submit" value="Submit">
    </form>
</body>
</html>