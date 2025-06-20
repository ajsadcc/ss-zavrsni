<?php include_once(__DIR__ . '/../config.php'); ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/header-footer/hf.css">

<div class="nav-bar">
    <div class="nav-div">
        <a href="<?= BASE_URL ?>/index.php" class="nav-link">
            <button type="button" class="nav-dugme">Početna</button>
        </a>
    </div>

    <?php if (isset($_SESSION['user_id'])): ?>
        <div class="nav-div">
            <a href="<?= BASE_URL ?>/lekcije.php" class="nav-link">
                <button type="button" class="nav-dugme">Lekcije</button>
            </a>
        </div>

        <div class="nav-div">
            <a href="<?= BASE_URL ?>/korisnikCRUD/add.php" class="nav-link">
                <button type="button" class="nav-dugme">Dodaj objavu</button>
            </a>
        </div>

        <div class="nav-div">
            <a href="<?= BASE_URL ?>/korisnikCRUD/profile.php" class="nav-link">
                <button type="button" class="nav-dugme">Moj profil</button>
            </a>
        </div>

        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <div class="nav-div">
                <a href="<?= BASE_URL ?>/admin/adminCRUD.php" class="nav-link">
                    <button type="button" class="nav-dugme">Admin panel</button>
                </a>
            </div>
        <?php endif; ?>

        <div class="nav-div">
            <form action="<?= BASE_URL ?>/log-reg/logout.php" method="post">
                <button type="submit" class="nav-dugme">Odjavi se</button>
            </form>
        </div>
    <?php endif; ?>
</div>
