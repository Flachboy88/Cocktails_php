<?php
session_start();

require_once __DIR__ . '/Projet/Code/header.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil – Application Recettes</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/style.css">
</head>
<body>

    <main class="container">
        <h1>Bienvenue sur l'application de recettes</h1>  
        <d> accéder à la page principale : <a href="<?= BASE_URL ?>/Projet/Code/pagePrincipale.php">Page principale</a></d>
    </main>

</body>
</html>
