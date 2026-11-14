<?php
declare(strict_types=1);

session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit;
}

$user = $_SESSION['user'];
$firstname = htmlspecialchars($user['prenom'] ?? '', ENT_QUOTES, 'UTF-8');
$lastname = htmlspecialchars($user['nom'] ?? '', ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace personnel - Blue Life Harmony</title>
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link rel="stylesheet" href="assets/css/index.css">
</head>
<body>
<header class="dashboard-header">
    <div class="logo">
        <img src="assets/img/logo.png" alt="Blue Life Harmony">
        <p>Blue <span>Life</span> <span>Harmony</span></p>
    </div>
    <nav class="user-info">
        <a href="../logout.php" class="logout-button">Déconnexion</a>
    </nav>
</header>
<main>
    <h1>Espace personnel</h1>
    <p>Bienvenue <?= $firstname ?> <?= $lastname ?>.</p>
    <section class="content">
        <h2>Vue d'ensemble</h2>
        <p>Bienvenue sur votre espace personnel.</p>
    </section>
</main>
<footer class="footer">
    <p>© <?= date('Y') ?> Blue Life Harmony. Tous droits réservés.</p>
</footer>
</body>
</html>
