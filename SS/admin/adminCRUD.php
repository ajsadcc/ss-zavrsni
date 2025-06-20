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
$search = isset($_GET['search']) ? $_GET['search'] : '';

$data = [];

if ($type === 'user') {
    $stmt = $conn->prepare("
        SELECT id, username, email, role, created_at 
        FROM user
        WHERE username LIKE ? OR email LIKE ?
        ORDER BY created_at DESC
    ");
    $like = "%$search%";
    $stmt->bind_param('ss', $like, $like);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

} elseif ($type === 'post') {
    $stmt = $conn->prepare("
        SELECT p.id, p.title, u.username AS author, p.created_at
        FROM post p
        JOIN user u ON p.user_id = u.id
        WHERE p.title LIKE ? OR u.username LIKE ?
        ORDER BY p.created_at DESC
    ");
    $like = "%$search%";
    $stmt->bind_param('ss', $like, $like);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

} elseif ($type === 'subject') {
    $stmt = $conn->prepare("
        SELECT id, name 
        FROM subject
        WHERE name LIKE ?
        ORDER BY id DESC
    ");
    $like = "%$search%";
    $stmt->bind_param('s', $like);
    $stmt->execute();
    $data = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <title>Administracija</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<h1>Administracija</h1>

<div class="top-bar">
    <form method="GET">
        <select name="type" onchange="this.form.submit()">
            <option value="user" <?= $type === 'user' ? 'selected' : '' ?>>Korisnici</option>
            <option value="post" <?= $type === 'post' ? 'selected' : '' ?>>Objave</option>
            <option value="subject" <?= $type === 'subject' ? 'selected' : '' ?>>Predmeti</option>
        </select>

        <input type="text" name="search" placeholder="Pretraga..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit" class="btn">Pretraži</button>
    </form>

    <a href="adminAdd.php?type=<?= htmlspecialchars($type) ?>" class="btn">+ Dodaj <?= htmlspecialchars($type) ?></a>
</div>

<div class="table-box">
    <?php if ($type === 'user'): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Korisničko ime</th>
                <th>Email</th>
                <th>Uloga</th>
                <th>Datum kreiranja</th>
                <th>Akcije</th>
            </tr>
            <?php foreach ($data as $row): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['role']) ?></td>
                    <td><?= $row['created_at'] ?></td>
                    <td>
                        <a href="adminEdit.php?type=user&id=<?= $row['id'] ?>" class="btn">Izmijeni</a>
                        <a href="adminDelete.php?type=user&id=<?= $row['id'] ?>" class="btn" onclick="return confirm('Da li želite da obrišete korisnika?')">Obriši</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

    <?php elseif ($type === 'post'): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Naslov</th>
                <th>Autor</th>
                <th>Datum kreiranja</th>
                <th>Akcije</th>
            </tr>
            <?php foreach ($data as $row): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= htmlspecialchars($row['author']) ?></td>
                    <td><?= $row['created_at'] ?></td>
                    <td>
                        <a href="../korisnikCRUD/edit.php?id=<?= $row['id'] ?>" class="btn">Izmijeni</a>
                        <a href="../korisnikCRUD/delete.php?id=<?= $row['id'] ?>" class="btn" onclick="return confirm('Da li želite da obrišete objavu?')">Obriši</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

    <?php elseif ($type === 'subject'): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Naziv predmeta</th>
                <th>Akcije</th>
            </tr>
            <?php foreach ($data as $row): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td>
                        <a href="adminEdit.php?type=subject&id=<?= $row['id'] ?>" class="btn">Izmijeni</a>
                        <a href="adminDelete.php?type=subject&id=<?= $row['id'] ?>" class="btn" onclick="return confirm('Da li želite da obrišete predmet?')">Obriši</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</div>

</body>
</html>

<?php require '../header-footer/footer.php'; ?>
