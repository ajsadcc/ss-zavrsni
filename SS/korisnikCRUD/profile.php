<?php
global $conn;
require '../db/connect.php';
session_start();
require '../header-footer/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$stmt = $conn->prepare("
    SELECT p.*, s.name AS subject_name
    FROM post p
    JOIN subject s ON p.subject_id = s.id
    WHERE p.user_id = ?
    ORDER BY p.created_at DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$posts = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title>Moj profil</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<h1>Moj profil</h1>

<div class="profile-box">
    <p><strong>Korisničko ime:</strong> <?= htmlspecialchars($user['username']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
    <p><strong>Kreiran:</strong> <?= date("d.m.Y", strtotime($user['created_at'])) ?></p>
</div>

<h2>Moje objave</h2>

<?php if (count($posts) > 0): ?>
    <?php foreach ($posts as $post): ?>
        <div class="card">
            <h2><?= htmlspecialchars($post['title']) ?></h2>
            <div class="info">
                Predmet: <strong><?= htmlspecialchars($post['subject_name']) ?></strong> |
                Datum: <?= date("d.m.Y", strtotime($post['created_at'])) ?>
            </div>
            <p><?= nl2br(htmlspecialchars(substr($post['content'], 0, 200))) ?>...</p>
            <a class="edit-btn" href="edit.php?id=<?= $post['id'] ?>">Izmijeni</a>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>Nemate nijednu objavu.</p>
<?php endif; ?>

</body>
</html>

<?php require '../header-footer/footer.php'; ?>
