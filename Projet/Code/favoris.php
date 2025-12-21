<?php
// démarrer la session si elle n'est pas déjà active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// inclusion du header commun et des données des recettes
include 'header.php';
include 'Donnees.inc.php';

// initialisation du tableau des favoris en session
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
    <link rel="stylesheet" href="<?= BASE_URL ?>/style.css">
</head>
<body>

<main class="favoris-container">

    <h1 class="favoris-title">Mes Favoris</h1>

    <?php if (empty($_SESSION['favoris'])): ?>

        <div class="favoris-empty">
            <p>Vous n'avez pas encore de favoris.</p>
            <a href="pagePrincipale.php" class="btn-retour">
                Découvrir des cocktails
            </a>
        </div>

    <?php else: ?>

        <ul class="favoris-grid">
            <?php foreach ($_SESSION['favoris'] as $recette_id): ?>

                <?php if (isset($Recettes[$recette_id])): 
                    // récupération des données de la recette à partir de son id
                    $recette = $Recettes[$recette_id];
                    // construction du chemin de l'image associée à la recette
                    $image = 'Photos/' . str_replace(' ', '_', $recette['titre']) . '.jpg';
                ?>

                <li>
                    <a 
                        href="boissonSpecifique.php?boissonSpecifique=<?= urlencode($recette_id) ?>" 
                        class="favoris-card"
                    >

                        <?php
                        // utilisation d'une image par défaut si l'image de la recette n'existe pas
                        $imageAffichee = file_exists($image) ? $image : 'Photos/mystere.jpg';
                        ?>
                        <img src="<?= htmlspecialchars($imageAffichee) ?>"
                            alt="<?= htmlspecialchars($boisson['titre']) ?>"
                                class="favoris-card-img"
                            >
                    
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
        <a href="<?= BASE_URL ?>/Projet/Code/pagePrincipale.php" class="btn-retour">
            Retour à la page principale
        </a>
    </div>

</main>

</body>
</html>
