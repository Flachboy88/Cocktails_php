<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLocal = in_array($_SERVER['SERVER_NAME'], ['localhost', '127.0.0.1']);

// on regarde si on est sur localhost ou pas
if ($isLocal) {
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db   = "cocktails";
} else {
    $host = "sql308.byethost7.com";
    $user = "b7_40483664";
    $pass = "CivWEB88";
    $db   = "b7_40483664_Coktails";
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

// calcul de la racine 
// récupère le chemin du script actuel 
$currentScript = $_SERVER['SCRIPT_NAME'];
$currentDir = dirname($currentScript);

// si on est dans le dossier "Code", la racine est deux niveaux au dessus
if (strpos($currentDir, 'Projet/Code') !== false) {
    $rootURL = dirname(dirname($currentDir));
} else {
    // Ss on est déjà à la racine
    $rootURL = $currentDir;
}

// nttoyage pour éviter les doubles slashes
$rootURL = rtrim($rootURL, '/\\');

// on s'assure que si c'est vide, on met un slash pour le domaine
$htmlRoot = ($rootURL === '') ? '' : $rootURL;

$GLOBALS['projectRoot'] = $htmlRoot;
?>

<header class="header">
    <a href="<?= $htmlRoot ?>/index.php" class="logo">MyCocktails</a>

    <div class="user-menu">
        <button class="user-btn" id="choixUtilisateur">
            <img src="<?= $htmlRoot ?>/Projet/Photos/connexion.jpg" alt="Connexion" class="user-icon">
        </button>

        <div class="user-choix_menu" id="scroll">
            <?php if (!isset($_SESSION['user'])): ?>
                <a href="<?= $htmlRoot ?>/Projet/Code/connexion.php">Connexion</a>
                <a href="<?= $htmlRoot ?>/Projet/Code/inscription.php">Inscription</a>   
                <a href="<?= $htmlRoot ?>/Projet/Code/favoris.php">Mes favoris</a>
            <?php else: ?>
                <span class="hello">
                    Bonjour <?= htmlspecialchars($_SESSION['user']['login']) ?>
                </span>
                <a href="<?= $htmlRoot ?>/Projet/Code/info_perso.php">Modifier mes infos</a>
                <a href="<?= $htmlRoot ?>/Projet/Code/favoris.php">Mes favoris</a>
                <a href="<?= $htmlRoot ?>/Projet/Code/deconnexion.php" class="logout">Déconnexion</a>
            <?php endif; ?>
        </div>
    </div>
</header>

<script>
document.getElementById("choixUtilisateur").onclick = function (e) {
    e.stopPropagation();
    document.getElementById("scroll").classList.toggle("show");
}
window.onclick = function() {
    var menu = document.getElementById("scroll");
    if (menu && menu.classList.contains('show')) {
        menu.classList.remove('show');
    }
}
</script>