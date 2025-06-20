<?php
global $conn;
require 'db/connect.php';
session_start();
require 'header-footer/header.php';

$predmeti = $conn->query("SELECT * FROM subject")->fetch_all(MYSQLI_ASSOC);

$filter = isset($_GET['subject_id']) ? intval($_GET['subject_id']) : null;

if ($filter) {
    $stmt = $conn->prepare("
        SELECT p.*, u.username, s.name AS subject_name
        FROM post p
        JOIN user u ON p.user_id = u.id
        JOIN subject s ON p.subject_id = s.id
        WHERE p.subject_id = ?
        ORDER BY p.created_at DESC
    ");
    $stmt->bind_param("i", $filter);
    $stmt->execute();
    $lekcije = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
} else {
    $lekcije = $conn->query("
        SELECT p.*, u.username, s.name AS subject_name
        FROM post p
        JOIN user u ON p.user_id = u.id
        JOIN subject s ON p.subject_id = s.id
        ORDER BY p.created_at DESC
        LIMIT 5
    ")->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title>Lekcije</title>
    <link rel="stylesheet" href="style.css">
    <script src="js/main.js" defer></script>
</head>
<body>

<h1>Lekcije</h1>

<div class="filter">
    <form method="GET">
        <label>Izaberite predmet:</label>
        <select name="subject_id" onchange="this.form.submit()">
            <option value="">-- svi predmeti --</option>
            <?php foreach ($predmeti as $p): ?>
                <option value="<?= $p['id'] ?>" <?= $filter == $p['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($p['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>
</div>

<?php foreach ($lekcije as $lekcija): ?>
    <div class="card" onclick="window.location.href='lekcija.php?id=<?= $lekcija['id'] ?>'">
        <h2><?= htmlspecialchars($lekcija['title']) ?></h2>
        <div class="info">
            Predmet: <strong><?= htmlspecialchars($lekcija['subject_name']) ?></strong> |
            Autor: <?= htmlspecialchars($lekcija['username']) ?> |
            Datum: <?= date("d.m.Y", strtotime($lekcija['created_at'])) ?>
        </div>
        <p><?= nl2br(htmlspecialchars(substr($lekcija['content'], 0, 200))) ?>...</p>
    </div>
<?php endforeach; ?>

</body>
</html>

<?php
require 'header-footer/footer.php';
?>
