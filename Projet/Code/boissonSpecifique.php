<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/Donnees.inc.php';

if (!isset($_SESSION['Aliment'])){
    $_SESSION['Aliment'] = 'Aliment';
}
if (!isset($_SESSION['ArbreDeRecherche'])){
    $_SESSION['ArbreDeRecherche'][0] = 'Aliment';
}
if (isset($_GET['boissonSpecifique'])) {
    $_SESSION['boissonSpecifique'] = $_GET['boissonSpecifique'];
}

// initialiser les favoris en session si nécessaire
if (!isset($_SESSION['favoris'])) {
    $_SESSION['favoris'] = [];
}

// ajouter/retirer des favoris
if (isset($_GET['action']) && $_GET['action'] === 'toggle_favori') {
    $recette_id = $_SESSION['boissonSpecifique'];
    
    if (in_array($recette_id, $_SESSION['favoris'])) {
        // retirer des favoris
        $_SESSION['favoris'] = array_diff($_SESSION['favoris'], [$recette_id]);
    } else {
        // ajouter aux favoris
        $_SESSION['favoris'][] = $recette_id;
    }
    
    // si connecté, synchroniser avec la bdd
    if (isset($_SESSION['user'])) {
        $user_id = $_SESSION['user']['id'];
        
        if (in_array($recette_id, $_SESSION['favoris'])) {
            // ajouter en bdd
            $stmt = $pdo->prepare("INSERT IGNORE INTO recettes_favorites (utilisateur_id, recette_id) VALUES (?, ?)");
            $stmt->execute([$user_id, $recette_id]);
        } else {
            // retirer de la bdd
            $stmt = $pdo->prepare("DELETE FROM recettes_favorites WHERE utilisateur_id = ? AND recette_id = ?");
            $stmt->execute([$user_id, $recette_id]);
        }
    }

    header("Location: boissonSpecifique.php");
    exit;
}

$index = $_SESSION['boissonSpecifique'];
$boisson = $Recettes[$index];
$isFavori = in_array($index, $_SESSION['favoris']);

// fonction pour normaliser le nom de fichier
function normaliserNomFichier($nom) {
    // remplacer les caractères accentués
    $nom = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $nom);
    // remplacer les espaces par des underscores
    $nom = str_replace(' ', '_', $nom);
    // supprimer les apostrophes et autres caractères spéciaux
    $nom = preg_replace("/[^a-zA-Z0-9_-]/", '', $nom);
    return $nom;
}

$nomImageBase = normaliserNomFichier($boisson['titre']);

// chercher d'abord avec .jpg, puis .png si introuvable
$extensions = ['jpg', 'png', 'jpeg'];
$imageAffichee = '../Photos/mystere.jpg'; // image par défaut

foreach ($extensions as $ext) {
    $cheminPhysique = __DIR__ . '/../Photos/' . $nomImageBase . '.' . $ext;
    if (file_exists($cheminPhysique)) {
        $imageAffichee = '../Photos/' . $nomImageBase . '.' . $ext;
        break;
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>MyCocktails – <?= htmlspecialchars($boisson['titre']) ?></title>
    <link rel="stylesheet" href="../../style.css">
</head>
<body>

<div class="boisson-container">
    <div class="boisson-header">
        <div class="boisson-actions">
            <a href="pagePrincipale.php" class="btn-action btn-retour-action">← Retour</a>
            
            <a href="boissonSpecifique.php?action=toggle_favori" class="btn-action btn-favori <?= $isFavori ? 'actif' : '' ?>">
                <?= $isFavori ? 'Retirer des favoris' : 'Ajouter aux favoris' ?>
            </a>
        </div>
    </div>

    <div class="boisson-card">
        <div class="boisson-content">
        <img src="<?= htmlspecialchars($imageAffichee) ?>"
            alt="<?= htmlspecialchars($boisson['titre']) ?>"
            class="boisson-image">
        
        <div class="boisson-content">
            <h1 class="boisson-titre"><?= htmlspecialchars($boisson['titre']) ?></h1>
            
            <div class="boisson-section">
                <h2 class="boisson-section-title">Ingrédients</h2>
                <ul class="ingredients-list">
                    <?php 
                    $ingredients = explode('|', $boisson['ingredients']);
                    foreach ($ingredients as $ingredient): ?>
                        <li><?= htmlspecialchars(trim($ingredient)) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            
            <div class="boisson-section">
                <h2 class="boisson-section-title">Préparation</h2>
                <div class="preparation-text">
                    <?= nl2br(htmlspecialchars($boisson['preparation'])) ?>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>