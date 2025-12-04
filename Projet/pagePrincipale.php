<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'header.php';
if (!isset($_SESSION['Aliment'])){
    $_SESSION['Aliment'] = 'Aliment';
}
if (!isset($_SESSION['ArbreDeRecherche'])){
    $_SESSION['ArbreDeRecherche'][0] = 'Aliment';
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
    <h1>Bienvenue sur MyCocktails</h1>

    <?php include 'rubrique.php'; ?>
    <br><br>
    <a href="index.php" class="btn-home">Retour à l'accueil</a>
</main>

</body>
</html>