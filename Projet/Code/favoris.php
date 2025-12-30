<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'header.php';
include 'Donnees.inc.php';
$rootURL = $GLOBALS['projectRoot']; // on récupère le chemin du projet ( utile pour les photos )

// si l'utilisateur n'est pas connecté, on initialise ses favoris à vide
if (!isset($_SESSION['favoris'])) {
    $_SESSION['favoris'] = [];
}

// récupération des favoris depuis la base de données si l'utilisateur est connecté
if (isset($_SESSION['user']) && empty($_SESSION['favoris'])) {
    $user_id = $_SESSION['user']['id'];
    $stmt = $pdo->prepare(
        "SELECT recette_id FROM recettes_favorites WHERE utilisateur_id = ?"
    );
    $stmt->execute([$user_id]);
    $_SESSION['favoris'] = $stmt->fetchAll(PDO::FETCH_COLUMN);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>MyCocktails – Mes Favoris</title>
    <link rel="stylesheet" href="<?= $rootURL ?>/style.css">
</head>
<body>

<main class="favoris-container">

    <h1 class="favoris-title">Mes Favoris</h1>

    <?php if (empty($_SESSION['favoris'])): ?> <!-- si aucun favori, on affiche un message -->

        <div class="favoris-empty">
            <p>Vous n'avez pas encore de favoris.</p>
            <a href="pagePrincipale.php" class="btn-retour">
                Découvrir des cocktails
            </a>
        </div>

    <?php else: ?>

        <ul class="favoris-grid">
            <!-- affichage de chaque favori -->
            <?php foreach ($_SESSION['favoris'] as $recette_id): ?>

                <?php if (isset($Recettes[$recette_id])): 
                    $recette = $Recettes[$recette_id];
                    $nomFichier = str_replace(' ', '_', $recette['titre']) . '.jpg';
                    
                    // chemin pour que le php vérifie l'existence du fichier sur le disque
                    $cheminPhysique = __DIR__ . '/../Photos/' . $nomFichier; // obligé de faire ca pour que ca marche sur la machine en ligne
                    
                    // chemin pour que le navigateur affiche l'image
                    $cheminURL = $rootURL . '/Projet/Photos/' . $nomFichier;
                    $imageMystere = $rootURL . '/Projet/Photos/mystere.jpg'; // image par défaut
                ?>

                <li> 

                    <a href="boissonSpecifique.php?boissonSpecifique=<?= urlencode($recette_id) ?>" class="favoris-card">

                        <?php
                        // vérification physique de l'image
                        $imageAffichee = file_exists($cheminPhysique) ? $cheminURL : $imageMystere;
                        ?>
                        
                        <!-- affichage de l'image -->
                        <img src="<?= htmlspecialchars($imageAffichee) ?>"
                             alt="<?= htmlspecialchars($recette['titre']) ?>"
                             class="favoris-card-img">
                    
                        <!-- affichage du titre de la boisson -->
                        <div class="favoris-card-content">
                            <h3 class="favoris-card-title">
                                <?= htmlspecialchars($recette['titre']) ?>
                            </h3>
                        </div>
                    </a>
                </li>

                <?php endif; ?>
            <?php endforeach; ?>
        </ul>

    <?php endif; ?>

    <div style="text-align:center; margin-top:40px;">
        <a href="pagePrincipale.php" class="btn-retour">
            Retour à la page principale
        </a>
    </div>

</main>

</body>
</html>