<?php
    session_start();
    include __DIR__ . '/header.php'; // inclus header depuis le même dossier ou adapté

    // initialisation des variables
    if (!isset($_SESSION['Aliment'])){
        $_SESSION['Aliment'] = 'Aliment';
    }
    if (!isset($_SESSION['ArbreDeRecherche'])){
        $_SESSION['ArbreDeRecherche'][0] = 'Aliment';
    }
    if (!isset($_SESSION['boissonSpecifique'])){
        $_SESSION['boissonSpecifique'] = 0;
    }
    if (!isset($_SESSION['tagsValide'])){
        $_SESSION['tagsValide'] = [];
    }
    if (!isset($_SESSION['tagsNonValide'])){
        $_SESSION['tagsNonValide'] = [];
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>MyCocktails – Page Principale</title>
    <!-- style depuis la racine du projet -->
    <link rel="stylesheet" href="<?= $projectRoot ?>/style.css">
</head>
<body>

<main class="container">
    <h1 class="page-title">Bienvenue sur MyCocktails</h1>

    <div class="content-wrapper"> <!-- on affiche le contenu dans la partie centrale -->
        <aside class="sidebar"> <!-- on affiche le menu dans la partie latérale -->
            <?php include 'rubrique.php'; ?>
        </aside>
        
        <div class="main-content">
            <?php include 'listeCocktails.php'; ?>
        </div>
    </div>

    <div style="text-align: center; margin-top: 40px;">
        <a href="<?= $projectRoot ?>/index.php" class="btn-retour">Retour à l'accueil</a>
    </div>
</main>

</body>
</html>
