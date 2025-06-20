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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($type === 'user') {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role = $_POST['role'];

        $stmt = $conn->prepare("INSERT INTO user (username, email, password, role, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param('ssss', $username, $email, $password, $role);
        $stmt->execute();

    } elseif ($type === 'post') {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $subject_id = $_POST['subject_id'];
        $user_id = $_SESSION['user_id'];

        $stmt = $conn->prepare("INSERT INTO post (title, content, subject_id, user_id, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param('ssii', $title, $content, $subject_id, $user_id);
        $stmt->execute();

    } elseif ($type === 'subject') {
        $name = $_POST['name'];

        $stmt = $conn->prepare("INSERT INTO subject (name) VALUES (?)");
        $stmt->bind_param('s', $name);
        $stmt->execute();
    }

    header("Location: adminCRUD.php?type=$type");
    exit;
}

$subjects = $conn->query("SELECT * FROM subject")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title>Dodavanje <?= htmlspecialchars($type) ?></title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<h1>Dodaj <?= htmlspecialchars($type) ?></h1>

<div class="form-box">
    <form method="POST">
        <?php if ($type === 'user'): ?>
            <input type="text" name="username" placeholder="Korisničko ime" required>
            <input type="email" name="email" placeholder="Email adresa" required>
            <input type="password" name="password" placeholder="Lozinka" required>
            <select name="role" required>
                <option value="user">Korisnik</option>
                <option value="admin">Administrator</option>
            </select>

        <?php elseif ($type === 'post'): ?>
            <input type="text" name="title" placeholder="Naslov" required>
            <textarea name="content" rows="8" placeholder="Sadržaj" required></textarea>
            <select name="subject_id" required>
                <?php foreach ($subjects as $s): ?>
                    <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
                <?php endforeach; ?>
            </select>

        <?php elseif ($type === 'subject'): ?>
            <input type="text" name="name" placeholder="Naziv predmeta" required>
        <?php endif; ?>

        <button type="submit" class="btn">Sačuvaj</button>
    </form>
</div>

</body>
</html>
