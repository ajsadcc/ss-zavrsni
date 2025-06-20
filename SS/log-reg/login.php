<?php
session_start();
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title>Prijava korisnika</title>
    <link rel="stylesheet" href="../style.css">
    <script src="../js/main.js" defer></script>
</head>
<body>
<h2>Prijava korisnika</h2>

<?php
if (isset($_SESSION['error'])) {
    echo '<p class="error-msg">' . htmlspecialchars($_SESSION['error']) . '</p>';
    unset($_SESSION['error']);
}
?>

<form method="POST" action="login_check.php" id="loginForm">
    <label for="username">Korisničko ime:</label>
    <input type="text" name="username" id="username" required><br>

    <label for="password">Lozinka:</label>
    <input type="password" name="password" id="password" required><br>

    <button type="submit">Prijavi se</button>
</form>
</body>
</html>
