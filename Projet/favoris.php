<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'header.php';
include 'Donnees.inc.php';

$favoriteRecetteIds = [];

if (isset($_SESSION['user'])) {

    $userId = $_SESSION['user']['id'];
    $stmt = $pdo->prepare("SELECT recette_id FROM recettes_favorites WHERE utilisateur_id = ?");
    $stmt->execute([$userId]);
    $results = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    $favoriteRecetteIds = array_map('intval', $results);
    $isGuest = false;

} else {
    if (!isset($_SESSION['guest_favorites'])) {
        $_SESSION['guest_favorites'] = [];
    }
    $favoriteRecetteIds = $_SESSION['guest_favorites'];
    $isGuest = true;
}

$favoriteRecettes = [];
foreach ($favoriteRecetteIds as $id) {
    if (isset($Recettes[$id])) {
        $favoriteRecettes[$id] = $Recettes[$id];
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Favoris – MyCocktails</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<main class="container" style="padding: 20px;">
    <h1>Mes Cocktails Favoris</h1>
    
    <?php if ($isGuest) : ?>
        <p>Connectez-vous pour sauvegarder définitivement cette liste de favoris et les fusionner avec vos favoris existants.</p>
        <br>
    <?php endif; ?>

    <?php if (empty($favoriteRecettes)) : ?>
        <p>Vous n'avez aucun cocktail favori pour le moment.</p>
    <?php else: ?>
        <ul>
        <?php foreach ($favoriteRecettes as $r => $nomboisson):?>
            <li class="cocktails-list-item">
                <a class="boisson" href="boissonSpecifique.php?boissonSpecifique=<?= urlencode($r) ?>">
                    <?= htmlspecialchars($nomboisson['titre']) ?>
                </a>
                
                <a href="favoris_action.php?id=<?= urlencode($r) ?>&action=remove&redirect=favoris.php" class="btn-action" style="float:right; margin-top:-25px; background: #ddd; color: #333; padding: 5px 10px;">
                    Supprimer
                </a>
            </li>
        <?php endforeach;?>
        </ul>
    <?php endif; ?>
    
</main>

</body>
</html>