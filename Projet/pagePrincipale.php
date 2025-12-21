<?php
session_start();
include 'header.php';
if (!isset($_SESSION['Aliment'])){
    $_SESSION['Aliment'] = 'Aliment';
}
if (!isset($_SESSION['ArbreDeRecherche'])){
    $_SESSION['ArbreDeRecherche'][0] = 'Aliment';
}
if (!isset($_SESSION['boissonSpecifique'])){
    $_SESSION['boissonSpecifique'] = 0;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>MyCocktails – Page Principale</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<main class="container">
    <h1 class="page-title">Bienvenue sur MyCocktails</h1>

    <div class="content-wrapper">
        <aside class="sidebar">
            <?php include 'rubrique.php'; ?>
        </aside>
        
        <div class="main-content">
            <?php include 'listeCocktails.php'; ?>
        </div>
    </div>

    <div style="text-align: center; margin-top: 40px;">
        <a href="index.php" class="btn-retour">Retour à l'accueil</a>
    </div>
</main>

</body>
</html>