<?php
global $conn;
session_start();
require '../db/connect.php';

$username = isset($_POST['username']) ? $_POST['username'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if ($username === '' || $email === '' || $password === '') {
    $_SESSION['reg_error'] = "Sva polja su obavezna!";
    header("Location: register.php");
    exit();
}

$stmt = $conn->prepare("SELECT id FROM user WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $_SESSION['reg_error'] = "Email je već registrovan.";
    header("Location: register.php");
    exit();
}

$stmt->close();

$stmt = $conn->prepare("SELECT id FROM user WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $_SESSION['reg_error'] = "Korisničko ime je već registrovano.";
    header("Location: register.php");
    exit();
}

$stmt->close();

$hashed = password_hash($password, PASSWORD_DEFAULT);

$insert = $conn->prepare("INSERT INTO user (username, email, password) VALUES (?, ?, ?)");
$insert->bind_param("sss", $username, $email, $hashed);
$insert->execute();

$_SESSION['reg_success'] = "Uspješno ste se registrovali. Možete se prijaviti.";
header("Location: register.php");
exit();
