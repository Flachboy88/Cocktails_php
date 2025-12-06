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

    <div class="main-content-layout">
        
        <div class="rubriques-colonne">
            <?php include 'rubrique.php'; ?>
            <br>
            <a href="index.php" class="btn-home">Retour à l'accueil</a>
        </div>

        <div class="cocktails-colonne">
            <?php include 'listeCocktails.php'; ?>
        </div>
        
    </div>
    <br><br>
    
</main>

</body>
</html>