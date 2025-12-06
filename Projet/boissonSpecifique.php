<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'header.php';
include 'Donnees.inc.php';
if (!isset($_SESSION['Aliment'])){
    $_SESSION['Aliment'] = 'Aliment';
}
if (!isset($_SESSION['ArbreDeRecherche'])){
    $_SESSION['ArbreDeRecherche'][0] = 'Aliment';
}
if (isset($_GET['boissonSpecifique'])) {
    $_SESSION['boissonSpecifique'] = $_GET['boissonSpecifique'];
}
$index = $_SESSION['boissonSpecifique'];
$boisson = $Recettes[$index];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>MyCocktails – Page Principale</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <a href="pagePrincipale.php">
        <button>Retour</button>
    </a>
    <a href="pagePrincipale.php">
        <button>Favorit</button>
    </a>
    <p>
        <?php 
            echo $boisson['titre'];
            echo '<br>';
            echo $boisson['ingredients'];
            echo '<br>';
            echo $boisson['preparation'];
            echo '<br>';
         ?>
    </p>
    
</body>