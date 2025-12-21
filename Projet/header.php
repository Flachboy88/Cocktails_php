<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$server = $_SERVER['SERVER_NAME'];

if ($server === "localhost" || $server === "127.0.0.1") {
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db   = "cocktails";
} else {
    $host = "sql308.byethost7.com";
    $user = "b7_40483664";
    $pass = "CivWEB88";
    $db   = "b7_40483664_Cocktails";
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
} catch (Exception $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
    die();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Cocktail Master</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<header class="header">
    <a href="index.php" class="logo">MyCocktails</a>

    <div class="user-menu">
        <button class="user-btn" id="choixUtilisateur">
            <img src="connexion.png" alt="Connexion" class="user-icon">
        </button>


        <div class="user-choix_menu" id="scroll">
            <?php if (!isset($_SESSION['user'])): ?>
                <a href="connexion.php">Connexion</a>
                <a href="inscription.php">Inscription</a>
                <a href="favoris.php">Mes favoris</a>
            <?php else: ?>
                <span class="hello">Bonjour <?= htmlspecialchars($_SESSION['user']['login']) ?></span>
                <a href="info_perso.php">Modifier mes infos</a>
                <a href="favoris.php">Mes favoris</a>
                <a href="deconnexion.php" class="logout">Déconnexion</a>
            <?php endif; ?>
        </div>
    </div>
</header>

<script>
document.getElementById("choixUtilisateur").onclick = function() { // permet d'afficher la liste des choix que l'on a 
    document.getElementById("scroll").classList.toggle("show");
}
</script>
</body>
</html>