<?php
global $conn;
require '../db/connect.php';
session_start();
require '../header-footer/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

$type = isset($_GET['type']) ? $_GET['type'] : 'user';
$id = intval($_GET['id']);
$subjects = $conn->query("SELECT * FROM subject")->fetch_all(MYSQLI_ASSOC);

if ($type === 'user') {
    $stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $item = $stmt->get_result()->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $role = $_POST['role'];

        $stmt = $conn->prepare("UPDATE user SET username=?, email=?, role=? WHERE id=?");
        $stmt->bind_param('sssi', $username, $email, $role, $id);
        $stmt->execute();

        header("Location: adminCRUD.php?type=user");
        exit;
    }

} elseif ($type === 'post') {
    $stmt = $conn->prepare("SELECT * FROM post WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $item = $stmt->get_result()->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $subject_id = $_POST['subject_id'];

        $stmt = $conn->prepare("UPDATE post SET title=?, content=?, subject_id=? WHERE id=?");
        $stmt->bind_param('ssii', $title, $content, $subject_id, $id);
        $stmt->execute();

        header("Location: adminCRUD.php?type=post");
        exit;
    }

} elseif ($type === 'subject') {
    $stmt = $conn->prepare("SELECT * FROM subject WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $item = $stmt->get_result()->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];

        $stmt = $conn->prepare("UPDATE subject SET name=? WHERE id=?");
        $stmt->bind_param('si', $name, $id);
        $stmt->execute();

        header("Location: adminCRUD.php?type=subject");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title>Izmjena <?= htmlspecialchars($type) ?></title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<h1>Izmijeni <?= htmlspecialchars($type) ?></h1>

<div class="form-box">
    <form method="POST">
        <?php if ($type === 'user'): ?>
            <input type="text" name="username" value="<?= htmlspecialchars($item['username']) ?>" required>
            <input type="email" name="email" value="<?= htmlspecialchars($item['email']) ?>" required>
            <select name="role" required>
                <option value="user" <?= $item['role'] === 'user' ? 'selected' : '' ?>>Korisnik</option>
                <option value="admin" <?= $item['role'] === 'admin' ? 'selected' : '' ?>>Administrator</option>
            </select>

        <?php elseif ($type === 'post'): ?>
            <input type="text" name="title" value="<?= htmlspecialchars($item['title']) ?>" required>
            <textarea name="content" rows="8" required><?= htmlspecialchars($item['content']) ?></textarea>
            <select name="subject_id" required>
                <?php foreach ($subjects as $s): ?>
                    <option value="<?= $s['id'] ?>" <?= $item['subject_id'] == $s['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($s['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

        <?php elseif ($type === 'subject'): ?>
            <input type="text" name="name" value="<?= htmlspecialchars($item['name']) ?>" required>
        <?php endif; ?>

        <button type="submit" class="btn">Sačuvaj</button>
    </form>
</div>

</body>
</html>
