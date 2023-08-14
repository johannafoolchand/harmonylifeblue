<?php 
session_start();
include('../scripts/config.php');

if (!isset($_SESSION["user"])) {
    header("location:login.php");
}

?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="assets/img/logo.png" />
    <title>Espace Perso - Blue Life Harmony</title>
    <link rel="stylesheet" href="assets/css/main.css" />
    <link rel="stylesheet" href="assets/css/dashboard.css"/>
    <link rel="stylesheet" href="assets/css/index.css"/>
    <script src="assets/js/index.js"></script>
  </head>
  <body>
  <header class="dashboard-header">
    <div class="logo">
        <img src="assets/img/logo.png" alt="logo" />
        <p>Blue <span>Life</span> <span>Harmony</span></p>
    </div>
    <div class="user-info">
    <a href="?logout" class="logout-button">Logout</a>
    </div>
</header>

    <main>
            <h2>Espace Perso</h2>
            <p><?php echo ('Bienvenue'.ucfirst($_SESSION["user"]["prenom"]) . ' ' . ucfirst($_SESSION["user"]["nom"]));?></p>
        <nav class="navbar">
      <a href="#">Accueil</a>
      <a href="#">Profil</a>
      <a href="#">Paramètres</a>
      <a href="#">Déconnexion</a>
    </nav>
    
    <div class="content">
      <h2>Vue d'ensemble</h2>
      <p>Bienvenue sur votre espace personnel. Vous pouvez accéder à vos informations, gérer vos paramètres et bien plus encore.</p>
    </div>
   
        </main>

    <footer class="footer">
        <div class="footer-container">
            <p class="footer-text">
                © <?php echo date("Y"); ?> Blue Life Harmony. Tous droits réservés.
            </p>
        </div>
    </footer>
  </body>
  </html>