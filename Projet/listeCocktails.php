<?php 
if (session_status() === PHP_SESSION_NONE) { //initialisation
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

$liste_feuille = [];
function cherche_arbre($noeud,$Hierarchie,&$liste_feuille){ // on cherche a trouver toutes les feuilles depuis un certain noeud
    if (!isset($Hierarchie[$noeud]['sous-categorie'])){ // si le noeud est une feuille on l'ajoute la la liste de feuille
        $liste_feuille[] = $noeud; 
    }
    else{
        foreach ($Hierarchie[$noeud]['sous-categorie'] as $key){ // sinon on applique la fonction a toutes les sous-catégories
            cherche_arbre($key,$Hierarchie,$liste_feuille);
        }
    }

}

function cherche_comparaison($tab1,$tab2){ // on compare la liste des feuilles avec les ingrédient de la boisson. Si il y en a au moins une en commun. alors on revoie true (ce qui permetera d'afficher la boisson)
    foreach($tab1 as $tab11){
        foreach($tab2 as $tab22){
            if($tab11 == $tab22){
                return true;
            }
        }
    }

    return false;
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

        if ($_SESSION['Aliment'] == 'Aliment'){
            foreach ($Recettes as $r => $nomboisson):?>
                <li>
                    <a class="boisson" href="boissonSpecifique.php?boissonSpecifique=<?= urlencode($r) ?>">
                        <?= htmlspecialchars($nomboisson['titre']) ?>
                    </a>
                </li>
            <?php endforeach;
        }
        else {
            cherche_arbre($_SESSION['Aliment'],$Hierarchie,$liste_feuille);
            foreach ($Recettes as $r => $nomboisson):
            if (cherche_comparaison($nomboisson['index'],$liste_feuille)):?>
                <li>
                    <a class="boisson" href="boissonSpecifique.php?boissonSpecifique=<?= urlencode($r) ?>">
                        <?= htmlspecialchars($nomboisson['titre']) ?>
                    </a>
                </li>
            <?php endif;
            endforeach;
            /*echo "<ul>"; //test de $liste_feuille
                foreach ($liste_feuille as $element) {
                    echo "<li>$element</li>";
                }
            echo "</ul>";*/

        }
    ?>
    </p>
</main>

</body>
</html>
