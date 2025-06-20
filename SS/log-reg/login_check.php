<?php
global $conn;
session_start();
require '../db/connect.php';

$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if (empty($username) || empty($password)) {
    $_SESSION['error'] = "Sva polja su obavezna!";
    header("Location: login.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];

    header("Location: ../lekcije.php");
    exit();
} else {
    $_SESSION['error'] = "Pogrešno korisničko ime ili lozinka.";
    header("Location: login.php");
    exit();
}
