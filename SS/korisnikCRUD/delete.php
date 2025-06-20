<?php
global $conn;
require 'db/connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['id'])) {
    echo "Neispravan zahtjev.";
    exit;
}

$post_id = intval($_GET['id']);

$stmt = $conn->prepare("
    DELETE FROM post 
    WHERE id = ? AND user_id = ?
");
$stmt->bind_param("ii", $post_id, $user_id);
$stmt->execute();

header("Location: profile.php");
exit;
?>
<link rel="stylesheet" href="../style.css">
