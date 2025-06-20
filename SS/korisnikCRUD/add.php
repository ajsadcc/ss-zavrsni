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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $subject_id = intval($_POST['subject_id']);

    if ($title && $content && $subject_id) {
        $stmt = $conn->prepare("
            INSERT INTO post (title, content, subject_id, user_id, created_at)
            VALUES (?, ?, ?, ?, NOW())
        ");
        $stmt->bind_param("ssii", $title, $content, $subject_id, $user_id);
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
    <title>Dodavanje objave</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<h1>Dodaj novu objavu</h1>

<div class="form-box">
    <form method="POST">
        <label for="title">Naslov</label>
        <input type="text" name="title" required>

        <label for="content">Sadržaj</label>
        <textarea name="content" rows="8" required></textarea>

        <label for="subject_id">Predmet</label>
        <select name="subject_id" required>
            <option value="">-- Izaberite predmet --</option>
            <?php foreach ($subjects as $s): ?>
                <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
            <?php endforeach; ?>
        </select>

        <button class="btn-save" type="submit">Sačuvaj objavu</button>
    </form>

    <?php if (isset($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
</div>

</body>
</html>

<?php require '../header-footer/footer.php'; ?>
