<?php
declare(strict_types=1);

session_start();

if (empty($_SESSION['contact_csrf'])) {
    $_SESSION['contact_csrf'] = bin2hex(random_bytes(32));
}

$errors = [];
$success = false;
$name = '';
$email = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL) ?: '';
    $message = trim($_POST['message'] ?? '');
    $csrfToken = $_POST['csrf_token'] ?? '';

    if (!is_string($csrfToken) || !hash_equals($_SESSION['contact_csrf'], $csrfToken)) {
        $errors[] = 'Votre session a expiré. Actualisez la page puis réessayez.';
    }
    if ($name === '' || mb_strlen($name) > 100) {
        $errors[] = 'Veuillez saisir un nom de 1 à 100 caractères.';
    }
    if ($email === '') {
        $errors[] = 'Veuillez saisir une adresse e-mail valide.';
    }
    if (mb_strlen($message) < 10 || mb_strlen($message) > 5000) {
        $errors[] = 'Votre message doit contenir entre 10 et 5 000 caractères.';
    }

    if ($errors === []) {
        $to = 'support@bluelifeharmony.com';
        $subject = 'Nouveau message depuis Blue Life Harmony';
        $headers = [
            'From' => 'Blue Life Harmony <support@bluelifeharmony.com>',
            'Reply-To' => $email,
            'Content-Type' => 'text/plain; charset=UTF-8',
        ];
        $body = "Nom : {$name}\nE-mail : {$email}\n\nMessage :\n{$message}";
        $mailSent = mail($to, $subject, $body, $headers);

        if ($mailSent) {
            $success = true;
            $name = $email = $message = '';
            $_SESSION['contact_csrf'] = bin2hex(random_bytes(32));
        } else {
            $errors[] = 'L’envoi du message a échoué. Veuillez réessayer plus tard.';
            error_log('Blue Life Harmony contact email could not be sent.');
        }
    }
}

function contact_escape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="assets/img/logo.png">
    <title>Blue Life Harmony - Contact</title>
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/contact.css">
    <script src="assets/js/index.js" defer></script>
</head>
<body>
<?php require __DIR__ . '/partials/header.php'; ?>
<main>
    <section class="contact-section">
        <div class="contact-container">
            <h2 class="contact-title">Contactez-nous</h2>
            <p class="contact-text">
                Vous avez des questions, des commentaires ou des suggestions ?
                Nous aimerions avoir de vos nouvelles. Remplissez le formulaire
                ci-dessous et nous vous répondrons dans les plus brefs délais.
            </p>

            <?php if ($success): ?>
                <p class="form-success" role="status">Merci ! Votre message a bien été envoyé.</p>
            <?php endif; ?>

            <?php foreach ($errors as $error): ?>
                <p class="form-error" role="alert"><?= contact_escape($error) ?></p>
            <?php endforeach; ?>

            <form class="contact-form" action="contact.php" method="post">
                <input type="hidden" name="csrf_token" value="<?= contact_escape($_SESSION['contact_csrf']) ?>">
                <div class="form-group">
                    <label for="name">Nom</label>
                    <input type="text" id="name" name="name" maxlength="100"
                           autocomplete="name" value="<?= contact_escape($name) ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" maxlength="254"
                           autocomplete="email" value="<?= contact_escape($email) ?>" required>
                </div>
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="6" minlength="10"
                              maxlength="5000" required><?= contact_escape($message) ?></textarea>
                </div>
                <div class="form-group">
                    <button type="submit">Envoyer</button>
                </div>
            </form>
        </div>
    </section>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
