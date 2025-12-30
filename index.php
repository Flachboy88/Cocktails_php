<?php
session_start();

require_once __DIR__ . '/Projet/Code/header.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil – Application Recettes</title>
    <link rel="preload" href="style.css" as="style"> <!-- on preload les styles sinon l'image apparait en grand avant que le site soit chargé -->
    <link rel="preload" href="Projet/Photos/connexion.jpg" as="image">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<main class="container">
    
    <section class="hero"> 
        <h1>Bienvenue sur MyCocktails</h1>
        <p>Trouvez les recettes de vos préférés</p>
    
        <a href="Projet/Code/pagePrincipale.php" class="hero-btn">
            Visiter le site
        </a>
    </section>
</main>

</body>
</html>
