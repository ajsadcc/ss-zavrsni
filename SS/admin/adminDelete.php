<?php
global $conn;
require '../db/connect.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

$type = isset($_GET['type']) ? $_GET['type'] : 'user';
$id = intval($_GET['id']);

if ($type === 'user') {
    $stmt = $conn->prepare("DELETE FROM user WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();

} elseif ($type === 'post') {
    $stmt = $conn->prepare("DELETE FROM post WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();

} elseif ($type === 'subject') {
    $stmt = $conn->prepare("DELETE FROM subject WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
}

header("Location: adminCRUD.php?type=$type");
exit;
?>
