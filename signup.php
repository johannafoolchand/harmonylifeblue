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
    $firstname = trim($_POST['firstname'] ?? '');
    $lastname = trim($_POST['lastname'] ?? '');
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';

    if ($firstname === '' || $lastname === '' || !$email || strlen($password) < 12) {
        $error = 'Vérifiez les champs. Le mot de passe doit contenir au moins 12 caractères.';
    } else {
        $check = $lien->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
        $check->bind_param('s', $email);
        $check->execute();
        $exists = $check->get_result()->fetch_assoc();
        $check->close();

        if ($exists) {
            $error = 'Cette adresse e-mail est déjà utilisée.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $insert = $lien->prepare('INSERT INTO users (prenom, nom, email, password) VALUES (?, ?, ?, ?)');
            $insert->bind_param('ssss', $firstname, $lastname, $email, $hash);
            $insert->execute();
            $userId = $insert->insert_id;
            $insert->close();

            session_regenerate_id(true);
            $_SESSION['user'] = [
                'id' => $userId,
                'prenom' => $firstname,
                'nom' => $lastname,
                'email' => $email,
            ];
            header('Location: espaceperso/index.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blue Life Harmony - Inscription</title>
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>
<?php require __DIR__ . '/partials/header.php'; ?>
<main class="login-page">
    <div class="login-container">
        <h1>Inscription</h1>
        <?php if ($error !== null): ?>
            <p class="form-error" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>
        <form action="signup.php" method="post">
            <div class="form-group">
                <label for="firstname">Prénom</label>
                <input type="text" id="firstname" name="firstname" maxlength="200" autocomplete="given-name" required>
            </div>
            <div class="form-group">
                <label for="lastname">Nom</label>
                <input type="text" id="lastname" name="lastname" maxlength="200" autocomplete="family-name" required>
            </div>
            <div class="form-group">
                <label for="email">Adresse e-mail</label>
                <input type="email" id="email" name="email" maxlength="100" autocomplete="email" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe (12 caractères minimum)</label>
                <input type="password" id="password" name="password" minlength="12" autocomplete="new-password" required>
            </div>
            <button type="submit">S'inscrire</button>
        </form>
    </div>
    <p>Déjà inscrit ? <a href="login.php">Connectez-vous ici</a></p>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
