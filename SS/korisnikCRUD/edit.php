<?php
require 'db/connect.php';
session_start();
require 'header.php';

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
    SELECT * FROM post 
    WHERE id = ? AND user_id = ?
");
$stmt->bind_param("ii", $post_id, $user_id);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();

if (!$post) {
    echo "Objava nije pronađena ili nemate dozvolu za izmjenu.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $subject_id = intval($_POST['subject_id']);

    if ($title && $content && $subject_id) {
        $stmt = $conn->prepare("
            UPDATE post 
            SET title = ?, content = ?, subject_id = ?, updated_at = NOW()
            WHERE id = ? AND user_id = ?
        ");
        $stmt->bind_param("ssiii", $title, $content, $subject_id, $post_id, $user_id);
        $stmt->execute();

        header("Location: profile.php");
        exit;
    } else {
        $error = "Sva polja su obavezna.";
    }
}

$subjects = $conn->query("SELECT * FROM subject")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title>Izmijeni objavu</title>
    <link rel="stylesheet" href="../style.css"></head>
<body>

<h1>Izmijeni objavu</h1>

<div class="form-box">
    <form method="POST">
        <label>Naslov:</label>
        <input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>" required>

        <label>Sadržaj:</label>
        <textarea name="content" rows="8" required><?= htmlspecialchars($post['content']) ?></textarea>

        <label>Predmet:</label>
        <select name="subject_id" required>
            <?php foreach ($subjects as $s): ?>
                <option value="<?= $s['id'] ?>" <?= ($s['id'] == $post['subject_id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($s['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button class="btn-save" type="submit">Sačuvaj izmjene</button>
    </form>

    <a class="btn-delete" href="delete.php?id=<?= $post['id'] ?>" onclick="return confirm('Da li ste sigurni da želite da obrišete ovu objavu?');">Obriši objavu</a>

    <?php if (isset($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
</div>

</body>
</html>

<?php require 'footer.php'; ?>
