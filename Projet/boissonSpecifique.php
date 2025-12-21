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

$image = 'Photos/' . str_replace(' ', '_',$boisson['titre']) . '.jpg'; 
// on initialise le chemain d'accet de l'image en remplacant les vides par un underscore pour corespondre au nom des photos

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>MyCocktails – <?= htmlspecialchars($boisson['titre']) ?></title>
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
            if (file_exists($image)) {
                echo '<img src="'.htmlspecialchars($image).'"/>';
            }
            echo $boisson['titre'];
            echo '<br>';
            echo $boisson['ingredients'];
            echo '<br>';
            echo $boisson['preparation'];
            echo '<br>';
         ?>
    </p>
    
</body>
</html>
