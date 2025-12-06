<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'Donnees.inc.php'; 

// ... (Logique de session et de filtrage inchangée) ...
if (!isset($_SESSION['Aliment'])){
    $_SESSION['Aliment'] = 'Aliment';
}
if (!isset($_SESSION['ArbreDeRecherche'])) {
    $_SESSION['ArbreDeRecherche'][0] = 'Aliment';
}
if (!isset($_SESSION['boissonSpecifique'])) {
    $_SESSION['boissonSpecifique'] = 0;
}

function getAllDescendants($category, $Hierarchie, &$descendants) {
    // ajoute la catégorie elle-même
    if (!in_array($category, $descendants) && $category !== 'Aliment') {
        $descendants[] = $category;
    }
    
    // si sous categories récursion
    if (isset($Hierarchie[$category]) && isset($Hierarchie[$category]['sous-categorie'])) {
        foreach ($Hierarchie[$category]['sous-categorie'] as $subCategory) {
            getAllDescendants($subCategory, $Hierarchie, $descendants);
        }
    }
}

$currentAliment = $_SESSION['Aliment'];
$allowedIngredients = [];
$filteredRecettes = [];

if ($currentAliment === 'Aliment') {
    $filteredRecettes = $Recettes;
} else {
    getAllDescendants($currentAliment, $Hierarchie, $allowedIngredients);
    
    // filtre
    foreach ($Recettes as $index => $recette) {
        $appartient = false;
        // vérifie si au moins un ingrédient de la recette est dans la liste des ingrédients
        foreach ($recette['index'] as $ingredientRecette) {
             if (in_array($ingredientRecette, $allowedIngredients)) {
                $appartient = true;
                break;
            }
        }
        
        if ($appartient) {
            $filteredRecettes[$index] = $recette;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<main>
    <h1>Cocktails disponibles</h1>
    <ul>
    <?php 
    foreach ($filteredRecettes as $r => $nomboisson):?>
        <li class="cocktails-list-item">
            <a class="boisson" href="boissonSpecifique.php?boissonSpecifique=<?= urlencode($r) ?>">
                <?= htmlspecialchars($nomboisson['titre']) ?>
            </a>
        </li>
    <?php endforeach;?>
    </ul>
    
</main>

</body>
</html>