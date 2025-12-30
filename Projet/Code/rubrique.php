<?php
    if (session_status() === PHP_SESSION_NONE) { // initialisation
        session_start();
    }
    if (!isset($_SESSION['Aliment'])){
        $_SESSION['Aliment'] = 'Aliment';
    }
    if (!isset($_SESSION['ArbreDeRecherche'])){
        $_SESSION['ArbreDeRecherche'][0] = 'Aliment';
    }
    include 'Donnees.inc.php';

    if (isset($_GET['Aliment'])) { 
        $_SESSION['Aliment'] = $_GET['Aliment'];

        $nouv = true;
        $i = 0;

        // parcourt le tableau pour éviter les doublons dans le chemin
        foreach ($_SESSION['ArbreDeRecherche'] as $recherche) {
            if ($nouv == false) {
                unset($_SESSION['ArbreDeRecherche'][$i]); // supprime les éléments suivants
            }
            if ($recherche == $_GET['Aliment']) {
                $nouv = false; // élément trouvé, on s'arrête là
            }
            $i++;
        }

        if ($nouv == true) {
            $_SESSION['ArbreDeRecherche'][$i] = $_GET['Aliment']; // ajoute la nouvelle catégorie
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Rubrique</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="../../style.css">
</head>

<div class="rubrique-container">
    <h2 class="rubrique-title"> Navigation</h2>

<!-- on affiche le chemin de recherche jusqu'à l'aliment sélectionné, les différents éléments sont cliquables afin de pouvoir modifier l'aliment sélectionné. -->
    <div class="breadcrumb"> 
        <?php foreach ($_SESSION['ArbreDeRecherche'] as $recherche) : ?>
            <a href="pagePrincipale.php?Aliment=<?= urlencode($recherche) ?>" class="breadcrumb-link">
                <?= htmlspecialchars($recherche) ?>
            </a>
            <span class="breadcrumb-separator">/</span>
        <?php endforeach; ?>
    </div>

    <h3 class="sous-categories-title">Catégories</h3>

    <ul class="categories-list">
        <?php
        $alimentActuel = $_SESSION['Aliment'];

        foreach ($Hierarchie as $key => $categorie) :
            if ($key == $alimentActuel) :
                if (isset($categorie['sous-categorie'])) :
                    foreach ($categorie['sous-categorie'] as $value) :
        ?>
                    <li class="category-item">
                        <a href="pagePrincipale.php?Aliment=<?= urlencode($value) ?>" class="category-link">
                            <?= htmlspecialchars($value) ?>
                        </a>
                    </li>
        <?php
                    endforeach;
                else :
                    echo "<p class='no-category'>Aucune sous-catégorie disponible.</p>";
                endif;
            endif;
        endforeach;
        ?>
    </ul>
</div>
    
</html>
