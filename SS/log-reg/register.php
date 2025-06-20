<?php
session_start();
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title>Registracija</title>
    <link rel="stylesheet" href="../style.css">
    <script src="../js/main.js" defer></script>
</head>
<body>
<h2>Registracija korisnika</h2>

<?php
if (isset($_SESSION['reg_error'])) {
    echo '<p class="error-msg">' . htmlspecialchars($_SESSION['reg_error']) . '</p>';
    unset($_SESSION['reg_error']);
}
if (isset($_SESSION['reg_success'])) {
    echo '<p class="success-msg">' . htmlspecialchars($_SESSION['reg_success']) . '</p>';
    unset($_SESSION['reg_success']);
}
?>

<form id="registerForm" method="POST" action="register_check.php">
    <label for="username">Korisničko ime:</label>
    <input type="text" name="username" id="username" required><br>

    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required><br>

    <label for="password">Lozinka:</label>
    <input type="password" name="password" id="password" required><br>

    <label for="confirm">Potvrdi lozinku:</label>
    <input type="password" name="confirm" id="confirm" required><br>

    <button type="submit">Registruj se</button>
</form>
</body>
</html>
