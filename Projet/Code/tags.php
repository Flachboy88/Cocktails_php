<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['tagsValide'])){
    $_SESSION['tagsValide'] = [];
}
if (!isset($_SESSION['tagsNonValide'])){
    $_SESSION['tagsNonValide'] = [];
}
include "Donnees.inc.php";
$feuille = [];
foreach($Hierarchie as $nom => $objet){ // on recupère toutes les feuilles 
    if (!isset($objet['sous-categorie'])) {
        $feuille[] = $nom;
    }
}
sort($feuille, SORT_STRING); //on les tries par ordre alphabétique
if (isset($_POST["submit"])){//lorsqu'on apuis sur le boutton valider on vas associers les résultats au tags valides 
    $_SESSION['tagsValide'] = []; //on pense bien a reset les deux tableaux pour eviter les doublons
    $_SESSION['tagsNonValide'] = [];
    foreach ($feuille as $nom){
        $nomPost = str_replace(' ', '_', $nom);//obligatoire pour des pbs avec le $_POST qui n'aime pas les escpaces 
        if($_POST[$nomPost] == 'bannie'){
            $_SESSION['tagsNonValide'][] = $nom;
        }
        elseif($_POST[$nomPost] == 'choisie'){
            $_SESSION['tagsValide'][] = $nom;
        }
        
    }
}

if (isset($_POST['reset'])) {// lorsque le bouton reset est utilisée
    $_SESSION['tagsValide'] = [];
    $_SESSION['tagsNonValide'] = [];
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Tags</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="../../style.css">
</head>
<body>
    <form method="post" action="#" >
        <fieldset>
            <legend>Choisir les Tags</legend>
            <?php 
                foreach ($feuille as $nom):
                    echo "$nom :";
                    $nomPost = str_replace(' ', '_', $nom); //obigatoire pour des pbs avec le $_POST
                    ?>
                    <input type="radio" name="<?php echo $nomPost ?>" value="choisie" <?php if (in_array($nom, $_SESSION['tagsValide'])):?> checked = "checked" <?php endif; ?>/> choisir
                    <input type="radio" name="<?php echo $nomPost ?>" value="bannie" <?php if (in_array($nom, $_SESSION['tagsNonValide'])):?> checked = "checked" <?php endif; ?>/> bannir
                    <input type="radio" name="<?php echo $nomPost ?>" value="pd" <?php if (!in_array($nom, $_SESSION['tagsNonValide']) && !in_array($nom, $_SESSION['tagsValide'])):?> checked = "checked" <?php endif; ?>/> par default
                    <br />

            <?php endforeach;
                /*if (!empty($_SESSION['tagsNonValide'])) { //test de tagsNonValide et de tagsValide
                    echo "<ul>";
                    foreach ($_SESSION['tagsNonValide'] as $tag) {
                        echo "<li>" . htmlspecialchars($tag) . "</li>";
                    }
                    echo "</ul>";
                } 
                /*if (!empty($_SESSION['tagsValide'])) {
                    echo "<ul>";
                    foreach ($_SESSION['tagsValide'] as $tag) {
                        echo "<li>" . htmlspecialchars($tag) . "</li>";
                    }
                    echo "</ul>";
                }*/
 
            ?>
            <input name="submit" type="submit" value="Valider" />
            <input type="submit" name="reset" value="Réinitialiser">


            <div style="text-align: center; margin-top: 40px;">
                <a href="pagePrincipale.php" class="btn-retour">Retour</a>
            </div>
        </fieldset>
        <br />
    </form>
</body>
</html>
