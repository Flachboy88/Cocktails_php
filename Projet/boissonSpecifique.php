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

$isFavorite = false;
$actionUrl = 'favoris_action.php?id=' . urlencode($index);

if (isset($_SESSION['user'])) {
    $userId = $_SESSION['user']['id'];
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM recettes_favorites WHERE utilisateur_id = ? AND recette_id = ?");
    $stmt->execute([$userId, $index]);
    $count = $stmt->fetchColumn();
    
    if ($count > 0) {
        $isFavorite = true;
        $actionUrl .= '&action=remove';
    } else {
        $actionUrl .= '&action=add';
    }
} else {
    // visiteur
    if (!isset($_SESSION['guest_favorites'])) {
        $_SESSION['guest_favorites'] = [];
    }
    if (in_array($index, $_SESSION['guest_favorites'])) {
        $isFavorite = true;
        $actionUrl .= '&action=remove';
    } else {
        $actionUrl .= '&action=add';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>MyCocktails – <?= htmlspecialchars($boisson['titre']) ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<main class="cocktail-detail-page">
    <div class="cocktail-actions">
        <a href="pagePrincipale.php" class="btn-action btn-back">
            < Retour
        </a>
        
        <a href="<?= $actionUrl ?>" class="btn-action btn-favorite">
            <?= $isFavorite ? '★ Retirer des Favoris' : '☆ Ajouter aux Favoris' ?>
        </a>
        
    </div>

    <div class="cocktail-card">
        <h1 class="cocktail-title"><?= htmlspecialchars($boisson['titre']) ?></h1>
        
        <section class="cocktail-section">
            <h2 class="section-subtitle">Ingrédients</h2>
            <ul class="ingredients-list">
                <?php 
                $ingredients = explode('|', $boisson['ingredients']);
                foreach ($ingredients as $ingredient) :
                    $trimmed_ingredient = trim($ingredient);
                    if (!empty($trimmed_ingredient)) :
                ?>
                    <li><?= htmlspecialchars($trimmed_ingredient) ?></li>
                <?php
                    endif;
                endforeach;
                ?>
            </ul>
        </section>

        <section class="cocktail-section">
            <h2 class="section-subtitle">Préparation</h2>
            <div class="preparation-text">
                <?php 
                echo nl2br(htmlspecialchars($boisson['preparation'])); // saut de ligne
                ?>
            </div>
        </section>
    </div>
</main>
    
</body>
</html>