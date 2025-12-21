<?php

// Détection automatique de la racine du projet
$scriptPath = dirname($_SERVER['SCRIPT_NAME']);
$basePath = str_replace('/Projet/Code', '', $scriptPath);
$basePath = str_replace('/Projet', '', $basePath);
define('BASE_URL', $basePath);

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
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (Exception $e) {
    die("Erreur BDD : " . $e->getMessage());
}
?>

<header class="header">
    <a href="<?= BASE_URL ?>/index.php" class="logo">MyCocktails</a>

    <div class="user-menu">
        <button class="user-btn" id="choixUtilisateur">
            <img src="<?= BASE_URL ?>/Projet/Photos/connexion.png" alt="Connexion" class="user-icon">
        </button>

        <div class="user-choix_menu" id="scroll">
            <?php if (!isset($_SESSION['user'])): ?>
                <a href="<?= BASE_URL ?>/Projet/Code/connexion.php">Connexion</a>
                <a href="<?= BASE_URL ?>/Projet//Code/inscription.php">Inscription</a>
                <a href="<?= BASE_URL ?>/Projet//Code/favoris.php">Mes favoris</a>
            <?php else: ?>
                <span class="hello">
                    Bonjour <?= htmlspecialchars($_SESSION['user']['login']) ?>
                </span>
                <a href="<?= BASE_URL ?>/Projet//Code/info_perso.php">Modifier mes infos</a>
                <a href="<?= BASE_URL ?>/Projet//Code/favoris.php">Mes favoris</a>
                <a href="<?= BASE_URL ?>/Projet//Code/deconnexion.php" class="logout">Déconnexion</a>
            <?php endif; ?>
        </div>
    </div>
</header>

<script>
document.getElementById("choixUtilisateur").onclick = function () {
    document.getElementById("scroll").classList.toggle("show");
}
</script>
