<?php 
if (session_status() === PHP_SESSION_NONE) { //initialisation
    session_start();
}

include 'Donnees.inc.php'; 


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
function cherche_arbre($noeud,&$liste_feuille){ // on cherche a trouver toutes les feuilles depuis un certain noeud
    global $Hierarchie;
    if (!isset($Hierarchie[$noeud]['sous-categorie'])){ // si le noeud est une feuille on l'ajoute la la liste de feuille
        $liste_feuille[] = $noeud; 
    }
    else{
        foreach ($Hierarchie[$noeud]['sous-categorie'] as $key){ // sinon on applique la fonction a toutes les sous-catégories
            cherche_arbre($key,$liste_feuille);
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

$tab_tag = [];
function cherche_nb_tags($nomboisson,$boisson,&$tab_tag){ // on cherche a trier les différantes recette en fonction du nombres de tags valides
    // on utilise un double tableau pour stocker les boissons en foction du nombre de tags. la première entrée est le nombre de tag, la deuxième le nom des boissons
    $i = 0;
    foreach ($boisson['index'] as $index){
        if (in_array($index, $_SESSION['tagsValide'])) {
            $i++;
        }

    }

    if($i >= 1){
        $tab_tag[$i][] = $nomboisson;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../../style.css">
</head>
<body>

<main>
    <h1>Cocktails disponibles</h1>
    <div class=input-control>
        <input type="text" id="search" placeholder="Rechercher un cocktail..." />
    </div>
    <ul id="listeC">
    <?php 

        if ($_SESSION['Aliment'] == 'Aliment' && empty($_SESSION["tagsValide"]) && empty($_SESSION["tagsNonValide"])){ // dans le cas ou il n'y a pas de tags et où on utilise la base de l'arbre on affiche juste toutes les boissons
            foreach ($Recettes as $nomboisson => $boisson):?>
                <li>
                    <a class="boisson" href="boissonSpecifique.php?boissonSpecifique=<?= urlencode($nomboisson) ?>">
                        <?= htmlspecialchars($boisson['titre']) ?>
                    </a>
                </li>
            <?php endforeach;
        }
        else {
            cherche_arbre($_SESSION['Aliment'],$liste_feuille);// on cherche a trouver toutes les feuilles depuis un certain noeud
            foreach ($Recettes as $nomboisson => $boisson):
            if (cherche_comparaison($boisson['index'],$liste_feuille) && !cherche_comparaison($boisson['index'], $_SESSION["tagsNonValide"])): 
                //on test si un des ingrédiants de la boisson apparait dans la liste de feuille et qu'aucun n'apparais dans tagsNonValide
                if (empty($_SESSION["tagsValide"])):?> <!-- cas ou il n'y a pas de Tags demandé par l'Utilisateur: pas besoin de trier --> 
                
                <li>
                    <a class="boisson" href="boissonSpecifique.php?boissonSpecifique=<?= urlencode($nomboisson) ?>">
                        <?= htmlspecialchars($boisson['titre']) ?>
                    </a>
                </li>
            <?php 
                else: //cas ou il y a des Tags demandé par l'Utilisateur: on met les boissons a affichier dans un tableau plutot que de les afficher directement. aussi on stocke le nombe de tags associés
                    cherche_nb_tags($nomboisson,$boisson,$tab_tag);
                ?>
               <?php endif;
            endif;
            endforeach;
            if (!empty($_SESSION["tagsValide"])) :
                krsort($tab_tag); // on retourne le tableau pour afficher les boisson avec le plus de tags en premier 
                foreach ($tab_tag as $nbTags => $boissons) :
                    foreach ($boissons as $nomboisson) :?>
                        <li>
                            <a class="boisson" href="boissonSpecifique.php?boissonSpecifique=<?= urlencode($nomboisson) ?>">
                                <?= htmlspecialchars($Recettes[$nomboisson]['titre']) ?>
                                (<?= $nbTags ?>)
                            </a>
                        </li>
                    <?php
                    endforeach;
                endforeach;
            endif;

            /*echo "<ul>"; //test de $liste_feuille
                foreach ($liste_feuille as $element) {
                    echo "<li>$element</li>";
                }
            echo "</ul>";*/

        }
    ?>
    </ul>
</main>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("search");
    const liste = document.getElementById("listeC");
    const items = liste.getElementsByTagName("li");
    searchInput.addEventListener("keyup", function () {
        const filtre = searchInput.value.toLowerCase();
        for (let i = 0; i < items.length; i++) {
            const lien = items[i].getElementsByTagName("a")[0];
            const texte = lien.textContent.toLowerCase();
            if (texte.includes(filtre)) {items[i].style.display = "";}
            else {items[i].style.display = "none";}
        }
    });
});
</script>
</body>
</html>
