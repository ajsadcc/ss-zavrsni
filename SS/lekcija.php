<?php
require 'header-footer/header.php';
global $conn;
require 'db/connect.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$stmt = $conn->prepare("
    SELECT p.*, u.username, s.name AS subject_name
    FROM post p
    JOIN user u ON p.user_id = u.id
    JOIN subject s ON p.subject_id = s.id
    WHERE p.id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$lekcija = $stmt->get_result()->fetch_assoc();

if (!$lekcija) {
    echo "Lekcija nije pronađena.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($lekcija['title']) ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="card">
    <h1><?= htmlspecialchars($lekcija['title']) ?></h1>
    <div class="info">
        Predmet: <strong><?= htmlspecialchars($lekcija['subject_name']) ?></strong> |
        Autor: <?= htmlspecialchars($lekcija['username']) ?> |
        Datum: <?= date("d.m.Y", strtotime($lekcija['created_at'])) ?>
    </div>
    <p><?= nl2br(htmlspecialchars($lekcija['content'])) ?></p>
</div>
<p><a href="lekcije.php">← Nazad na sve lekcije</a></p>

</body>
</html>

<?php
require 'header-footer/footer.php';
?>
