<?php
declare(strict_types=1);

session_start();
require_once __DIR__ . '/scripts/config.php';

if (isset($_SESSION['user'])) {
    header('Location: espaceperso/index.php');
    exit;
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';

    if (!$email || $password === '') {
        $error = 'Identifiants incorrects. Vérifiez vos informations.';
    } else {
        $statement = $lien->prepare('SELECT id, email, password, nom, prenom FROM users WHERE email = ? LIMIT 1');
        $statement->bind_param('s', $email);
        $statement->execute();
        $user = $statement->get_result()->fetch_assoc();
        $statement->close();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            unset($user['password']);
            $_SESSION['user'] = $user;
            header('Location: espaceperso/index.php');
            exit;
        }

        $error = 'Identifiants incorrects. Vérifiez vos informations.';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blue Life Harmony - Connexion</title>
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>
<?php require __DIR__ . '/partials/header.php'; ?>
<main class="login-page">
    <div class="login-container">
        <h1>Espace personnel</h1>
        <?php if ($error !== null): ?>
            <p class="form-error" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>
        <form action="login.php" method="post">
            <div class="form-group">
                <label for="email">Adresse e-mail</label>
                <input type="email" id="email" name="email" autocomplete="email" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" autocomplete="current-password" required>
            </div>
            <button type="submit">Se connecter</button>
        </form>
    </div>
    <p>Pas encore de compte ? <a href="signup.php">Inscrivez-vous ici</a></p>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
